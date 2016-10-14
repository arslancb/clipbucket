<?php
/*
 * This file contains linkquery class and some usefull functions used in this plugin
 */ 


// Global Object $importCSVobject is used in the plugin
$importCSVobject = new importCSVobject();
$Smarty->assign_by_ref('importCSVobject', $importCSVobject);


/**_____________________________________________________
 * mimetype_check
 * _____________________________________________________
 * Return True if uploaded file mimetype is allowed or not
 * input $mime : a string that contains the uploaded file mimetype
 * output : return true if it's ok otherwise false
 */
/*function mimetype_check($mime){
	$allowed = array('application/doc', 'application/pdf', 'image.png', 'image.jpeg'); //allowed mime-type
	return (in_array($mime, $allowed)); 	  //Check uploaded file type
}*/

/**_____________________________________________________
 * filesize_check
 * _____________________________________________________
 * Return True if upload file size is under a specified value or not
 * input $size : a string that contains the value of the size
 * output : return true if it's ok otherwise false
 */
/*function filesize_check($size){
	$a=$size;
	global $db;
	$req=" name = 'document_max_filesize'";
	$res=$db->select(tbl('config'),'*',$req,false,false,false);
	//return $size < 25000000;
	return $size < $res[0]["value"];
}*/



/**_____________________________________________________
 * Class importCSVobject
 * _____________________________________________________
 *Contains all actions that can affect the  document plugin 
 */
class importCSVobject extends CBCategory{
	private $basic_fields = array();
	
	/**_____________________________________
	 * importCSVobject
	 * _____________________________________
	 *Constructor for importCSVobject's instances
	 */
	function importCSVobject()	{
	}
	
