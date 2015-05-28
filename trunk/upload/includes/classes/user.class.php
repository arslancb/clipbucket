<?php
/* 
**************************
* @ Author : Arslan Hassan
* @ Software : ClipBucket
* @ Since : 2007
* @ Modified : 06-08-2009
* $Id: user.class.php 1071 2013-07-06 08:35:50Z hassanbaryar $
* @ License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
**************************

Notice : Maintain this section
*/
 
define('NO_AVATAR','no_avatar.png'); //if there is no avatar or profile pic, this file will be used

class userquery extends CBCategory{
	
	var $userid = '';
	var $username = '';
	var $level = '';
	var $permissions = '';
	var $access_type_list = array(); //Access list
	var $usr_levels = array();
	var $signup_plugins = array(); //Signup Plugins
	var $custom_signup_fields = array();
	var $custom_profile_fields = array();
	var $custom_profile_fields_groups = array();
	var $delete_user_functions = array();
	var $user_manager_functions = array();
	var $logout_functions = array();
	var $init_login_functons = array();
	var $user_exist = '';
	var $user_account = array();
	var $user_sessions = array();
	var $profileItem = '';
	var $sessions = '';
	var $user_sess = ''; //variable which holds current user session
	var $is_login = false;
	
	var $dbtbl = array(
					   'user_permission_type'	=> 'user_permission_types',
					   'user_permissions'		=> 'user_permissions',
					   'user_level_permission'	=> 'user_levels_permissions',
					   'user_profile'			=> 'user_profile',
					   'users'					=> 'users',
					   'action_log'				=> 'action_log',
					   'subtbl'					=> 'subscriptions',
					   'contacts'				=> 'contacts',
					   );
	
	var $udetails = array();

    private $basic_fields = array();
    private $extra_fields = array();

	function userquery()
	{
        global $cb_columns;
       
		$this->cat_tbl = 'user_categories';

        $basic_fields = array(
            'userid', 'username', 'email', 'avatar', 'sex', 'avatar_url',
            'dob', 'level', 'usr_status', 'user_session_key','featured',
            'ban_status','total_photos','profile_hits','total_videos',
            'subscribers','total_subscriptions'
        );

        $cb_columns->object( 'users' )->register_columns( $basic_fields );
	}
	
	
	function init()
	{
		global $sess,$Cbucket;

		
//		$this->user_sess = $sess->get('user_sess');
    	$this->sess_salt = $sess->get('sess_salt');
		$this->sessions = $this->get_sessions();
		
		if($this->sessions['smart_sess'])
		{
			$this->userid = $this->sessions['smart_sess']['session_user'];
		}
		//$this->level = $sess->get('level');
		
		//Setting Access
		//Get list Of permission
		//$perms = $this->get_permissions();
		//foreach($perms as $perm)
		//{
		//	$this->add_access_type($perm['permission_code'],$perm['permission_name']);
		//}
		
		/*$this->add_access_type('admin_access','Admin Access');
		$this->add_access_type('upload_access','Upload Access');
		$this->add_access_type('channel_access','Channel Access');
		$this->add_access_type('mod_access','Moderator Access');*/
		
		//Fetching List Of User Levels
		$levels = $this->get_levels();
		foreach($levels as $level)
		{
			$this->usr_levels[$level['user_level_id']]=$level["user_level_name"];
		}
		
		$udetails = "";
		
		if($this->userid)
			$udetails = $this->get_user_details($this->userid,true);




		if($udetails)
		{			
			
			$this->udetails = $udetails;
			$this->username = $udetails['username'];
			$this->level = $this->udetails['level'];
			$this->permission = $this->get_user_level(userid());
			//exit();

			//Calling Logout Functions
			$funcs = $this->init_login_functions;
			if(is_array($funcs) && count($funcs)>0)
			{
				foreach($funcs as $func)
				{
					if(function_exists($func))
					{
						$func();
					}
				}
			}


			if($sess->get("dummy_username")=="") {
				$this->UpdateLastActive(userid());
			}
		}else {
			
			$this->permission = $this->get_user_level(4,TRUE);
		}
			
		
		
		//Adding Actions such Report, share,fav etc
		$this->action = new cbactions();
		$this->action->type = 'u';
		$this->action->name = 'user';
		$this->action->obj_class = 'userquery';
		$this->action->check_func = 'user_exists';
		$this->action->type_tbl = $this->dbtbl['users'];
		$this->action->type_id_field = 'userid';
		
		define('AVATAR_SIZE',config('max_profile_pic_width'));
		define('AVATAR_SMALL_SIZE',40);
		define('BG_SIZE',config('max_bg_width'));
		define('BACKGROUND_URL',config('background_url'));
		//define("USE_GAVATAR",config('gravatars') ? config('gravatars') : false); //Use Gavatar
		define('BACKGROUND_COLOR',config('background_color'));
		define('USE_GAVATAR', false);
		if(isSectionEnabled('channels'))
		$Cbucket->search_types['channels'] = "userquery";
		
	}
	
	/**
	 * Function used to create user session key
	 */
	function create_session_key($session,$pass)
	{
		$newkey = $session.$pass;
		$newkey = md5($newkey);
		return $newkey;
	}
	
	/**
	 * Function used to create user session code
	 * just for session authentication incase user wants to login again
	 */
	function create_session_code()
	{
		$code = rand(10000,99999);
		return $code;
	}

    /**
     * @return array
     */
    function get_basic_fields() {
        return $this->basic_fields;
    }

    function set_basic_fields( $fields = array() ) {
        return $this->basic_fields = $fields;
    }

    function basic_fields_setup() {

        # Set basic video fields
        $basic_fields = array(
            'userid', 'username', 'email', 'avatar', 'sex', 'avatar_url',
            'dob', 'level', 'usr_status', 'user_session_key','total_photos','profile_hits','total_videos','total_subscriptions'
        );

        return $this->set_basic_fields( $basic_fields );
    }

    function get_extra_fields() {
        return $this->extra_fields;
    }

    function set_extra_fields( $fields = array() ) {
        return $this->extra_fields = $fields;
    }

    function get_user_db_fields( $extra_fields = array() ) {
        $fields = $this->get_basic_fields();
        $extra = $this->get_extra_fields();

        if ( empty( $fields ) ) {
            $fields = $this->basic_fields_setup();
        }

        if ( !empty( $extra ) ) {
            $fields = array_merge( $fields, $extra );
        }

        if ( !empty( $extra_fields ) ) {
            if ( is_array( $extra_fields ) ) {
                $fields = array_merge( $fields, $extra_fields );
            } else {
                $fields[] = $extra_fields;
            }
        }

        # Do make array unqiue, otherwise we might get duplicate
        # fields
        $fields = array_unique( $fields );

        return $fields;
    }

    function add_user_field( $field ) {
        $extra_fields = $this->get_extra_fields();

        if ( is_array( $field ) ) {
            $extra_fields = array_merge( $extra_fields, $field );
        } else {
            $extra_fields[] = $field;
        }


        return $this->set_extra_fields( $extra_fields );
    }

	/**
	 * Neat and clean function to login user
	 * this function was made for v2.x with User Level System
	 * param VARCHAR $username
	 * param TEXT $password
	 */
	function login_user($username,$password,$remember=false)
	{
		global $LANG,$sess,$cblog,$db;
		//Now checking if user exists or not
		$pass = pass_code($password);
		
		$udetails = $this->get_user_with_pass($username,$pass);
		
		//Inerting Access Log
		$log_array = array('username'=>$username);
		
		//First we will check weather user is already logged in or not
		if($this->login_check(NULL,true))
			$msg[] = e(lang('you_already_logged'));
		elseif(!$this->user_exists($username))
			$msg[] = e(lang('user_doesnt_exist'));
		elseif(!$udetails)
			$msg[] = e(lang('usr_login_err'));
		elseif(strtolower($udetails['usr_status']) != 'ok')
			$msg[] = e(lang('user_inactive_msg'));
		elseif($udetails['ban_status'] == 'yes')
			$msg[] = e(lang('usr_ban_err'));
		else
		{
			
			$log_array['userid'] = $userid  = $udetails['userid'];
			$log_array['useremail'] = $udetails['email'];
			$log_array['success'] = 1;
			
			$log_array['level'] = $level  = $udetails['level'];

			//Adding Sessing In Database 
			//$sess->add_session($userid,'logged_in');
			
			//$sess->set('username',$username);
			//$sess->set('userid',$userid);
			
			//Setting Timeout
			if($remember)
				$sess->timeout = 86400*REMBER_DAYS;
				
			//Starting special sessions for security
			$session_salt = RandomString(5);
			$sess->set('sess_salt',$session_salt);
			$sess->set('PHPSESSID',$sess->id);
			
			$smart_sess = md5($udetails['user_session_key'].$session_salt);
			
			$db->delete(tbl("sessions"),array("session","session_string"),array($sess->id,"guest"));
			$sess->add_session($userid,'smart_sess',$smart_sess);
			
			//$sess->set('user_sess',$smart_sess);
			
			//$sess->set('user_session_key',$udetails['user_session_key']);
			//$sess->set('user_session_code',$udetails['user_session_code']);
			
			
			//Setting Vars
			$this->userid = $udetails['userid'];
			$this->username = $udetails['username'];
			$this->level = $udetails['level'];
			
			//Updating User last login , num of visist and ip
			$db->update(tbl('users'),
						array(
							  'num_visits','last_logged','ip'
							  ),
						array(
							  '|f|num_visits+1',NOW(),$_SERVER['REMOTE_ADDR']
							  ),
						"userid='".$userid."'"
						);
			
			
			$this->init();
			//Logging Actiong
			$cblog->insert('login',$log_array);
			
			return true;
		}
		
		//Error Loging
		if(!empty($msg))
		{
			//Loggin Action
			$log_array['success'] = no;
			$log_array['details'] = $msg[0];
			$cblog->insert('login',$log_array);
		}
	}
	
	
	
	
	/**
	 * Function used to check weather user is login or not
	 * it will also check weather user has access or not
	 * @param VARCHAR acess type it can be admin_access, upload_acess etc
	 * you can either set it as level id
	 */
	function login_check($access=NULL,$check_only=FALSE,$verify_logged_user=TRUE)
	{
		global $LANG,$Cbucket,$sess;
		
		if($verify_logged_user)
		{
			//First check weather userid is here or not
			if(!userid())
			{
				
				if(!$check_only)
					e(lang('you_not_logged_in'));
				return false;

			}
			elseif(!$this->session_auth(userid()))
			{
				
				if(!$check_only)
				e(lang('usr_invalid_session_err'));
				return false;
			}
			
			//Now Check if logged in user exists or not
			elseif(!$this->user_exists(userid(),TRUE))
			{
				if(!$check_only)
				e(lang('invalid_user'));
				return false;
			}
			//Now Check logged in user is banned or not
			elseif($this->is_banned(userid())=='yes')
			{
				if(!$check_only)
				e(lang('usr_ban_err'));
				return false;
			}
		}

		//Now user have passed all the stages, now checking if user has level access or not
		if($access)
		{	
			//$access_details = $this->get_user_level(userid());
			$access_details = $this->permission;

			if(is_numeric($access))
			{
				if($access_details['level_id'] == $access)
				{
					return true;
				}else{
					
					if(!$check_only)
					e(lang('insufficient_privileges'));
					$Cbucket->show_page(false);
					return false;
				}
			}else
			{
				if($access_details[$access] == 'yes')
				{
					
					return true;
				}
				else
				{
					if(!$check_only)
					{
						e(lang('insufficient_privileges'));
						$Cbucket->show_page(false);
					}
					return false;
				}
			}
		}
		else
		{
			return true;
		}
	}
	
	/**
	 * This function was used to check
	 * user is logged in or not -- for v1.7.x and old
	 * it has been replaced by login_check in v2
	 * this function is sitll in use so
	 * we are just replace the lil code of it
	 */
	function logincheck($access=NULL,$redirect=TRUE)
	{
		
		if(!$this->login_check($access))
		{
			if($redirect==TRUE)
				redirect_to(BASEURL.signup_link);
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/** 
	 * Function used to authenticate user session
	 */
	function session_auth($uid)
	{
		global $sess;
		
		return true;
		/*if($sess->get('user_session_key') == $ufields['user_session_key'] 
			&& $sess->get('user_session_code') == $ufields['user_session_code'])*/
		if($this->user_sessions['key']=='')
		{
			$ufields = $this->get_user_fields($uid,'user_session_key,user_session_code');
			//echo test;
			$this->user_sessions['key'] =  $ufields['user_session_key'];
			$this->user_sessions['code'] =  $ufields['user_session_code'];
		}
		
		if($this->user_sessions['key']==$sess->get('user_session_key')
			&& $this->user_sessions['code']==$sess->get('user_session_code')
			  ||( has_access("admin_access") && $sess->get("dummy_username")!=""))
		
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to get user details using username and password
	 */
	function get_user_with_pass($username,$pass)
	{
		global $db;
		$results = $db->select(tbl("users"),
							   "userid,email,level,usr_status,user_session_key,user_session_code",
							   "(username='$username' OR userid='$username') AND password='$pass'");
		if($db->num_rows > 0)
		{
			return $results[0];
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to check weather user is banned or not
	 */
	function is_banned($uid)
	{
		global $db;
		//echo $this->udetails['ban_status'];
		if(empty($this->udetails['ban_status']) && userid())
			$this->udetails['ban_status'] = $this->get_user_field($uid,'ban_status');
		return $this->udetails['ban_status'];
	}
	
	function admin_check()
	{
		return $this->login_check('admin_access');
	}

	/**
	 * Function used to check user is admin or not
	 * @param BOOLEAN if true, after checcking user will be redirected to login page if needed
	 */
	function admin_login_check($check_only=false)
	{
		if(!has_access('admin_access',true))
		{
			if($check_only==FALSE)
				redirect_to('login.php');
			return false;
		}else{
			return true;
		}
	}
		
	//This Function Is Used to Logout
	function logout($page='login.php')
	{
		global $sess;
		
		//Calling Logout Functions
		$funcs = $this->logout_functions;
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func();
				}
			}
		}
		
		$sess->un_set('sess_salt');
		$sess->destroy();
		//$sess->remove_session(userid());
	}
	
 
 
	/**
	 * Function used to delete user
	 */
	function delete_user($uid)
	{
		global $db;
		
		if($this->user_exists($uid))
		{
			
			$udetails = $this->get_user_details($uid);

			if(userid()!=$uid&&has_access('admin_access',true)&&$uid!=1)
			{
				//list of functions to perform while deleting a video
				$del_user_funcs = $this->delete_user_functions;
				if(is_array($del_user_funcs))
				{
					foreach($del_user_funcs as $func)
					{
						if(function_exists($func))
						{
							$func($udetails);
						}
					}
				}
				
				//Removing Subsriptions and subscribers
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
				
				e(lang("usr_del_msg"),"m");
			}else{
				e(lang("you_cant_delete_this_user"));
			}
		}else{
			e(lang("user_doesnt_exist"));
		}
	}
	
	/**
	 * Remove all user subscriptions
	 */
	function remove_user_subscriptions($uid)
	{
		global $db;
		if(!$this->user_exists($uid))
			e(lang("user_doesnt_exist"));
		elseif(!has_access('admin_access'))
			e(lang("you_dont_hv_perms"));
		else
		{
			$db->execute("DELETE FROM ".tbl($this->dbtbl['subtbl'])." WHERE userid='$uid'");
			e(lang("user_subs_hv_been_removed"),"m");
		}
	}
	
	/**
	 * Remove all user subscribers
	 */
	function remove_user_subscribers($uid)
	{
		global $db;
		if(!$this->user_exists($uid))
			e(lang("user_doesnt_exist"));
		elseif(!has_access('admin_access'))
			e(lang("you_dont_hv_perms"));
		else
		{
			$db->execute("DELETE FROM ".tbl($this->dbtbl['subtbl'])." WHERE subscribed_to='$uid'");
			e(lang("user_subsers_hv_removed"),"m");
		}
	}
	
	
	//Delete User
	function DeleteUser($id){
		return $this->delete_user($id);
	}

	//Count Inactive users
	function CountUsers(){
		
	} 
		
	//Check User Exists or Not
	function Check_User_Exists($id,$global=false){
		global $db;
		
		if($global)
		{
			if(empty($this->user_exist))
			{
				if(is_numeric($id))
					$result = $db->count(tbl($this->dbtbl['users']),"userid"," userid='".$id."' ");
				else
					$result = $db->count(tbl($this->dbtbl['users']),"userid"," username='".$id."' ");
				if($result>0)
				{
					$this->user_exist = 'yes';
				}else{
					$this->user_exist = 'no';
				}	
			}
			
			if($this->user_exist=='yes')
				return true;
			else
				return false;
		}else
		{
			if(is_numeric($id))
				$result = $db->count(tbl($this->dbtbl['users']),"userid"," userid='".$id."'");
			else
				$result = $db->count(tbl($this->dbtbl['users']),"userid"," username='".$id."'");
			if($result>0)
			{
				return true;
			}else{
				return false;
			}	
		}
		
	}
	
	function user_exists($username,$global=false)
	{
		return $this->Check_User_Exists($username,$global);
	}
	
	/**
	 * Function used to get user details using userid
	 */
	function get_user_details( $id=NULL, $checksess=false )
	{
		global $db,$sess;
		/*if(!$id)
			$id = userid();*/


        $is_email = ( strpos( $id , '@' ) !== false ) ? true : false;
        $select_field = ( !$is_email and !is_numeric( $id ) ) ? 'username' : ( !is_numeric( $id ) ? 'email' : 'userid' );

        $fields = tbl_fields( array( 'users' => array( '*' ) ) );

        $query = "SELECT $fields FROM ".cb_sql_table( 'users' );
        $query .= " WHERE users.$select_field = '$id'";

        $result = select( $query );

        if ( $result ) {
            $details = $result[ 0 ];

            if ( !$checksess ) {
                return apply_filters( $details, 'get_user' );
            } else {
                $session = $this->sessions['smart_sess'];
                $smart_sess = md5($details['user_session_key'] . $sess->get('sess_salt'));
                if ( $smart_sess == $session['session_value'] ) {
                    $this->is_login = true;
                    return apply_filters( $details, 'get_user' );
                } else {
                    return false;
                }
            }
        }

        return false;

	}function GetUserData($id=NULL){ return $this->get_user_details($id); }
	

	

	//Function Used To Activate User
	function activate_user_with_avcode($user,$avcode)
	{
		global $eh;
		$data = $this->get_user_details($user);
		if(!$data  || !$user)
			e(lang("usr_exist_err"));
		elseif($data['usr_status']=='Ok')
			e(lang('usr_activation_err'));
		elseif($data['ban_status']=='yes')
			e(lang('ban_status'));
		elseif($data['avcode'] !=$avcode)
			e(lang('avcode_incorrect'));
		else
		{
			$this->action('activate',$data['userid']);
			$eh->flush();
			e(lang("usr_activation_msg"),"m");
			
			if($data['welcome_email_sent']=='no')
				$this->send_welcome_email($data,TRUE);
		}
	}
	
	
	/**
	 * Function used to send activation code
	 * to user
	 * @param : $usenrma,$email or $userid
	 */
	function send_activation_code($email)
	{
		global $db,$cbemail;
		$udetails = $this->get_user_details($email);
		
		if(!$udetails || !$email)
			e(lang("usr_exist_err"));
		elseif($udetails['usr_status']=='Ok')
			e(lang('usr_activation_err'));
		elseif($udetails['ban_status']=='yes')
			e(lang('ban_status'));
		else
		{
			$tpl = $cbemail->get_template('avcode_request_template');
			$more_var = array
			('{username}'	=> $udetails['username'],
			 '{email}'		=> $udetails['email'],
			 '{avcode}'		=> $udetails['avcode']
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$udetails['email'],'from'=>SUPPORT_EMAIL,'subject'=>$subj,'content'=>$msg));
			e(lang('usr_activation_em_msg'),"m");
		}
	}
	function SendActivation($email)
	{
		return $this->send_activation_code($email);
	}
	
	/**
	 * Function used to send welcome email
	 */
	function send_welcome_email($user,$update_email_status=FALSE)
	{
		global $db,$cbemail;
		
		if(!is_array($user))
			$udetails = $this->get_user_details($user);
		else
			$udetails = $user;
		
		if(!$udetails)
			e(lang("usr_exist_err"));
		else
		{
			$tpl = $cbemail->get_template('welcome_message_template');
			$more_var = array
			('{username}'	=> $udetails['username'],
			 '{email}'		=> $udetails['email'],
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$udetails['email'],'from'=>WELCOME_EMAIL,'subject'=>$subj,'content'=>$msg));
			
			if($update_email_status)
				$db->update(tbl($this->dbtbl['users']),array('welcome_email_sent'),array("yes")," userid='".$udetails['userid']."' ");
		}
	}
	
	
	/**
	 * Function used to change user password
	 */
	function ChangeUserPassword($array)
	{
		global $db;
		
		$old_pass 	= $array['old_pass'];
		$new_pass 	= $array['new_pass'];
		$c_new_pass	= $array['c_new_pass'];
		
		$uid = $array['userid'];
		
		if(!$this->get_user_with_pass($uid,pass_code($old_pass)))
			e(lang('usr_pass_err'));
		elseif(empty($new_pass))
			e(lang('usr_pass_err2'));
		elseif($new_pass != $c_new_pass)
			e(lang('usr_cpass_err1'));
		else
		{
			$db->update(tbl($this->dbtbl['users']),array('password'),array(pass_code($array['new_pass']))," userid='".$uid."'");
			e(lang("usr_pass_email_msg"),"m");

		}
		
		return $msg;
	}
	function change_user_pass($array){ return $this->ChangeUserPassword($array); }
	function change_password($array){ return $this->ChangeUserPassword($array); }
	
