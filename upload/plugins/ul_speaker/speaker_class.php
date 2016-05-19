<?php

$speakerquery = new speakerquery();
function get_speakers($param) {
	global $speakerquery;
	return $speakerquery->get_speakers($param);
}


function get_speaker_fields($array=NULL) {
	global $cb_columns;
	return $cb_columns->object('speakers')->get_columns();
}

class speakerquery extends CBCategory{
	private $basic_fields = array();
	private $extra_fields = array();
	
	function speakerquery()	{
		global $cb_columns;
		$basic_fields = array('id', 'name', 'slug', 'photo');
		$cb_columns->object( 'speakers' )->register_columns( $basic_fields );
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
				$db->execute("DELETE FROM ".tbl("speaker")." WHERE id='$id'");
				e(lang("usr_del_msg"),"m");
		}else{
			e(lang("user_doesnt_exist"));
		}
	}
	
}
?>