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
 

class userquery {
	
	var $userid = '';
	var $username = '';
	var $level = '';
	var $permissions = '';
	var $access_type_list = array(); //Access list
	
	function init()
	{
		global $sess;
		$this->userid = $sess->get('userid');
		$this->username = $sess->get('username');
		$this->level = $sess->get('level');
		
		
		//Setting Access
		$this->add_access_type('admin_access','Admin Access');
		$this->add_access_type('upload_access','Upload Access');
		$this->add_access_type('channel_access','Channel Access');
		$this->add_access_type('mod_access','Moderator Access');
	
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
			
			//Updating User last login and num of visist
			$db->update('users',
						array(
							  'num_visits','last_logged'
							  ),
						array(
							  '|f|num_visits+1',NOW()
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
			$access_details = $this->get_user_level(userid());
			if(is_numeric($access))
			{
				$access_details = $this->get_user_level(userid());
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
							   "username='$username' AND password='$pass'");
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
		$admin = 'Admin';
        if(isset($_SESSION['userid']) && isset($_SESSION['username']) && isset($_SESSION['session']))
        {
		$userid = @$_SESSION['userid'];
		$username = @$_SESSION['username'];
		$session = @$_SESSION['session'];

					$query = mysql_query("SELECT * FROM users WHERE level='".$admin."' AND username ='".$username."' AND userid = '".$userid."' AND session='".$session."'");
					if(mysql_num_rows($query)>0){
					$answer = 1;
                    return $answer;
					}else{
					$answer = 0;
                    return $answer;
					}
        }
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
		
					/*//This Fucntion Is Used To Check Weather User as Admin has Been Lggen in or Not FOR LOGIN PAGE
					function admin_login_check_2(){
						$admin = 'Admin';
						$userid = @$_SESSION['userid'];
						$username = @$_SESSION['username'];
						$session = @$_COOKIE['PHPSESSID'];
									$query = mysql_query("SELECT * FROM users WHERE level='".$admin."' AND username ='".$username."' AND userid = '".$userid."' AND session='".$session."'");
									if(mysql_num_rows($query)>0){
									$login = true;
									}else{
									
									}

						return @$login;
							
						}*/
	
	/*//Function Used To Check if SuperAdmin is loggged in or no
	function SuperAdminCheck(){
		$username = $_SESSION['username'];
		$session = $_COOKIE['PHPSESSID'];
		$query = mysql_query("SELECT * FROM admin WHERE username = '".$username."' AND session = '".$session."'");
		if(mysql_num_rows($query)>0){
			$login = true;
		}else{
		 	redirect_to('main.php?msg=Please%20Loggin%20As%20SuperAdmin');
		}
	}*/
		
	//This Function Is Used to Logout
	function logout($page='login.php'){
	setcookie('username','',time()-3600,'/');
	setcookie('userid','',time()-3600,'/');
	setcookie('session','',time()-3600,'/');
	session_unregister('username');
	session_unregister('superadmin');
	session_unregister('userid');
    session_destroy();
	redirect_to($page);
	}
	
	//List All Users
	
	function Get_All_Users($orderby,$order){
	$myquery = new myquery();
	$query = mysql_query("SELECT * FROM users ORDER BY '".$orderby."' '".$order."'");
	while($data=$myquery->fetch($query)){
	}
	return $data;
	}

 //Updating Super Admin
 	function UpdateSuperAdmin(){
	global $LANG;
	$query = mysql_query("SELECT * FROM admin WHERE admin_id = '1' ");
	$data = mysql_fetch_array($query);
	
	$pass = $data['password'];
	
		$uname = clean($_POST['uname']);
			if(empty($uname)){
				$msg = e($LANG['usr_sadmin_err']);
			}
		$op = pass_code($_POST['opass']);
		$np = pass_code($_POST['npass']);
		$cp = pass_code($_POST['cnpass']);
		
		if(!empty($_POST['npass'])){
			if($np != $cp){
				$msg = e($LANG['usr_cpass_err']);
			}elseif($op != $pass){
				$msg = e($LANG['usr_pass_err']);
			}else{
			$pass_query = " , password = '".$np."'";
			}
		}
		if(empty($msg)){
			mysql_query("UPDATE admin SET username = '".$uname."' $pass_query WHERE username = '".$data['username']."'");
			$msg = e($LANG['usr_sadmin_msg'],m);
		}
		return $msg;
	}
 //--------ADMIN ACTIONS START ---------//
 
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
		$query = mysql_query("SELECT * FROM users WHERE userid='".$id."' OR username='".$id."'");
		if(mysql_num_rows($query)>0){
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
		if(!$id)
			$id = userid();
			
		$results = $db->select('users','*'," userid='$id' ");
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
	
	//Activate User
	function Activate($user){
	$avcode = RandomString(10);
	mysql_query("UPDATE users SET usr_status ='Ok',avcode='".$avcode."' WHERE userid='".$user."'");
	return true;
	}
	
	//DeActivate User
	function DeActivate($user){
	$avcode = RandomString(10);
	mysql_query("UPDATE users SET usr_status ='ToActivate',avcode='".$avcode."' WHERE userid='".$user."'");
	return true;
	}
	
	//Featured User
	function MakeFeatured($user){
	mysql_query("UPDATE users SET featured ='Yes' WHERE userid='".$user."'");
	return true;
	}
	
	//UnFeatured User
	function MakeUnFeatured($user){
	mysql_query("UPDATE users SET featured ='No' WHERE userid='".$user."'");
	return true;
	}
	
	//Ban User
	function ban($user){
	mysql_query("UPDATE users SET ban_status ='yes' WHERE userid='".$user."'");
	return true;
	}
	
	//UnBan User
	function unban($user){
	mysql_query("UPDATE users SET ban_status ='no' WHERE userid='".$user."'");
	return true;
	}
	
	
 //--------ADMIN ACTIONS END ---------//
 
 	//User Login
	
	function userlogin($username,$password){
	//FUNCTION PENDING DUE TO FAILED IMPLEMENTATION -- ARSLAN
/*	if(LOGIN_BRIDGE==1){
		require('login_bridge.php');
		$bridgeid = LOGIN_BRIDGE_ID;
		$param = $this->GetBridgeParams($bridgeid);
		$param['username'] = $username;
		$param['password'] = $password;
		$brige_results = BridgePHPBB($param);	
	}else{*/

			$query 	= mysql_query("Select * FROM users WHERE username = '".$username."' and password = '".$password."'");
			$user_query 	= mysql_query("Select num_visits FROM users WHERE username = '".$username."'");
			$user_data 		= mysql_fetch_array($user_query);
			$videos_query 	= mysql_query("SELECT * FROM video WHERE username='".$username."'");
			$videoscount	= mysql_num_rows($videos_query);
			$comments_query = mysql_query("SELECT * FROM channel_comments WHERE channel_user='".$username."'");
			$commentscount	= mysql_num_rows($comments_query);
			$num_visits = $user_data['num_visits']+1;
			$updatequery = "UPDATE users SET session='".$_COOKIE['PHPSESSID']."' , last_logged =now(), num_visits='".$num_visits."',total_videos='".$videoscount."',total_comments='".$commentscount."',ip='".$_SERVER['REMOTE_ADDR']."' WHERE username = '".$username."'";
		//}
				//if(mysql_num_rows($query) >0 || $brige_results==true){ -- In case we turn LoginBrigge on
				if(mysql_num_rows($query) >0){
						$data = mysql_fetch_array($query);
						if($data['ban_status'] != 'yes'){
						setcookie('username',$data['username'],time()+7200,'/');
						setcookie('userid',$data['userid'],time()+7200,'/');
						setcookie('session',$_COOKIE['PHPSESSID'],time()+7200,'/');
						session_register('username');
						session_register('userid');
						session_register('session');
						session_register('admin');
						$_SESSION['username'] = $data['username'];
						$_SESSION['userid'] = $data['userid'];
						$_SESSION['session'] = $_COOKIE['PHPSESSID'];
						if(!empty($admin) || $data['level'] == 'Admin'){
							$_SESSION['admin'] = $data['username'];
						}
						if($data['userid'==1]){
						$_SESSION['superadmin'] = $data['username'];
						}
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
	
	
	
		
	function logincheck2(){
		@$userid = $_SESSION['userid'];
		@$username = $_SESSION['username'];
		@$session = $_COOKIE['PHPSESSID'];
					$query = mysql_query("SELECT * FROM users WHERE username ='".$username."' AND userid = '".$userid."' AND session='".$session."'");
					if(mysql_num_rows($query)>0){
					$login = true;
					}else{
					$login = false;
					}
			return $login;
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
	
	//Function Used To Change Password
	
		function ChangeUserPassword($userid){
			global $LANG;
			$old_pass 	= pass_code($_POST['old_pass']);
			$new_pass 	= pass_code($_POST['new_pass']);
			$c_new_pass	= pass_code($_POST['c_new_pass']);
				$query = mysql_query("SELECT * FROM users WHERE userid = '".$userid."' AND password = '".$old_pass."'");
					if(mysql_num_rows($query)>0){
						if($new_pass == $c_new_pass){
						mysql_query("UPDATE users Set password='".$new_pass."' WHERE userid='".$userid."'");
						$msg = e($LANG['usr_pass_msg'],m);
						}else{
						$msg = e($LANG['usr_cpass_err1']);
						}
					}else{
					$msg = e($LANG['usr_pass_err1']);
					}
				return $msg;
			}
			
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
		
		//Function Used To Subscribe to User
		function SubscribeUser($sub_user,$sub_to){
		global $LANG;
			if(!empty($sub_user) || !empty($sub_to)){
				$query=mysql_query("SELECT * FROM subscriptions WHERE subscribed_user='".$sub_user."' AND subscribed_to='".$sub_to."'");
					if(mysql_num_rows($query)==0){
						mysql_query("INSERT INTO subscriptions(subscribed_user,subscribed_to)VALUES('".$sub_user."','".$sub_to."')");
						$this->UpdateSubscribers($sub_to);
						$msg = e($LANG['usr_sub_msg'].$sub_to,m);
						}else{
						$msg = e($LANG['usr_sub_err'].$sub_to);
						}
				}
			return $msg;
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
		$sql = "UPDATE users SET last_active = now() WHERE username='".$username."'";
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
	function getUserThumb($udetails,$size='',$uid=NULL)
	{
		if(empty($udetails['userid']))
			$udetails = $this->get_user_details($uid);
		$thumbnail = $udetails['avatar'] ? $udetails['avatar'] : 'noavatar.png';
		$thumb_file = BASEDIR.'/images/avatars/'.$thumbnail;
		if(file_exists($thumb_file))
			$thumb_file = BASEURL.'/images/avatars/'.$thumbnail;
		else
			$thumb_file = BASEURL.'/images/avatars/no_avatar.jpg';
		$ext = GetExt($thumb_file);
		$file = getName($thumb_file);
		if(!empty($size))
			$thumb = BASEURL.'/images/avatars/'.$file.'-'.$size.'.'.$ext;
		else
			$thumb = BASEURL.'/images/avatars/'.$file.'.'.$ext;
		return $thumb;
	}
	function avatar($udetails,$size='',$uid=NULL)
	{
		return $this->getUserThumb($udetails,$size,$uid);
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
		$results = $db->select('users',$field,"userid='$uid'");
		
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
	function get_user_level($uid)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		$level = $this->get_user_field($uid,'level');
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
		$results = $db->select("user_levels","*");
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
				$db->delete("user_levels",array("user_level_id"),$id);
				$db->delete("user_levels_permissions",array("user_level_id"),$id);
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
		return $this->get_user_fields(user_id(),'username');
	}
	
	/**
	 * Function used to create profile link
	 */
	function profile_link($udetails)
	{
		if(!is_array($udetails) && is_numeric($udetails))
			$udetails = $this->get_user_details($udetails);
		return BASEURL.'/view_profile.php?uid='.$udetails['userid'];
	}
}
?>