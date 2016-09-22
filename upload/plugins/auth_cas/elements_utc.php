<?php
/*
Plugin Name: Choose Thumbnail
Description: Add a button to choose the thumbnail from a time in the video
Author: Adrien Ponchelet
Author Website: https://www.u-picardie.fr
ClipBucket Version: 2.8.1 rc1
Version: 1.0
*/

/*
	Fichier modifié :
		upload/includes/common.php
		upload/includes/defined_links.php
		upload/includes/functions.php
		upload/includes/classes/authclass.class.php
		upload/includes/classes/user.class.php
*/


/* ************************************************************************
		upload/includes/common.php
************************************************************************ */
	define('EMAIL_VERIFICATION', FALSE); // nécéssaire pour créer automatiquement les utilisateurs lors de nouvelles connexions via le CAS


	require_once('classes/authcas.class.php');







/* ************************************************************************
		upload/includes/defined_links.php
************************************************************************ */

# Ajout de l'URL d'identification via le CAS
$auth_cas = new AuthCAS();
$login_cas_url = $auth_cas->login_url();
$cbLinks['login_cas'] = array($login_cas_url, $login_cas_url);






/* ************************************************************************
		upload/includes/functions.php
************************************************************************ */

	//Redirect Using JAVASCRIPT
	
	function redirect_to($url){
		echo '<script type="text/javascript">
		window.location = "'.$url.'"
		</script>';
		exit("Javascript is turned off, <a href='$url'>click here to go to requested page</a>");
	}
        
    function redirect_to_cas() {
        $cas = new AuthCAS();
        redirect_to($cas->login_url());
    }
	

	/**
	 * Function used to get userid anywhere 
	 * if there is no user_id it will return false
	 */
	function user_id($check_cas=false)
	{
		global $userquery;
		if(($userquery->userid !='' && $userquery->is_login) ||
            ($check_cas && $userquery->login_with_cas($check_cas)))    
            return $userquery->userid;
        else 
            return false;
	}








/* ************************************************************************
		upload/includes/classes/authclass.class.php
************************************************************************ */
voir fichier authcas.class.php







/* ************************************************************************
		upload/includes/classes/user.class.php
************************************************************************ */

	function init()
	{
		global $sess,$Cbucket;

+        $this->login_with_cas();
        







	/**
	 * Return the user ID from a user name, create it if necessary
	 * (users not yet logged with CAS)
	 */
	function signup_user_if_doesnt_exist($username){
		$user_id = $this->get_userid_from_username($username);
		if ($user_id>=0)
			return $user_id;
		$fakepass = base64_encode(openssl_random_pseudo_bytes(20));
		$auth_cas = new AuthCAS();
		$user_infos = array(
				'username' => $username,
				'password' => $fakepass,
				'cpassword' => $fakepass,
				'email' => "$username@{$auth_cas->email_domain}",
				'agree' => 'yes', // agree to the terms of service
				'level' => 2, // user level. 2 = Registered User
				'country' => 'FR',
				'gender' => 'male',
				'dob' => '1980-01-01',
				'category' => 1, // Basic User
				'usr_status' => 1, // activated => requires that EMAIL_VERIFICATION is set to FALSE
		);

		// insert id if user was created...
		return $this->signup_user($user_infos, FALSE);
   }

	/**
	 * If the user is logged to the CAS, log him to the site.
	 * Note : a combination of the Init and login_user methods
	 * @author : Mickael Urrutia
	 */
	function login_with_cas($force_check=false){
		global $LANG,$sess,$cblog,$db;

		# get the userid
		$this->sess_salt = $sess->get('sess_salt');
		$this->sessions = $this->get_sessions();

		if($this->sessions['smart_sess'])
			$this->userid = $this->sessions['smart_sess']['session_user'];

		# already logged to the site
		if($this->userid) return true;
        		
        # check CAS Authentication
		$auth_cas = new AuthCAS();
		if(! $auth_cas->logged_in($force_check)) return false;
        
		$username = $auth_cas->username();

        # If the user doesn't exist in the DB, add him
		$this->signup_user_if_doesnt_exist($username);

        # get the password
		$results = $db->select(tbl("users"),"password",	"username='$username'");
		if(count($results) == 0) return FALSE;
		$pass = $results[0]['password'];

        #################################################
		# From here it's copy / pasted from login_user()

		$udetails = $this->get_user_with_pass($username,$pass);

		//Inserting Access Log
		$log_array = array('username'=>$username);

		# First we will check whether user is allowed to log in
		if(strtolower($udetails['usr_status']) != 'ok')
			$msg[] = e(lang('user_inactive_msg'));
		elseif($udetails['ban_status'] == 'yes')
			$msg[] = e(lang('usr_ban_err'));
        # Then we get his details
		else
		{

			$log_array['userid'] = $userid  = $udetails['userid'];
			$log_array['useremail'] = $udetails['email'];
			$log_array['success'] = 1;

			$log_array['level'] = $level  = $udetails['level'];

            # création de l'identifiant de session (voir login_user pour les 'explications')
            $smart_sess = md5($udetails['user_session_key'].$this->sess_salt);
            
			$db->delete(tbl("sessions"),array("session","session_string"),array($sess->id,"guest"));
			$sess->add_session($userid,'smart_sess',$smart_sess);

			//Setting Vars
			$this->userid = $udetails['userid'];
			$this->username = $udetails['username'];
			$this->level = $udetails['level'];

			//Updating User last login , num of visist and ip
			$db->update(tbl('users'),
						array('num_visits', 'last_logged', 'ip'),
						array('|f|num_visits+1', NOW(), $_SERVER['REMOTE_ADDR']),
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

		# CAS Logout
		$auth_cas = new AuthCAS();
		if($auth_cas->logged_in()) $auth_cas->logout('', FALSE);
	}







/* ************************************************************************
	TEMPLATE
		header.html
************************************************************************ */

	<div class='login_utc'>
	{if !$userquery->login_check('',true)}
			<a href="{link name='login_cas'}">{lang code='login'}</a>
	{else}
    	<span class='username'>Utilisateur : {$userquery->username}</span>

      {if $userquery->login_check('',true) and $has_upload_rights}
     	<a href="{link name='upload'}">Upload video</a>
     	{/if}

      {get_videos assign=user_has_videos limit=1 order="date_added ASC" user=$u.userid show_hidden=1}
      {if $user_has_videos}
      <a href='{$baseurl}/user_videos.php'>{lang code='my_videos'}</a>
      {/if}
      <a href="{link name='logout'}">{lang code='logout'}</a></div>
    {/if}
    </div><!-- login_utc end -->

    <div class="login_con clearfix">
        <div class="user_login_block clearfix">
        {if !$userquery->login_check('',true)}
            <a class='utc' href="{link name='login_cas'}">Login UTC</a>
            <hr/>











?>
