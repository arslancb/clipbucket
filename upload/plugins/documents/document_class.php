<?php

// Global Object $documentquery is used in the plugin
$documentquery = new Document();
$Smarty->assign_by_ref('documentquery', $documentquery);


/**
 * Check for autorized upload file types
 * 
 * @param str $mine
 * 		the mimetype of the uploaded file
 * @return bool
 * 		true if uploaded file mimetype is allowed otherwiseor false
 */
function documentMimetypeCheck($mime){
	$allowed = array('application/msword', 'application/mspowerpoint','application/excel',
			'application/pdf', 'image/png', 'image/jpeg',
								
	); //allowed mime-type
	return (in_array($mime, $allowed)); 	  //Check uploaded file type
}

/**
 * Generate UUID
 * @see https://gist.github.com/dahnielson/508447 for original source code
 */
function uuid(){
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff),
			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),
			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,
			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,
			// 48 bits for "node"
			mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
			);
}

/**
 * Check if the uploaded file size is allowed
 * 
 * $parap int $size
 * 		the size of the uploaded file
 * $return bool 
 * 		true if upload file size is under the value stored in the database otherwise false
 */
function documentFilesizeCheck($size){
	$a=$size;
	global $db;
	$req=" name = 'document_max_filesize'";
	$res=$db->select(tbl('config'),'*',$req,false,false,false);
	//return $size < 25000000;
	return $size < $res[0]["value"];
}



/**
 * Contains all actions that can affect the  document plugin 
 */
class Document extends CBCategory{
	private $basic_fields = array();
	
	/**
	 * Constructor for documentquery's instances
	 */
	function Document()	{
		global $cb_columns;
		$basic_fields = array('id', 'documentkey','title','filename','size','creationdate','storedfilename','mimetype');
		$cb_columns->object( 'documents' )->register_columns( $basic_fields );
		$basic_fields = array('id', 'video_id','document_id');
		$cb_columns->object( 'video_documents' )->register_columns( $basic_fields );
	}

