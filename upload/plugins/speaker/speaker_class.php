<?php
/*
 * This file contains speakerquery class and some usefull functions used in this plugin
 */ 


// Global Object $speakerquery is used in the plugin
$speakerquery = new speakerquery();
$Smarty->assign_by_ref('speakerquery', $speakerquery);

/**_____________________________________
 * slugify
 * _____________________________________
 *Transform text to a slug (remove non alphabetic or numeric chars, transform in lowercase string)
 *
 *input $text : string to be normalized
 *output : a string corresponding to the slug
 *
 *WARNING: "php5-intl" package is required
 */
function slugify($text) {
	// replace non letter or digits by -
	$text = preg_replace('~[^\pL\d]+~u', '-', $text);
	$text=preg_replace('/\pM*/u','',normalizer_normalize( $text, Normalizer::FORM_D));
	// transliterate
	$text = iconv('utf-8', 'ASCII//TRANSLIT', $text);
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);
	// trim
	$text = trim($text, '-');
	// remove duplicate -
	$text = preg_replace('~-+~', '-', $text);
	// lowercase
	$text = strtolower($text);
	if (empty($text)) {
		return 'n-a';
	}
	return $text;
}

function prepareSlug($firstname, $lastname) {
	return $firstname.'-'.$lastname;
	//return substr($firstname,0,4).'-'.$lastname;
}

/**_____________________________________
 * speaker_role_check
 * _____________________________________
 *Validate speaker's roles if there's no empty role added
 *
 *input $val : is an array containing all speaker's roles
 */
function speaker_role_check($val) {
	foreach ($val as $v) {
		if ($v=="")
			return false;
	}
	return true;
}

/**_____________________________________________________
 * Class speakerquery
 * _____________________________________________________
 *Contains all actions that can affect the speaker's plugin 
 */
class speakerquery extends CBCategory{
	private $basic_fields = array();
	private $extra_fields = array();
	
	/**_____________________________________
	 * speakerquery
	 * _____________________________________
	 *Constructor for speakerquery's instances
	 */
	function speakerquery()	{
		global $cb_columns;
		$basic_fields = array('id', 'firstname','lastname', 'slug', 'photo');
		$cb_columns->object( 'speakers' )->register_columns( $basic_fields );
		$basic_fields = array('id', 'description','speaker_id');
		$cb_columns->object( 'speakerfunction' )->register_columns( $basic_fields );
		$basic_fields = array('id', 'video_id','speakerfunction_id');
		$cb_columns->object( 'video_speaker' )->register_columns( $basic_fields );
	}

