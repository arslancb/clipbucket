<?php
/*
 * This file contains linkquery class and some usefull functions used in this plugin
 */ 


// Global Object $documentquery is used in the plugin
$documentquery = new documentquery();
$Smarty->assign_by_ref('documentquery', $documentquery);


/**_____________________________________________________
 * mimetype_check
 * _____________________________________________________
 * Return True if uploaded file mimetype is allowed or not
 * input $mime : a string that contains the uploaded file mimetype
 * output : return true if it's ok otherwise false
 */
function mimetype_check($mime){
	$allowed = array('application/doc', 'application/pdf', 'image.png', 'image.jpeg'); //allowed mime-type
	return (in_array($mime, $allowed)); 	  //Check uploaded file type
}

/**_____________________________________________________
 * filesize_check
 * _____________________________________________________
 * Return True if upload file size is under a specified value or not
 * input $size : a string that contains the value of the size
 * output : return true if it's ok otherwise false
 */
function filesize_check($size){
	$a=$size;
	global $db;
	$req=" name = 'document_max_filesize'";
	$res=$db->select(tbl('config'),'*',$req,false,false,false);
	//return $size < 25000000;
	return $size < $res[0]["value"];
}



/**_____________________________________________________
 * Class documentquery
 * _____________________________________________________
 *Contains all actions that can affect the  document plugin 
 */
class documentquery extends CBCategory{
	private $basic_fields = array();
	
	/**_____________________________________
	 * documentquery
	 * _____________________________________
	 *Constructor for documentquery's instances
	 */
	function documentquery()	{
		global $cb_columns;
		$basic_fields = array('id', 'title','filename','size','creationdate','storedfilename','mimetype');
		$cb_columns->object( 'documents' )->register_columns( $basic_fields );
		$basic_fields = array('id', 'video_id','document_id');
		$cb_columns->object( 'video_documents' )->register_columns( $basic_fields );
	}