	/**_____________________________________
	 * import_mapping_model
	 * ____________________________________
	 *Function used to add a new documents
	 *
	 *input $array : a dictionnary that contains all fields for a document. $_POST is used if empty
	 * output : return document's id if exists , otherwise false
	 */
	function import_mapping_model($filename, $separator=";"){
		global $db;
		if (($handle = fopen($filename, "r")) !== FALSE) {
			$i=0;
			while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
				if ($i==0){
					$head=$data;
				}
				else {
					for ($j=0; $j<count($head); $j++){
						$mytbl[$head[$j]]=$data[$j];
					}
					$query ='SHOW COLUMNS from '.tbl($mytbl['cb_table_name'])." like '".$mytbl['cb_field_name']."'";
					$result=$db->Execute($query);
					while($row = mysqli_fetch_array($result)){
						//echo $row['Field']." ".$row['Type']."<br>";
					}
					if (!$row){
						$db->Execute("ALTER TABLE ".tbl($mytbl['cb_table_name'])." ADD `".$mytbl['cb_field_name']."` ".$mytbl['cb_field_type']." NULL");
					}
					$fields_query="";
					$values_query="";
					for ($j=0; $j< count($head); $j++) {
						$fields_query .= $head[$j];
						$values_query .= "'".$mytbl[$head[$j]]."'"; 
						if($j<count($head)-1){
							$fields_query .= ',';
							$values_query .= ',';
							}
					}
					$query="INSERT INTO ".tbl('importCSV_mapping')." ($fields_query) VALUES ($values_query)";
					$db->Execute($query);
				}
				$i++;
			}
			fclose($handle);
		}
	}

	function delete_mapping_model(){
		global $db;
		$result=$db->select(tbl('importCSV_mapping'),"*");
		foreach ($result as $r){
			if ($r['cb_field_type']!= ''){
				$db->Execute("ALTER TABLE ".tbl($r['cb_table_name'])." DROP `".$r['cb_field_name']."`");
			}
		}
		$query='DELETE  FROM '.tbl("importCSV_mapping").' WHERE 1';
		$db->Execute($query);
	}
	
	/**_____________________________________
	 * import_join_model
	 * ____________________________________
	 *Function used to add a new documents
	 *
	 *input $array : a dictionnary that contains all fields for a document. $_POST is used if empty
	 * output : return document's id if exists , otherwise false
	 */
	function import_join_model($filename, $separator=";"){
		global $db;
		
		$row = 1;
		if (($handle = fopen($filename, "r")) !== FALSE) {
			$i=0;
			while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
				if ($i==0){
					$head=$data;					
				}
				else {
					$db->insert(tbl('importCSV_join'), $head, $data);
				}
				$i++;
			}
			fclose($handle);
		}
	}
	
	/**_____________________________________
	 * import_mapping_data
	 * ____________________________________
	 *Function used to add a new documents
	 *
	 *input $array : a dictionnary that contains all fields for a document. $_POST is used if empty
	 * output : return document's id if exists , otherwise false
	 */
	function import_mapping_data($tablename, $filename, $separator=";"){
		global $db;
		$map=$db->select(tbl('importCSV_mapping'),'*',"`cb_table_name` = '".$tablename."'");
		// get field type for future conversion
		$query ='SHOW COLUMNS from '.tbl($tablename);
		$result=$db->Execute($query);
		while($row = mysqli_fetch_array($result)){
			$cbFieldType[$row['Field']]=$row['Type'];
			//echo $row['Field']." ".$row['Type']."<br>";
		}
		
		foreach ($map as $mapentry){
			$importfields[]=$mapentry['import_field_name'];
			$staticvalues[]=$mapentry['static_value'];
			$cbfields[]=$mapentry['cb_field_name'];

				
		}
		if (($handle = fopen($filename, "r")) !== FALSE) {
			$i=0;
			while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
				if ($i==0){
					$head=$data;
				}
				else {
					for ($j=0; $j<count($head); $j++){
						$mytbl[$head[$j]]=$data[$j];
					}
					$values=[];
					// Parse each mapping fields for the specified database table
					for ($j=0; $j<count($importfields); $j++){
						$field=$importfields[$j];

						// if staticvalue in not null then take this value instead of imported field value
						$val=$staticvalues[$j];
						if ($val != "")
							$value=$val;
						else
							$value=$mytbl[$field];

						//replace content of value field using the search_value and replace_value from the mapping table
						$search=$map[$j]["search_value"];
						$replace=$map[$j]["replace_value"];
						$tsearch=explode("#",$search);
						$treplace=explode("#",$replace);
						$value=str_replace($tsearch, $treplace, $value);
						
						
						//convert date to timestamp or date format before inserting into database
						switch ($cbFieldType[$map[$j][cb_field_name]]){
							case 'timestamp':
								//$value=strtotime(str_replace(["/"],["-"],$value));
								$value=date('Y-m-d',strtotime(str_replace(["/"],["-"],$value)));
								break;
							case 'date':
								$value=date('Y-m-d',strtotime(str_replace(["/"],["-"],$value)));
								break;
						}
						
						$values[]=$value;
					}
					$db->insert(tbl($tablename), $cbfields, $values);
				}
				$i++;
			}
			fclose($handle);
		}
	}
	

	/**_____________________________________
	 * import_join_data
	 * ____________________________________
	 *Function used to add a new documents
	 *
	 *input $array : a dictionnary that contains all fields for a document. $_POST is used if empty
	 * output : return document's id if exists , otherwise false
	 */
	function import_join_data($tablename, $filename, $separator=";"){
		global $db;
		
		$map=$db->select(tbl('importCSV_join'),'*',"`cb_tablejoin_name` = '".$tablename."'");
		if (count($map)==1){
			$map=$map[0];
			if (($handle = fopen($filename, "r")) !== FALSE) {
				$i=0;
				while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
					if ($i==0){
						$head=$data;
					}
					else {
						for ($j=0; $j<count($head); $j++)
							$mytbl[$head[$j]]=$data[$j];
						$query="SELECT `".$map["cb_table1_field"]."` from ".tbl($map["cb_table1_name"]);
						$query .= " WHERE `".$map["cb_table1_field_search"]  ."` = '" . $mytbl[$map["import_field1"]]."'";
						$result_id1=$db->_select($query);
						$query="SELECT `".$map["cb_table2_field"]."` from ".tbl($map["cb_table2_name"]);
						$query .= " WHERE `".$map["cb_table2_field_search"]  ."` = '" . $mytbl[$map["import_field2"]]."'";
						$result_id2=$db->_select($query);
						if (count($result_id1)==1 && count($result_id2)==1){
							$query="INSERT INTO ".tbl($map['cb_tablejoin_name']);
							$query.=" (`".$map['cb_tablejoin_field1']."`, `".$map['cb_tablejoin_field2']."`) ";
							$query.=" VALUES ('".$result_id1[0][$map['cb_table1_field']]."', '".$result_id2[0][$map['cb_table2_field']]."') ";
							$db->Execute($query);
						}
					}
					$i++;
				}
				fclose($handle);
			}
		}
	}
	

}

?>