	/**_____________________________________
	 * add_speaker
	 * ____________________________________
	 *Function used to add a new speakers 
	 *
	 *input $array : a dictionnary that contains all fields for a speaker. $_POST is used if empty
	 * output : return speaker's id if exists , otherwise false
	 */
	function add_speaker($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array);
		if(!error()) {
			$firstname=mysql_clean($array['firstname']);
			$lastname=mysql_clean($array['lastname']);
			//$slug=mysql_clean($array['slug']);
			$slug=slugify(prepareSlug($firstname,$lastname));
			$req=" firstname = '$firstname' AND lastname='$lastname'";
			$res=$db->select(tbl('speaker'),'id',$req,false,false,false);
			// test speaker's unicity
			if (count($res)>0){
				e(lang("speaker_already_exists"));
				return false;
			}
			else {
				// insert speaker
				$db->insert(tbl('speaker'), array('firstname','lastname','slug','photo'), array($firstname,$lastname,$slug,''));
				$res=$db->select(tbl('speaker'),'id',$req,false,false,false);
				$id=$res[0]['id'];
				$desc=$array['description'];
				// insert speaker roles
				for ($i=0; $i<count($desc); $i++)
					$db->insert(tbl('speakerfunction'), array('description','speaker_id'), array(mysql_clean($desc[$i]),$id));
				return $id;		
			}
		}
	}
	
	/**_____________________________________
	 * update_speaker
	 * ____________________________________
	 *Function used to update a speakers 
	 *
	 *input $array : a dictionnary that contains all fields for a speaker. $_POST is used if empty
	 * output : return speaker's id if exists , otherwise false
	 */
	function update_speaker($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array);
		if(!error()) {
			$firstname=mysql_clean($array['firstname']);
			$lastname=mysql_clean($array['lastname']);
			//$slug=mysql_clean($array['slug']);
			$speakerid=mysql_clean($array['speakerid']);
			$slug=slugify(prepareSlug($firstname,$lastname));
			$req=" firstname = '$firstname' AND lastname='$lastname' AND id<>$speakerid";
			$res=$db->select(tbl('speaker'),'id',$req,false,false,false);
			// test speaker's unicity
			if (count($res)>0){
				e(lang("speaker_already_exists"));
				return false;
			}
			else {
				// update speaker
				$db->update(tbl('speaker'), array('firstname','lastname','slug','photo'), array($firstname,$lastname,$slug,''),"id='$speakerid'");
				$req=" speaker_id=$speakerid";
				$res=$db->select(tbl('speakerfunction'),'*',$req,false,false,false);
				$roleids=$array['roleids'];
				$nbdeleted=0;
				$desc=$array['description'];
				for ($i=0; $i<count($res); $i++){
					$id=$res[$i]['id'];
					if (in_array($id, $roleids, true)){
						$db->update(tbl('speakerfunction'), array('description'), array($desc[$i-$nbdeleted]),"id='$id'");
					}
					else{
						$db->execute("DELETE FROM ".tbl("speakerfunction")." WHERE id='$id'");
						$nbdeleted++;
					}
				}
				for ($i=count($res); $i<count($desc)+$nbdeleted; $i++){
					$db->insert(tbl('speakerfunction'), array('description','speaker_id'), array(mysql_clean($desc[$i-$nbdeleted]),$speakerid));
				}
				return $speakerid;		
			}
		}
	}
	
	/**_____________________________________
	 * search_speaker
	 * ____________________________________
	 *Function used to test if a speakers exists testing the existance of  firstname & lastname fields
	 * 
	 *input $array : a dictionnary that contains fields for a speaker. $_POST is used if empty
	 * output : return true if speaker exists , otherwise false
	 */
	function search_speaker($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array,false);
		if(!error()) {
			$firstname=$array['firstname'];
			$lastname=$array['lastname'];
			$req=" firstname like '%".$firstname."%' AND lastname like '%".$lastname."%'";
			$res=$db->select(tbl('speaker'),'*',$req,false,false,false);
			// test speaker's unicity
			if (count($res)>0){
				$s="";
				for ($i=0; $i<count($res); $i++)
					$s=$s.$res[$i]['firstname'].' '.$res[$i]['lastname'].', ';
				e(lang("speaker_already_exists")." : ".$s,"w");
				return true;
			}
			else {
				e(lang("speaker_does_not_exist"),"m");
				return false;
			}
		}
	}
	
	/**_____________________________________
	 * get_speakers
	 * ____________________________________
	 *Function used to get speakers 
	 *
	 *input $params : is a dictionary containing information about the requested speakers
	 *				$params['limit'] is for pagination (ie '0.100')
	 *				$params['order'] is for ordering
	 *				$params['cond'] is the "where" condition of the sql request
	 * 			$params['count_only'] used only if we want to retrive number of speakers
	 * 			$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template
	 * output : return specified speakers
	 */
	function get_speakers($params=NULL)	{
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
			$fields = array('speaker' => $cb_columns->object('speakers')->get_columns(),);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('speaker')." AS speaker ";
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
			$result = $db->count(tbl('speaker')." AS speaker ",'id',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
	}

	/**_____________________________________
	 * get_speaker_and_roles
	 * ____________________________________
	 *Function used to get speakers and speaker's roles for a specific video
	 *
	 *input $params : is a dictionary containing information about the requested speakers
	 *				$params['limit'] is for pagination (ie '0.100')
	 *				$params['order'] is for ordering
	 *				($params['selected'] if =="yes" returns speakers linked to the video
	 *										 if =="no" returns speakers not linked to the video
	 *				$params['videoid'] is the video's id
	 *				$params['cond'] is the "where" condition of the sql request
	 * 			$params['count_only'] used only if we want to retrive number of speakers
	 * 			$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template
	 * output : return specified speakers and roles
	 */
	function get_speaker_and_roles($params=NULL){
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['selected']=='yes' && $params['videoid']) {// return only speaker functions that are linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " video_id = '".$params['videoid']."' ";
		}
		if($params['selected']=='no' && $params['videoid']) {// return only speakears functions that are not linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
			else 
				$cond .= "  speakerfunction.id NOT IN (SELECT speakerfunction2.id as id2 FROM ".tbl('speaker')." AS speaker2 LEFT JOIN " 
					.tbl('speakerfunction')." AS speakerfunction2 ON speaker2.id=speakerfunction2.speaker_id LEFT JOIN "
					 .tbl('video_speaker')." AS video_speaker2 ON speakerfunction2.id=video_speaker2.speakerfunction_id WHERE video_id=".$params['videoid'].')'; 
				//$cond .= "  NOT EXISTS SELECT * FROM tbl('video_speaker') speakerfunction_id <> speakerfunction.id ";
		}
		if($params['cond']) {
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".$params['cond']." ";
		}
	
	
		if(!$params['count_only']) {
			$fields = array(
					'speaker' => $cb_columns->object('speakers')->get_columns(),
					'speakerfunction' => $cb_columns->object('speakerfunction')->get_columns(),
					'video_speaker' => $cb_columns->object('video_speaker')->get_columns(),
			);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('speaker')." AS speaker LEFT JOIN " 
					.tbl('speakerfunction')." AS speakerfunction ON speaker.id=speakerfunction.speaker_id LEFT JOIN "
					 .tbl('video_speaker')." AS video_speaker ON speakerfunction.id=video_speaker.speakerfunction_id";
			// add alias on speaker.id to avoid any conflict between speaker.id and speakerfunction.id
			$query = str_replace(' speaker.id,',' speaker.id as sid,',$query);
			$query = str_replace(' video_speaker.id',' video_speaker.id as vid',$query);
				
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
			$result = $db->count(tbl('speaker')." AS speaker LEFT JOIN ".tbl('speakerfunction')." AS speakerfunction ON speaker.id=speakerfunction.speaker_id",'*',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
		
	}
	
	
	/**_____________________________________
	 * speaker_exists
	 * ____________________________________
	 *Test if speaker's id exists or not 
	 *
	 *input $id : is teh speaker's id
	 *output : true if speaker exists otherwise false
	 */
	function speaker_exists($id){
		global $db;
		$result = $db->count(tbl('speaker'),"id"," id='".$id."'");
		return ($result>0);
	}
	
	
	/**_____________________________________
	 * get_speaker_details
	 * ____________________________________
	 *Function used to get speaker details using it's id 
	 *
	 *input $id : speaker's id
	 *output : a dictionary containig each fields for a speaker, false if no speaker found
	 */
	function get_speaker_details($id=NULL)	{
		global $db;
		$fields = tbl_fields(array('speaker' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('speaker');
		$query .= " WHERE speaker.id = '$id'";
		$result = select($query);
		Assign('speaker', $result);
		
		if ($result) {
			$details = $result[0];
			$fields = tbl_fields(array('speakerfunction' => array('id','description')));
			$query = "SELECT $fields FROM ".cb_sql_table('speakerfunction');
			$query .= " WHERE speakerfunction.speaker_id = '$id'";
			$result = select($query);
			for ($i=0; $i<count($result); $i++){
				$arr=$result[$i];
				$keys=array_keys($arr);
				for ($j=0; $j<count($keys); $j++){
					if ($keys[$j]=='id') $keys[$j]='role_id';
					$details[$keys[$j]]=[];
				}
			}
			for ($i=0; $i<count($result); $i++){
				$arr=$result[$i];
				$keys=array_keys($arr);
				$values=array_values($arr);
				for ($j=0; $j<count($keys); $j++){
					if ($keys[$j]=='id') $keys[$j]='role_id';
					$details[$keys[$j]][]=$values[$j];
				}
			}
				
			return $details;
		}
		return false;
	}
	
	/**_____________________________________
	 * delete_speaker
	 * ____________________________________
	 *Remove speaker and speaker's role from the database. 
	 *TODO : if the speaker is linked to a video, then nothing is done, just an error message appears.
	 *input $id : the id of ths speaker to be deleted 
	 */
	function delete_speaker($id) {
		global $db;
		if($this->speaker_exists($id)) {
			$udetails = $this->get_speaker_details($id);
				$test=$db->execute("DELETE FROM ".tbl("speakerfunction")." WHERE speaker_id='$id'");
				$test2=$db->execute("DELETE FROM ".tbl("speaker")." WHERE id='$id'");
				if (!$test2)
					e(lang("cant_del_linked_speaker_msg")." id=".$id,"e");
				else
					e(lang("speaker_del_msg")." id=".$id,"m");
		}else{
			e(lang("speaker_does_not_exist"));
		}
	}
	
	/**_____________________________________
	 * link_speaker
	 * ____________________________________
	 *Associate a speaker's role to video 
	 *
	 *input $id : speaker role's id
	 *			$videoid : the video's id
	 */
	function link_speaker($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_speaker'),'*',"speakerfunction_id=".$id.	" and video_id=".$videoid);
		if ($cnt==0)
			$db->insert(tbl('video_speaker'), array('video_id','speakerfunction_id'), array(mysql_clean($videoid),mysql_clean($id)));
	}

	/**_____________________________________
 	 * unlink_speaker
 	 * ____________________________________
	 *Remove associate between a speaker's role and a video 
	 *
	 *input $id : speaker role's id
	 *			$videoid : the video's id
	 */
	function unlink_speaker($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_speaker'),'*',"speakerfunction_id=".$id.	" and video_id=".$videoid);
		if ($cnt>0)
			$db->execute("DELETE FROM ".tbl("video_speaker")." WHERE video_id='$videoid' AND speakerfunction_id='$id'");
	}
	
	/**_____________________________________
 	 * load_speaker_fields
 	 * ____________________________________
 	 *Create initial array for speaker fields 
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
 	 *input $input : a dictionary with speaker's informations (if null $_POST is used)
	 *		$strict : if trus then field is requiered in the data form
 	 *output : Fields for the administration page of the plugin
 	 */
	function load_speaker_fields($input=NULL,$strict=true) {
		global $LANG,$Cbucket;
		$default = array();
		if(isset($input))
			$default = $input;
		if(empty($default))
			$default = $_POST;
	
		$user_fname = (isset($default['firstname'])) ? $default['firstname'] : "";
		$user_lname = (isset($default['lastname'])) ? $default['lastname'] : "";
		$slug = (isset($default['slug'])) ? $default['slug'] : "";

		$my_fields = array (
				'firstname' => array(
						'title'=> lang('user_fname'),
						'type'=> "textfield",
						'name'=> "firstname",
						'id'=> "firstname",
						'value'=> $user_fname,
						'db_field'=>'firstname',
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
				'lastname' => array(
						'title'=> lang('user_lname'),
						'type'=> "textfield",
						'name'=> "lastname",
						'id'=> "lastname",
						'value'=> $user_lname,
						'db_field'=>'lastname',
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
				/*'slug' => array(
						'title'=> lang('Slug'),
						'type'=> "textfield",
						'name'=> "slug",
						'id'=> "slug",
						'value'=> $slug,
						'db_field'=>'slug',
						'required'=>($strict) ? 'yes' : 'no',
						//'invalid_err'=>lang('usr_cpass_err'),
						//'extra_tags'=>'readonly',
						//'syntax_type'=> 'email',
						//'db_value_check_func'=> 'email_exists',
						//'db_value_exists'=>false,
						//'db_value_err'=>lang('usr_email_err3')
				),*/
		);
		return $my_fields;
	}


	/**_____________________________________
	 * load_speaker_role_fields
	 * ____________________________________
	 *Create initial array for speaker roles fields 
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
	 *input $input : a dictionary with speaker's informations (if null $_POST is used)
	 *output : Fields for the administration page of the plugin
	 */
	function load_speaker_role_fields($input=NULL) {
		global $LANG,$Cbucket;
		$default = array();
		if(isset($input))
			$default = $input;
		if(empty($default))
			$default = $_POST;
		$roles = (isset($default['description'])) ? $default['description'] : NULL;
		$my_fields = array (
				'description' => array(
						'title'=> lang('role'),
						'type'=> "textfield",
						'name'=> "description",
						'id'=> "description",
						'value'=> "",
						'values' => $roles,
						'db_field'=>'description',
						'required'=>'yes',
						'validate_function'=> 'speaker_role_check',
						'function_error_msg' => lang('speaker_empty_role_err'),
						//'hint_1'=> lang('user_allowed_format'),
						//'hint_2'=> lang('user_allowed_format'),
						// 'syntax_type'=> 'username',
						//'function_error_msg' => lang('user_contains_disallow_err'),
						//'db_value_check_func'=> 'user_exists',
						//'db_value_exists'=>false,
						//'db_value_err'=>lang('usr_uname_err2'),
						//'min_length'	=> config('min_username'),
						//'max_length' => config('max_username'),
				),
		);
		return $my_fields;
	}
	
	/**_____________________________________
	 * load_speaker_role_ids
	 * ____________________________________
	 *return ids for speakers roles
	 *
	 *input $input : a dictionary containing speaker'id (if NULL $_POST is used)
	 *output : list of roles ids
	 */
	function load_speaker_role_ids($input=NULL) {
		global $db;
		global $LANG,$Cbucket;
		$default = array();
		if(isset($input))
			$default = $input;
		if(empty($default))
			$default = $_POST;
		$speakerid=mysql_clean($default['id']);
		$req=" speaker_id=$speakerid";
		$res=$db->select(tbl('speakerfunction'),'*',$req,false,false,false);
		return $res;
	}
	
	/**_____________________________________
	 * validate_form_fields
	 * ____________________________________
	 *Validate speaker's administrion form fields (Add and Edit forms) 
	 *
 	 *input $input : a dictionary with speaker's informations (if null $_POST is used)
	 *		$strict : if trus then field is requiered in the data form
	 *output : true if the form is valid, otherwise false
	 */
	function validate_form_fields($array=NULL,$strict=true) {
		$fields= $this->load_speaker_fields($array,$strict);
		$extrafields = $this->load_speaker_role_fields(NULL,$strict);
		$ok=false;
		foreach($extrafields as $field) {
			if(is_array($array[$field['name']])) 
				$ok=true;
		}
		if ($ok)
			$fields= array_merge($this->load_speaker_fields($array,$strict),	$extrafields = $this->load_speaker_role_fields(NULL,$strict));
		if($array==NULL)
			$array = $_POST;
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
	
		validate_cb_form($fields,$array);
	}
	
	
}

?>