	/**_____________________________________
	 * add_document
	 * ____________________________________
	 *Function used to add a new documents 
	 *
	 *input $array : a dictionnary that contains all fields for a document. $_POST is used if empty
	 * output : return document's id if exists , otherwise false
	 */
	function add_document($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array);
		if(!error()) {
			$title=mysql_clean($array['title']);
			$filename=mysql_clean($array['filename']);
			$mimetype=mysql_clean($array['mimetype']);
			$size=mysql_clean($array['size']);
			$storedfilename=mysql_clean($array['storedfilename']);
			$req=" title = '$title' AND filename='$filename'";
			$res=$db->select(tbl('documents'),'id',$req,false,false,false);
			// test document's unicity
			if (count($res)>0){
				e(lang("document_already_exists"));
				return false;
			}
			else {
				// insert document
				$db->insert(tbl('documents'), array('title','filename','mimetype','size','storedfilename'), array($title,$filename,$mimetype,$size,$storedfilename));
				$res=$db->select(tbl('documents'),'id',$req,false,false,false);
				$id=$res[0]['id'];
				return $id;		
			}
		}
	}
	
	/**_____________________________________
	 * update_document
	 * ____________________________________
	 *Function used to update a documents 
	 *
	 *input $array : a dictionnary that contains all fields for a document. $_POST is used if empty
	 * output : return document's id if exists , otherwise false
	 */
	function update_document($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array);
		if(!error()) {
			$title=mysql_clean($array['title']);
			$filename=mysql_clean($array['filename']);
			$mimetype=mysql_clean($array['mimetype']);
			$size=mysql_clean($array['size']);
			$storedfilename=mysql_clean($array['storedfilename']);
			$documentid=mysql_clean($array['documentid']);
			$req=" title = '$title' ";
			$res=$db->select(tbl('documents'),'id',$req,false,false,false);
			// test document's unicity
			if (count($res)>0){
				e(lang("document_already_exists"));
				return false;
			}
			else {
				// update documents
				$db->update(tbl('documents'), array('title','filename','mimetype','size','storedfilename'), 
						array($title,$filename,$mimetype,$size,$storedfilename),"id='$documentid'");
				return $documentid;		
			}
		}
	}
	
	/**_____________________________________
	 * search_document
	 * ____________________________________
	 *Function used to test if a document already exists 
	 * 
	 *input $array : a dictionnary that contains fields for a document. $_POST is used if empty
	 * output : return true if document exists , otherwise false
	 */
	function search_document($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array,false);
		if(!error()) {
			$title=$array['title'];
			$req=" title like '%".title."%'";
			$res=$db->select(tbl('documents'),'*',$req,false,false,false);
			// test document's unicity
			if (count($res)>0){
				$s="";
				for ($i=0; $i<count($res); $i++)
					$s=$s.$res[$i]['title'].', ';
				e(lang("document_already_exists")." : ".$s,"w");
				return true;
			}
			else {
				e(lang("document_does_not_exist"),"m");
				return false;
			}
		}
	}
	
	/**_____________________________________
	 * get_documents
	 * ____________________________________
	 *Function used to get documents 
	 *
	 *input $params : is a dictionary containing information about the requested documents
	 *				$params['limit'] is for pagination (ie '0.100')
	 *				$params['order'] is for ordering
	 *				$params['cond'] is the "where" condition of the sql request
	 * 			$params['count_only'] used only if we want to retrive number of documents
	 * 			$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template
	 * output : return specified documents
	 */
	function get_documents($params=NULL)	{
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['cond']) {
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".$params['cond']." ";
		}
		if(!$params['count_only']) {
			$fields = array('document' => $cb_columns->object('documents')->get_columns(),);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('documents')." AS document ";
			if ($cond) 
				$query .= " WHERE ".$cond;
			if ($order)
				$query .= " ORDER BY ".$order;
			if ($limit)
				$query .= " LIMIT  ".$limit;
			//$result = $db->select(tbl('users'),'*',$cond,$limit,$order);
			$result = select( $query );
		}
		if($params['count_only']){
			$result = $db->count(tbl('documents')." AS document ",'id',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
	}

	/**_____________________________________
	 * get_document_for_video
	 * ____________________________________
	 *Function used to get documents for a specific video
	 *
	 *input $params : is a dictionary containing information about the requested documents
	 *				$params['limit'] is for pagination (ie '0.100')
	 *				$params['order'] is for ordering
	 *				($params['selected'] if =="yes" returns documents linked to the video
	 *										 if =="no" returns documents not linked to the video
	 *				$params['videoid'] is the video's id
	 *				$params['cond'] is the "where" condition of the sql request
	 * 			$params['count_only'] used only if we want to retrive number of documents
	 * 			$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template
	 * output : return related documents
	 */
	function get_document_for_video($params=NULL){
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['selected']=='yes' && $params['videoid']) {// return only documents that are linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " video_id = '".$params['videoid']."' ";
		}
		if($params['selected']=='no' && $params['videoid']) {// return only documents that are not linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
			else 
				$cond .= "  documents.id NOT IN (SELECT documents2.id as id2 FROM ".tbl('documents')." AS documents2 LEFT JOIN " 
					 .tbl('video_documents')." AS video_documents2 ON documents2.id=video_documents2.document_id 
					 		WHERE video_id=".$params['videoid'].')'; 
		}
		if($params['cond']) {
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".$params['cond']." ";
		}
	
	
		if(!$params['count_only']) {
			$fields = array(
					'documents' => $cb_columns->object('documents')->get_columns(),
					'video_documents' => $cb_columns->object('video_documents')->get_columns(),
			);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('documents')." AS documents LEFT JOIN " 
					 .tbl('video_documents')." AS video_documents ON documents.id=video_documents.document_id";
			// add alias on video_documents.id to avoid any conflict between documents.id and video_documents.id
			$query = str_replace(' video_documents.id',' video_documents.id as vid',$query);
				
			if ($cond) 
				$query .= " WHERE ".$cond;
			if ($order)
				$quer .= " ORDER BY ".$order;
			if ($limit)
				$query .= " LIMIT  ".$limit;
			$result = select( $query );
		}
		if($params['count_only']){
			$result = $db->count(tbl('documents')." AS documents ",'*',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
		
	}
	
	
	/**_____________________________________
	 * document_exists
	 * ____________________________________
	 *Test if document's id exists or not 
	 *
	 *input $id : is the document's id
	 *output : true if document exists otherwise false
	 */
	function document_exists($id){
		global $db;
		$result = $db->count(tbl('documents'),"id"," id='".$id."'");
		return ($result>0);
	}
	
	
	/**_____________________________________
	 * get_document_details
	 * ____________________________________
	 *Function used to get document details using it's id 
	 *
	 *input $id : document's id
	 *output : a dictionary containig each fields for a document, false if no document found
	 */
	function get_document_details($id=NULL)	{
		global $db;
		$fields = tbl_fields(array('documents' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('documents');
		$query .= " WHERE documents.id = '$id'";
		$result = select($query);
		Assign('document', $result);
		if ($result) {
			$details = $result[0];
			return $details;
		}
		return false;
	}
	
	/**_____________________________________
	 * delete_document
	 * ____________________________________
	 *Remove document from the database. 
	 *TODO : if the document is associated to a video, then nothing is done, just an error message appears.
	 *input $id : the id of the document to be deleted 
	 */
	function delete_document($id) {
		global $db;
		if($this->document_exists($id)) {
			$details = $this->get_document_details($id);
			$test2=$db->execute("DELETE FROM ".tbl("documents")." WHERE id='$id'");
			if (!$test2)
				e(lang("cant_del_linked_document_msg")." id=".$id,"e");
			else {
				unlink(DOCUMENT_DOWNLOAD_DIR."/".$details['storedfilename']);
				e(lang("document_del_msg")." id=".$id,"m");
			}
		}else{
			e(lang("document_does_not_exist"));
		}
	}
	
	/**_____________________________________
	 * link_document
	 * ____________________________________
	 *Associate a document to video 
	 *
	 *input $id : document's id
	 *			$videoid : the video's id
	 */
	function link_document($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_documents'),'*',"document_id=".$id.	" and video_id=".$videoid);
		if ($cnt==0)
			$db->insert(tbl('video_documents'), array('video_id','document_id'), array(mysql_clean($videoid),mysql_clean($id)));
	}

	/**_____________________________________
 	 * unlink_document
 	 * ____________________________________
	 *Remove associate between a document and a video 
	 *
	 *input $id : document's id
	 *			$videoid : the video's id
	 */
	function unlink_document($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_documents'),'*',"document_id=".$id.	" and video_id=".$videoid);
		if ($cnt>0)
		$db->execute("DELETE FROM ".tbl("video_documents")." WHERE video_id='$videoid' AND document_id='$id'");
	}
	
	/**_____________________________________
 	 * load_documents_fields
 	 * ____________________________________
 	 *Create initial array for document fields 
	 * this will tell
	 * array(
	 *       title [text that will represents the field]
	 *       type [One of the following values : textfield, password,texarea, checkbox,radiobutton, dropbox]
	 *       name [name of the fields, input NAME attribute]
	 *       id [id of the fields, input ID attribute]
	 *       value [value of the fields, input VALUE attribute]
	 *       size
	 *       class [CSS class of the field]
	 *       label
	 *       extra_tags [Extra tags added as is to the field]
	 *       hint_1 [hint before field]
	 *       hint_2 [hint after field]
	 *       anchor_before [anchor before field]
	 *       anchor_after [anchor after field]
	 *      )
	 *
 	 *input $input : a dictionary with document's informations (if null $_POST is used)
	 *		$strict : if trus then field is requiered in the data form
 	 *output : Fields for the administration page of the plugin
 	 */
	function load_documents_fields($input=NULL,$strict=true) {
		global $LANG,$Cbucket;
		$default = array();
		if(isset($input))
			$default = $input;
		if(empty($default))
			$default = $_POST;
	
		$title = (isset($default['title'])) ? $default['title'] : "";
		$filename = (isset($default['filename'])) ? $default['filename'] : "";
		$size = (isset($default['size'])) ? $default['size'] : "";
		$filename = (isset($default['filename'])) ? $default['filename'] : "";
		
		$my_fields = array (
				'title' => array(
						'title'=> lang('title'),
						'type'=> "textfield",
						'name'=> "title",
						'id'=> "title",
						'value'=> $title,
						'db_field'=>'title',
						'required'=>($strict) ? 'yes' : 'no',
						//'hint_1'=> lang('user_allowed_format'),
						//'hint_2'=> lang('user_allowed_format'),
						// 'syntax_type'=> 'username',
						//'validate_function'=> 'username_check',
						//'function_error_msg' => lang('user_contains_disallow_err'),
						//'db_value_check_func'=> 'user_exists',
						//'db_value_exists'=>false,
						//'db_value_err'=>lang('usr_uname_err2'),
						//'min_length'	=> config('min_username'),
						//'max_length' => config('max_username'),
				),
				'filename' => array(
						'title'=> lang('filename'),
						'type'=> "textfield",
						'name'=> "filename",
						'id'=> "filename",
						'value'=> $filename,
						'db_field'=>'filename',
						'required'=>($strict) ? 'yes' : 'no',
				),
				'storedfilename' => array(
						'title'=> lang('storedfilename'),
						'type'=> "textfield",
						'name'=> "storedfilename",
						'id'=> "storedfilename",
						'value'=> $storedfilename,
						'db_field'=>'storedfilename',
						'required'=>($strict) ? 'yes' : 'no',
				),
				'size' => array(
						'title'=> lang('size'),
						'type'=> "textfield",
						'name'=> "size",
						'id'=> "size",
						'value'=> $size,
						'db_field'=>'size',
						'required'=>($strict) ? 'yes' : 'no',
						'validate_function'=> 'filesize_check',
						'function_error_msg' => lang('file_is_too_big'),
				),
				'mimetype' => array(
						'title'=> lang('mimetype'),
						'type'=> "textfield",
						'name'=> "mimetype",
						'id'=> "mimetype",
						'value'=> $mimetype,
						'db_field'=>'mimetype',
						'required'=>($strict) ? 'yes' : 'no',
						'validate_function'=> 'mimetype_check',
						'function_error_msg' => lang('filetype_not_allowed'),
				),
		);
		return $my_fields;
	}

	/**_____________________________________
	 * validate_form_fields
	 * ____________________________________
	 *Validate document's administion form fields (Add and Edit forms) 
	 *
 	 *input $input : a dictionary with document's informations (if null $_POST is used)
	 *		$strict : if trus then field is requiered in the data form
	 *output : true if the form is valid, otherwise false
	 */
	function validate_form_fields($array=NULL,$strict=true) {
		$fields= $this->load_documents_fields($array,$strict);
		if($array==NULL)
			$array = $_POST;
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
	
		validate_cb_form($fields,$array);
	}
	
	
}

?>