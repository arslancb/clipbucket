<?php

// Global Object $importCSVobject is used in the plugin
$importCSVobject = new importCSVobject();
$Smarty->assign_by_ref('importCSVobject', $importCSVobject);


/**
 * Contains all actions that can affect the  document plugin 
 */
class importCSVobject extends CBCategory{
	
	/**
	 * Constructor for importCSVobject's instances
	 */
	function importCSVobject()	{
	}
	
	/**
	 * import a CSV file for mapping tables and fields
	 * 
	 * Get the correspondance between tables & fields from the imported database to the CB database.
	 *
	 * @param string $filename 
	 * 		the CSV file name to upload
	 * @param string $separator
	 * 		the separator char used in the file. The default value is ';'
	 * @see the README.md file describe the needed file format
	 */
	function importMappingModel($filename, $separator=";"){
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
					$flag=false;
					while($row = mysqli_fetch_array($result)){
						//echo $row['Field']." ".$row['Type']."<br>";
						$flag=true;
					}
					if (!$flag){
						$db->Execute("ALTER TABLE ".tbl($mytbl['cb_table_name'])." ADD `".$mytbl['cb_field_name']."` ".$mytbl['cb_field_type']." NULL");
					}
					$fields_query="";
					$values_query="";
					for ($j=0; $j< count($head); $j++) {
						$fields_query .= $head[$j];
						$values_query .= "'".mysql_clean($mytbl[$head[$j]])."'"; 
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

	/**
	 * Cleanup the importCSV_mapping table
	 */
	function deleteMappingModel(){
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
	
	/**
	 * import a CSV file for mapping join tables 
	 * 
	 * Get the correspondance between  join tables  from the imported database to the CB database.
	 *
	 * @param string $filename 
	 * 		the CSV file name to upload
	 * @param string $separator
	 * 		the separator char used in the file. The default value is ';'
	 * @see the README.md file describe the needed file format
	 */
	function importJoinModel($filename, $separator=";"){
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
	
	/**
	 * Import a CSV file containing data from the source database to CB database table
	 * 
	 * Each mapping fields imported with importMappingModel() function will be imported into CB.
	 *
	 * @param string $tablename
	 * 		the name of the source database table you are importing
	 * @param string $filename 
	 * 		the CSV file name to upload
	 * @param string $separator
	 * 		the separator char used in the file. The default value is ';'
	 */
	function importMappingData($tablename, $filename, $separator=";"){
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
			//while (($data = fgetcsv($handle, 100000, $separator)) !== FALSE) {
			while (($line = fgets($handle)) !== FALSE) {
				$line=str_replace("\n","",$line);
				$line=str_replace("\r","",$line);
				$data=explode($separator,$line);
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
						$value=str_replace('\n',"\n",str_replace($tsearch, $treplace, $value));
						
						
						//convert date to timestamp or date format before inserting into database
						switch ($cbFieldType[$map[$j][cb_field_name]]){
							case 'timestamp':
								if (strpos($value,"/") !== FALSE)
									$value=date('Y-m-d',strtotime(str_replace(["/"],["-"],$value)));
								break;
							case 'date':
								if (strpos($value,"/") !== FALSE)
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
	

	/**
	 * Import a CSV file containing data from the source database join tables to CB database table
	 * 
	 * Each mapping fields imported with importMappingModel() function will be imported into CB.
	 *
	 * @param string $tablename
	 * 		the name of the source database join table you are importing
	 * @param string $filename 
	 * 		the CSV file name to upload
	 * @param string $separator
	 * 		the separator char used in the file. The default value is ';'
	 */
	function importJoinData($tablename, $filename, $separator=";"){
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
							$query.=" VALUES ('".mysql_clean($result_id1[0][$map['cb_table1_field']])."', '".
								mysql_clean($result_id2[0][$map['cb_table2_field']])."') ";
							$db->Execute($query);
						}
					}
					$i++;
				}
				fclose($handle);
			}
		}
	}
	
	function generateVideoFileNames(){
		global $db;
		$query="SELECT * FROM ".tbl('video');
		$result=$db->_select($query);
		foreach ($result as $v){
			$file_directory = create_dated_folder(NULL,$v['date_added']);
			$query="UPDATE ".tbl('video')." SET `file_directory`='".$file_directory."' WHERE `videoid`=".$v['videoid'];
			$db->Execute($query);
			$tmp="".strtotime($v['date_added']);
			if (strpos($v["file_name"],$tmp) === false) {
				$file_name = strtotime($v['date_added']).RandomString(5);
				$query="UPDATE ".tbl('video')." SET `file_name`='".$file_name."' WHERE `videoid`=".$v['videoid'];
				$db->Execute($query);
			}
		}
	}
	
}

?>