<?php
/* 
**************************
* @ Author : Arslan Hassan
* @ Co Author : Frank White
* @ Software : ClipBucket
* @ Since : 2007
* @ Modified : 06-08-2009
* @ license : CBLA
**************************
-- Do not use it for commercial use
Notice : Maintain this section
*/
 
define('NO_AVATAR','no_avatar.jpg'); //if there is no avatar or profile pic, this file will be used
define('AVATAR_SIZE',250);
define('AVATAR_SMALL_SIZE',30);
define('BG_SIZE',1200);

class userquery {
	
	var $userid = '';
	var $username = '';
	var $level = '';
	var $permissions = '';
	var $access_type_list = array(); //Access list
	var $usr_levels = array();
	
	var $dbtbl = array(
					   'user_permission_type'	=> 'user_permission_types',
					   'user_permissions'		=> 'user_permissions',
					   'user_level_permission'	=> 'user_levels_permissions',
					   'user_profile'			=> 'user_profile',
					   'users'					=> 'users',
					   'action_log'				=> 'action_log',
					   'subtbl'					=> 'subscriptions',
					   );
	
	function init()
	{
		global $sess;
		$this->userid = $sess->get('userid');
		$this->username = $sess->get('username');
		$this->level = $sess->get('level');
		
		
		
		//Setting Access
		//Get list Of permission
		$perms = $this->get_permissions();
		foreach($perms as $perm)
		{
			$this->add_access_type($perm['permission_code'],$perm['permission_name']);
		}
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
		
		if(user_id())
		{
			$this->permission = $this->get_user_level(userid());
			$this->UpdateLastActive(userid());
		}else
			$this->permission = $this->get_user_level(4,TRUE);

	}
	