	/**
	 * Function used to add contact
	 */
	function add_contact($uid,$fid)
	{
		global $cbemail,$db;
		
		$friend = $this->get_user_details($fid);
		$sender = $this->get_user_details($uid);
		
		if(!$friend)
			e(lang('usr_exist_err'));
		elseif($this->is_requested_friend($uid,$fid))
			e(lang("you_already_sent_frend_request"));
		elseif($this->is_requested_friend($uid,$fid,"in"))
		{
			$this->confirm_friend($fid,$uid);
			e(lang("friend_added"));
		}elseif($uid==$fid)
		{
			e(lang("friend_add_himself_error"));
		}else
		{
			$db->insert(tbl($this->dbtbl['contacts']),array('userid','contact_userid','date_added','request_type'),
												 array($uid,$fid,now(),'out'));
			$insert_id = $db->insert_id();
			
			e(lang("friend_request_sent"),"m");
			
			//Sending friendship request email
			$tpl = $cbemail->get_template('friend_request_email');
			
			
			$more_var = array
			(
			 '{reciever}'	=> $friend['username'],
			 '{sender}'		=> $sender['username'],
			 '{sender_link}'=> $this->profile_link($sender),
			 '{request_link}'=> BASEURL.'/manage_contacts.php?mode=request&confirm='.$uid
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$friend['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));		
		}
		
	}
	
	/**
	 * Function used to check weather users are confirmed friends or not
	 */
	function is_confirmed_friend($uid,$fid)
	{
		global $db;
		$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id",
					" (userid='$uid' AND contact_userid='$fid') OR (userid='$fid' AND contact_userid='$uid') AND confirmed='yes'" );
		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * function used to check weather users are firends or not
	 */
	function is_friend($uid,$fid)
	{
		global $db;
		$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id",
					" (userid='$uid' AND contact_userid='$fid') OR (userid='$fid' AND contact_userid='$uid')" );
		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to check weather user has already requested friendship or not
	 */
	function is_requested_friend($uid,$fid,$type='out',$confirm=NULL)
	{
		global $db;
		
		$query = "";
		if($confirm)
			$query = " AND confirmed='$confirm' ";
			
		if($type=='out')
			$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id"," userid='$uid' AND contact_userid='$fid' $query" );
			
		else
			$count = $db->count(tbl($this->dbtbl['contacts']),"contact_id"," userid='$fid' AND contact_userid='$uid' $query" );

		if($count[0]>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to confirm friend
	 */
	function confirm_friend($uid,$rid,$msg=TRUE)
	{
		global $cbemail,$db;
		if(!$this->is_requested_friend($rid,$uid,'out','no'))
		{
			if($msg)
			e(lang("friend_confirm_error"));
		}else
		{
			addFeed(array('action' => 'add_friend','object_id' => $rid,'object'=>'friend','uid'=>$uid));
			addFeed(array('action' => 'add_friend','object_id' => $uid,'object'=>'friend','uid'=>$rid));
			
			$db->insert(tbl($this->dbtbl['contacts']),array('userid','contact_userid','date_added','request_type','confirmed'),
												 array($uid,$rid,now(),'in','yes'));
			$db->update(tbl($this->dbtbl['contacts']),array('confirmed'),array("yes")," userid='$rid' AND contact_userid='$uid' " );
			if($msg)
				e(lang("friend_confirmed"),"m");
			//Sending friendship confirmation email
			$tpl = $cbemail->get_template('friend_confirmation_email');
			
			$friend = $this->get_user_details($rid);
			$sender = $this->get_user_details($uid);
			
			$more_var = array
			(
			 '{reciever}'	=> $friend['username'],
			 '{sender}'		=> $sender['username'],
			 '{sender_link}'=> $this->profile_link($sender),
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			
			//Now Finally Sending Email
			cbmail(array('to'=>$friend['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));	
			
			
			//Loggin Friendship
			
			$log_array = array
			(
			 'success'=>'yes',
			 'action_obj_id' => $friend['userid'],
			 'details'=>"friend with ".$friend['username']
			 );
			
			insert_log('add_friend',$log_array);
			
			$log_array = array
			(
			 'success'=>'yes',
			 'username' => $friend['username'],
			 'userid' => $friend['userid'],
			 'userlevel' => $friend['level'],
			 'useremail' => $friend['email'],
			 'action_obj_id' => $insert_id,
			 'details'=> "friend with ".userid()
			);
			
			//Login Upload
			insert_log('add_friend',$log_array);
		}	
	}
		
	/**
	 * Function used to confirm request
	 */
	function confirm_request($rid,$uid=NULL)
	{
		global $db;
		
		if(!$uid)
			$uid = userid();
			
		$result = $db->select(tbl($this->dbtbl['contacts']),"*"," userid='$rid' AND contact_userid='$uid' ");
		$result = $result[0];
		
		if($db->num_rows==0)
			e(lang("friend_request_not_found"));
		elseif($uid!=$result['contact_userid'])
			e(lang("you_cant_confirm_this_request"));
		elseif($result['confirmed']=='yes')
			e(lang("friend_request_already_confirmed"));
		else
		{
			$this->confirm_friend($uid,$result['userid']);
		}
	}
		
	
	/**
	 * Function used to get user contacts
	 */
	function get_contacts($uid,$group=0,$confirmed=NULL,$count_only=false,$type=NULL)
	{
		global $db;
		
		$query = "";
		if($confirmed)
			$query .= " AND ".tbl("contacts").".confirmed='$confirmed' ";
		if($type)
			$query .= " AND ".tbl("contacts").".request_type='$type' ";
		if(!$count_only)
		{
			$result = $db->select(tbl("contacts,users"),
			tbl("contacts.contact_userid,contacts.confirmed,contacts.request_type ,users.*"),
			tbl("contacts.userid")."='$uid' AND ".tbl("users.userid")."=".tbl("contacts.contact_userid")."
			$query AND ".tbl("contacts").".contact_group_id='$group' ");
			
			if($db->num_rows>0)
				return $result;
			else
				return false;
		}else{
			$count =  $db->count(tbl("contacts"),
			tbl("contacts.contact_userid"),
			tbl("contacts.userid")."='$uid' 
			$query AND ".tbl("contacts").".contact_group_id='$group' ");
			return $count;
		}
	}
	
	/**
	 * Function used to get pending contacts
	 */
	function get_pending_contacts($uid,$group=0,$count_only=false)
	{
		global $db;
		
		if(!$count_only)
		{
			$result = $db->select(tbl("contacts,users"),
			tbl("contacts.userid,contacts.confirmed,contacts.request_type ,users.*"),
			tbl("contacts.contact_userid")."='$uid' AND ".tbl("users.userid")."=".tbl("contacts.userid")."
			AND ".tbl("contacts.confirmed")."='no' AND ".tbl("contacts").".contact_group_id='$group' ");
			if($db->num_rows>0)
				return $result;
			else
				return false;
		}else
		{
			$count =  $db->count(tbl("contacts"),
			tbl("contacts.contact_userid"),
			tbl("contacts.contact_userid")."='$uid' AND ".tbl("contacts.confirmed")."='no' AND ".tbl("contacts").".contact_group_id='$group' ");
			//echo $db->db_query;
			return $count;
		}
	}
	
	/**
	 * Function used to get pending contacts
	 */
	function get_requested_contacts($uid,$group=0)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['contacts']),"*"," contact_userid='$uid' AND confirmed='no' AND contact_group_id='$group' ");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	
	/**
	 * Function used to remove user from contact list
	 * @param fid {id of friend that user wants to remove}
	 * @param uid {id of user who is removing other from friendlist}
	 */
	function remove_contact($fid,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		if(!$this->is_friend($fid,$uid))
			e(lang("user_no_in_contact_list"));
		else
		{
			$db->Execute("DELETE from ".tbl($this->dbtbl['contacts'])." WHERE 
						(userid='$uid' AND contact_userid='$fid') OR (userid='$fid' AND contact_userid='$uid')" );
			e(lang("user_removed_from_contact_list"),"m");
		}
	}
		
	/**
	 * Funcion used to increas user total_watched field
	 */
	function increment_watched_vides($userid)
	{
		global $db;
		$db->update(tbl($this->dbtbl['users']),array('total_watched'),array('|f|total_watched+1')," userid='$userid'");
	}
			
	/**
	 * Old Function : GetNewMsgs
	 * This function is used to get user messages
	 * @param : user
	 * @param : sent/inbox 
	 * @param : count (TRUE : FALSE)
	 */
		 
	function get_pm_msgs($user,$box='inbox',$count=FALSE){
		global $db,$eh,$LANG;
		if(!$user)
			$user = user_id();	
		if(!user_id())
		{
			$eh->e(lang('you_not_logged_in'));
		}else{
			switch($box)
			{
				case 'inbox':
				default:
				$boxtype = 'inbox';
				break;
				
				case 'sent':
				case 'outbox':
				$boxtype = 'outbox';
				break;
			}
			
			if($count)
				$status_query = " AND status = '0' ";
				
			$results = $db->select(tbl("messages"),
						" message_id ",
						"(".$boxtype."_user = '$user' OR ".$boxtype."_user_id = '$user') $status_query");
			
	
			if($db->num_rows > 0)
			{
				if($count)
				return $db->num_rows;
				else
				return $results;
			}
			else
			{
				return false;
			}
		}
	}
	function GetNewMsgs($user)
	{
		$msgs = $this->get_pm_msgs($user,'inbox',TRUE);
		if($msgs)
			return $msgs;
		else
			return 0;
	}
		
		
	/**
	 * Function used to subscribe user
	 */
	function subscribe_user($to,$user=NULL)
	{
		if(!$user)
			$user = userid();
		global $db;
		
		$to_user = $this->get_user_details($to);
		
		if(!$this->user_exists($to))
			e(lang('usr_exist_err'));
		elseif(!$user)
			e(sprintf(lang('please_login_subscribe'),$to_user['username']));
		elseif($this->is_subscribed($to,$user))
			e(sprintf(lang("usr_sub_err"),$to_user['username']));
		elseif($to_user['userid'] == $user)
			e(lang("you_cant_sub_yourself"));	
		else
		{
			$db->insert(tbl($this->dbtbl['subtbl']),array('userid','subscribed_to','date_added'),
											   array($user,$to,NOW()));
			$db->update(tbl($this->dbtbl['users']),array('subscribers'),
											   array($this->get_user_subscribers($to,true))," userid='$to' ");
			$db->update(tbl($this->dbtbl['users']),array('total_subscriptions'),
											   array($this->get_user_subscriptions($user,'count'))," userid='$user' ");
			//Loggin Comment			
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "subsribed to ".$to_user['username'],
			 'action_obj_id' => $to_user['userid'],
			 'action_done_id' => $db->insert_id(),
			);
			insert_log('subscribe',$log_array);
			
			e(sprintf(lang('usr_sub_msg'),$to_user['username']),'m');
		}			
	}
	function SubscribeUser($sub_user,$sub_to){return $this->subscribe_user($sub_to,$sub_user);}
		
	/**
	 * Function used to check weather user is already subscribed or not
	 */
	function is_subscribed($to,$user=NULL)
	{
		if(!$user)
			$user = userid();
		global $db;
		
		if(!$user)
			return false;
		$result = $db->select(tbl($this->dbtbl['subtbl']),"*"," subscribed_to='$to' AND userid='$user'");
		if($db->num_rows>0)
			return $result;
		else
			return false;			
	}
	
	/**
	 * Function used to remove user subscription
	 */
	function remove_subscription($subid,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		if($this->is_subscribed($subid,$uid))
		{
			$db->execute("DELETE FROM ".tbl($this->dbtbl['subtbl'])." WHERE userid='$uid' AND subscribed_to='$subid'");
			e(lang("class_unsub_msg"),"m");
			
			$db->update(tbl($this->dbtbl['users']),array('subscribers'),
											   array($this->get_user_subscribers($subid,true))," userid='$subid' ");
			$db->update(tbl($this->dbtbl['users']),array('total_subscriptions'),
											   array($this->get_user_subscriptions($uid,'count'))," userid='$uid' ");
			
			
			return true;
		}else
			e(lang("you_not_subscribed"));
		
		return false;
	}function unsubscribe_user($subid,$uid=NULL){ return $this->remove_subscription($subid,$uid); }
	
	
	/**
	 * Function used to get user subscibers
	 * @param userid
	 */
	function get_user_subscribers($id,$count=false)
	{
		global $db;
		if(!$count)
		{
			$result = $db->select(tbl('subscriptions'),"*",
			" subscribed_to='$id' ");
			if($db->num_rows>0)
				return $result;
			else
				return false;	
		}else
		{
			return $db->count(tbl($this->dbtbl['subtbl']),"subscription_id"," subscribed_to='$id' ");
		}
	}
	
	/**
	 * function used to get user subscribers with details
	 */
	function get_user_subscribers_detail($id,$limit=NULL)
	{
		global $db;
		$result = $db->select(tbl("users,".$this->dbtbl['subtbl']),"*"," ".tbl("subscriptions.subscribed_to")." = '$id' AND ".tbl("subscriptions.userid")."=".tbl("users.userid"),$limit);
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to get user subscriptions
	 */
	function get_user_subscriptions($id,$limit=NULL)
	{	
		global $db;
		if($limit!='count')
		{
			$result = $db->select(tbl("users,".$this->dbtbl['subtbl']),"*"," ".tbl("subscriptions.userid")." = '$id' AND ".tbl("subscriptions.subscribed_to")."=".tbl("users.userid"),$limit);
			
			if($db->num_rows>0)
				return $result;
			else
				return false;
		}else
		{
			$result =  $db->count(tbl($this->dbtbl['subtbl']),"subscription_id"," userid = '$id'");
			return $result;
		}
	}
	
	

	
	/**
	 * Function used to reset user password
	 * it has two steps
	 * 1 to send confirmation
	 * 2 to reset the password
	 */
	 
	function reset_password($step,$input,$code=NULL)
	{
		global $cbemail,$db;
		switch($step)
		{
			case 1:
			{
				$udetails = $this->get_user_details($input);
				if(!$udetails)
					e(lang('usr_exist_err'));
				 //verifying captcha...
				elseif(!verify_captcha())
					e(lang('usr_ccode_err'));
				else
				{
					//Sending confirmation email
					$tpl = $cbemail->get_template('password_reset_request');
					$avcode = $udetails['avcode'];
					if(!$udetails['avcode']) 
					{
						$avcode = RandomString(10);
						$db->update(tbl($this->dbtbl['users']),array('avcode'),array($avcode)," userid='".$udetails['userid']."'");			
					}
										
					$more_var = array
					('{username}'	=> $udetails['username'],
					 '{email}'		=> $udetails['email'],
					 '{avcode}'		=> $avcode,
					 '{userid}'		=> $udetails['userid'],
					);
					if(!is_array($var))
						$var = array();
					$var = array_merge($more_var,$var);
					$subj = $cbemail->replace($tpl['email_template_subject'],$var);
					$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
					
					//Now Finally Sending Email
					cbmail(array('to'=>$udetails['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
				
					e(lang('usr_rpass_email_msg'),"m");
					return true;
				}
			}
			break;
			case 2:
			{
				$udetails = $this->get_user_details($input);
				if(!$udetails)
					e(lang('usr_exist_err'));
				 //verifying captcha...
				elseif($udetails['avcode'] !=$code)
					e(lang('usr_ccode_err'));
				else
				{
					$newpass = RandomString(6);
					$pass 	 = pass_code($newpass);
					$avcode = RandomString(10);
					$db->update(tbl($this->dbtbl['users']),array('password','avcode'),array($pass,$avcode)," userid='".$udetails['userid']."'");
					//sending new password email...
					//Sending confirmation email
					$tpl = $cbemail->get_template('password_reset_details');
					$more_var = array
					('{username}'	=> $udetails['username'],
					 '{email}'		=> $udetails['email'],
					 '{avcode}'		=> $udetails['avcode'],
					 '{userid}'		=> $udetails['userid'],
					 '{password}'	=> $newpass,
					);
					if(!is_array($var))
						$var = array();
					$var = array_merge($more_var,$var);
					$subj = $cbemail->replace($tpl['email_template_subject'],$var);
					$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
					
					//Now Finally Sending Email
					cbmail(array('to'=>$udetails['email'],'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
					e(lang('usr_pass_email_msg'),'m');
					return true;
				}
			}
			break;
		}
	}
										
	/**
	 * Function used to recover username
	 */
	function recover_username($email)
	{
		global $cbemail;
		$udetails = $this->get_user_details($email);
		if(!$udetails)
			e(lang('no_user_associated_with_email'));
		elseif(!verify_captcha())
			e(lang('usr_ccode_err'));
		else
		{
			$tpl = $cbemail->get_template('forgot_username_request');
			$more_var = array
			(
			 '{username}'	=> $udetails['username'],
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			cbmail(array('to'=>$udetails['email'],'from'=>SUPPORT_EMAIL,'subject'=>$subj,'content'=>$msg));
			e(lang("usr_uname_email_msg"),'m');
		}
	return $msg;
	
	
	}
	
	//FUNCTION USED TO UPDATE LAST ACTIVE FOR OF USER
	// @ Param : username
	function UpdateLastActive($username)
	{
		global $db;
		
		$sql = "UPDATE ".tbl("users")." SET last_active = '".NOW()."' WHERE username='".$username."' OR userid='".$username."' ";
		$db->Execute($sql);
	}

	
	/**
	 * FUNCTION USED TO GE USER THUMBNAIL
	 * @param : thumb file
	 * @param : size (NULL,small)
	 */
	function getUserThumb($udetails,$size='',$uid=NULL,$just_file=false)
	{
		global $Cbucket;
		$remote = false;
		if(empty($udetails['userid']) && $uid)
			$udetails = $this->get_user_details($uid);
		//$thumbnail = $udetails['avatar'] ? $udetails['avatar'] : NO_AVATAR;
		$thumbnail = $udetails['avatar'];
		$thumb_file = USER_THUMBS_DIR.'/'.$thumbnail;
		
		
		if(file_exists($thumb_file) && $thumbnail)
			$thumb = USER_THUMBS_URL.'/'.$thumbnail;
		elseif(!empty($udetails['avatar_url']))
		{
			$thumb = $udetails['avatar_url'];
			$remote  = true;
		}else
		{	
			if(!USE_GAVATAR)
				$thumb_file = $this->get_default_thumb();
			else
			{
				switch($size)
				{
					case "small":
					{
						$thesize = AVATAR_SMALL_SIZE;
						$default = $this->get_default_thumb('small');
						
					}
					break;
					default:
					{
						$thesize = AVATAR_SIZE;
						$default = $this->get_default_thumb();
					}
				}
				
				$email = $udetails['email'];
				$email = $email ? $email : $udetails['anonym_email'];
				$gravatar = new Gravatar($email, $default);
				$gravatar->size = $thesize;
				$gravatar->rating = "G";
				$gravatar->border = "FF0000";
				
				$thumb = $gravatar->getSrc();
				//echo $gravatar->toHTML();
			}
		}

		$ext = GetExt($thumb_file);
		$file = getName($thumb_file);

				
		if(!$remote)
		{
			if(!empty($size) && !$thumb)
			{
				$thumb = USER_THUMBS_URL.'/'.$file.'-'.$size.'.'.$ext;
				$thumb_path = $file.'.'.$ext;
			}
			elseif(!$thumb)
			{
				$thumb = USER_THUMBS_URL.'/'.$file.'.'.$ext;
				$thumb_path = "";
			}
		}


		$thumb_name = $file.'.'.$ext;


		
		if($just_file)
			return $file.'.'.$ext;
		
		if($size){

			$params = array(
				'size'=> $size,
				'thumb_path'=>"images/avatars/",
				'thumb_name' => $thumb_name,
				'just_file' => $just_file,
				'is_remote' => $remote,
			);

			if( count( $Cbucket->custom_user_thumb ) > 0 ) {
				
		        $functions = $Cbucket->custom_user_thumb;
		        foreach( $functions as $func ) {
		            if( function_exists( $func ) ) {
		                $func_data = $func( $params );
		                if( $func_data ) {
		                    return $func_data;
		                }
		            }
		        }
		    }

			return $thumb = USER_THUMBS_URL.'/'.$file.'.'.$ext;
			//return $this->resizer($size,$thumb);
			
		}
		return $thumb;
	}

	/**
	 * Function used to resize image of avatar using timthumb
	 */
	function resizer($size,$src)
	{
		if($size)
		$size =	explode('x', $size);

		$w=$size[0];
		$h=$size[1];
		
		if(!$w) $w=120;
	
		if(!$h) $h=90;		

		return	BASEURL."/includes/classes/timthumb/timthumb.php?src=$src&w=$w&h=$h";
	}

	function avatar($udetails,$size='',$uid=NULL)
	{
		return $this->getUserThumb($udetails,$size,$uid);
	}
	
	/**
	 * Function used to get default user thumb
	 */
	function get_default_thumb($size=NULL)
	{
		if($size=="small" && file_exists(TEMPLATEDIR.'/images/thumbs/no_avatar-small.png'))
		{
			return TEMPLATEURL.'/images/thumbs/no_avatar-small.png';
		}elseif(file_exists(TEMPLATEDIR.'/images/thumbs/no_avatar.png') && !$size)
		{
			return TEMPLATEURL.'/images/thumbs/no_avatar.png';
		}else
		{
			if($size=='small')
				return USER_THUMBS_URL.'/'.getName(NO_AVATAR).'-small.'.getExt(NO_AVATAR);
			else
				return USER_THUMBS_URL.'/'.NO_AVATAR;
		}
	}
	
	/**
	 * Function used to get user Background
	 * @param : bg file
	 */
	function getUserBg($udetails,$check=false)
	{
		$remote = false;
		if(empty($udetails['userid']))
			$udetails = $this->get_user_details($uid);
		//$thumbnail = $udetails['avatar'] ? $udetails['avatar'] : 'no_avatar.jpg';
		$file = $udetails['background'];
		$bgfile = USER_BG_DIR.'/'.$file;
		if(file_exists($bgfile) && $file)
			$thumb_file = USER_BG_URL.'/'.$file;
		elseif(!empty($udetails['background_url']) && BACKGROUND_URL=='yes')
		{
			$thumb_file = $udetails['background_url'];
			$remote  = true;
		}elseif(!empty($udetails['background_color']) && BACKGROUND_COLOR =='yes' && $check) 
		{
			return true;
		}else
			return false;

		return $thumb_file;
	}	
	
	

	/**
	 * Function used to get user subscriber's list
	 * @param VARCHAR//INT username or userid , both works fine
	 */
	function get_user_subscriber($username)
	{
		global $db;
		$results = $db->Execute("SELECT * FROM ".tbl("subscriptions")." WHERE subsctibe_to='$username'");
		if($results->recordcount() > 0)
			return $results->getrows();
		else
			return false;
	}
	
	
	
	/**
	 * Function used to get user field
	 * @ param INT userid 
	 * @ param FIELD name
	 */
	function get_user_field($uid,$field)
	{
		global $db;
		
		if(is_numeric($uid))
			$results = $db->select(tbl('users'),$field,"userid='$uid'");
		else
			$results = $db->select(tbl('users'),$field,"username='$uid'");

		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}function get_user_fields($uid,$field){return $this->get_user_field($uid,$field);}
	
	
	/**
	 * This function will return
	 * user field without array
	 */
	function get_user_field_only($uid,$field)
	{
		$fields = $this->get_user_field($uid,$field);
		return $fields[$field];
	}
	
	/**
	 * Function used to get user level and its details
	 * @param INT userid
	 */
	function get_user_level($uid,$is_level=false)
	{
		global $db;
		
		if($is_level) 
		{
			$level = $uid;
		}
		else
		{
			$level = $this->udetails['level'];
		}

        if ( $level == userid() or $level == $this->udetails[ 'level' ] ) {
            if ( $this->permission ) {
                return $this->permission;
            }
        }

		$result = $db->select(tbl('user_levels,user_levels_permissions'),'*',
							  tbl("user_levels_permissions.user_level_id")."='".$level."' 
							  AND ".tbl("user_levels_permissions.user_level_id")." = ".tbl("user_levels.user_level_id"));
							  
		
		/*		
		pr($result);
		$results = $db->select(tbl('user_levels'),'*'," user_level_id='".$level['level']."'");
		if($db->num_rows == 0)
		 //incase user level is not valid, it will consider it as registered user
			$u_level['user_level_id'] = 3;
		else
			$u_level = $results[0];
			
		//Now Getting Access Details
		$access_results = $db->select("user_levels_permissions","*",
									  "user_level_id = '".$u_level['user_level_id']."'");
		$a_results = $access_results[0];*/
		
		//Now Merging the two arrays
		$user_level = $result[0];
		//pr($user_level);
		return $user_level;
	}
	
	
	/**
	 * Function used to get all levels
	 * @param : filter
	 */
	function get_levels($filter=NULL)
	{
		global $db;
		$results = $db->select(tbl("user_levels"),"*",NULL,NULL," user_level_id ASC" );


        if($db->num_rows > 0)
		{
			return $results;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to get level details
	 * @param : level_id INT
	 */
	function get_level_details($lid)
	{
		global $db;
		$results = $db->select(tbl("user_levels"),"*"," user_level_id='$lid' ");
		if($db->num_rows > 0 )
		{
			return $results[0];
		}else{
			e(lang("cant_find_level"));
			return false;
		}
	}
	
	/**
	 * Function used to get users of particular level
	 * @param : level_id
	 * @param : count BOOLEAN (if TRUE it will return NUMBERS)
	 */
	function get_level_users($id,$count=FALSE,$fields="level")
	{
		global $db;
		if($fields == "all")
			$fields = "*";
			
		$results = $db->select(tbl("users"),$fields," level='$id'");
		if($db->num_rows>0)
		{
			if($count)
				return $db->num_rows;
			else
				return $results;
		}else{
			return 0;
		}
	}
	
	
	/**
	 * Function used to add user level
	 */
	function add_user_level($array)
	{
		global $db;
		if(!is_array($array))
			$array = $_POST;
		$level_name = mysql_clean($array['level_name']);
		if(empty($level_name))
			e(lang("please_enter_level_name"));
		else
		{
			$db->insert(tbl("user_levels"),array('user_level_name'),array($level_name));
			$iid = $db->insert_id();
			
			$fields_array[] = 'user_level_id';
			$value_array[] = $iid;
			foreach($this->get_access_type_list() as $access => $name)
			{
				$fields_array[] = $access;
				$value_array[] = $array[$access] ? $array[$access] : 'no';
			}
			$db->insert(tbl("user_levels_permissions"),$fields_array,$value_array);		
			return true;
		}
	}
	
	/**
	 * Function usewd to get level permissions
	 */
	function get_level_permissions($id)
	{
		global $db;
		$results = $db->select(tbl("user_levels_permissions"),"*"," user_level_id = '$id'");		
		if($db->num_rows>0)
			return $results[0];
		else
			return false;
	}
	
	/**
	 * Function used to get custom permissions
	 */
	function get_access_type_list()
	{
		if(!$this->access_type_list)
		{
			$perms = $this->get_permissions();
			foreach($perms as $perm)
			{
				$this->add_access_type($perm['permission_code'],$perm['permission_name']);
			}
		}
		return $this->access_type_list;
	}
	
	/**
	 * Function used to add new custom permission
	 */
	function add_access_type($access,$name)
	{
		if(!empty($access) && !empty($name))
			$this->access_type_list[$access] = $name;
	}
	
	/**
	 * Function get access
	 */
	function get_access($access)
	{
		return $this->access_type_list[$access];
	}
	
	/**
	 * Function used to update user level
	 * @param INT level_id
	 * @param ARRAY perm_level
	 */
	function update_user_level($id,$array)
	{
		global $db;
		if(!is_array($array))
			$array = $_POST;
		
		//First Checking Level
		$level = $this->get_level_details($id);
		

		if($level)
		{
			foreach($this->get_access_type_list() as $access => $name)
			{
				$fields_array[] = $access;
				$value_array[] = $array[$access];
			}


			
			//Checking level Name
			if(!empty($array['level_name']))
			{
				$level_name = mysql_clean($array['level_name']);
				//Upadting Now				
				$db->update(tbl("user_levels"),array("user_level_name"),array($level_name)," user_level_id = '$id'");
			}
			

			if(isset($_POST['plugin_perm']))
			{
				$fields_array[] = 'plugins_perms';
				$value_array[] = '|no_mc|'.json_encode($_POST['plugin_perm']);
			}
			
			
			//Updating Permissions
			$db->update(tbl("user_levels_permissions"),$fields_array,$value_array," user_level_id = '$id'");
			
			e(lang("level_updated"),'m');
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to delete user levels
	 * @param INT level_id
	 */
	function delete_user_level($id)
	{
		global $db;
		$level_details = $this->get_level_details($id);
		$de_level = $this->get_level_details(3);
		if($level_details)
		{
			//CHeck if leve is deleteable or not
			if($level_details['user_level_is_default']=='no')
			{
				$db->delete(tbl("user_levels"),array("user_level_id"),array($id));
				$db->delete(tbl("user_levels_permissions"),array("user_level_id"),array($id));
				e(sprintf(lang("level_del_sucess"),$de_level['user_level_name']));
				
				$db->update(tbl("users"),array("level"),array(3)," level='$id'");
				return true;
				
			}else{
				e(lang("level_not_deleteable"));
				return false;
			}
		}
	}
	
	
	/**
	 * Function used to count total video comments
	 */
	function count_profile_comments($id)
	{
		global $db;
		$total_comments = $db->count(tbl('comments'),"comment_id","type='c' AND type_id='$id'");
		return $total_comments;
	}
	function count_channel_comments($id){ return $this->count_profile_comments($id); }
	
	/**
	 * Function used to count total comments made by users
	 */
	function count_comments_by_user($uid)
	{
		global $db;
		$total_comments = $db->count(tbl('comments'),"comment_id","userid='$uid'");
		return $total_comments;
	}
	
	/**
	 * Function used to update user comments
	 */
	function update_comments_by_user($uid)
	{
		global $db;
		$total_comments = $this->count_comments_by_user($id);
		$db->update(tbl("users"),array("total_comments","last_commented"),array($total_comments,now())," userid='$id'");
	}
	
	/**
	 * Function used to update user comments count
	 */
	function update_comments_count($id)
	{
		global $db;
		$total_comments = $this->count_profile_comments($id);
		$db->update(tbl("users"),array("comments_count","last_commented"),array($total_comments,now())," userid='$id'");
	
	}
	/**
	 * Function used to add comment on users profile
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL,$type='c')
	{
		global $myquery;
		if(!$this->user_exists($obj_id))
			e(lang("usr_exist_err"));
		else
		{
			$add_comment = $myquery->add_comment($comment,$obj_id,$reply_to,$type,$obj_id);
		}
		if($add_comment)
		{
			//Loggin Comment			
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "comment on a profile",
			 'action_obj_id' => $obj_id,
			 'action_done_id' => $add_comment,
			);
			insert_log('profile_comment',$log_array);
			
			//Updating Number of comments of video
			$this->update_comments_count($obj_id);
		}
		return $add_comment;
	}
	
	/**
	 * Function used to remove video comment
	 */
	function delete_comment($cid,$is_reply=FALSE)
	{
		global $myquery,$db;
		$remove_comment =  $myquery->delete_comment($cid,'c',$is_reply);
		if($remove_comment)
		{
			//Updating Number of comments of video
			$this->update_comments_count($obj_id);
		}
		return $remove_comment;
	}
	
	
	
	/**
	 * Function used to get number of videos uploaded by user
	 * @param INT userid
	 * @param Conditions
	 */
	function get_user_vids($uid,$cond=NULL,$count_only=false)
	{
		global $db;
		if($cond!=NULL)
			$cond = " AND $cond ";
			
		$results = $db->select(tbl("video"),"*"," userid = '$uid' $cond");
		if($db->num_rows > 0)
		{
			if($count_only)
				return $db->num_rows;
			else
				return $results[0];
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to get logged in username
	 */
	function get_logged_username()
	{
		return $this->get_user_field_only(user_id(),'username');
	}

	/**
	 * FUnction used to get username from userid
	 */
	function get_username($uid)
	{
		return $this->get_user_field_only($uid,'username');
	}
	
	/**
	 * Function used to create profile link
	 */
	function profile_link($udetails)
	{
		if(!is_array($udetails) && is_numeric($udetails))
			$udetails = $this->get_user_details($udetails);
		if(SEO!="yes")
			return BASEURL.'/view_channel.php?user='.$udetails['username'];
		else
			return BASEURL.'/user/'.$udetails['username'];
	}
	function get_user_link($u)
	{
		return $this->profile_link($u);
	}
	
	
	/**
	 * Function used to get permission types
	 */
	function get_level_types()
	{
		global $db;
		return $db->select(tbl($this->dbtbl['user_permission_type']),"*");
	}
	
	/**
	 * Function used to check weather level type exists or not
	 */
	function level_type_exists($id)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['user_permission_type']),"*"," user_permission_type_id='".$id."' OR user_permission_type_name='$id'");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to add new permission
	 */
	function add_new_permission($array)
	{
		global $db;
		if(empty($array['code']))
			e(lang("perm_code_empty"));
		elseif(empty($array['name']))
			e(lang("perm_name_empty"));
		elseif($this->permission_exists($array['code']))
			e(lang("perm_already_exist"));
		elseif(!$this->level_type_exists($array['type']))
			e(lang("perm_type_not_valid"));
		else
		{
			$type = $this->level_type_exists($array['type']);
			$typeid = $type['user_permission_type_id'];
			$code = mysql_clean($array['code']);
			$name = mysql_clean($array['name']);
			$desc = mysql_clean($array['desc']);
			$default = mysql_clean($array['default']);
			$default = $default ? $default : "yes";
			$db->insert(tbl($this->dbtbl['user_permissions']),
						array('permission_type','permission_code','permission_name','permission_desc','permission_default'),
						array($typeid,$code,$name,$desc,$default));
			$db->execute("ALTER TABLE ".tbl($this->dbtbl['user_level_permission'])." ADD `".$code."` ENUM( 'yes', 'no' ) NOT NULL DEFAULT '".$default."'");
			e(lang("perm_added"),"m");
		}
	}
	
	/**
	 * Function used to check permission exists or not
	 * @Param permission code
	 */
	function permission_exists($code)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['user_permissions']),"*"," permission_code='".$code."' OR permission_id='".$code."'");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to get permissions
	 */
	function get_permissions($type=NULL)
	{
		global $db;
		if($type)
			$cond = " permission_type ='$type'";
		$result = $db->select(tbl($this->dbtbl['user_permissions']),"*",$cond);
		if($db->num_rows>0)
		{
			return $result;
		}else
		{
			return false;
		}
	}
	
	/**
	 * Function used to remove Permission
	 */
	function remove_permission($id)
	{
		global $db;
		$permission = $this->permission_exists($id);
		if($permission)
		{
			$field = $permission['permission_code'];
			$db->delete(tbl($this->dbtbl['user_permissions']),array("permission_id"),array($id));
			$db->execute("ALTER TABLE `".tbl($this->dbtbl['user_level_permission'])."` DROP `".$field."` ");
			e(lang("perm_deleted"),"m");
		}else
			e(lang("perm_doesnt_exist"));
	}
	
	
	/**
	 * Function used to check weather current user has permission
	 * to view page or not
	 * it will also check weather current page requires login 
	 * if login is required, user will be redirected to signup page
	 */
	function perm_check($access='',$check_login=FALSE,$control_page=true)
	{
		global $Cbucket;
		/*if($check_login)
		{
			return $this->login_check($access);
		}else
		{*/
			$access_details = $this->permission;
			//pr($access_details);
			if(is_numeric($access))
			{
				if($access_details['level_id'] == $access)
				{
					return true;
				}else{
					if(!$check_only)
					e(lang('insufficient_privileges'));
					
					if($control_page)
					$Cbucket->show_page(false);
					return false;
				}
			}else
			{
				
				if($access_details[$access] == 'yes')
				{
					return true;
				}
				else
				{
					
					if(!$check_login)
						e(lang('insufficient_privileges'));
					else
					{	if(userid())
							e(lang('insufficient_privileges'));
						else
							e(sprintf(lang('insufficient_privileges_loggin'),cblink(array('name'=>'signup')),cblink(array('name'=>'signup'))));
					}
					
					if($control_page)
					$Cbucket->show_page(false);
					return false;
				}
			}
		//}
	}
	
	
	/** 
	 * Function used to get user profile details
	 */
	function get_user_profile($uid)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['user_profile']),"*"," userid='$uid'");
		if($db->num_rows>0)
		{
			return $result[0];
		}else
			return false;
	}
	
	
	
	
	
	/**
	 * User Profile Fields
	 */
	function load_profile_fields($default)
	{
		if(!$default)
			$default = $_POST;
		
		$profile_fields = $this->load_personal_details($default);
		$other_details = $this->load_location_fields($default);
		$more_details = $this->load_education_interests($default);
		$channel = $this->load_channel_settings($default);
		$privacy_field = $this->load_privacy_field($default);
		return array_merge($profile_fields,$other_details,$more_details,$channel,$privacy_field);
	}
	
	
	
	
	
	/**
	 * Function used to update use details
	 */
	function update_user($array)
	{
		global $LANG,$db,$signup,$Upload;
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);

		$userfields = $this->load_profile_fields($array);
		//$signup_fields = $this->load_signup_fields($array);
		$custom_signup_fields = $this->load_custom_signup_fields($array);
		
		//Adding Custom Form Fields
		if(count($this->custom_profile_fields)>0)
			$userfields = array_merge($userfields,$this->custom_profile_fields);
		
		//Adding custom fields from group
		if(count($this->custom_profile_fields_groups)>0)
		{
			$custom_fields_from_group_fields = array();
			$custom_fields_from_group = $this->custom_profile_fields_groups;
			foreach($custom_fields_from_group as $cffg)
			{
				$custom_fields_from_group_fields = array_merge($custom_fields_from_group_fields,$cffg['fields']);
			}						
			
			$userfields = array_merge($userfields,$custom_fields_from_group_fields);
		}
			
			
		validate_cb_form($custom_signup_fields,$array);
		
		validate_cb_form($userfields,$array);
	//	pr();
		foreach($userfields as $field)
		{
			$name = formObj::rmBrackets($field['name']);
			$val = $array[$name];
			
			if($field['use_func_val'])
				$val = $field['validate_function']($val);
			
			
			if(!empty($field['db_field']))
			$query_field[] = $field['db_field'];
			
			if(is_array($val))
			{
				$new_val = '';
				foreach($val as $v)
				{
					$new_val .= "#".$v."# ";
				}
				$val = $new_val;
			}
			if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
				$val = mysql_clean($val);
			else
				$val = apply_func($field['clean_func'],sql_free(/*'|no_mc|'.*/$val));

			
			if(!empty($field['db_field']))
			$query_val[] = $val;
		}
		
		//Category
		if($cat_field)
		{
			$field = $cat_field;
			$name = formObj::rmBrackets($field['name']);
			$val = $array[$name];
			
			if($field['use_func_val'])
				$val = $field['validate_function']($val);
			
			
			if(!empty($field['db_field']))
			$uquery_field[] = $field['db_field'];
			
			if(is_array($val))
			{
				$new_val = '';
				foreach($val as $v)
				{
					$new_val .= "#".$v."# ";
				}
				$val = $new_val;
			}
			if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
				$val = mysql_clean($val);
			else
				$val = apply_func($field['clean_func'],sql_free(/*'|no_mc|'.*/$val));
			
			if(!empty($field['db_field']))
			$uquery_val[] = $val;
		}
		

		
		//updating user detail
		if(has_access('admin_access',TRUE) && isset($array['admin_manager']))
		{
			//Checking Username
			if(empty($array['username']))
				e(lang('usr_uname_err'));
			//elseif($array['dusername'] != $array['username'] && $this->username_exists($array['username']))
				//e(lang('usr_uname_err2'));
			elseif(!username_check($array['username']))
				e(lang('usr_uname_err3'));
			else
				$username = $array['username'];
			
			//Checking Email
			if(empty($array['email']))
				e(lang('usr_email_err1'));
			elseif(!is_valid_syntax('email',$array['email']))
				e(lang('usr_email_err2'));
			elseif(email_exists($array['email']) && $array['email'] != $array['demail'])
				e(lang('usr_email_err3'));
			else
				$email = $array['email'];
				
			$uquery_field[] = 'username';
			$uquery_val[]	= $username;
			
			$uquery_field[] = 'email';
			$uquery_val[]	= $email;
			
			//Changning Password
			if(!empty($array['pass']))
			{
				if($array['pass']!=$array['cpass'])
					e(lang("pass_mismatched"));
				else
					$pass = pass_code($array['pass']);
				$uquery_field[] = 'password';
				$uquery_val[]	= $pass;
			}
			
			//Changing User Level
			$uquery_field[] = 'level';
			$uquery_val[] = $array['level'];
			
			//Checking for user stats
			$uquery_field[] = 'profile_hits';
			$uquery_val[] = $array['profile_hits'];
			$uquery_field[] = 'total_watched';
			$uquery_val[] = $array['total_watched'];
			$uquery_field[] = 'total_videos';
			$uquery_val[] = $array['total_videos'];
			$uquery_field[] = 'total_comments';
			$uquery_val[] = $array['total_comments'];
			$uquery_field[] = 'subscribers';
			$uquery_val[] = $array['subscribers'];
			$uquery_field[] = 'comments_count';
			$uquery_val[] = $array['comments_count'];
			$query_field[] = 'rating';
			
			$rating = $array['rating'];
			if($rating<1 || $rating>10)
				$rating = 1;
			$query_val[] = $rating ;
			$query_field[] = 'rated_by';
			$query_val[] = $array['rated_by'];
			
			//Changing JOined Date
			if(isset($array['doj']))
			{
				$uquery_field[] = 'doj';
				$uquery_val[] = $array['doj'];
			}
			
		}
		
		//Changing Gender
		if($array['sex'])
		{
			$uquery_field[] = 'sex';
			$uquery_val[] = mysql_clean($array['sex']);
		}
		
		//Changing Country
		if($array['country'])
		{
			$uquery_field[] = 'country';
			$uquery_val[] = mysql_clean($array['country']);
		}
		
		//Changing Date of birth
		if(isset($array['dob']))
		{
			$uquery_field[] = 'dob';
			$uquery_val[] = $array['dob'];
		}
		
		//Changing category
		if(isset($array['category']))
		{
			$uquery_field[] = 'category';
			$uquery_val[] = $array['category'];
		}
		
		
		//Updating User Avatar
		if($array['avatar_url'])
		{
			$uquery_field[] = 'avatar_url';
			$uquery_val[] = $array['avatar_url'];
		}
		if($array['remove_avatar_url']=='yes')
		{
			$uquery_field[] = 'avatar_url';
			$uquery_val[] = '';
		}
		
		//Deleting User Avatar
		if($array['delete_avatar']=='yes')
		{
			$file = USER_THUMBS_DIR.'/'.$array['avatar_file_name'];
			if(file_exists($file) && $array['avatar_file_name'] !='')
				unlink($file);
		}
		
		//Deleting User Bg
		if($array['delete_bg']=='yes')
		{
			
			$file = USER_BG_DIR.'/'.$array['bg_file_name'];
			if(file_exists($file) && $array['bg_file_name'])
			unlink($file);
		}
		
		
		if(isset($_FILES['avatar_file']['name']))
		{
			$file = $Upload->upload_user_file('a',$_FILES['avatar_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'avatar';
				$uquery_val[] = $file;
			}
		}
		
		
		//Updating User Background
		if($array['background_url'])
		{
			$uquery_field[] = 'background_url';
			$uquery_val[] = $array['background_url'];
		}
		
		if($array['background_color'])
		{
			$uquery_field[] = 'background_color';
			$uquery_val[] = $array['background_color'];
		}
		
		if($array['background_repeat'])
		{
			$uquery_field[] = 'background_repeat';
			$uquery_val[] = $array['background_repeat'];
		}
		
		
		if(isset($_FILES['background_file']['name']))
		{
			$file = $Upload->upload_user_file('b',$_FILES['background_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'background';
				$uquery_val[] = $file;
			}
		}
		
		
		//Adding Custom Field
		if(is_array($custom_signup_fields))
		{
			foreach($custom_signup_fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];
				
				if($field['use_func_val'])
					$val = $field['validate_function']($val);
				
				
				if(!empty($field['db_field']))
				$uquery_field[] = $field['db_field'];
				
				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
					$val = mysql_clean($val);
				else
					$val = apply_func($field['clean_func'],sql_free(/*'|no_mc|'.*/$val));
	
				
				if(!empty($field['db_field']))
					$uquery_val[] = $val;
			}
		}
		
		if(!error() && is_array($uquery_field))
		{
			$db->update(tbl($this->dbtbl['users']),$uquery_field,$uquery_val," userid='".mysql_clean($array['userid'])."'");
			e(lang("usr_upd_succ_msg"),'m');
		}
		
		
		
		//updating user profile
		if(!error())
		{
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "updated profile"
			);
			//Login Upload
			insert_log('profile_update',$log_array);
			
			$db->update(tbl($this->dbtbl['user_profile']),$query_field,$query_val," userid='".mysql_clean($array['userid'])."'");
			e(lang("usr_pof_upd_msg"),'m');
		}
	}
	
	
	/**
	 * Function used to update user avatar and background only
	 */
	function update_user_avatar_bg($array)
	{
		global $db,$signup,$Upload;
		//Updating User Avatar
		$uquery_field[] = 'avatar_url';
		$uquery_val[] = mysql_clean($array['avatar_url']);
	
		
		//Deleting User Avatar
		if($array['delete_avatar']=='yes')
		{
			$file = USER_THUMBS_DIR.'/'.$array['avatar_file_name'];
			if(file_exists($file) && $array['avatar_file_name'] !='')
				unlink($file);
		}
		
		//Deleting User Bg
		if($array['delete_bg']=='yes')
		{
			$file = USER_BG_DIR.'/'.$array['bg_file_name'];
			if(file_exists($file) && $array['bg_file_name'] !='')
				unlink($file);
		}
		
		
		if(isset($_FILES['avatar_file']['name']))
		{
			$file = $Upload->upload_user_file('a',$_FILES['avatar_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'avatar';
				$uquery_val[] = $file;
			}
		}
		
		
		//Updating User Background
		$uquery_field[] = 'background_url';
		$uquery_val[] = mysql_clean($array['background_url']);
		
		$uquery_field[] = 'background_color';
		$uquery_val[] = mysql_clean($array['background_color']);
		
		if($array['background_repeat'])
		{
			$uquery_field[] = 'background_repeat';
			$uquery_val[] = mysql_clean($array['background_repeat']);
		}
		
		//Background ATtachement
		$uquery_field[] = 'background_attachement';
		$uquery_val[] = mysql_clean($array['background_attachement']);
		
		
		if(isset($_FILES['background_file']['name']))
		{
			
			$file = $Upload->upload_user_file('b',$_FILES['background_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'background';
				$uquery_val[] = mysql_clean($file);
			}
		}
		
		$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "updated profile"
			);

			//Login Upload
			insert_log('profile_update',$log_array);
			
		$db->update(tbl($this->dbtbl['users']),$uquery_field,$uquery_val," userid='".mysql_clean($array['userid'])."'");
		e(lang("usr_avatar_bg_update"),'m');

	}

	/* Written by Sajjad Ashraf */

	public function updateCover($array = array()){
		if(!empty($array)){
			if(isset($array["coverPhoto"])){
				$coverPhoto = $array["coverPhoto"];
				$photoType = $coverPhoto["type"];
				$photoSize = (int) $coverPhoto["size"]; // in kbs
				$maxPhotoSize = 2048; // in kbs
				if($photoType){
					$name = $array["userid"];
					$coverPhoto = $array["coverPhoto"]["tmp_name"];
					$ext = $this->getImageExt($array["coverPhoto"]["name"]);
					if (!file_exists(COVERS_DIR . "/{$name}")) {
					    mkdir(COVERS_DIR . "/{$name}", 0777, true);
					}
					list($width, $height, $type, $attr) = getimagesize($coverPhoto);
					$width = (int) $width;
					$height = (int) $height;
					if(($width > 1) && ($height > 2)){
						$files = glob(COVERS_DIR . "/{$name}/{$name}.*"); // get all file names
						foreach($files as $file){ // iterate files
						  if(is_file($file))
						    unlink($file); // delete file
						}
						move_uploaded_file($coverPhoto, COVERS_DIR . "/{$name}/{$name}.{$ext}");
						//$this->resizeImage(COVERS_DIR . "/{$name}/{$name}.{$ext}", COVERS_DIR . "/{$name}/{$name}.{$ext}");
						return array(
							"status" => true,
							"msg" => "Succesfully Uploaded",
							);
					}else{
						return array(
							"status" => false,
							"msg" => "Only 1150 x 220 images are allowed {$width} x {$height} provided",
							);
					}
				}
			}
		}
		return array(
			"status" => false,
			"msg" => "no data was sent",
			);
	}

	public function getCover($userId = false){
		if(!$userId){
			$userId = userid();
		}
		$coverPath = COVERS_DIR . "/{$userId}";
		
		if (file_exists($coverPath)) 
		{
			$files = scandir($coverPath);
			array_shift($files); array_shift($files);
			$coverPhoto = array_shift($files);
			return BASEURL . "/files/cover_photos/{$userId}/$coverPhoto";
		}
	}

	public function getImageExt($imageName = false){
		if($imageName){
			$nameParts = explode(".", $imageName);
			$ext = array_pop($nameParts);
			return $ext;
		}
	}

	public function resizeImage($source_image_path, $thumbnail_image_path){
		define('THUMBNAIL_IMAGE_MAX_WIDTH', 1150);
		define('THUMBNAIL_IMAGE_MAX_HEIGHT', 220);
	    list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
	    switch ($source_image_type) {
	        case IMAGETYPE_GIF:
	            $source_gd_image = imagecreatefromgif($source_image_path);
	            break;
	        case IMAGETYPE_JPEG:
	            $source_gd_image = imagecreatefromjpeg($source_image_path);
	            break;
	        case IMAGETYPE_PNG:
	            $source_gd_image = imagecreatefrompng($source_image_path);
	            break;
	    }
	    if ($source_gd_image === false) {
	        return false;
	    }
	    $source_aspect_ratio = $source_image_width / $source_image_height;
	    $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
	    if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
	        $thumbnail_image_width = $source_image_width;
	        $thumbnail_image_height = $source_image_height;
	    } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
	        $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
	        $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
	    } else {
	        $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
	        $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
	    }
	    $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
	    imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
	    imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 90);
	    imagedestroy($source_gd_image);
	    imagedestroy($thumbnail_gd_image);
	    return true;
	}
	
	
	/**
	 * Function used to check weather username exists or not
	 */
	function username_exists($i)
	{
		global $db;
		//echo test;
		return $db->count(tbl($this->dbtbl['users']),"username"," username='$i'");
	}
	
	/**
	 * function used to check weather email exists or not
	 */
	 function email_exists($i)
	{
		global $db;
		$db->select(tbl($this->dbtbl['users']),"email"," email='$i'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	
	/**
	 * Function used to get user access log
	 */
	function get_user_action_log($uid,$limit=NULL)
	{
		global $db;
		$result = $db->select(tbl($this->dbtbl['action_log']),"*"," action_userid='$uid'",$limit," date_added DESC");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Load Custom Profile Field
	 */
	function load_custom_profile_fields($data,$group_based=false)
	{
		if(!$group_based)
		{
			$array = $this->custom_profile_fields;
			foreach($array as $key => $fields)
			{
					if($data[$fields['db_field']])
						$value = $data[$fields['db_field']];
					elseif($data[$fields['name']])
						$value = $data[$fields['name']];
						
						
					if($fields['type']=='radiobutton' || 
					   $fields['type']=='checkbox' ||
					   $fields['type']=='dropdown')
					$fields['checked'] = $value;
					else
					$fields['value'] = $value;						
					$new_array[$key] = $fields;
			}
			return $new_array;
		}else
		{
			$groups = $this->custom_profile_fields_groups;
			
			$new_grp = array();
			if($groups)
			foreach($groups as $grp)
			{
				$fields = array();
				foreach($grp['fields'] as $key => $fields)
				{
					if($data[$fields['db_field']])
						$value = $data[$fields['db_field']];
					elseif($data[$fields['name']])
						$value = $data[$fields['name']];
						
						
					if($fields['type']=='radiobutton' || 
					   $fields['type']=='checkbox' ||
					   $fields['type']=='dropdown')
					$fields['checked'] = $value;
					else
					$fields['value'] = $value;		
				}
				$grp['fields'][$key] = $fields;
				$new_grp[] = $grp;
			}
		}
		
		return $new_grp;
	}
	
	/**
	 * Load Custom Signup Field
	 */
	function load_custom_signup_fields($data,$ck_display_admin=FALSE,$ck_display_user=FALSE)
	{
		$array = $this->custom_signup_fields;
		foreach($array as $key => $fields)
		{
			$ok = 'yes';
			if($ck_display_admin)
			{
				if($fields['display_admin'] == 'no_display')
					$ok = 'no';
			}
			
			if($ck_display_user)
			{
				if($fields['display_user'] == 'no_display')
					$ok = 'no';
			}
			
			if($ok=='yes')
			{
				if(!$fields['value'])
					$fields['value'] = $data[$fields['db_field']];
				$new_array[$key] = $fields;
			}
		}
		
		return $new_array;
	}
	
	
	/**
	 * Function used to get channel links
	 * ie Playlist, favorites etc etc
	 */
	function get_inner_channel_top_links($u)
	{
		$channel_links = array();
		
		if(isSectionEnabled('videos'))
		{
			$channel_links[lang('upload')] = array('link'=>$this->get_user_videos_link($u));
			$channel_links[lang('favorites')] = array('link'=>cblink(array('name'=>'user_favorites')).$u['username']);
		}
		if(isSectionEnabled('channels'))
		$channel_links[lang('contacts')] = array('link'=>cblink(array('name'=>'user_contacts')).$u['username']);
		
		return $channel_links;
	}
	
	/**
	 * Function used to get user channel action links
	 * ie Add to friends, send message etc etc
	 */
	function get_channel_action_links($u)
	{
			return array(lang('send_message')=>array('link'=>cblink(array('name'=>'compose_new','extra_params'=>'to='.$u['username']))),
                     lang('add_as_friend')=>array('link'=>'javascript:void(0)','onclick'=>"add_friend('".$u['userid']."','result_cont')"),
                     lang('block_user')=>array('link'=>'javascript:void(0)','onclick'=>"block_user('".$u['username']."','result_cont')")
                     );	}
	
	/**
	 * Function used to get user videos link
	 */
	function get_user_videos_link($u)
	{
		return cblink(array('name'=>'user_videos')).$u['username'];
	}	
	
	
	/**
	 * Function used to get user channel video
	 */
	function get_user_profile_video($u)
	{
		global $db,$cbvid;
		if(empty($u['profile_video'])&&!$cbvid->video_exists($u))
		{
			$u = $this->get_user_profile($u);
		}
		
		if($cbvid->video_exists($u['profile_video']))
			return $cbvid->get_video_details($u['profile_video']);
		else
			return false;
	}
	
	
	/**
	 * My Account links Edited on 12 march 2014 for user account links
	 */
    function my_account_links()
    {


        $array[lang('account')]	=
            array
            (
                lang('my_account')	=> 'myaccount.php',
                lang('block_users')	=> 'edit_account.php?mode=block_users',
                lang('user_change_pass')	=>'edit_account.php?mode=change_password',
                lang('user_change_email') 	=>'edit_account.php?mode=change_email',
                lang('com_manage_subs')	=> 'edit_account.php?mode=subscriptions',
                lang('Contacts Manager')	=> 'manage_contacts.php'
            );


        $array[lang('user_channel_profiles')] = array
        (
            lang('account_settings') =>'edit_account.php?mode=account',
            lang('user_profile_settings') =>'edit_account.php?mode=profile',
            lang('change_avatar') 	=> 'edit_account.php?mode=avatar_bg',
            lang('change_bg') => 'edit_account.php?mode=channel_bg',
        );

        if(isSectionEnabled('videos'))
            $array[lang('videos')] =  array
            (
                lang('uploaded_videos')=>'manage_videos.php',
                lang('user_fav_videos')=>'manage_videos.php?mode=favorites',
            );

        if(isSectionEnabled('groups'))
            $array[lang('groups')] =  array
            (
                lang('grp_groups_title') =>'manage_groups.php',
                lang('user_create_group') =>cblink(array('name'=>'create_group')),
                lang('grp_joined_groups')=>'manage_groups.php?mode=joined',
            );

        if(isSectionEnabled('playlists'))
            $array[lang('playlists')]=array
            (
                lang('manage_playlists') =>'manage_playlists.php',
                lang('video_playlists') =>'manage_playlists.php?mode=manage_video_playlist',
            );
        $array[lang('messages')] = array
        (
            lang('inbox')	=> 'private_message.php?mode=inbox',
            lang('notifications') => 'private_message.php?mode=notification',
            lang('sent')	=> 'private_message.php?mode=sent',
            lang('title_crt_new_msg')=> cblink(array('name'=>'compose_new')),
        );

        if(isSectionEnabled('channels'))
            $array[lang('contacts')] =  array
            (
                lang('com_manage_contacts') => 'manage_contacts.php?mode=manage',
                lang('add_contact_list') => 'manage_contacts.php?mode=new_group',
            );



        if(count($this->user_account)>0)
        {
            foreach($this->user_account as $key => $acc)
            {
                if(array_key_exists($key,$array))
                {
                    foreach($acc as $title => $link)
                        $array[$key][$title] = $link;
                }else
                    $array[$key] = $acc;
            }
            //$array = array_merge($array,$this->user_account);
        }

        return $array;
    }
	
	
	/**
	 * Function used to change email
	 */
	function change_email($array)
	{
		global $db;
		//function used to change user email
		if(!isValidEmail($array['new_email']) || $array['new_email']=='')
			e(lang("usr_email_err2"));
		elseif($array['new_email']!=$array['cnew_email'])
			e(lang('user_email_confirm_email_err'));
		elseif(!$this->user_exists($array['userid']))	
			e(lang('usr_exist_err'));
		elseif($this->email_exists($array['new_email']))
			e(lang('usr_email_err3'));	
		else
		{
			$db->update(tbl($this->dbtbl['users']),array('email'),array($array['new_email'])," userid='".$array['userid']."'");
			e(lang("email_change_msg"),"m");
		}
	}
	
	/**
	 * Function used to ban users
	 */
	function block_users($users,$uid=NULL){ return $this->ban_users($users,$uid); }
	function ban_users($users,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid  = userid();
		$users_array = explode(',',$users);
		$new_users = array();
		foreach($users_array as $user)
		{
			if($user!=username() && !is_numeric($user) && $this->user_exists($user))
			{
				$new_users[] = $user;
			}
		}	
		if(count($new_users)>0)
		{
			$new_users = array_unique($new_users);
			$banned_users = implode(',',$new_users);
			$db->update(tbl($this->dbtbl['users']),array('banned_users'),array($banned_users)," userid='$uid'");
			e(lang("user_ban_msg"),"m");
		}elseif (!$users){
			$db->update(tbl($this->dbtbl['users']),array('banned_users'),array($users)," userid='$uid'");
			e(lang("no_user_ban_msg"),"m");
		}
	}
	
	/**
	 * Function used to ban single user
	 */
	function ban_user($user)
	{
		global $db;
		$uid  = userid();
		
		if(!$uid)
			e(lang('you_not_logged_in'));
		elseif($user!=username() && !is_numeric($user) && $this->user_exists($user))
		{
			$banned_users = $this->udetails['banned_users'];
			if($banned_users)
				$banned_users .= ",$user";
			else
				$banned_users = "$user";
			
			if(!$this->is_user_banned($user))
			{
				$db->update(tbl($this->dbtbl['users']),array('banned_users'),array($banned_users)," userid='$uid'");
				e(lang("user_blocked"),"m");
			}else
				e(lang("user_already_blocked"));
		}else
		{
			e(lang("you_cant_del_user"));
		}
	}
	
	
	
	/**
	 * Function used to check weather user is banned or not
	 */
	function is_user_banned($ban,$user=NULL,$banned_users=NULL)
	{
		global $db;
		if(!$user)
			$user = userid();
		
		if(!$banned_users)
		{
			if(is_numeric($user))
				$result = $db->select(tbl($this->dbtbl['users']),"banned_users"," userid='$user' ");
			else
				$result = $db->select(tbl($this->dbtbl['users']),"banned_users"," username='$user' ");
			$banned_users = $result[0]['banned_users'];
		}
		
		$ban_user = explode(',',$banned_users);
		if(in_array($ban,$ban_user)) {
			return true;
		} else {
			return false;
		}
		
		return false;
	}
	
	/**
	 * function used to get user details with profile
	 */
	function get_user_details_with_profile($uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		$result = $db->select(tbl($this->dbtbl['users'].",".$this->dbtbl['user_profile']),"*",tbl($this->dbtbl['users']).".userid ='$uid' AND ".tbl($this->dbtbl['users']).".userid = ".tbl($this->dbtbl['user_profile']).".userid");
		return $result[0];
	}
	
	
	function load_signup_fields($input=NULL)
	{
		global $LANG,$Cbucket;

		$default = array();

		if(isset($input)){
			$default = $input;
		}else{
			//return false;
		}
		/**
		 * this function will create initial array for user fields
		 * this will tell 
		 * array(
		 *       title [text that will represents the field]
		 *       type [type of field, either radio button, textfield or text area]
		 *       name [name of the fields, input NAME attribute]
		 *       id [id of the fields, input ID attribute]       
		 *       value [value of the fields, input VALUE attribute]
		 *       size
		 *       class
		 *       label
		 *       extra_params
		 *       hint_1 [hint before field]
		 *       hint_2 [hint after field]
		 *       anchor_before [anchor before field]
		 *       anchor_after [anchor after field]
		 *      )
		 */
		// ini_set('display_errors',1);
		// ini_set('display_startup_errors',1);
		// error_reporting(-1);
		

		
			if(empty($default))
				$default = $_POST;
			
			if(empty($default))
				$default = $_POST;
			
			$username = (isset($default['username'])) ? $default['username'] : "";
			$email = (isset($default['email'])) ? $default['email'] : "";
			if(isset($default['country'])){
				$dcountry = (isset($default['country'])) ? $default['country'] : $Cbucket->configs['default_country_iso2'];
			}else{
				$dcountry = "";
			}
			$dob = (isset($default['dob'])) ? $default['dob'] : "";
			//var_dump($_POST);
			//die();
			 $dob =  $dob ? date(config("date_format"),strtotime($dob)) : date(config("date_format"),strtotime('14-10-1989'));
				
					 
			 $user_signup_fields = array
			 (
			  'username' => array(
								  'title'=> lang('username'),
								  'type'=> "textfield",
								  'name'=> "username",
								  'id'=> "username",
								  'value'=> $username,
								  'hint_2'=> lang('user_allowed_format'),
								  'db_field'=>'username',
								  'required'=>'yes',
								 // 'syntax_type'=> 'username',
								  'validate_function'=> 'username_check',
								  'function_error_msg' => lang('user_contains_disallow_err'),
								  'db_value_check_func'=> 'user_exists',
								  'db_value_exists'=>false,
								  'db_value_err'=>lang('usr_uname_err2'),
								  'min_length'	=> config('min_username'),
								  'max_length' => config('max_username'),
								  ),
			  'email' => array(
								  'title'=> lang('email'),
								  'type'=> "textfield",
								  'name'=> "email",
								  'id'=> "email",
								  'value'=> $email,
								  'db_field'=>'email',
								  'required'=>'yes',
								  'syntax_type'=> 'email',
								  'db_value_check_func'=> 'email_exists',
								  'db_value_exists'=>false,
								  'db_value_err'=>lang('usr_email_err3')
								  ),
			  'password' => array(
								  'title'=> lang('password'),
								  'type'=> "password",
								  'name'=> "password",
								  'id'=> "password",
								  'db_field'=>'password',
								  'required'=>'yes',
								  'invalid_err'=>lang('usr_pass_err2'),
								  'relative_to' => 'cpassword',
								  'relative_type' => 'exact',
								  'relative_err' => lang('usr_pass_err3'),
								  'validate_function' => 'pass_code',
								  'use_func_val'=>true
								  ),
			  'cpassword' => array(
								  'title'=> lang('user_confirm_pass'),
								  'type'=> "password",
								  'name'=> "cpassword",
								  'id'=> "cpassword",
								  'required'=>'no',
								  'invalid_err'=>lang('usr_cpass_err'),
								  ),
			  'country'	=> array(
								 'title'=> lang('country'),
								 'type' => 'dropdown',
								 'value' => $Cbucket->get_countries(iso2),
								 'id'	=> 'country',
								 'name'	=> 'country',
								 'checked'=> $dcountry,
								 'db_field'=>'country',
								 'required'=>'yes',
								 ),
			  'gender' => array(
								'title' => lang('gender'),
								'type' => 'radiobutton',
								'name' => 'gender',
								'class' => 'radio',
								'id' => 'gender',
								'value' => array('Male'=>lang('male'),'Female'=>lang('female')),
								'sep'=> '&nbsp;',
								'checked'=>'Male',
								'db_field'=>'sex',
								'required'=>'yes',
								),
			  'dob'	=> array(
							 'title' => lang('user_date_of_birth'),
							 'type' => 'textfield',
							 'name' => 'dob',
							 'readonly' => 'true',
							 'id' => 'dob',
							 'anchor_after' => 'date_picker',
							 'value'=> $dob,
	                         'validate_function' => 'verify_age',
							 'db_field'=>'dob',
							 'required'=>'yes',
	                         'invalid_err'=>lang('You must be atleast '.config('min_age_reg').' to register'),
							 ),
							 						 					 
			  'cat'		=> array('title'=> lang('Category'),
								 'type'=> 'dropdown',
								 'name'=> 'category',
								 'id'=> 'category',
								 'value'=> array('category', isset($default['category'])),
								 'db_field'=>'category',
								 'checked'=> isset($default['category']),
								 'required'=>'yes',
								 'invalid_err'=>lang("Please select your category"),
								 'display_function' => 'convert_to_categories',
								 'category_type'=>'user',
								 )
			  );


	         $new_array = array();

	         foreach($user_signup_fields as $id => $fields)
	         {
	             $the_array = $fields;
	             if(isset($the_array['hint_1']))
	                 $the_array['hint_before'] = $the_array['hint_1'];

	             if(isset($the_array['hint_2']))
	                 $the_array['hint_after'] = $the_array['hint_2'];

	             $new_array[$id] = $the_array;

	         }
	         //die();
			 return $new_array;
		 
	}
	
	
	/**
	 * Function used to validate Signup Form
	 */
	function validate_form_fields($array=NULL)
	{
		global $userquery;
		$fields = $this->load_signup_fields($array);

		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);

		//Mergin Array
		$signup_fields = array_merge($fields,$this->custom_signup_fields);
		
		validate_cb_form($signup_fields,$array);
		
	}
	
	
	/**
	 * Function used to validate signup form
	 */
	function signup_user($array=NULL,$send_signup_email=true)
	{
		global $LANG,$db,$userquery;
		// echo "<pre>";
		// var_dump($array);
		// echo "</pre>";

		//die();


		if($array==NULL)
			$array = $_POST;

		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		$this->validate_form_fields($array);

		//checking terms and policy agreement
		if($array['agree']!='yes' && !has_access('admin_access',true))
			e(lang('usr_ament_err'));

		// first checking if captha plugin is enabled
		// do not trust the form cb_captcha_enabled value
		if(get_captcha() && !$userquery->admin_login_check(true)){
			// now checking if the user posted captha value is not empty and cb_captcha_enabled == yes
			if(!isset($array['cb_captcha_enabled']) || $array['cb_captcha_enabled'] == 'no'){
				e(lang('usr_ccode_err'));
				//echo "wrong captha input";
			}
			if(!verify_captcha()){
				e(lang('usr_ccode_err'));
			}
		}
		if(!error())
		{
			$signup_fields = $this->load_signup_fields($array);

			//Adding Custom Signup Fields
			if(count($this->custom_signup_fields)>0)
				$signup_fields = array_merge($signup_fields,$this->custom_signup_fields);

			foreach($signup_fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];

				if($field['use_func_val'])
					$val = $field['validate_function']($val);


				if(!empty($field['db_field']))
				$query_field[] = $field['db_field'];

				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
					$val = mysql_clean($val);
				else
					$val = apply_func($field['clean_func'],sql_free('|no_mc|'.$val));

				if(!empty($field['db_field']))
				$query_val[] = $val;

			}

			// Setting Verification type
			if(EMAIL_VERIFICATION == '1'){
				$usr_status = 'ToActivate';
				$welcome_email = 'no';
			}else{
				$usr_status = 'Ok';
				$welcome_email = 'yes';
			}

			if(has_access('admin_access',true))
			{
				if($array['active']=='Ok')
				{
					$usr_status = 'Ok';
					$welcome_email = 'yes';
				}else{
					$usr_status = 'ToActivate';
					$welcome_email = 'no';
				}

				$query_field[] = "level";
				$query_val[] = $array['level'];
			}

			$query_field[] = "usr_status";
			$query_val[] = $usr_status;

			$query_field[] = "	welcome_email_sent";
			$query_val[] = $welcome_email;

			//Creating AV Code
			$avcode		= RandomString(10);
			$query_field[] = "avcode";
			$query_val[] = $avcode;



			//Signup IP
			$signup_ip	= $_SERVER['REMOTE_ADDR'];
			$query_field[] = "signup_ip";
			$query_val[] = $signup_ip;

			//Date Joined
			$now = NOW();
			$query_field[] = "doj";
			$query_val[] = $now;


			/**
			 * A VERY IMPORTANT PART OF
			 * OUR SIGNUP SYSTEM IS
			 * SESSION KEY AND CODE
			 * WHEN A USER IS LOGGED IN
			 * IT IS ONLY VALIDATED BY
			 * ITS SIGNUP KEY AND CODE
			 *
			 */
			$sess_key = $this->create_session_key($_COOKIE['PHPSESSID'],$array['password']);
			$sess_code = $this->create_session_code();

			$query_field[] = "user_session_key";
			$query_val[] = $sess_key;

			$query_field[] = "user_session_code";
			$query_val[] = $sess_code;

			$query = "INSERT INTO ".tbl("users")." (";
			$total_fields = count($query_field);

			//Adding Fields to query
			$i = 0;
			foreach($query_field as $qfield)
			{
				$i++;
				$query .= $qfield;
				if($i<$total_fields)
				$query .= ',';
			}

			$query .= ") VALUES (";

			$i = 0;
			//Adding Fields Values to query
			foreach($query_val as $qval)
			{
				$i++;
				$query .= "'$qval'";
				if($i<$total_fields)
				$query .= ',';
			}

			//Finalzing Query
			$query .= ")";

			$db->Execute($query);
			$insert_id = $db->insert_id();
			$db->insert(tbl($userquery->dbtbl['user_profile']),array("userid"),array($insert_id));

			if(!has_access('admin_access',true) && EMAIL_VERIFICATION && $send_signup_email)
			{
				global $cbemail;
				$tpl = $cbemail->get_template('email_verify_template');
				$more_var = array
				('{username}'	=> post('username'),
				 '{password}'	=> post('password'),
				 '{email}'		=> post('email'),
				 '{avcode}'		=> $avcode,
				);
				if(!is_array($var))
					$var = array();
				$var = array_merge($more_var,$var);
				$subj = $cbemail->replace($tpl['email_template_subject'],$var);
				$msg = nl2br($cbemail->replace($tpl['email_template'],$var));

				//Now Finally Sending Email
				cbmail(array('to'=>post('email'),'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
			}
			elseif(!has_access('admin_access',true) && $send_signup_email)
			{
				$this->send_welcome_email($insert_id);
			}

			$log_array = array
			('username'	=> $array['username'],
			 'userid'	=> $insert_id,
			 'userlevel'=> $array['level'],
			 'useremail'=> $array['email'],
			 'success'=>'yes',
			 'details'=> sprintf("%s signed up",$array['username']));

			//Login Signup
			insert_log('signup',$log_array);


			//Adding User has Signup Feed
			addFeed(array('action' => 'signup','object_id' => $insert_id,'object'=>'signup','uid'=>$insert_id));

			return $insert_id;
		}

		return false;
	}

	
	
		
	//Duplicate User Check
	function duplicate_user($name){
		global $myquery;
		if($myquery->check_user($name)){
			return true;
		}else{
			return false;
		}
	}
	
	function duplicate_email($name){
		$myquery =  new myquery();
		if($myquery->check_email($name)){
		return true;
		}else{
		return false;
		}
	}
	
	//Validate Email
	
	function isValidEmail($email){
      return isValidEmail($email);
   }
	
	//Validate Username
	function isValidUsername($uname){
		return $this->is_username($uname);
	}
   
    /**
	  * Function used to make username valid
	  * this function will also check if username is banned or not
	  * it will also filter the username and also filter its patterns
	  * as given in administratio panel
	  */
	 function is_username($username)
	 {
		 global $Cbucket;
		//Our basic pattern for username is
		//$pattern = "^^[_a-z0-9-]+$";
		$pattern = "^^[_a-z0-9-]+$"; 
		//Now we will check if admin wants to change the pattern
		if (eregi($pattern, $username)){
			return true;
		}else {
			return false;
		}  
		
	 }
	
	
	/**
	 * Function used to get users
	 */
	function get_users($params=NULL,$force_admin=FALSE)
	{
		global $db;
		
		$limit = $params['limit'];
		$order = $params['order'];
		
		$cond = "";
		if(!has_access('admin_access',TRUE) && !$force_admin)
			$cond .= " 	users.usr_status='Ok' AND users.ban_status ='no' ";
		else
		{
			if($params['ban'])
				$cond .= " users.ban_status ='".$params['ban']."'";
				
			if($params['status'])
			{
				if($cond!='')
					$cond .=" AND ";
				$cond .= " 	users.usr_status='".$params['status']."'";
			}

		}
		
		//Setting Category Condition
		if(!is_array($params['category']))
			$is_all = strtolower($params['category']);
			
		if($params['category'] && $is_all!='all')
		{
			if($cond!='')
				$cond .= ' AND ';
				
			$cond .= " (";
			
			if(!is_array($params['category']))
			{
				$cats = explode(',',$params['category']);
			}else
				$cats = $params['category'];
				
			$count = 0;
			
			foreach($cats as $cat_params)
			{
				$count ++;
				if($count>1)
				$cond .=" OR ";
				$cond .= " users.category LIKE '%$cat_params%' ";
			}
			
			$cond .= ")";
		}
		
		//date span
		if($params['date_span'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".cbsearch::date_margin("users.doj",$params['date_span']);
		}
		
		/*//uid 
		if($params['user'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " userid='".$params['user']."'";
		}
		
		$tag_n_title='';
		//Tags
		if($params['tags'])
		{
			//checking for commas ;)
			$tags = explode(",",$params['tags']);
			if(count($tags)>0)
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$total = count($tags);
				$loop = 1;
				foreach($tags as $tag)
				{
					$tag_n_title .= " tags LIKE '%".$tag."%'";
					if($loop<$total)
					$tag_n_title .= " OR ";
					$loop++;
					
				}
			}else
			{
				if($tag_n_title!='')
					$tag_n_title .= ' OR ';
				$tag_n_title .= " tags LIKE '%".$params['tags']."%'";
			}
		}
		//TITLE
		if($params['title'])
		{
			if($tag_n_title!='')
				$tag_n_title .= ' OR ';
			$tag_n_title .= " title LIKE '%".$params['tags']."%'";
		}
		
		if($tag_n_title)
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ($tag_n_title) ";
		}*/
		
		//FEATURED
		if($params['featured'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " users.featured = '".$params['featured']."' ";
		}
		
		//Email
		if($params['username'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " users.username = '".$params['username']."' ";
		}
		
		//Email
		if($params['email'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " users.email = '".$params['email']."' ";
		}
		
		//Exclude Users
		if($params['exclude'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " users.userid <> '".$params['exclude']."' ";
		}
		
		//Getting specific User
		if($params['userid'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " users.userid = '".$params['userid']."' ";
		}
		
		//Sex
		if($params['gender'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " users.sex = '".$params['gender']."' ";
		}
		
		//Level
		if($params['level'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " users.level = '".$params['level']."' ";
		}
		
		if($params['cond'])
		{
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".$params['cond']." ";
		}
                


                
		if(!$params['count_only'])
        {

            $fields = array(
                'users' => get_user_fields(),
                'profile' => array( 'rating', 'rated_by', 'voters', 'first_name', 'last_name' ),
            );
            $fields['users'][] = 'last_active';
            $query = " SELECT ".tbl_fields( $fields )." FROM ".tbl( 'users'  )." AS users ";
            $query .= " LEFT JOIN ".table( 'user_profile', 'profile ' )." ON users.userid = profile.userid ";

            if ( $cond ) {
                $query .= " WHERE ".$cond;
            }

            if( $order )
                $query .= " ORDER BY ".$order;

            if( $limit )
                $query .= " LIMIT  ".$limit;

    //$result = $db->select(tbl('users'),'*',$cond,$limit,$order);

            $result = select( $query );
        }
		
		
		if($params['count_only']){

            //$cond= substr($cond,8);
			$result = $db->count(tbl('users')." AS users ",'userid',$cond);
            //echo $cond;
            //return $result;
		}
		if($params['assign'])
			assign($params['assign'],$result);
		else
			return $result;
	}
	
	/**
	 * Function used to perform several actions with a video
	 */
	function action($case,$uid)
	{
		global $db;
		if(!$this->user_exists($uid))
			return false;
		//Lets just check weathter user exists or not
		$tbl = tbl($this->dbtbl['users']);
		switch($case)
		{
			//Activating a user
			case 'activate':
			case 'av':
			case 'a':
			{
				$avcode = RandomString(10);
				$db->update($tbl,array('usr_status','avcode'),array('Ok',$avcode)," userid='$uid' ");
				e(lang("User has been activated"),'m');
			}
			break;
			
			//Deactivating a user
			case "deactivate":
			case "dav":
			case "d":
			{
				$avcode = RandomString(10);
				$db->update($tbl,array('usr_status','avcode'),array('ToActivate',$avcode)," userid='$uid' ");
				e(lang("User has been deactivated"),'m');
			}
			break;
			
			//Featuring user
			case "feature":
			case "featured":
			case "f":
			{
				$db->update($tbl,array('featured','featured_date'),array('yes',now())," userid='$uid' ");
				e(lang("User has been set as featured"),'m');
			}
			break;
			
			
			//Unfeatured user
			case "unfeature":
			case "unfeatured":
			case "uf":
			{
				$db->update($tbl,array('featured'),array('no')," userid='$uid' ");
				e(lang("User has been removed from featured users"),'m');
			}
			break;
			
			//Ban User
			case "ban":
			case "banned":
			{
				$db->update($tbl,array('ban_status'),array('yes')," userid='$uid' ");
				e(lang("User has been banned"),'m');
			}
			break;
			
			
			//Ban User
			case "unban":
			case "unbanned":
			{
				$db->update($tbl,array('ban_status'),array('no')," userid='$uid' ");
				e(lang("User has been unbanned"),'m');
			}
			break;
		}
	}
	
	
	/**
	 * Is registration allowed
	 */
	 function is_registration_allowed()
	 {
		if(ALLOW_REG == 1)
        {
		    return true;
        }
		return false;
	 }
	 
	/**
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 */
	function init_search()
	{
		$this->search = new cbsearch;
		$this->search->db_tbl = "users";
		// added more conditions usr_status='Ok' and ban_status='no'
		/*
		array('field'=>'usr_status','type'=>'=','var'=>'Ok','op'=>'AND','value'=>'static'),
			array('field'=>'ban_status','type'=>'=','var'=>'no','op'=>'AND','value'=>'static'),
		*/
		if(!has_access('admin_access',TRUE))
		{
			$this->search->columns =array(
				array('field'=>'username','type'=>'LIKE','var'=>'%{KEY}%'),
				array('field'=>'usr_status','type'=>'=','var'=>'Ok','op'=>'AND','value'=>'static'),
				array('field'=>'ban_status','type'=>'=','var'=>'no','op'=>'AND','value'=>'static'),
			);
		}
		else
		{
			$this->search->columns =array(
				array('field'=>'username','type'=>'LIKE','var'=>'%{KEY}%'),
			);
		}	
			
		$this->search->cat_tbl = $this->cat_tbl;

		$this->search->display_template = LAYOUT.'/blocks/user.html';
		$this->search->template_var = 'user';
		$this->search->multi_cat = false;
		$this->search->date_added_colum = 'doj';
		$this->search->results_per_page = config('users_items_search_page');
												 
		/**
		 * Setting up the sorting thing
		 */
		
		$sorting	= 	array(
						'doj'	=> lang("date_added"),
						'profile_hits'		=> lang("views"),
						'total_comments'  => lang("comments"),
						'total_videos' 	=> lang("videos"),
						);
		
		$this->search->sorting	= array(
						'doj'=> " doj DESC",
						'profile_hits'		=> " profile_hits DESC",
						'total_comments'  => " total_comments DESC ",
						'total_videos' 	=> " total_videos DESC",
						);
		/**
		 * Setting Up The Search Fields
		 */
		 
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		$uploaded = $default['datemargin'];
		$sort = $default['sort'];
		
		$this->search->search_type['channels'] = array('title'=>lang('users'));
		
		$fields = array(
		'query'	=> array(
						'title'=> lang('keywords'),
						'type'=> 'textfield',
						'name'=> 'query',
						'id'=> 'query',
						'value'=>cleanForm($default['query'])
						),
		'category'	=>  array(
						'title'		=> lang('category'),
						'type'		=> 'checkbox',
						'name'		=> 'category[]',
						'id'		=> 'category',
						'value'		=> array('category',$cat_array),
						'category_type'=>'user',
						),
		'date_margin'	=>  array(
						'title'		=> lang('joined'),
						'type'		=> 'dropdown',
						'name'		=> 'datemargin',
						'id'		=> 'datemargin',
						'value'		=> $this->search->date_margins(),
						'checked'	=> $uploaded,
						),
		'sort'		=> array(
						'title'		=> lang('sort_by'),
						'type'		=> 'dropdown',
						'name'		=> 'sort',
						'value'		=> $sorting,
						'checked'	=> $sort
							)
		);

		$this->search->search_type['users']['fields'] = $fields;
	}
	
	
	
	/**
	  * Function used to get number of users online
	  */
	 function get_online_users($group=true,$count=false)
	 {
		 global $db;
		
		 if($group)
		 {
			 $results =  $db->select(tbl("sessions")." LEFT JOIN (".tbl("users").") ON 
			 (".tbl("sessions.session_user=").tbl("users").".userid)" ,
			 tbl("sessions.*,users.username,users.userid,users.email").",count(".tbl("sessions.session_user").") AS logins"
			 ," TIMESTAMPDIFF(MINUTE,".tbl("sessions.last_active").",'".NOW()."')  < 6 GROUP BY ".tbl("users.userid"));	
		 }else
		 {
			 if($count)
			 {
				  $results =  $db->count(tbl("sessions")." LEFT JOIN (".tbl("users").") ON 
				 (".tbl("sessions.session_user=").tbl("users").".userid)" ,
				 tbl("sessions.session_id")
				 ," TIMESTAMPDIFF(MINUTE,".tbl("sessions.last_active").",'".NOW()."')  < 6 ");
			 }else
			 {
				  $results =  $db->select(tbl("sessions")." LEFT JOIN (".tbl("users").") ON 
				 (".tbl("sessions.session_user=").tbl("users").".userid)" ,
				 tbl("sessions.*,users.username,users.userid,users.email")
				 ," TIMESTAMPDIFF(MINUTE,".tbl("sessions.last_active").",'".NOW()."')  < 6 ");
			 }
		 }

	 	 return $results;
	 }
	 
	 
	 
	 
	/**
	 * Function will let admin to login as user
	 */
	function login_as_user($id,$realtime=false)
	{
		global $sess,$db,$cblog;
		$udetails = $this->get_user_details($id);
		if($udetails)
		{
			if(!$realtime)
			{
				/*			
				$sess->set('dummy_username',$sess->get("username"));
				$sess->set('dummy_level',$sess->get("level"));
				$sess->set('dummy_userid',$sess->get("userid"));
				$sess->set('dummy_user_session_key',$sess->get("user_session_key"));
				$sess->set('dummy_user_session_code',$sess->get("user_session_code"));
				
				
				$sess->set('username',$udetails['username']);
				$sess->set('level',$udetails['level']);
				$sess->set('userid',$udetails['userid']);
				$sess->set('user_session_key',$udetails['session_key']);
				$sess->set('user_session_code',$udetails['session_code']);
				*/
								
				$sess->set('dummy_sess_salt',$sess->get("sess_salt"));
				$sess->set('dummy_PHPSESSID',$sess->get("PHPSESSID"));
				$sess->set('dummy_userid',userid());
				$sess->set('dummy_user_session_key',$this->udetails['user_session_key']);
				
				$userid = $udetails['userid'];
				$session_salt = RandomString(5);
				$sess->set('sess_salt',$session_salt);
				$sess->set('PHPSESSID',$sess->id);
				
				$smart_sess = md5($udetails['user_session_key'].$session_salt);
				
				$db->delete(tbl("sessions"),array("session"),array($sess->id));
				$sess->add_session($userid,'smart_sess',$smart_sess);
			
			}else
			{
				if($this->login_check(NULL,true))
					$msg[] = e(lang('you_already_logged'));
				elseif(!$this->user_exists($udetails['username']))
					$msg[] = e(lang('user_doesnt_exist'));
				elseif(!$udetails)
					$msg[] = e(lang('usr_login_err'));
				elseif(strtolower($udetails['usr_status']) != 'ok')
					$msg[] = e(lang('user_inactive_msg'));
				elseif($udetails['ban_status'] == 'yes')
					$msg[] = e(lang('usr_ban_err'));
				else
				{
					
					$userid = $udetails['userid'];
					$log_array['userid'] = $userid  = $udetails['userid'];
					$log_array['useremail'] = $udetails['email'];
					$log_array['success'] = 1;
					
					$log_array['level'] = $level  = $udetails['level'];
		
					//Adding Sessing In Database 
					//$sess->add_session($userid,'logged_in');
					
					//$sess->set('username',$username);
					//$sess->set('userid',$userid);
					
					//Setting Timeout
					if($remember)
						$sess->timeout = 86400*REMBER_DAYS;
						
					//Starting special sessions for security
					$session_salt = RandomString(5);
					$sess->set('sess_salt',$session_salt);
					$sess->set('PHPSESSID',$sess->id);
					
					$smart_sess = md5($udetails['user_session_key'].$session_salt);
					
					$db->delete(tbl("sessions"),array("session","session_string"),array($sess->id,"guest"));
					$sess->add_session($userid,'smart_sess',$smart_sess);
					
					//$sess->set('user_sess',$smart_sess);
					
					//$sess->set('user_session_key',$udetails['user_session_key']);
					//$sess->set('user_session_code',$udetails['user_session_code']);
					
					
					//Setting Vars
					$this->userid = $udetails['userid'];
					$this->username = $udetails['username'];
					$this->level = $udetails['level'];
					
					//Updating User last login , num of visist and ip
					$db->update(tbl('users'),
								array(
									  'num_visits','last_logged','ip'
									  ),
								array(
									  '|f|num_visits+1',NOW(),$_SERVER['REMOTE_ADDR']
									  ),
								"userid='".$userid."'"
								);
					
					
					$this->init();
					//Logging Actiong
					$cblog->insert('login',$log_array);
					
					return true;
				}
				
				//Error Loging
				if(!empty($msg))
				{
					//Loggin Action
					$log_array['success'] = no;
					$log_array['details'] = $msg[0];
					$cblog->insert('login',$log_array);
				}
			}
						
			return true;
		}else
			e(lang("usr_exist_err"));
	}
	
	/**
	 * Function used to revert back to admin
	 */
	function revert_from_user()
	{
		global $sess,$db;
		if($this->is_admin_logged_as_user())
		{
				
				$userid = $sess->get('dummy_userid');
				$session_salt = $sess->get('dummy_sess_salt');
				$user_session_key = $sess->get('dummy_user_session_key');
				$smart_sess = md5($user_session_key.$session_salt);
				
				$sess->set('sess_salt',$session_salt);
				$sess->set('PHPSESSID',$sess->get('dummy_PHPSESSID'));
					
				$db->delete(tbl("sessions"),array("session"),array($sess->get('dummy_PHPSESSID')));
				$sess->add_session($userid,'smart_sess',$smart_sess);

				$sess->set('dummy_sess_salt','');
				$sess->set('dummy_PHPSESSID','');
				$sess->set('dummy_userid','');
				$sess->set('dummy_user_session_key','');

		}
	}
	
	/**
	 * Function used to check weather user is logged in as admin or not
	 */
	function is_admin_logged_as_user()
	{
		 global $sess;
		 if($sess->get("dummy_sess_salt")!="")
		 {
			 return true;
		 }
		return false;
	}
	
	
	/**
	 * Function used to get anonymous user
	 */
	function get_anonymous_user()
	{
		global $db;
		$uid = config('anonymous_id');
		/*Added to resolve bug 222*/    
		$result = $db->select(tbl("users"),"userid"," username='anonymous%' AND email='anonymous%'","1");
        if($result[0]['userid'])
			return $result[0]['userid'];/*End **/
		else
		{
			$result = $db->select(tbl("users"),"userid"," level='6' AND usr_status='ToActivate' ","1");
			if($result[0]['userid'])
				return $result[0]['userid'];
			else
			{
				$pass =  RandomString(10);
				
				if($_SERVER['HTTP_HOST']!='localhost')
					$email = 'anonymous'.RandomString(5).'@'.$_SERVER['HTTP_HOST'];
				else
					$email = 'anonymous'.RandomString(5).'@'.$_SERVER['HTTP_HOST'].'.tld';
				
				//Create Anonymous user
				$uid = $this->signup_user(
				array(
				'username' => 'anonymous'.RandomString(5),
				'email'	=> $email,
				'password' => $pass,
				'cpassword' => $pass,
				'country' => get_country(config('default_country_iso2')),
				'gender' => 'Male',
				'dob'	=> '2000-10-10',
				'category' => '1',
				'level' => '6',
				'active' => 'yes',
				'agree' => 'yes',
				),false);
				
				
			   /*Added to resolve bug 222*/    
			   global $myquery;
			   $myquery->Set_Website_Details('anonymous_id',$uid);
			   /*End*/
							   
				return $uid;
			}
		}
	}
	
	
	/**
	 * Function used to delete user videos
	 */
	function delete_user_vids($uid)
	{
		global $cbvid,$eh;
		$vids = get_videos(array('user'=>$uid));
		if(is_array($vids))
		foreach($vids as $vid)
			$cbvid->delete_video($vid['videoid']);
		$eh->flush_msg();
		e(lang("user_vids_hv_deleted"),"m");	
	}
	
	/**
	 * Function used to remove user contacts
	 */
	function remove_contacts($uid)
	{
		global $eh;
		$contacts = $this->get_contacts($uid);
		if(is_array($contacts))
		foreach($contacts as $contact)
		{
			$this->remove_contact($contact['userid'],$contact['contact_userid']);
		}
		$eh->flush_msg();
		e(lang("user_contacts_hv_removed"),"m");
	}
	
	/**
	 * Function used to remove user private messages
	 */
	function remove_user_pms($uid,$box='both')
	{
		global $db,$cbpm,$eh;
		
		if($box=="inbox" || $box=="both")
		{
			$inboxs = $cbpm->get_user_inbox_messages($uid);
			if(is_array($inboxs))
			foreach($inboxs as $inbox)
			{
				$cbpm->delete_msg($inbox['message_id'],$uid);
			}
			$eh->flush_msg();
			e(lang("all_user_inbox_deleted"),"m");
		}
		if($box=="sent" || $box=="both")
		{
			$outs = $cbpm->get_user_outbox_messages($uid);
			if(is_array($outs))
			foreach($outs as $out)
			{
				$cbpm->delete_msg($out['message_id'],$uid,'out');
			}
			$eh->flush_msg();
			e(lang("all_user_sent_messages_deleted"),"m");

		}		
	}

	/**
	 * This will get user subscriptions
	 * uploaded videos and photos
	 * This is a test function
	 */	
function getSubscriptionsUploadsWeek($uid,$limit=20,$uploadsType="both",$uploadsTimeSpan="this_week")
{
	$usr_cond = "";
	$users = $this->get_user_subscriptions($uid);
	if($users)
	{
		foreach($users as $user)
		{
			if($user_cond)
				$user_cond .= " OR ";
			$user_cond .= 	tbl("users.userid")."='".$user[0]."' ";	
		}
		$user_cond = " (".$user_cond.") ";
		global $cbphoto,$cbvideo;
		$photoCount = 1;
		$videoCount = 1;	
		switch($uploadsType)
		{
			case "both":
			default:
			{
				$photos = $cbphoto->get_photos(array("limit"=>$limit,"extra_cond"=>$user_cond,"order"=>" date_added DESC","date_span"=>$uploadsTimeSpan));
				$videos = $cbvideo->get_videos(array("limit"=>$limit,"cond"=>" AND".$user_cond,"order"=>" date_added DESC","date_span"=>$uploadsTimeSpan));					
				if(!empty($photos) && !empty($videos))
					$finalResult = array_merge($videos,$photos);
				elseif(empty($photos) && !empty($videos))
					$finalResult = array_merge($videos,array());
				elseif(!empty($photos) && empty($videos))
					$finalResult = array_merge($photos,array());
				
				if(!empty($finalResult))
				{
					foreach($finalResult as $result)
					{
						if($result['videoid'])
						{
							$videoArr[] = $result;
							$return['videos'] = array(
								"title" => lang("videos"),
								"total" => $videoCount++,
								"items" => $videoArr
							);
						}
						
						if($result['photo_id'])
						{
							$photosArr[] = $result;
							$return['photos'] = array(
								"title" => lang("photos"),
								"total" => $photoCount++,
								"items" => $photosArr
							);	
						}
									
					}
					//pr($return,true)	;	
					return $return;
				} else
					return false;
			}
			break;
			
			case "photos": case "photo" : case "p":
			{
				$photos = $cbphoto->get_photos(array("limit"=>$limit,"extra_cond"=>$user_cond,"order"=>" date_added DESC","date_span"=>$uploadsTimeSpan));
				if($photos)
				{
					foreach($photos as $photo)
					{
						$photosArr[] = $photo;
						$return['photos'] = array(
							"title" => lang("photos"),
							"total" => $photoCount++,
							"items" => $photosArr
						);								
					}
				}
				else
					return false;		
			}
			break;
			
			case "videos": case "video": case "v":
			{
				$videos = $cbvideo->get_videos(array("limit"=>$limit,"cond"=>" AND".$user_cond,"order"=>" date_added DESC","date_span"=>$uploadsTimeSpan));
				if($videos)
				{
					foreach($videos as $video)
					{
						$videoArr[] = $video;
						$return['videos'] = array(
							"title" => lang("videos"),
							"total" => $videoCount++,
							"items" => $videoArr
						);							
					}
				}
				else
					return false;	
			}
			break;
		}
		return $return;			
	}
}
	
	/**
	 * Get subscred videos
	 */
	function get_subscribed_videos($uid,$limit=20)
	{
		$user_cond = "";
		$users = $this->get_user_subscriptions($uid);
		if($users)
		{
			foreach($users as $user)
			{
				//pr($user);
				//Creating Query
				if($user_cond)
					$user_cond .= " OR ";
				$user_cond .= tbl("users.userid")."='".$user[0]."' ";
			}
			$user_cond = " AND (".$user_cond.") ";
			global $cbvid,$db;
			$vids = $cbvid->get_videos(array('limit'=>$limit,'cond'=>$user_cond,"order"=>" date_added DESC ","date_span"=>"this_week"));
			// echo $db->db_query;
			return $vids;
		}
		return false;
	}
	
	
	/**
	 * Function used to set item as profile item
	 */
	function setProfileItem($id,$type='v',$uid=NULL)
	{
		global $cbvid,$db,$cbphoto;
		if(!$uid)
			$uid = userid();
		if(!$uid)
		{
			e("user_doesnt_exist"); 
			return false;
		}
		switch($type)
		{
			case "v":
			{
				if($cbvid->video_exists($id))
				{
					$array['type'] = 'v';
					$array['id'] = $id;
					$db->update(tbl('user_profile'),array('profile_item'),array("|no_mc|".json_encode($array))
					," userid='$uid' ");
					
					e(sprintf(lang("this_has_set_profile_item"),lang("video")),"m");
				}else
					e("class_vdo_del_err");	
			}
			break;
			
			case "p":
			{
				if($cbphoto->photo_exists($id))
				{
					$array['type'] = 'p';
					$array['id'] = $id;
					$db->update(tbl('user_profile'),array('profile_item'),array("|no_mc|".json_encode($array))
					," userid='$uid' ");
					
					e(sprintf(lang("this_has_set_profile_item"),lang("photo")),"m");
				}else
					e("photo_not_exists");	
			}
			break;
		}
	}
	
	/**
	 * Remove Profile item
	 */
	function removeProfileItem($uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		if(!$uid)
		{
			e("user_doesnt_exist"); 
			return false;
		}
		
		$db->update(tbl('user_profile'),array('profile_item'),array("")
		," userid='$uid' ");
		
		e(lang("profile_item_removed"),"m");
	}
	
	/**
	 * function used to get profile item
	 */
	function getProfileItem($uid=NULL,$withDetails = false)
	{
		global $db,$cbvid,$cbphoto;
		if(!$uid)
			$uid = userid();
		if(!$uid)
		{
			e("user_doesnt_exist"); 
			return false;
		}
		
		if($uid == userid() && $this->profileItem && !$withDetails)
			return $this->profileItem;
		
		$profileItem = $db->select(tbl("user_profile"),"profile_item"," userid='$uid'");
		$profileItem = $profileItem[0]['profile_item'];
		
		$profileItem = json_decode($profileItem,true);
		
		if($withDetails)
		{
			switch($profileItem['type'])
			{
				case "p":
				{
					$photo = $cbphoto->get_photo($profileItem['id']);
					$photo['type'] = 'p';
					if($photo)
						return $photo;
				}
				break;
				case "v":
				{
					$video = $cbvid->get_video($profileItem['id']);
					$video['type'] = 'v';
					if($video)
						return $video;
				}
				break;
			}
		}
		return $this->profileItem = $profileItem;
	}
	
	/**
	 * Function used to check weather input given item
	 * is profile item or not
	 */
	function isProfileItem($id,$type='v',$uid=NULL)
	{
		$profileItem = $this->getProfileItem($uid);
		
		if($profileItem['type'] == $type && $profileItem['id'] == $id)
			return true;
		else
			return false;
	}
	
	
	
	
	/**
	 * FUnction loading personal details
	 */
	function load_personal_details($default)
	{
		
		if(!$default)
			$default = $_POST;
		$profile_fields = array
		(
		'first_name' => array(
						  'title'=> lang("user_fname"),
						  'type'=> "textfield",
						  'name'=> "first_name",
						  'id'=> "first_name",
						  'value'=> $default['first_name'],
						  'db_field'=>'first_name',
						  'required'=>'no',
						  'syntax_type'=> 'name',
						  'auto_view'=>'yes'
						  ),
		'last_name' => array(
						  'title'=> lang("user_lname"),
						  'type'=> "textfield",
						  'name'=> "last_name",
						  'id'=> "last_name",
						  'value'=> $default['last_name'],
						  'db_field'=>'last_name',
						  'syntax_type'=> 'name',
						  'auto_view'=>'yes'
						  ),
		
		
		'relation_status' => array(
						  'title'=>  lang("user_relat_status"),
						  'type'=> "dropdown",
						  'name'=> "relation_status",
						  'id'=> "last_name",
						  'value'=> array(
										  lang('usr_arr_no_ans'),
										  lang('usr_arr_single'),
										  lang('usr_arr_married'),
										  lang('usr_arr_comitted'),
										  lang('usr_arr_open_relate')
										  ),
						  'checked'=> $default['relation_status'],
						  'db_field'=>'relation_status',
						  'auto_view'=>'yes',
						  
		
						  ),
		'show_dob' => array(
						  'title'=>  lang("show_dob"),
						  'type'=> "radiobutton",
						  'name'=> "show_dob",
						  'id'=> "show_dob",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked'	=> $default['show_dob'],
						  'db_field'=>'show_dob',
						  'syntax_type'=> 'name',
						  'auto_view'=>'no',
						  'sep'=>'&nbsp;'
						  ),
		'about_me' => array(
						  'title'=>  lang("user_about_me"),
						  'type'=> "textarea",
						  'name'=> "about_me",
						  'id'=> "about_me",
						  'value'=> cleanForm($default['about_me']),
						  'db_field'=>'about_me',
						  'auto_view'=>'no',
						  'clean_func' => 'Replacer',
						  ),
		'profile_tags' => array(
						  'title'=>  lang("profile_tags"),
						  'type'=> "textfield",
						  'name'=> "profile_tags",
						  'id'=> "profile_tags",
						  'value'=> $default['profile_tags'],
						  'db_field'=>'profile_tags',
						  'auto_view'=>'no'
						  ),
		'web_url' => array(
						  'title'=>  lang("website"),
						  'type'=> "textfield",
						  'name'=> "web_url",
						  'id'=> "web_url",
						  'value'=> $default['web_url'],
						  'db_field'=>'web_url',
						  'auto_view'=>'yes',
						  'display_function'=>'outgoing_link'
						  ),

		
		);
		
		return $profile_fields;
	}
	
	
	/**
	 * function used to load location fields
	 */
	function load_location_fields($default)
	{
		if(!$default)
			$default = $_POST;
		$other_details = array
		(
		'postal_code' => array(
						  'title'=>  lang("postal_code"),
						  'type'=> "textfield",
						  'name'=> "postal_code",
						  'id'=> "postal_code",
						  'value'=> $default['postal_code'],
						  'db_field'=>'postal_code',
						  'auto_view' => 'yes'
						  ),
		'hometown' => array(
						  'title'=>  lang("hometown"),
						  'type'=> "textfield",
						  'name'=> "hometown",
						  'id'=> "hometown",
						  'value'=> $default['hometown'],
						  'db_field'=>'hometown',
						  'auto_view' => 'yes'
						  ),
		'city' => array(
						  'title'=>  lang("city"),
						  'type'=> "textfield",
						  'name'=> "city",
						  'id'=> "city",
						  'value'=> $default['city'],
						  'db_field'=>'city',
						  'auto_view' => 'yes'
						  ),
		);
		return $other_details;
	}
	
	
	/**
	 * Function used to load experice fields
	 */
	function load_education_interests($default)
	{
		if(!$default)
			$default = $_POST;
		$more_details = array
		(
		'education' => array(
						  'title'=>  lang("education"),
						  'type'=> "dropdown",
						  'name'=> "education",
						  'id'=> "education",
						  'value'=> array(lang('usr_arr_no_ans'),
										  lang('usr_arr_elementary'),
										  lang('usr_arr_hi_school'),
										  lang('usr_arr_some_colg'),
										  lang('usr_arr_assoc_deg'),
										  lang('usr_arr_bach_deg'),
										  lang('usr_arr_mast_deg'),
										  lang('usr_arr_phd'),
										  lang('usr_arr_post_doc'),
										  ),
						  'checked'=>$default['education'],
						  'db_field'=>'education',
						  'auto_view'=>'yes',
						  ),
		'schools' => array(
						  'title'=>  lang("schools"),
						  'type'=> "textarea",
						  'name'=> "schools",
						  'id'=> "schools",
						  'value'=> cleanForm($default['schools']),
						  'db_field'=>'schools',
						  'clean_func' => 'Replacer',
						  'auto_view'=>'yes',
						  ),
		'occupation' => array(
						  'title'=>  lang("occupation"),
						  'type'=> "textarea",
						  'name'=> "occupation",
						  'id'=> "occupation",
						  'value'=> cleanForm($default['occupation']),
						  'db_field'=>'occupation',
						  'clean_func' => 'Replacer',
						  'auto_view'=>'yes',
						  ),
		'companies' => array(
						  'title'=>  lang("companies"),
						  'type'=> "textarea",
						  'name'=> "companies",
						  'id'=> "companies",
						  'value'=> cleanForm($default['companies']),
						  'db_field'=>'companies',
						  'clean_func' => 'Replacer',
						  'auto_view'=>'yes',
						  ),
		'hobbies' => array(
						  'title'=>  lang("hobbies"),
						  'type'=> "textarea",
						  'name'=> "hobbies",
						  'id'=> "hobbies",
						  'value'=> cleanForm($default['hobbies']),
						  'db_field'=>'hobbies',
						  'clean_func' => 'Replacer',
						  'auto_view'=>'yes',
						  ),
		'fav_movies' => array(
						  'title'=>  lang("user_fav_movs_shows"),
						  'type'=> "textarea",
						  'name'=> "fav_movies",
						  'id'=> "fav_movies",
						  'value'=> cleanForm($default['fav_movies']),
						  'db_field'=>'fav_movies',
						  'clean_func' => 'Replacer',
						  'auto_view'=>'yes',
						  ),
		'fav_music' => array(
						  'title'=>  lang("user_fav_music"),
						  'type'=> "textarea",
						  'name'=> "fav_music",
						  'id'=> "fav_music",
						  'value'=> cleanForm($default['fav_music']),
						  'db_field'=>'fav_music',
						  'clean_func' => 'Replacer',
						  'auto_view'=>'yes',
						  ),
		'fav_books' => array(
						  'title'=>  lang("user_fav_books"),
						  'type'=> "textarea",
						  'name'=> "fav_books",
						  'id'=> "fav_books",
						  'value'=> cleanForm($default['fav_books']),
						  'db_field'=>'fav_books',
						  'clean_func' => 'Replacer',
						  'auto_view'=>'yes',
						  ),
		
		);
		return $more_details;
	}
	
	
	/**
	 * Function used to load privacy fields
	 */
	function load_privacy_field($default)
	{
		if(!$default)
			$default = $_POST;
			
		$privacy = array
		(
		'online_status' => array(
						  'title'=>  lang("online_status"),
						  'type'=> "dropdown",
						  'name'=> "privacy",
						  'id'=> "privacy",
						  'value'=> array('online'=>lang('online'),'offline'=>lang('offline'),'custom'=>lang('custom')),
						  'checked'=>$default['online_status'],
						  'db_field'=>'online_status',
						  ),
		'show_profile' => array(
						  'title'=>  lang("show_profile"),
						  'type'=> "dropdown",
						  'name'=> "show_profile",
						  'id'=> "show_profile",
						  'value'=> array('all'=>lang('all'),'members'=>lang('members'),'friends'=>lang('friends')),
						  'checked'=>$default['show_profile'],
						  'db_field'=>'show_profile',
						  'sep' => '&nbsp;'
						  ),
		'allow_comments'=>array(
						  'title'=>  lang("vdo_allow_comm"),
						  'type'=> "radiobutton",
						  'name'=> "allow_comments",
						  'id'=> "allow_comments",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['allow_comments']),
						  'db_field'=>'allow_comments',
						  'sep' => '&nbsp;'
						  ),
		'allow_ratings'=>array(
						  'title'=>  lang("allow_ratings"),
						  'type'=> "radiobutton",
						  'name'=> "allow_ratings",
						  'id'=> "allow_ratings",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['allow_ratings']),
						  'db_field'=>'allow_ratings',
						  'sep' => '&nbsp;'
						  ),
		'allow_subscription'=>array(
						  'title'=>  lang("allow_subscription"),
						  'type'=> "radiobutton",
						  'name'=> "allow_subscription",
						  'id'=> "allow_subscription",
						  'hint_1' => lang('allow_subscription_hint'),
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['allow_subscription']),
						  'db_field'=>'allow_subscription',
						  'sep' => '&nbsp;'
						  ),
		);
		
		return $privacy;
	}
	
	/**
	 * load_channel_settings
	 *
	 * @param $input defaults value for channel settings
	 * @return arra of channel info fields
	 */
	function load_channel_settings($default)
	{
		if(!$default)
			$default = $_POST;
			
		$channel_settings = array
		(
		'profile_title' => array(
						  'title'=>  lang("channel_title"),
						  'type'=> "textfield",
						  'name'=> "profile_title",
						  'id'=> "profile_title",
						  'value'=> $default['profile_title'],
						  'db_field'=>'profile_title',
						  'auto_view'=>'no'
		
						  ),
		'profile_desc' => array(
						  'title'=>  lang("channel_desc"),
						  'type'=> "textarea",
						  'name'=> "profile_desc",
						  'id'=> "profile_desc",
						  'value'=> $default['profile_desc'],
						  'db_field'=>'profile_desc',
						  'auto_view'=>'yes',
						  'clean_func' => 'Replacer',
						  ),
		'show_my_friends'=>array(
						  'title'=>  lang("show_my_friends"),
						  'type'=> "radiobutton",
						  'name'=> "show_my_friends",
						  'id'=> "show_my_friends",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['show_my_friends']),
						  'db_field'=>'show_my_friends',
						  'sep' => '&nbsp;'
						  ),
		'show_my_videos'=>array(
						  'title'=>  lang("show_my_videos"),
						  'type'=> "radiobutton",
						  'name'=> "show_my_videos",
						  'id'=> "show_my_videos",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['show_my_videos']),
						  'db_field'=>'show_my_videos',
						  'sep' => '&nbsp;'
						  ),
		'show_my_photos'=>array(
						  'title'=>  lang("show_my_photos"),
						  'type'=> "radiobutton",
						  'name'=> "show_my_photos",
						  'id'=> "show_my_photos",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['show_my_photos']),
						  'db_field'=>'show_my_photos',
						  'sep' => '&nbsp;'
						  ),
		'show_my_subscriptions'=>array(
						  'title'=>  lang("show_my_subscriptions"),
						  'type'=> "radiobutton",
						  'name'=> "show_my_subscriptions",
						  'id'=> "show_my_subscriptions",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['show_my_subscriptions']),
						  'db_field'=>'show_my_subscriptions',
						  'sep' => '&nbsp;'
						  ),
		'show_my_subscribers'=>array(
						  'title'=>  lang("show_my_subscribers"),
						  'type'=> "radiobutton",
						  'name'=> "show_my_subscribers",
						  'id'=> "show_my_subscribers",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['show_my_subscribers']),
						  'db_field'=>'show_my_subscribers',
						  'sep' => '&nbsp;'
						  ),
		'show_my_collections'=>array(
						  'title'=>  lang("show_my_collections"),
						  'type'=> "radiobutton",
						  'name'=> "show_my_collections",
						  'id'=> "show_my_collections",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['show_my_collections']),
						  'db_field'=>'show_my_collections',
						  'sep' => '&nbsp;'
						  ),
		
		);
		
		return $channel_settings;
	}
	
	
	/**
	 * load_user_fields
	 * 
	 * @param $input default values for user profile fields
	 * @return array of user fields
	 *
	 * Function used to load Video fields
	 * in clipbucket v2.1 , video fields are loaded in form of groups arrays
	 * each group has it name and fields wrapped in array 
	 * and that array will be part of video fields
	 */
	
	function load_user_fields($default,$type='all')
	{
		$getChannelSettings = false;
		$getProfileSettings = false;
		$fields = array();
		
		switch($type)
		{
			case "all":
			{
				$getChannelSettings = true;
				$getProfileSettings = true;
			}
			break;
			
			case "channel":
			case "channels":
			{
				$getChannelSettings = true;
			}
			break;
			
			case "profile":
			case "profile_settings":
			{
				$getProfileSettings = true;
			}
			break;
			
		}
		
		
		if($getChannelSettings)
		{
			$channel_settings = array
			(
				array
				(
					'group_name' => lang('channel_settings'),
					'group_id'	=> 'channel_settings',
					'fields'	=> array_merge($this->load_channel_settings($default)
									,$this->load_privacy_field($default)),
				),
			);
		}
		
		if($getProfileSettings)
		{
			$profile_settings = array
			(
				array
				(
					'group_name' => lang('profile_basic_info'),
					'group_id'	=> 'profile_basic_info',
					'fields'	=> $this->load_personal_details($default),
				),
				
				array
				(
					'group_name' => lang('location'),
					'group_id'=> 'profile_location',
					'fields' => $this->load_location_fields($default)
				),
				
				array
				(
					'group_name' => lang('profile_education_interests'),
					'group_id' => 'profile_education_interests',
					'fields' => $this->load_education_interests($default)
				)
			);
			
			
			//Adding Custom Fields
			$custom_fields = $this->load_custom_profile_fields($default,false);
			
			if($custom_fields)
			{
				$more_fields_group = 
				array
				(
					'group_name' => lang('more_fields'),
					'group_id'	=> 'custom_fields',
					'fields'	=> $custom_fields,
				);
			}
		
			//Loading Custom Profile Forms
			$custom_fields_with_group = $this->load_custom_profile_fields($default,true);
			
			//Finaling putting them together in their main array called $fields
			if($custom_fields_with_group)
			{
				$custFieldGroups = $custom_fields_with_group;
			
				foreach($custFieldGroups as $gKey => $fieldGroup)
				{
					
					$group_id = $fieldGroup['group_id'];
					
					foreach($profile_settings as $key => $field)
					{ 
						
						if($field['group_id'] == $group_id)
						{
							$inputFields = $field['fields'];
							//Setting field values
							$newFields = $fieldGroup['fields'];
							
							
							
							$mergeField = array_merge($inputFields,$newFields);
							
							
							//Finally Updating array
							$newGroupArray =
							array
							(
								'group_name' => $field['group_name'],
								'group_id' => $field['group_id'],
								'fields' => $mergeField,
							);
							
							$fields[$key] = $newGroupArray;
							
							$matched = true;
							break;
						}else
							$matched = false;
							
					}
					
					if(!$matched)
						$profile_settings[] = $fieldGroup;			
					
				}
			}
			
		}
				
		
		if($channel_settings)
			$fields = array_merge($fields,$channel_settings);
		if($profile_settings)
			$fields = array_merge($fields,$profile_settings);
		if($more_fields_group)
			$fields[] = $more_fields_group;	
		return $fields;
	}
	
	/**
	 * Used to rate photo
	 */
	function rate_user($id,$rating)
	{
		global $db,$json;
		
		if(!is_numeric($rating) || $rating <= 9)
			$rating = 0;
		if($rating >= 10)
			$rating = 10;
			
		$c_rating = $this->current_rating($id);
		$voters   = $c_rating['voters'];
		
		$new_rate = $c_rating['rating'];
		$rated_by = $c_rating['rated_by'];
		
		if(phpversion < '5.2.0')
			$voters = $json->json_decode($voters,TRUE);
		else
			$voters = json_decode($voters,TRUE);

		if(!empty($voters))
			$already_voted = array_key_exists(userid(),$voters);
			
		if(!userid())
			e(lang("please_login_to_rate"));
		elseif(userid()==$c_rating['userid'] && !config('own_channel_rating'))
			e(lang("you_cant_rate_own_channel"));
		elseif(!empty($already_voted))
			e(lang("you_have_already_voted_channel"));
		elseif($c_rating['allow_ratings'] == 'no' || !config('channel_rating'))
			e(lang("channel_rating_disabled"));
		else
		{
			$voters[userid()] = array('rate'=>$rating,'time'=>NOW());
			if(phpversion < '5.2.0')
				$voters = $json->json_encode($voters);
			else
				$voters = json_encode($voters);
					
			$t = $c_rating['rated_by'] * $c_rating['rating'];
			$rated_by = $c_rating['rated_by'] + 1;
			$new_rate = ($t + $rating) / $rated_by;
			$db->update(tbl('user_profile'),array('rating','rated_by','voters'),
			array("$new_rate","$rated_by","|no_mc|$voters"),
			" userid = ".$id."");
			$userDetails = array(
				"object_id"	=>	$id,
				"type"	=>	"user",
				"time"	=>	now(),
				"rating"	=>	$rating,
				"userid"	=>	userid(),
				"username"	=>	username()
			);	
			/* Updating user details */		
			update_user_voted($userDetails);			
			e(lang("thnx_for_voting"),"m");			
		}
		
		$return = array("rating"=>$new_rate,"rated_by"=>$rated_by,'total'=>10,"id"=>$id,"type"=>"user","disable"=>"disabled");
		return $return;	
	}
	
	
	/**
	 * Used to get current rating
	 */
	function current_rating($id)
	{
		global $db;
		$result = $db->select(tbl('user_profile'),'userid,allow_ratings,rating,rated_by,voters'," userid = ".$id."");
		if($result)
			return $result[0];
		else
			return false;				
	}
	
	
	/**
	 * function used to check weather user is  online or not
	 */
	function isOnline($last_active,$status=NULL)
	{
		$time = strtotime($last_active);
		$timeDiff = time() - $time;
		if($timeDiff>60 || $status=='offline')
			return false;
		else
			return true;
	}
	
	
	/**
	 * Function used to get list of subscribed users and then
	 * send subscription email
	 */
	function sendSubscriptionEmail($vidDetails,$updateStatus=true)
	{
		global $cbemail,$db;
		$v = $vidDetails;
		if(!$v['videoid'])
		{
			e(lang("invalid_videoid"));
			return false;
		}
		
		if(!$v['userid'])
		{
			e(lang("invalid_userid"));
			return false;
		}
		
		//Lets get the list of subscribers 
		$subscribers = $this->get_user_subscribers_detail($v['userid'],false);	
		//Now lets get details of our uploader bhai saab
		$uploader = $this->get_user_details($v['userid']);
		//Loading subscription email template
		$tpl = $cbemail->get_template('video_subscription_email');
		
		$total_subscribers = count($subscribers);
		if($subscribers)
		foreach($subscribers as $subscriber)
		{
			$var = $this->custom_subscription_email_vars;
			
			$more_var = array
			('{username}'	=> $subscriber['username'],
			 '{uploader}'		=> $uploader['username'],
			 '{video_title}' => $v['title'],
			 '{video_description}' => $v['description'],
			 '{video_link}' => video_link($v),
			 '{video_thumb}' => get_thumb($v),
			);
			if(!is_array($var))
				$var = array();
			$var = array_merge($more_var,$var);
			$subj = $cbemail->replace($tpl['email_template_subject'],$var);
			$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
			
			//Now Finally Sending Email
			
			cbmail(array('to'=>$subscriber['email'],'from'=>WELCOME_EMAIL,'subject'=>$subj,'content'=>$msg));
			
		}
		
		if($total_subscribers)
		{
			//Updating video subscription email status to sent
			if($updateStatus)
			$db->update(tbl('video'),array('subscription_email'),array('sent')," videoid='".$v['videoid']."'");
			$s = "";
			if($total_subscribers>1)
				$s = "s";
			e(sprintf(lang('subs_email_sent_to_users'),$total_subscribers,$s),"m");
			return true;
		}
		
		e(lang("no_user_subscribed_to_uploader"));
		
		return true;
	}
	
	/**
	 * function used to get user seesions
	 */
	function get_sessions()
	{
		global $sess;
		$sessions = $sess->get_sessions();
		$new_sessions = array();
		if($sessions)
		{
			foreach($sessions as $session)
			{
				$new_sessions[$session['session_string']] = $session;
			}
		}else
		{
			$sess->add_session(0,'guest','guest');
		}
		
		return $new_sessions;
	}
	
	function update_user_voted($array,$userid=NULL)
	{
		global $db;
		//$voted = array();
		if(!$userid)
			$userid = userid();
		if(phpversion < '5.2.0')
		{
			global $json;
			$js = $json;	
		}
		if(is_array($array))
		{
			$votedDetails = $db->select(tbl("users"),"voted"," userid = '$userid'");
			if(!empty($votedDetails))
				if(!empty($js))
					$voted = $js->json_decode($votedDetails[0]['voted'],TRUE);
				else
					$voted = json_decode($votedDetails[0]['voted'],TRUE);
				
			
			
			if(!empty($js))
				$votedEncode = $js->json_encode($voted);
			else
				$votedEncode = json_encode($voted);
				
			if(!empty($votedEncode))
				$db->update(tbl("users"),array("voted"),array("|no_mc|$votedEncode")," userid='$userid'");		
		}
	}

	/**
	 * Function used to display user manger link
	 */
	function user_manager_link($link,$vid)
	{
		if(function_exists($link) && !is_array($link))
		{
			return $link($vid);
		}else
		{
			if(!empty($link['title']) && !empty($link['link']))
			{
				return '<a href="'.$link['link'].'">'.$link['title'].'</a>';
			}
		}
	}
}
?>