	/**
	 * Add a new documents 
	 *
	 *	@param array $array
	 *		a dictionnary that contains all fields for a document. $_POST is used if empty
	 * @return bool|int
	 * 		the document's id if it exists otherwise false
	 * @see validateFormFields for compleate list of fields in $array
	 */
	function addDocument($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validateFormFields($array);
		if(!error()) {
			$title=mysql_clean($array['title']);
			$filename=mysql_clean($array['filename']);
			$mimetype=mysql_clean($array['mimetype']);
			$size=mysql_clean($array['size']);
			$key=uuid();
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
				$db->insert(tbl('documents'), array('documentkey','title','filename','mimetype','size','storedfilename'), array($key,$title,$filename,$mimetype,$size,$storedfilename));
				$res=$db->select(tbl('documents'),'id',$req,false,false,false);
				$id=$res[0]['id'];
				return $id;		
			}
		}
	}
	
	/**
	 * update a documents from a data form
	 * 
	 * @param array $array
	 * 		a dictionnary that contains all fields for a document. $_POST is used if empty
	 * @return bool|int
	 * 		the document's id if exists otherwise false
	 * @see validateFormFields for compliete list of fields in $array
	 */
	function updateDocument($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validateFormFields($array);
		if(!error()) {
			$title=mysql_clean($array['title']);
			$filename=mysql_clean($array['filename']);
			$mimetype=mysql_clean($array['mimetype']);
			$size=mysql_clean($array['size']);
			$storedfilename=mysql_clean($array['storedfilename']);
			$documentid=mysql_clean($array['documentid']);
			$req=" title = '$title' ";
			$res=$db->select(tbl('documents'),'id',$req,false,false,false);
			// test document's existence
			if (count($res)==0){
				e(lang("document_does_not_exist"));
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
	
	/**
	 * Check if a document already exists 
	 * 
	 * @param array $array 
	 * 		a dictionnary that contains fields for a document. $_POST is used if empty
	 * @return bool
	 * 		true if document exists otherwise false
	 * @see validateFormFields for compliete list of fields in $array
	 */
	function searchDocument($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validateFormFields($array,false);
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
	
	/**
	 * Get all documents speficied by the $param attribute 
	 *
	 * @param array $params
	 * 		a dictionary containing information about the requested documents
	 *		<ul>
	 *			<li>$params['limit'] is for pagination (ie '0.100')</li>
	 *			<li>$params['order'] is for ordering</li>
	 *			<li>$params['cond'] is the "where" condition of the sql request</li>
	 * 			<li>$params['count_only'] used only if we want to retrive number of documents</li>
	 * 			<li>$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template</li>
	 * 		</ul>
	 * @return int|array
	 * 		the number of documents if $params['count_only'] is set otherwise an array of all specified documents objects
	 */
	function getDocuments($params=NULL)	{
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
			$result = $db->_select($query);
		}
		if($params['count_only']){
			$result = $db->count(tbl('documents')." AS document ",'id',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
	}

	/**
	 * Get documents relatively to a specific video
	 * 
	 * Depending on the $params['selected'] value it get all documents linked to th video or all documents non linked to the video 
	 *
	 * @param array $params 
	 * 		a dictionary containing information about the requested documents
	 *		<ul>
	 *			<li>$params['limit'] is for pagination (ie '0.100')</li>
	 *			<li>$params['order'] is for ordering</li>
	 *			<li>$params['selected'] if =="yes" returns documents linked to the video, 
	 *									if =="no" returns documents not linked to the video</li>
	 *			<li>$params['videoid'] is the video's id</li>
	 *			<li>$params['cond'] is the "where" condition of the sql request</li>
	 * 			<li>$params['count_only'] used only if we want to retrive number of documents</li>
	 * 			<li>$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template</li>
	 * 		</ul>
	 * @return int|array
	 * 		the number of documents if $params['count_only'] is set otherwise an array of all specified documents objects
	 */
	function getDocumentForVideo($params=NULL){
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['selected']=='yes' && $params['videoid']) {// return only documents that are linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
				$cond .= "  documents.id IN (SELECT documents2.id as id2 FROM ".tbl('documents')." AS documents2 LEFT JOIN "
						.tbl('video_documents')." AS video_documents2 ON documents2.id=video_documents2.document_id
					 		WHERE video_id=".$params['videoid'].')';
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
			);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('documents')." AS documents"; 
			// add alias on video_documents.id to avoid any conflict between documents.id and video_documents.id
			$query = str_replace(' video_documents.id',' video_documents.id as vid',$query);
				
			if ($cond) 
				$query .= " WHERE ".$cond;
			if ($order)
				$query .= " ORDER BY ".$order;
			if ($limit)
				$query .= " LIMIT  ".$limit;
			$result = $db->_select($query);
		}
		if($params['count_only']){
			$result = $db->count(tbl('documents')." AS documents ",'*',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
		
	}
	
	
	/**
	 * Check if the document's id exists or not 
	 *
	 * $param int  $id : 
	 * 		the document's id
	 * @return bool	
	 * 		true if document exists otherwise false
	 */
	function documentExists($id){
		global $db;
		$result = $db->count(tbl('documents'),"id"," id='".$id."'");
		return ($result>0);
	}
	
	
	/**
	 * Get document details using it's id 
	 *
	 *	@param int|string $id 
	 *		if $id is numeric the id of the document object otherwise the documentkey of the document object
	 * @return bool|array 
	 * 		a dictionary containing each fields for a document or false if no document found
	 */
	function getDocumentDetails($id=NULL)	{
		global $db;
		$fields = tbl_fields(array('documents' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('documents');
		if (is_numeric($id)) 
			$query .= " WHERE documents.id = '$id'";
		else
			$query .= " WHERE documents.documentkey = '$id'";
		$result = select($query);
		Assign('document', $result);
		if ($result) {
			$details = $result[0];
			return $details;
		}
		return false;
	}
	
	/**
	 * Remove the document from the database and file system.
	 *  
	 * The removal is only treated if the document is not linked to a video. 
	 * If it is associated to a video, then nothing is done, just an error message appears.
	 * 
	 * @param int $id 
	 * 		the id of the document to be deleted 
	 */
	function deleteDocument($id) {
		global $db;
		if($this->documentExists($id)) {
			$details = $this->getDocumentDetails($id);
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
	
	/**
	 * Associate a document to video 
	 *
	 * @param int $id 
	 * 		the document's id
	 * @param int $videoid
	 * 		the video's id
	 */
	function linkDocument($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_documents'),'*',"document_id=".$id.	" and video_id=".$videoid);
		if ($cnt==0)
			$db->insert(tbl('video_documents'), array('video_id','document_id'), array(mysql_clean($videoid),mysql_clean($id)));
	}

	/**
	 * Remove associate between a document and a video 
	 *
	 * @param int $id 
	 * 		the document's id
	 * @param int $videoid
	 * 		the video's id
	 */
	function unlinkDocument($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_documents'),'*',"document_id=".$id.	" and video_id=".$videoid);
		if ($cnt>0)
		$db->execute("DELETE FROM ".tbl("video_documents")." WHERE video_id='$videoid' AND document_id='$id'");
	}

	/**
	 * Remove associate between any document and a video
	 *
	 * @param int $videoid
	 * 		the video's id
	 */
	function unlinkAllDocuments($videoid) {
		global $db;
		$cnt= $db->count(tbl('video_documents'),'*',"video_id=".$videoid);
		if ($cnt>0){
			$db->execute("DELETE FROM ".tbl("video_documents")." WHERE video_id='$videoid' ");
			e(lang("documents_have_been_disconected"),'m');
		}
	}
	
	
	/**
 	 * Create initial array for document fields 
 	 * 
	 * @param array $input 
	 * 		a dictionary with document's informations (if null $_POST is used)
	 * @param bool $strict
	 * 		if true then field is requiered in the data form. Default value is true.
	 * @return array
	 * 		Fields for the administration page of the plugin. 
	 * 		Fields are ('title','filename','storedfilename','size','mimetype'). For each field this will tell
	 * 		<br/>array(
	 *      <br/>title [text that will represents the field]
	 *      <br/>type [One of the following values : textfield, password,texarea, checkbox,radiobutton, dropbox]
	 *      <br/>name [name of the fields, input NAME attribute]
	 *      <br/>id [id of the fields, input ID attribute]
	 *      <br/>value [value of the fields, input VALUE attribute]
	 *      <br/>size
	 *      <br/>class [CSS class of the field]
	 *      <br/>label
	 *      <br/>extra_tags [Extra tags added as is to the field]
	 *      <br/>hint_1 [hint before field]
	 *      <br/>hint_2 [hint after field]
	 *      <br/>anchor_before [anchor before field]
	 *      <br/>anchor_after [anchor after field]
	 *      <br/>)
 	 */
	function loadDocumentsFields($input=NULL,$strict=true) {
		global $LANG,$Cbucket;
		$default = array();
		if(isset($input))
			$default = $input;
		if(empty($default))
			$default = $_POST;
	
		$title = (isset($default['title'])) ? $default['title'] : "";
		$filename = (isset($default['filename'])) ? $default['filename'] : "";
		$size = (isset($default['size'])) ? $default['size'] : "";
		$mimetype = (isset($default['mimetype'])) ? $default['mimetype'] : "";
		$storedfilename = (isset($default['$storedfilename'])) ? $default['$storedfilename'] : "";
		
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
						'validate_function'=> 'documentFilesizeCheck',
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
						'validate_function'=> 'documentMimetypeCheck',
						'function_error_msg' => lang('filetype_not_allowed'),
				),
		);
		return $my_fields;
	}

	/**
	 * Validate document's administration form fields (Add and Edit forms) 
	 *
 	 * @param array $input
 	 * 		a dictionary with document's informations (if null $_POST is used)
	 *	@param array $strict
	 *		if true then field is requiered in the data form. Default value is true
	 * @return bool
	 * 		true if the form is valid otherwise false
	 * @see loadDocumentsFields for more information about $array content
	 */
	function validateFormFields($array=NULL,$strict=true) {
		$fields= $this->loadDocumentsFields($array,$strict);
		if($array==NULL)
			$array = $_POST;
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		validate_cb_form($fields,$array);
	}

	/**
	 * Used encode photo key
	 */
	function encode_key($key)
	{
		return base64_encode(serialize($key));
	}
	
	/**
	 * Used encode photo key
	 */
	function decode_key($key)
	{
		return unserialize(base64_decode($key));
	}
	
	/**
	 * Download a document and change it's name on the fly
	 * 
	 * @param int $id
	 * 		the document's id
	 * @todo
	 * 		Need to control download permissions by checking if there is almost one  video which is active, 
	 *		correctly encoded, and public or passworded or visible only if logged... before allowing the donwload itself 
	 */
	function download($id)	{
		/** @see photo.class.php :: $file = $this->ready_photo_file($key); */
		$file= $this->getDocumentDetails($id);
		if($file) {
			$p = $file['details'];
			$mime=$file['mimetype'];
			$filepath=DOCUMENT_DOWNLOAD_DIR.'/'.$file['storedfilename'];
			if(file_exists($filepath)) {
				if(is_readable($filepath)) {
					$size = filesize($filepath);
					if($fp=@fopen($filepath,'r')) {
						// sending the headers
						header("Content-type: $mime");
						header("Content-Length: $size");
						header("Content-Disposition: attachment; filename=\"".$file['filename']."\"");
						// send the file content
						fpassthru($fp);
						// close the file
						fclose($fp);
						// and quit
						exit;
					}
				} else {
					e(lang("document_not_readable"));
				}
			} else {
				e(lang("document_not_exist"));
			}
		} else
			return false;
	}
	
}

?>