	/**
	 * Function used to create user session key
	 */
	function create_session_key($session,$pass)
	{
		$newkey = $session.$pass;
		$newkey = md5($newkey);
		
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
	 * Neat and clean function to login user
	 * this function was made for v2.x with User Level System
	 * param VARCHAR $username
	 * param TEXT $password
	 */
	function login_user($username,$password)
	{
		global $LANG,$sess,$cblog,$db;
		//Now checking if user exists or not
		$pass = pass_code($password);
		$udetails = $this->get_user_with_pass($username,$pass);
		
		//Inerting Access Log
		$log_array = array('username'=>$username);
		
		//First we will check weather user is already logged in or not
		if($this->login_check)
			$msg[] = e($LANG['you_already_logged']);
		elseif(!$this->user_exists($username))
			$msg[] = e($LANG['user_doesnt_exist']);
		elseif(!$udetails)
			$msg[] = e($LANG['usr_login_err']);
		elseif(strtolower($udetails['usr_status']) != 'ok')
			$msg[] = e($LANG['user_inactive_msg']);
		elseif($udetails['ban_status'] == 'yes')
			$msg[] = e($LANG['usr_ban_err']);
		else
		{
			
			$log_array['userid'] = $userid  = $udetails['userid'];
			$log_array['useremail'] = $udetails['email'];
			$log_array['success'] = 1;
			
			$log_array['level'] = $level  = $udetails['level'];
			
			//Adding Sessing In Database 
			//$sess->add_session($userid,'logged_in');
			
			$sess->set('username',$username);
			$sess->set('level',$level);
			$sess->set('userid',$userid);
			
			//Starting special sessions for security
			$sess->set('user_session_key',$udetails['user_session_key']);
			$sess->set('user_session_code',$udetails['user_session_code']);
			
			//Setting Vars
			$this->userid = $sess->get('userid');
			$this->username = $sess->get('username');
			$this->level = $sess->get('level');
			
			//Updating User last login , num of visist and ip
			$db->update('users',
						array(
							  'num_visits','last_logged','ip'
							  ),
						array(
							  '|f|num_visits+1',NOW(),$_SERVER['HTTP_HOST']
							  ),
						"userid='".$userid."'"
						);
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
	function login_check($access=NULL,$check_only=FALSE)
	{
		global $LANG,$Cbucket,$sess;
		//First check weather userid is here or not
		if(!userid())
		{
			if(!$check_only)
			e($LANG['you_not_logged_in']);
			return false;
		}
		elseif(!$this->session_auth(userid()))
		{
			if(!$check_only)
			e($LANG['usr_invalid_session_err']);
			return false;
		}
		//Now Check if logged in user exists or not
		elseif(!$this->user_exists(userid()))
		{
			if(!$check_only)
			e($LANG['invalid_user']);
			return false;
		}
		//Now Check logged in user is banned or not
		elseif($this->is_banned(userid())=='yes')
		{
			if(!$check_only)
			e($LANG['usr_ban_err']);
			return false;
		}
		
		//Now user have passed all the stages, now checking if user has level access or not
		elseif($access)
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
					e($LANG['insufficient_privileges']);
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
					e($LANG['insufficient_privileges']);
					$Cbucket->show_page(false);
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
	function logincheck($redirect=TRUE)
	{
		
		if(!$this->login_check())
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
		$ufields = $this->get_user_fields($uid,'user_session_key,user_session_code');
		if($sess->get('user_session_key') == $ufields['user_session_key'] 
			&& $sess->get('user_session_code') == $ufields['user_session_code'])
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
		$results = $db->select("users",
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
		$details = $this->get_user_field($uid,'ban_status');
		return $details['ban_status'];
	}
	
	/*
	
	-- USED WITH PRIRIOR VERSIONS OF CB
	
	//This Function Is Used to Login Admin
	function adminlogin($username,$password){
			$query = mysql_query("Select * FROM users WHERE level = 'Admin' and username = '".$username."' and password = '".$password."'");
			$updatequery = "UPDATE users SET session='".$_COOKIE['PHPSESSID']."' WHERE username = '".$username."'";
				
				if(mysql_num_rows($query) >0 ){
					$data = mysql_fetch_array($query);
						if($data['ban_status'] != 'yes'){
					setcookie('username',$username,time()+7200,'/');
					setcookie('userid',$data['userid'],time()+7200,'/');
					setcookie('session',$_COOKIE['PHPSESSID'],time()+7200,'/');
					session_register('username');
					session_register('superadmin');
					session_register('admin');
					session_register('userid');
					$_SESSION['userid'] = $data['userid'];
					$_SESSION['username'] = $data['username'];
					if($data['userid']==1){
					$_SESSION['superadmin'] = $data['username'];
					}
					$_SESSION['admin'] = $data['username'];
					mysql_query($updatequery);
					$login = 'loggedin';
						}else{
							$login = 'banned';
						}
					}else{
					$login = 'failed';
				}
				return $login;
			}
	*/
	function admin_check(){
		return $this->login_check('admin_access');
	}

	/**
	 * Function used to check user is admin or not
	 * @param BOOLEAN if true, after checcking user will be redirected to login page if needed
	 */
	function admin_login_check($check_only=false)
	{
		if(!$this->login_check('admin_access'))
		{
			if($check_only==FALSE)
				redirect_to('login.php');
			return false;
		}else{
			return true;
		}
	}
		
	//This Function Is Used to Logout
	function logout($page='login.php'){
		global $sess;

		$sess->un_set('username');
		$sess->un_set('level');
		$sess->un_set('userid');
		$sess->un_set('user_session_key');
		$sess->un_set('user_session_code');
		//$sess->remove_session(userid());
	}
	
 
	//Delete User
	
	function DeleteUser($id){
	global $stats;
		if($id !=1){
		$query = 'DELETE FROM users WHERE userid="'.$id.'"';
		$result = mysql_query($query);
		$stats->UpdateUserRecord(2);
        if (mysql_errno()){
            $result = false;
			}else{
			$result = true;
			}
			return $result;
		}else{
			return false;
		}
	}
		
	//Check User Exists or Not
	function Check_User_Exists($id){
		global $db;
		$result = $db->count($this->dbtbl['users'],"userid"," userid='".$id."' OR username='".$id."'");
		if($result>0)
		{
			return true;
		}else{
			return false;
		}	
	}
	
	function user_exists($username)
	{
		return $this->Check_User_Exists($username);
	}
	
	/**
	 * Function used to get user details using userid
	 */
	function get_user_details($id=NULL)
	{
		global $db;
		/*if(!$id)
			$id = userid();*/
			
		$results = $db->select('users','*'," userid='$id' OR username='".$id."'");
		return $results[0];		
	}function GetUserData($id=NULL){ return $this->get_user_details($id); }
	
	
	//Get User Data from Database
	function GetUserData_username($username){
	$query = mysql_query("SELECT * FROM users WHERE username='".$username."'");
	$data = mysql_fetch_array($query);
	return $data;
	}

	//Get User Data from Database
	function CheckVideoOwner($videoid,$username){
	$query = mysql_query("SELECT * FROM video WHERE videoid='".$videoid."'");
	$videodata = mysql_fetch_array($query);
    if($videodata['username'] == $username)
    {
    $response = 1;
    }
    else
    {
    $response = 0;
    }
	return $response;
	}
	
	
	//Function Used to Count Number of Videos Uploaded By User
	
	function TotalVideos($username){
		$query = mysql_query("SELECT * FROM video WHERE username = '".$username."'");
		$total = mysql_num_rows($query);
		return $total;
	}
	
	//Function Used to Count Number of Friends of User
	
	function TotalFriends($username){
		$query = mysql_query("SELECT * FROM contacts WHERE username = '".$username."' AND type='1'");
		$total = mysql_num_rows($query);
		return $total;
	}
	
	//Function Used to Count Number of Groups of User
	
	function TotalGroups($username){
		$query = mysql_query("SELECT * FROM groups WHERE username = '".$username."'");
		$total = mysql_num_rows($query);
		return $total;
	}
		
		
		
	//Function Used To Activate User
	function ActivateUser($user,$avcode){
		$data = $this->GetUserData_username($user);
			if($data['usr_status'] == 'Ok' || $data['avcode'] !=$avcode || empty($user)){
			return false;
			}else{
			$this->Activate($data['userid']);
			return true;
			}
		}
	
	//Function Used To Send Activation Code To User
	function SendActivation($email){
			$query = mysql_query("SELECT * FROM users WHERE email='".$email."'");
			$data = mysql_fetch_array($query);
			if(!empty($data['username']) && $data['usr_status'] != 'Ok'){
			$username	= $data['username'];
			$avcode 	= $data['avcode'];
			$cur_date 	= date('m-d-Y');
			$title		= TITLE;
			$baseurl	= BASEURL;
			$from		= SUPPORT_EMAIL;
			$to			= $email;
			require_once(BASEDIR.'/includes/email_templates/activation_request.template.php');
			require_once(BASEDIR.'/includes/email_templates/activation_request.header.php');
			send_email($from,$to,$subj,nl2br($body));
			return true;
			}else{
			return false;
			}
	}
	
	//Function Made to Update User Profile And Channel
	function UpdateUserProfile($userid){
		//Getting Personal Information
		$fname 		= mysql_clean(@$_POST['fname']);
		$lname 		= mysql_clean(@$_POST['lname']);
		$sex   		= @$_POST['gender'];
		$relation	= @$_POST['relationship'];
		$show_dob	= $_POST['show_dob'];
		$about_me	= mysql_clean($_POST['about_me']);
		$web_url	= mysql_clean($_POST['web_url']);
		
		//Getting Professional Information
		$education	= $_POST['education'];
		$schools	= mysql_clean($_POST['schools']);
		$occupation	= mysql_clean($_POST['occupation']);
		$companies	= mysql_clean($_POST['campanies']);
		
		//Getting Interest & Hobbies
		$hobbies	= mysql_clean($_POST['hobbies']);
		$fav_movies	= mysql_clean($_POST['fav_movies']);
		$fav_music	= mysql_clean($_POST['fav_music']);
		$fav_books	= mysql_clean($_POST['fav_books']);
		
		//Getting Avatar
		$file 		= $_FILES['avatar']['name'];
		$ext 		= substr($file, strrpos($file, '.') + 1);
		$thumb		= $_POST['thumb'];
		$thumb_ext 	= substr($thumb, strrpos($thumb, '.') + 1);
		$small_t	= substr($thumb, 0, strrpos($thumb, '.')).'-small.'.$thumb_ext;
		
		//Getting Channel Details
		$title		= mysql_clean($_POST['title']);
		$des		= mysql_clean($_POST['des']);
		$rating		= $_POST['rating'];
		$comment	= $_POST['comment'];
		$f_video	= $_POST['f_video'];
		
				if(!empty($file)){
					$image = new ResizeImage();	
					if($image->ValidateImage($_FILES['avatar']['tmp_name'],$ext)){
						$thumb_file = BASEDIR.'/images/avatars/'.$thumb;
						$small_thumb_file=BASEDIR.'/images/avatars/'.$small_t;
						if($thumb != 'no_avatar.jpg' && file_exists($thumb_file) && file_exists($small_thumb_file)){
						unlink($thumb_file);
						unlink($small_thumb_file);
						}
						$newname			= $userid;
						$newthumb			= $newname.'.'.$ext;
						$newthumb_small		= $newname.'-small.'.$ext;
						$new_thumb			= BASEDIR.'/images/avatars/'.$newthumb;
						$new_thumb_small 	= BASEDIR.'/images/avatars/'.$newthumb_small;
						copy($_FILES['avatar']['tmp_name'],$new_thumb);
						$image->CreateThumb($new_thumb,$new_thumb,90,$ext);
						$image->CreateThumb($new_thumb,$new_thumb_small,30,$ext);
						$thumb = $newthumb;
						}
					}
					
				$bgfile 		= $_FILES['background']['name'];
				$bg				= $_POST['bg'];
				$ext 			= substr($bgfile, strrpos($bgfile, '.') + 1);
				
				//Delete background
				if($_POST['remove_bg'] == 'yes'){
				if(is_file(BASEDIR.'/images/backgrounds/'.$bg) && file_exists(BASEDIR.'/images/backgrounds/'.$bg)){
					unlink(BASEDIR.'/images/backgrounds/'.$bg);
					}
				$bg = "";		
				}
				if(!empty($bgfile)){
						$image = new ResizeImage();	
						if($image->ValidateImage($_FILES['background']['tmp_name'],$ext)){
							if(file_exists(BASEDIR.'/images/backgrounds/'.$bg)){
							unlink(BASEDIR.'/images/backgrounds/'.$bg);
							}
							$newname			= RandomString(10);
							$newthumb			= $newname.'.'.$ext;
							$new_thumb			= BASEDIR.'/images/backgrounds/'.$newthumb;
							copy($_FILES['background']['tmp_name'],$new_thumb);
							$bg					= $newthumb;
						}
				}
				
		mysql_query("UPDATE users SET
		first_name		= '".$fname."',
		last_name		= '".$lname."',
		sex				= '".$sex."',
		relation_status	= '".$relation."',
		about_me		= '".$about_me."',
		web_url			= '".$web_url."',
		show_dob		= '".$show_dob."',
		
		education		= '".$education."',
		schools			= '".$schools."',
		occupation		= '".$occupation."',
		companies		= '".$companies."',
		
		hobbies			= '".$hobbies."',
		fav_movies		= '".$fav_movies."',
		fav_music		= '".$fav_music."',
		fav_books		= '".$fav_books."',
		
		avatar			= '".$thumb."',
		background		= '".$bg."',
		
		channel_title	= '".$title."',
		channel_des		= '".$des."',
		featured_video	= '".$f_video."',
		allow_comment	= '".$comment."',
		allow_rating	= '".$rating."'
		
		WHERE userid='".$userid."'");
		redirect_to($_COOKIE['page']."?updated=successfull");
		}
		
	//Function Used To Update Email Settings For User
	
		function UpdateUserEmailSettings($usreid){
			$email 		= mysql_clean($_POST['email']);
			$msg_notify	= $_POST['msg_notify'];
			$signup = new signup();
			if($signup->isValidEmail($email)){
				mysql_query("UPDATE users SET email='".$email."',msg_notify='".$msg_notify."' WHERE userid='".$usreid."'");
			$msg = e($LANG['usr_email_msg'],m);
			}else{
			$msg = e($LANG['usr_email_err']);
			}
			return $msg;
		}
	
	/**
	 * Function used to change user password
	 */
	function ChangeUserPassword($array){
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
			$db->update($this->dbtbl['users'],array('password'),array(pass_code($array['new_pass']))," userid='".$uid."'");
			e(lang("usr_pass_email_msg"),"m");

		}
		
		return $msg;
	}
	function change_user_pass($array){ return $this->ChangeUserPassword($array); }
	function change_password($array){ return $this->ChangeUserPassword($array); }
	
	//Function Used to update number of channel / profile views of user
	
		function UpdateChannelViews($user){
			$query 	= mysql_query("SELECT profile_hits FROM users WHERE username='".$user."'");
			$data 	= mysql_fetch_array($query);
			$views = $data['profile_hits']+1;
			if(!isset($_COOKIE['view_'.$user])){
				mysql_query("UPDATE users SET profile_hits = '".$views."' WHERE username = '".$user."'");
					setcookie('view_'.$user,'true',time()+3600,'/');
				}
		}
	
	
		//Function Used To Add Channel Comment
	
		function AddChannelComment($username,$comment){
		global $LANG,$stats;
				if(empty($_SESSION['username']) ||empty($_COOKIE['session'])){
					$msg[] = e($LANG['usr_cmt_err']);
					}else{
						if(empty($comment)){
						$msg[] = e($LANG['usr_cmt_err1']);
						}
						$userdetails = $this->GetUserData_username($username);
						if($_SESSION['username'] == $userdetails['username']){
						$msg[] = e($LANG['usr_cmt_err2']);
						}
						$query = mysql_query("SELECT * FROM channel_comments WHERE channel_user ='".$username."' AND username = '".$_SESSION['username']."'");
						if(mysql_num_rows($query)>0){
						$msg[] = e($LANG['usr_cmt_err3']);
						}
					}
				if(empty($msg)){
					$stats->UpdateUserRecord(6);
					mysql_query("INSERT into channel_comments(comment,username,channel_user,date_added)VALUES('".$comment."','".$_SESSION['username']."','".$username."',now())");
					$msg[] = e($LANG['usr_cmt_err4']);
				}
			return $msg;
			}
			
		
		//Add Contact to Contact list
		
		function AddContact($friend,$username,$type=1){
		global $LANG;
			if($friend == $username){
				$msg = e($LANG['usr_cnt_err']);
				}
			$query = mysql_query("SELECT * FROM contacts WHERE friend_username = '".$friend."' AND username='".$username."'");
			if(mysql_num_rows($query)>0){
				$msg = e($LANG['usr_cnt_err1']);
				}
				if(empty($msg)){
					mysql_query("INSERT INTO contacts (friend_username,username,type)VALUES('".$friend."','".$username."','".$type."')");
					$msg = e($LANG['usr_cnt_msg']);
				}
			return $msg;
		}
		
		//Function Used to Update Videos Watch By A User
		function UpdateWatched($userid){
		global $LANG;
			$data = $this->GetUserData($userid);
			$watched = $data['total_watched']+1;
			mysql_query("UPDATE users SET total_watched ='".$watched."' WHERE userid='".$userid."'");
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
				$eh->e($LANG['you_not_logged_in']);
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
					
				$results = $db->select("messages",
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
			
		//Function Used To Unpdat Numner Of Subscrtibers of user
		function UpdateSubscribers($user){
		global $LANG;
			$query = mysql_query("SELECT * FROM subscriptions WHERE subscribed_to ='".$user."' ");
			$subs	= mysql_num_rows($query);
			mysql_query("UPDATE users SET subscribers = '".$subs."' WHERE username='".$user."'");
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
		else
		{
			$db->insert($this->dbtbl['subtbl'],array('userid','subscribed_to','date_added'),
											   array($user,$to,NOW()));
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
		$result = $db->select($this->dbtbl['subtbl'],"*"," subscribed_to='$to' AND userid='$user'");
		if($db->num_rows>0)
			return $result;
		else
			return false;			
	}
	
	
	/**
	 * Function used to get user subscibers
	 * @param userid
	 */
	function get_user_subscribers($id)
	{
		global $id;
		$result = $db->select($this->dbtbl['subtbl'],"*"," subscribed_to='$to' ");
		if($db->num_rows>0)
			return $result;
		else
			return false;	
	}
	
	/**
	 * function used to get user subscribers with details
	 */
	function get_user_subscribers_detail($id)
	{
		global $db;
		$result = $db->select("users,".$this->dbtbl['subtbl'],"*"," subscriptions.subscribed_to = '$id' AND subscriptions.userid=users.userid");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to get user subscriptions
	 */
	function get_user_subscriptions($id)
	{	
		global $db;
		$result = $db->select("users,".$this->dbtbl['subtbl'],"*"," subscriptions.userid = '$id' AND subscriptions.subscribed_to=users.userid");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
		
		//Function Used To Reset Passoword
			function ResetPassword($step){
			global $LANG,$row;
						if($step == 1){
							$user 	= mysql_clean($_POST['username']);
							$verify	= $_POST['vcode'];
							$query = mysql_query("SELECT * FROM users WHERE username = '".$user."'");
							$data = mysql_fetch_array($query);
									if(!mysql_num_rows($query)>0){
									$msg[] = e($LANG['usr_exist_err']);
									}
									//Check Confirmation Code
									if($row['captcha_type'] == '2'){
									require "captcha/class.img_validator.php";
									$img = new img_validator();
									if(!$img->checks_word($verify)){
									$msg[] = e($LANG['usr_ccode_err']);
										}
									}
									if($row['captcha_type'] == 1){
										if($verify != $_SESSION['security_code']){
											$msg[] = e($LANG['usr_ccode_err']);
										}
									}
									if(empty($msg)){
										$myquery 	= new myquery();
										$to 		= $data['email'];
										$from 		= SUPPORT_EMAIL;
										$subj		= $LANG['usr_pass_reset_conf'];
										$message 	= $LANG['usr_dear_user'].",
										".$LANG['usr_pass_reset_msg']."
										".BASEURL."/forgot.php?action=reset_pass&code=".md5($to)."___AAAWWWx54s5d744_sad1sad&avcode=".$data['avcode']."&user=".$user;
										send_email($from,$to,$subj,nl2br($message));
									$msg = $LANG['usr_rpass_email_msg'];
									}
							}
						if($step==2){
						$user 	= mysql_clean($_GET['user']);
						$avcode	= mysql_clean($_GET['avcode']);
						$query = mysql_query("SELECT * FROM users WHERE username='".$user."' AND avcode ='".$avcode."'");
						$data = mysql_fetch_array($query);
							if(mysql_num_rows($query)>0&& !empty($avcode)){
								$newpass = RandomString(6);
								$pass 	 = pass_code($newpass);
								mysql_query("UPDATE users SET password = '".$pass."' WHERE username = '".$user."'");
								$msg = e($LANG['usr_pass_email_msg'],m);
								
								$myquery 	= new myquery();
										$to 		= $data['email'];
										$from 		= SUPPORT_EMAIL;
										$subj		= $LANG['usr_rpass_msg'];
										$message 	= $LANG['usr_dear_user'].",
										".$LANG['usr_rpass_req_msg'].$newpass;
								send_email($from,$to,$subj,nl2br($message));	
								}else{
								$msg = e($LANG['usr_exist_err']);
								}
							}
		
			return $msg;
			}
										
		//Function Used to recover USername
		function RecoverUsername(){
		global $LANG;
			$email 	= mysql_clean($_POST['email']);
			$verify	= $_POST['vcode'];
			$query 	= mysql_query("SELECT * FROM users WHERE email='".$email."'");
			$data 	= mysql_fetch_array($query);
				if(!mysql_num_rows($query)>0){
				$msg[] = e($LANG['usr_exist_err1']);
				}
				
				//Check Confirmation Code
				require "captcha/class.img_validator.php";
				$img = new img_validator();
				if(!$img->checks_word($verify)){
				$msg[] = e($LANG['usr_ccode_err']);
				}
				
				if(empty($msg)){
					$to 	= $email;
					$from	= SUPPORT_EMAIL;
					$subj	= $ANG['usr_uname_recovery'];
					$message= $LANG['usr_dear_user'].",
					".$LANG['usr_uname_req_msg'].$data['username'];
					send_email($from,$to,$subj,nl2br($message));
					$msg = e($LANG['usr_uname_email_msg'],m);	
					}
		return $msg;

		
		}
	//Gettin Bridge Paramaters
	function GetBridgeParams($bridgeid){
		$query = mysql_query("SELECT * FROM login_bridges WHERE bridge_id='".$bridgeid."'");
		return mysql_fetch_array($query);
	}
	
	//Updateing User if login 
	function UpdateBrigeUser($username,$session,$bridge){
		$query = mysql_query("SELECT * FROM bridge_users where username='".$username."'");
		$date = time();
		if(mysql_num_rows($query)>0){
			mysql_query("UPDATE bridge_users SET session = '".$session."', date_updated = '".$date."'WHERE username='".$username."'");
		}else{
			mysql_query("INSERT INTO bridge_users(username,session,bridge,date_update)VALUES('".$username."','".$session."','".$bridge."','".$date."'");
		}
	}
	
	//FUNCTION USED TO UPDATE LAST ACTIVE FOR OF USER
	// @ Param : username
	function UpdateLastActive($username)
	{
		global $db;
		$sql = "UPDATE users SET last_active = '".NOW()."' WHERE username='".$username."' OR userid='".$username."' ";
		$db->Execute($sql);
	}
	
	//FUNCTION USED TO DELETE COMMMENT
	// @ Param : username
	// @ Param : commentid
	function deleteUserComment($username,$commentid)
	{
		global $is_admin,$db,$LANG;
		if($_SESSION['username']==$username || $is_admin ==1)
		{
			$sql = "DELETE FROM channel_comments WHERE comment_id='".$commentid."'
						AND channel_user = '".$username."'";
			$db->Execute($sql);
			$msg = e($LANG['usr_cmt_del_msg'],m);
		}else{
			$msg = e($LANG['usr_cmt_del_err']);
		}
		return $msg;
	}
	
	/**
	 * FUNCTION USED TO GE USER THUMBNAIL
	 * @param : thumb file
	 * @param : size (NULL,small)
	 */
	function getUserThumb($udetails,$size='',$uid=NULL,$just_file=false)
	{
		
		$remote = false;
		if(empty($udetails['userid']))
			$udetails = $this->get_user_details($uid);
		//$thumbnail = $udetails['avatar'] ? $udetails['avatar'] : NO_AVATAR;
		$thumbnail = $udetails['avatar'];
		$thumb_file = BASEDIR.'/images/avatars/'.$thumbnail;
		if(file_exists($thumb_file) && $thumbnail!='')
			$thumb_file = BASEURL.'/images/avatars/'.$thumbnail;
		elseif(!empty($udetails['avatar_url']))
		{
			$thumb_file = $udetails['avatar_url'];
			$remote  = true;
		}else
			$thumb_file = BASEURL.'/images/avatars/'.NO_AVATAR;
		$ext = GetExt($thumb_file);
		$file = getName($thumb_file);
		
		if(!$remote)
		{
			if(!empty($size))
				$thumb = BASEURL.'/images/avatars/'.$file.'-'.$size.'.'.$ext;
			else
				$thumb = BASEURL.'/images/avatars/'.$file.'.'.$ext;
		}else
			$thumb = $thumb_file;
		
		if($just_file)
			return $file.'.'.$ext;
			
		return $thumb;
	}
	function avatar($udetails,$size='',$uid=NULL)
	{
		return $this->getUserThumb($udetails,$size,$uid);
	}
	
	/**
	 * Function used to get user Background
	 * @param : bg file
	 */
	function getUserBg($udetails)
	{
		$remote = false;
		if(empty($udetails['userid']))
			$udetails = $this->get_user_details($uid);
		//$thumbnail = $udetails['avatar'] ? $udetails['avatar'] : 'no_avatar.jpg';
		$file = $udetails['background'];
		$bgfile = BASEDIR.'/images/backgrounds/'.$file;
		if(file_exists($bgfile) && $file)
			$thumb_file = BASEURL.'/images/backgrounds/'.$file;
		elseif(!empty($udetails['background_url']))
		{
			$thumb_file = $udetails['background_url'];
			$remote  = true;
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
		$results = $db->Execute("SELECT * FROM subscriptions WHERE subsctibe_to='$username'");
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
		$results = $db->select('users',$field,"userid='$uid' OR username='$uid'");
		
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
			$level['level'] = $uid;
		else
		{
			if(!$uid)
				$uid = userid();
			$level = $this->get_user_field($uid,'level');
		}
		
		$results = $db->select('user_levels','*'," user_level_id='".$level['level']."'");
		if($db->num_rows == 0)
		 //incase user level is not valid, it will consider it as registered user
			$u_level['user_level_id'] = 3;
		else
			$u_level = $results[0];
			
		//Now Getting Access Details
		$access_results = $db->select("user_levels_permissions","*",
									  "user_level_id = '".$u_level['user_level_id']."'");
		$a_results = $access_results[0];
		
		//Now Merging the two arrays
		$user_level = array_merge($u_level,$a_results);
		
		return $user_level;
	}
	
	
	/**
	 * Function used to get all levels
	 * @param : filter
	 */
	function get_levels($filter=NULL)
	{
		global $db;
		$results = $db->select("user_levels","*",NULL,NULL," user_level_id ASC" );
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
		$results = $db->select("user_levels","*"," user_level_id='$lid' ");
		if($db->num_rows > 0 )
		{
			return $results[0];
		}else{
			e("Cannot find level");
			return false;
		}
	}
	
	/**
	 * Function used to get users of particular level
	 * @param : level_id
	 * @param : count BOOLEAN (if TRUE it will return NUMBERS)
	 */
	function get_level_users($id,$count=FALSE)
	{
		global $db;
		$results = $db->select("users","level"," level='$id'");
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
			e("Please enter level nane");
		else
		{
			$db->insert("user_levels",array('user_level_name'),array($level_name));
			$iid = $db->insert_id();
			
			$fields_array[] = 'user_level_id';
			$value_array[] = $iid;
			foreach($this->get_access_type_list() as $access => $name)
			{
				$fields_array[] = $access;
				$value_array[] = $array[$access] ? $array[$access] : 'no';
			}
			$db->insert("user_levels_permissions",$fields_array,$value_array);		
			return true;
		}
	}
	
	/**
	 * Function usewd to get level permissions
	 */
	function get_level_permissions($id)
	{
		global $db;
		$results = $db->select("user_levels_permissions","*"," user_level_id = '$id'");		
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
				$db->update("user_levels",array("user_level_name"),array($level_name)," user_level_id = '$id'");
			}
			
			//Updating Permissions
			$db->update("user_levels_permissions",$fields_array,$value_array," user_level_id = '$id'");
			
			e("Level has been updated",m);
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
				$db->delete("user_levels",array("user_level_id"),array($id));
				$db->delete("user_levels_permissions",array("user_level_id"),array($id));
				e("User level has been deleted, 
				  all users of this level has been transfered to '".$de_level['user_level_name']."' ");
				
				$db->update("users",array("level"),array(3)," level='$id'");
				return true;
				
			}else{
				e("This level is not deletable");
				return false;
			}
		}
	}
	
	/**
	 * Function used to add comment on users profile
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL,$type='c')
	{
		global $myquery;
		if(!$this->user_exists($obj_id))
			e("User does not exists");
		return $myquery->add_comment($comment,$obj_id,$reply_to,$type);
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
			
		$results = $db->select("video","*"," userid = '$uid' $cond");
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
	 * Function used to create profile link
	 */
	function profile_link($udetails)
	{
		if(!is_array($udetails) && is_numeric($udetails))
			$udetails = $this->get_user_details($udetails);
		return BASEURL.'/view_channel.php?user='.$udetails['username'];
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
		return $db->select($this->dbtbl['user_permission_type'],"*");
	}
	
	/**
	 * Function used to check weather level type exists or not
	 */
	function level_type_exists($id)
	{
		global $db;
		$result = $db->select($this->dbtbl['user_permission_type'],"*"," user_permission_type_id='".$id."' OR user_permission_type_name='$id'");
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
			e("Permission code is empty");
		elseif(empty($array['name']))
			e("Permission name is empty");
		elseif($this->permission_exists($array['code']))
			e("Permission already exists");
		elseif(!$this->level_type_exists($array['type']))
			e("Permission type is not valid");
		else
		{
			$type = $this->level_type_exists($array['type']);
			$typeid = $type['user_permission_type_id'];
			$code = mysql_clean($array['code']);
			$name = mysql_clean($array['name']);
			$desc = mysql_clean($array['desc']);
			$default = mysql_clean($array['default']);
			$default = $default ? $default : "yes";
			$db->insert($this->dbtbl['user_permissions'],
						array('permission_type','permission_code','permission_name','permission_desc','permission_default'),
						array($typeid,$code,$name,$desc,$default));
			$db->execute("ALTER TABLE `".$this->dbtbl['user_level_permission']."` ADD `".$code."` ENUM( 'yes', 'no' ) NOT NULL DEFAULT '".$default."'");
			e("New Permission has been added","m");
		}
	}
	
	/**
	 * Function used to check permission exists or not
	 * @Param permission code
	 */
	function permission_exists($code)
	{
		global $db;
		$result = $db->select($this->dbtbl['user_permissions'],"*"," permission_code='".$code."' OR permission_id='".$code."'");
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
		$result = $db->select($this->dbtbl['user_permissions'],"*",$cond);
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
			$db->delete($this->dbtbl['user_permissions'],array("permission_id"),array($id));
			$db->execute("ALTER TABLE `".$this->dbtbl['user_level_permission']."` DROP `".$field."` ");
			e("Permission has been delete","m");
		}else
			e("Permission does not exist");
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
			if(is_numeric($access))
			{
				if($access_details['level_id'] == $access)
				{
					return true;
				}else{
					if(!$check_only)
					e($LANG['insufficient_privileges']);
					
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
		$result = $db->select($this->dbtbl['user_profile'],"*"," userid='$uid'");
		if($db->num_rows>0)
		{
			return $result[0];
		}else
			return false;
	}
	
	
	
	/**
	 * FUnction loading personal details
	 */
	function load_personal_details($default)
	{
		
		$user_vids = get_videos(array('user'=>$default['userid']));
		if(is_array($user_vids))
		foreach($user_vids as $user_vid)
		{
			$usr_vids[$user_vid['videoid']] =  $user_vid['title'];
			
		}
		
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
						  'required'=>'yes',
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
		'profile_title' => array(
						  'title'=>  lang("profile_title"),
						  'type'=> "textfield",
						  'name'=> "profile_title",
						  'id'=> "last_name",
						  'value'=> $default['profile_title'],
						  'db_field'=>'profile_title',
						  'auto_view'=>'no'
		
						  ),
		'profile_desc' => array(
						  'title'=>  lang("profile_desc"),
						  'type'=> "textarea",
						  'name'=> "profile_desc",
						  'id'=> "last_name",
						  'value'=> $default['profile_desc'],
						  'db_field'=>'profile_desc',
						  'auto_view'=>'no'
		
						  ),
		'relation_status' => array(
						  'title'=>  lang("user_relat_status"),
						  'type'=> "dropdown",
						  'name'=> "relation_status",
						  'id'=> "last_name",
						  'value'=> array(lang('usr_arr_single')=>lang('usr_arr_single'),
										  lang('usr_arr_married')=>lang('usr_arr_married'),
										  lang('usr_arr_comitted')=>lang('usr_arr_comitted'),
										  lang('usr_arr_open_relate')=>lang('usr_arr_open_relate')),
						  'checked'=> $default['relation_status'],
						  'db_field'=>'relation_status',
						  'auto_view'=>'yes',
						  'return_checked'	=> true,
		
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
						  'auto_view'=>'no'
						  ),
		'about_me' => array(
						  'title'=>  lang("user_about_me"),
						  'type'=> "textarea",
						  'name'=> "about_me",
						  'id'=> "about_me",
						  'value'=> $default['about_me'],
						  'db_field'=>'about_me',
						  'auto_view'=>'yes',
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
		'profile_video' => array(
						  'title' => lang('Profile Video'),
						  'type' => 'dropdown',
						  'name' => 'profile_video',
						  'id' => 'profile_video',
						  'value' => $usr_vids,
						  'checked' => $default['profile_video'],
						  'db_field' => 'profile_video',
						  'auto_view' => 'no',

						  )
		
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
						  ),
		'hometown' => array(
						  'title'=>  lang("hometown"),
						  'type'=> "textfield",
						  'name'=> "hometown",
						  'id'=> "hometown",
						  'value'=> $default['hometown'],
						  'db_field'=>'hometown',
						  ),
		'city' => array(
						  'title'=>  lang("city"),
						  'type'=> "textfield",
						  'name'=> "city",
						  'id'=> "city",
						  'value'=> $default['city'],
						  'db_field'=>'city',
						  ),
		);
		return $other_details;
	}
	
	
	/**
	 * Function used to load experice fields
	 */
	function load_other_fields($default)
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
						  'value'=> array(lang('usr_arr_no_ans')=>lang('usr_arr_no_ans'),
										  lang('usr_arr_elementary')=>lang('usr_arr_elementary'),
										  lang('usr_arr_hi_school')=>lang('usr_arr_hi_school'),
										  lang('usr_arr_some_colg')=>lang('usr_arr_some_colg'),
										  lang('usr_arr_assoc_deg')=>lang('usr_arr_assoc_deg'),
										  lang('usr_arr_bach_deg')=>lang('usr_arr_bach_deg'),
										  lang('usr_arr_mast_deg')=>lang('usr_arr_mast_deg'),
										  lang('usr_arr_phd')=>lang('usr_arr_phd'),
										  lang('usr_arr_post_doc')=>lang('usr_arr_post_doc'),
										  ),
						  'checked'=>$default['education'],
						  'db_field'=>'education',
						  ),
		'schools' => array(
						  'title'=>  lang("schools"),
						  'type'=> "textarea",
						  'name'=> "schools",
						  'id'=> "schools",
						  'value'=> $default['schools'],
						  'db_field'=>'schools',
						  ),
		'occupation' => array(
						  'title'=>  lang("occupation"),
						  'type'=> "textarea",
						  'name'=> "occupation",
						  'id'=> "occupation",
						  'value'=> $default['occupation'],
						  'db_field'=>'occupation',
						  ),
		'companies' => array(
						  'title'=>  lang("companies"),
						  'type'=> "textarea",
						  'name'=> "companies",
						  'id'=> "companies",
						  'value'=> $default['companies'],
						  'db_field'=>'companies',
						  ),
		'hobbies' => array(
						  'title'=>  lang("hobbies"),
						  'type'=> "textarea",
						  'name'=> "hobbies",
						  'id'=> "hobbies",
						  'value'=> $default['hobbies'],
						  'db_field'=>'hobbies',
						  ),
		'fav_movies' => array(
						  'title'=>  lang("user_fav_movs_shows"),
						  'type'=> "textarea",
						  'name'=> "fav_movies",
						  'id'=> "fav_movies",
						  'value'=> $default['fav_movies'],
						  'db_field'=>'fav_movies',
						  ),
		'fav_music' => array(
						  'title'=>  lang("user_fav_music"),
						  'type'=> "textarea",
						  'name'=> "fav_music",
						  'id'=> "fav_music",
						  'value'=> $default['fav_music'],
						  'db_field'=>'fav_music',
						  ),
		'fav_books' => array(
						  'title'=>  lang("user_fav_books"),
						  'type'=> "textarea",
						  'name'=> "fav_books",
						  'id'=> "fav_books",
						  'value'=> $default['fav_books'],
						  'db_field'=>'fav_books',
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
						  ),
		'allow_comments'=>array(
						  'title'=>  lang("vdo_allow_comm"),
						  'type'=> "radiobutton",
						  'name'=> "allow_comments",
						  'id'=> "allow_comments",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['allow_comments']),
						  'db_field'=>'allow_comments',
						  ),
		'allow_ratings'=>array(
						  'title'=>  lang("allow_ratings"),
						  'type'=> "radiobutton",
						  'name'=> "allow_ratings",
						  'id'=> "allow_ratings",
						  'value' => array('yes'=>lang('yes'),'no'=>lang('no')),
						  'checked' => strtolower($default['allow_ratings']),
						  'db_field'=>'allow_ratings',
						  ),
		);
		
		return $privacy;
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
		$more_details = $this->load_other_fields($default);
		$privacy = $this->load_privacy_field($default);		
		return array_merge($profile_fields,$other_details,$more_details,$privacy);
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
		
		validate_cb_form($userfields,$array);
		
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
				$val = apply_func($field['clean_func'],$val);
			
			if(!empty($field['db_field']))
			$query_val[] = $val;
		}
		
		//updating user detail
		if(has_access('admin_access',TRUE) && isset($array['admin_manager']))
		{
			//Checking Username
			if(empty($array['username']))
				e(lang('usr_uname_err'));
			elseif($array['dusername'] != $array['username'] && $this->username_exists($array['username']))
				e(lang('usr_uname_err2'));
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
			$uquery_field[] = 'rating';
			
			$rating = $array['rating'];
			if($rating<1 || $rating>10)
				$rating = 1;
			$uquery_val[] = $rating ;
			$uquery_field[] = 'rated_by';
			$uquery_val[] = $array['rated_by'];
			
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
		
		//Updating User Avatar
		if($array['avatar_url'])
		{
			$uquery_field[] = 'avatar_url';
			$uquery_val[] = $array['avatar_url'];
		}
		
		//Deleting User Avatar
		if($array['delete_avatar']=='yes')
		{
			$file = BASEDIR.'/images/avatars/'.$array['avatar_file_name'];
			if(file_exists($file) && $array['avatar_file_name'] !='')
				unlink($file);
		}
		
		//Deleting User Bg
		if($array['delete_bg']=='yes')
		{
			$file = BASEDIR.'/images/backgrounds/'.$array['bg_file_name'];
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
		
		if(!error() && is_array($uquery_field))
		{
			$db->update($this->dbtbl['users'],$uquery_field,$uquery_val," userid='".mysql_clean($array['userid'])."'");
			e(lang("usr_upd_succ_msg"),'m');
		}
		
		
		
		//updating user profile
		if(!error())
		{
			$db->update($this->dbtbl['user_profile'],$query_field,$query_val," userid='".mysql_clean($array['userid'])."'");
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
		if($array['avatar_url'])
		{
			$uquery_field[] = 'avatar_url';
			$uquery_val[] = mysql_clean($array['avatar_url']);
		}
		
		//Deleting User Avatar
		if($array['delete_avatar']=='yes')
		{
			$file = BASEDIR.'/images/avatars/'.$array['avatar_file_name'];
			if(file_exists($file) && $array['avatar_file_name'] !='')
				unlink($file);
		}
		
		//Deleting User Bg
		if($array['delete_bg']=='yes')
		{
			$file = BASEDIR.'/images/backgrounds/'.$array['bg_file_name'];
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
		if($array['background_url'])
		{
			$uquery_field[] = 'background_url';
			$uquery_val[] = mysql_clean($array['background_url']);
		}
		
		if($array['background_color'])
		{
			$uquery_field[] = 'background_color';
			$uquery_val[] = mysql_clean($array['background_color']);
		}
		
		if($array['background_repeat'])
		{
			$uquery_field[] = 'background_repeat';
			$uquery_val[] = mysql_clean($array['background_repeat']);
		}
		
		
		if(isset($_FILES['background_file']['name']))
		{
			
			$file = $Upload->upload_user_file('b',$_FILES['background_file'],$array['userid']);
			if($file)
			{
				$uquery_field[] = 'background';
				$uquery_val[] = mysql_clean($file);
			}
		}
		
		$db->update($this->dbtbl['users'],$uquery_field,$uquery_val," userid='".mysql_clean($array['userid'])."'");
		e(lang("usr_avatar_bg_update"),'m');

	}
	
	
	/**
	 * Function used to check weather username exists or not
	 */
	function username_exists($i)
	{
		global $db;
		$db->select($this->dbtbl['users'],"username"," username='$i'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	/**
	 * function used to check weather email exists or not
	 */
	 function email_exists($i)
	{
		global $db;
		$db->select($this->dbtbl['users'],"email"," email='$i'");
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
		$result = $db->select($this->dbtbl['action_log'],"*"," action_userid='$uid'",$limit," date_added DESC");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Load Custom Profile Field
	 */
	function load_custom_profile_fields($array)
	{
		return false;
	}
	
	/**
	 * Load Custom Signup Field
	 */
	function load_custom_signup_fields($array)
	{
		return false;
	}
	
	
	/**
	 * Function used to get channel links
	 * ie Playlist, favorites etc etc
	 */
	function get_inner_channel_top_links($u)
	{
		return array(lang('uploads')=>'uploads',lang('favorites')=>'favorites',lang('contacts')=>'contacts');
	}
	
	/**
	 * Function used to get user channel action links
	 * ie Add to friends, send message etc etc
	 */
	function get_channel_action_links($u)
	{
		return array(lang('Send Message')=>'sm',lang('Add as friend')=>'aaf',lang('Block user')=>'bu');
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
	 * My Account links
	 */
	function my_account_links()
	{
		$array = array
		(
		 'Account'	=>array
		 			('My Account'	=> 'myaccount.php',
					 'Ban users'	=> 'edit_account.php?mode=ban_users',
					 'Change Password'	=>'edit_account.php?mode=change_password',
					 'Change Email' 	=>'edit_account.php?mode=change_email',
					 ),
		 'Profile'	=>array
		 			('Profile Settings'	=>'edit_account.php',
					 'Change Avatar' 	=> 'edit_account.php?mode=avatar_bg',
					 'Change Background' => 'edit_account.php?mode=avatar_bg',
					 ),
		'Videos' =>array
					(
					 'Uploaded Videos'=>'manage_videos.php',
					 'Favorite Videos'=>'manage_videos.php?mode=favorites',
					 ),
		'Messages' => array
					(
					 'Inbox'	=> 'private_message.php?mode=inbox',
					 'Notifications' => 'private_message.php?mode=notification',
					 'Sent'	=> 'private_message.php?mode=sent',
					 'Compose New'=> 'private_message.php?mode=new_msg',
					 )
		);
		
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
		else
		{
			$db->update($this->dbtbl['users'],array('email'),array($array['new_email'])," userid='".$array['userid']."'");
			e(lang("email_change_msg"),"m");
		}
	}
	
	/**
	 * Function used to ban users
	 */
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
			$db->update($this->dbtbl['users'],array('banned_users'),array($banned_users)," userid='$uid'");
			e(lang("user_ban_msg"),"m");
		}else{
			e(lang("no_user_ban_msg"),"m");
		}
	}
	
	
	
	/**
	 * Function used to check weather user is banned or not
	 */
	function is_user_banned($ban,$user=NULL)
	{
		global $db;
		if(!$user)
			$user = userid();
		$result = $db->count($this->dbtbl['users'],"userid"," banned_users LIKE '%$ban%' AND (username='$user' OR userid='$user') ");
		if($result)
			return true;
		else
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
		$result = $db->select($this->dbtbl['users'].",".$this->dbtbl['user_profile'],"*",$this->dbtbl['users'].".userid ='$uid' AND ".$this->dbtbl['users'].".userid = ".$this->dbtbl['user_profile'].".userid");
		return $result[0];
	}
}
?>