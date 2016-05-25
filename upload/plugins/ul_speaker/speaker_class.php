<?php

$speakerquery = new speakerquery();
$Smarty->assign_by_ref('speakerquery', $speakerquery);

function get_speakers($param) {
	global $speakerquery;
	return $speakerquery->get_speakers($param);
}


function get_speaker_fields($array=NULL) {
	global $cb_columns;
	return $cb_columns->object('speakers')->get_columns();
}

function speaker_role_check($val) {
	$i=1;
	foreach ($val as $v) {
		if ($v=="")
			return false;
	}
	return true;
}

class speakerquery extends CBCategory{
	private $basic_fields = array();
	private $extra_fields = array();
	
	function speakerquery()	{
		global $cb_columns;
		$basic_fields = array('id', 'firstname','lastname', 'slug', 'photo');
		$cb_columns->object( 'speakers' )->register_columns( $basic_fields );
	}

	/**
	 * Function used to add a new speakers
	 */
	function add_speaker($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array);
		if(!error()) {
			$firstname=$array['firstname'];
			$lastname=$array['lastname'];
			$req=" firstname = '".$firstname."' AND lastname='".$lastname."'";
			$res=$db->select(tbl('speaker'),'id',$req,false,false,false);
			// test speaker's unicity
			if (count($res)>0){
				e(lang("speaker_still_exists"));
				return false;
			}
			else {
				// insert speaker
				$db->insert(tbl('speaker'), array('firstname','lastname','slug','photo'), array($firstname,$lastname,$array['slug'],''));
				$res=$db->select(tbl('speaker'),'id',$req,false,false,false);
				$id=$res[0]['id'];
				$desc=$array['description'];
				// insert speaker roles
				for ($i=0; $i<count($desc); $i++)
					$db->insert(tbl('speakerfunction'), array('description','speaker_id'), array($desc[$i],$id));
				return $id;		
			}
		}
	}
	
	/**
	 * Function used to test if a speakers still exists
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
				e(lang("speaker_still_exists")." : ".$s,"w");
				return true;
			}
			else {
				e(lang("speaker_doesnt_exist"),"m");
				return false;
			}
		}
	}
	
	/**
	 * Function used to get speakers
	 */
	function get_speakers($params=NULL)	{
		global $db;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
	
		//name
		if($params['name']) {
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " speaker.name = '".$params['name']."' ";
		}
		if($params['cond']) {
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".$params['cond']." ";
		}
	
	
		if(!$params['count_only']) {
			$fields = array(
					'speaker' => get_speaker_fields(),
			);
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
		else
			return $result;
	}

	//Check Speaker Exists or Not
	function Check_Speaker_Exists($id){
		global $db;
		if(is_numeric($id))
				$result = $db->count(tbl('speaker'),"id"," id='".$id."'");
			else
				$result = $db->count(tbl('speaker'),"id"," name='".$id."'");
		return ($result>0);
	}
	
	function speaker_exists($username){
	return $this->Check_Speaker_Exists($username);
	}
	
	/**
	 * Function used to get speaker details using id
	 */
	function get_speaker_details($id=NULL)	{
		global $db;
		$select_field = (!is_numeric($id) ) ? 'name' : 'id';
		$fields = tbl_fields(array('speaker' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('speaker');
		$query .= " WHERE speaker.$select_field = '$id'";
		$result = select($query);
	
		if ( $result ) {
			$details = $result[0];
			return apply_filters( $details, 'get_speakers' );
		}
		return false;
	}
	
	/**
	 * Function used to delete speaker
	 */
	function delete_speaker($id) {
		global $db;
		if($this->speaker_exists($id)) {
			$udetails = $this->get_speaker_details($id);
				//list of functions to perform while deleting a video
				/*$del_user_funcs = $this->delete_user_functions;
				if(is_array($del_user_funcs))
				{
					foreach($del_user_funcs as $func)
					{
						if(function_exists($func))
						{
							$func($udetails);
						}
					}
				}*/
	
/*				//Removing Subsriptions and subscribers
				$this->remove_user_subscriptions($uid);
				$this->remove_user_subscribers($uid);
	
				//Changing User Videos To Anonymous
				$db->execute("UPDATE ".tbl("video")." SET userid='".$this->get_anonymous_user()."' WHERE userid='".$uid."'");
				//Changing User Group To Anonymous
				$db->execute("UPDATE ".tbl("groups")." SET userid='".$this->get_anonymous_user()."' WHERE userid='".$uid."'");
				//Deleting User Contacts
				$this->remove_contacts($uid);
	
				//Deleting User PMS
				$this->remove_user_pms($uid);
				//Changing From Messages to Anonymous
				$db->execute("UPDATE ".tbl("messages")." SET message_from='".$this->get_anonymous_user()."' WHERE message_from='".$uid."'");
				//Finally Removing Database entry of user
				$db->execute("DELETE FROM ".tbl("users")." WHERE userid='$uid'");
				$db->execute("DELETE FROM ".tbl("user_profile")." WHERE userid='$uid'");
	
				e(lang("usr_del_msg"),"m");*/
				$db->execute("DELETE FROM ".tbl("speakerfunction")." WHERE speaker_id='$id'");
				$db->execute("DELETE FROM ".tbl("speaker")." WHERE id='$id'");
				e(lang("usr_del_msg"),"m");
		}else{
			e(lang("user_doesnt_exist"));
		}
	}
	
	/**
	 * this function will create initial array for speaker fields
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
				'slug' => array(
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
				),
		);
		return $my_fields;
	}


		/**
		 * this function will create array for speaker role fields
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
	
	
	/**
	 * Function used to validate Add or Edit Speaker fields
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