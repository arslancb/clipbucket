<?php
/**
 Name : Signup Class
 ****************************************************
 Don Not Edit These Classes , It May cause your script 
 not to run properly
 This source file is subject to the ClipBucket End-User 
 License Agreement, available online at:
 http://clip-bucket.com/cbla
 By using this software, you acknowledge having read this 
 Agreement and agree to be bound thereby.
 ****************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 *****************************************************
*/
class signup {
	
	function load_signup_fields($default=NULL)
	{
		global $LANG,$user_signup_fields,$Cbucket;;
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
		 *       anchor_before [before after field]
		 *       anchor_after [anchor after field]
		 *      )
		 */
		 if(empty($default))
		 {
			 $username = $_POST['username'];
			 $email = $_POST['email'];
			 $dcountry = $_POST['country'];
			 $dob = $_POST['dob'];
		 }else{
			 $username = $default['username'];
			 $email = $default['email'];
			 $dcountry = $default['country'];
			 $dob = $default['dob'];
		 }
		 $dob =  $dob ? date("d M, Y",strtotime($dob)) : '14 Oct, 1989';
		 
		 $user_signup_fields = array
		 (
		  'username' => array(
							  'title'=> $LANG['username'],
							  'type'=> "textfield",
							  'name'=> "username",
							  'id'=> "username",
							  'value'=> $username,
							  'hint_2'=> $LANG['user_allowed_format'],
							  ),
		  'email' => array(
							  'title'=> $LANG['email'],
							  'type'=> "textfield",
							  'name'=> "email",
							  'id'=> "email",
							  'value'=> $email,
							  ),
		  'password' => array(
							  'title'=> $LANG['password'],
							  'type'=> "password",
							  'name'=> "password",
							  'id'=> "password",
							  ),
		  'cpassword' => array(
							  'title'=> $LANG['user_confirm_pass'],
							  'type'=> "password",
							  'name'=> "cpassword",
							  'id'=> "cpassword",
							  ),
		  'country'	=> array(
							 'title'=> $LANG['country'],
							 'type' => 'dropdown',
							 'value' => $Cbucket->get_countries(iso2),
							 'id'	=> 'country',
							 'name'	=> 'country',
							 'checked'=> $dcountry
							 ),
		  'gender' => array(
							'title' => $LANG['gender'],
							'type' => 'radiobutton',
							'name' => 'gender',
							'id' => 'gender',
							'value' => array('Male'=>$LANG['male'],'Female'=>$LANG['female']),
							'sep'=> '&nbsp;',
							'checked'=>'female',
							),
		  'dob'	=> array(
						 'title' => $LANG['user_date_of_birth'],
						 'type' => 'textfield',
						 'name' => 'dob',
						 'id' => 'dob',
						 'class'=>'date_field',
						 'anchor_after' => 'date_picker',
						 'value'=> $dob,
						 )
		  );
		 
		 return $user_signup_fields;
	}
	
	
	//Creating DOB field
	
	
	//Duplicate User Check
	function duplicate_user($name){
		$myquery =  new myquery();
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
      $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
      if (eregi($pattern, $email)){
         return true;
      }
      else {
         return false;
      }   
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
	
	//Validate Admin Member
	function Admin_Add_User(){
		global $LANG,$stats;
		$uname 		= mysql_clean($_POST['username']);
		$email		= mysql_clean($_POST['email']);
		$pass 		= pass_code(mysql_clean($_POST['password']));
		$fname		= mysql_clean($_POST['fname']);
		$lname		= mysql_clean($_POST['lname']);
		$gender		= mysql_clean($_POST['gender']);
		$level		= mysql_clean($_POST['level']);
		$dob		= mysql_clean($_POST['dob']);
		$ht			= mysql_clean($_POST['hometown']);
		$city 		= mysql_clean($_POST['city']);
		$country 	= $_POST['country'];
		$zip		= mysql_clean($_POST['zip']);
		$active		= $_POST['active'];
		
		if(empty($uname)){
		$msg[] = e($LANG['usr_uname_err']);
		}
		if($this->duplicate_user($uname)){
		$msg[] = e($LANG['usr_uname_err2']);
		}
		if(!$this->isValidUsername($uname)){
			$msg[] = e($LANG['usr_uname_err3']);
		}
		if(empty($_POST['password'])){
		$msg[] = e($LANG['usr_pass_err2']);
		}
		if(empty($email)){
		$msg[] = e($LANG['usr_email_err1']);
		}elseif(!$this->isValidEmail($email)){
		$msg[] = e($LANG['usr_email_err2']);
		}
		if($this->duplicate_email($email)){
		$msg[] = e($LANG['usr_email_err3']);
		}
		if(!empty($zip) && !is_numeric($zip)){
		$msg[] = e($LANG['usr_pcode_err']);
		}
		
		if(!$this->is_username($uname))
			$msg[] = 'Username is not valid';
		
		$dob = strtotime($dob) ;
		if(date("Y",$dob) < 1960 || date("Y",$dob) > date("Y"))
		   $msg[] = "Please enter valid date of birth";
		   
		$dob = date('Y-m-d',strtotime($dob));
		
			if(empty($msg)){
			if(!mysql_query("INSERT INTO users (username,password,email,first_name,last_name,sex,level,dob,hometown,city,country,zip,usr_status)
		VALUES('".$uname."','".$pass."','".$email."','".$fname."','".$lname."','".$gender."','".$level."','".$dob."','".$ht."','".$city."','".$country."','".$zip."','".$active."')")) die(mysql_error());
			$stats->UpdateUserRecord(1);
			redirect_to($_SERVER['PHP_SELF'].'?msg='.urlencode($LANG['usr_add_succ_msg']));
			}
		
		return $msg;
	
	}
	
	
	//UPDATE USER
	function Admin_Edit_User(){
	global $LANG;
	$email		= mysql_clean($_POST['email']);
	$fname		= mysql_clean($_POST['fname']);
	$lname		= mysql_clean($_POST['lname']);
	$gender		= mysql_clean(@$_POST['gender']);
	$level		= mysql_clean($_POST['level']);
	$ht			= mysql_clean($_POST['hometown']);
	$city 		= mysql_clean($_POST['city']);
	$country 	= $_POST['country'];
	$zip		= mysql_clean($_POST['zip']);
	$email_inuse = $_POST['email_inuse'];
	
	if($_GET['userid'] == 1){
		if($_SESSION['superadmin'] == ''){
		$msg[] = "You Are Not Allowed To Change 'Super Admin' Details";
		}
	}
	if(empty($email)){
	$msg[] = e($LANG['usr_email_err1']);
	}elseif(!$this->isValidEmail($email)){
	$msg[] = e($LANG['usr_email_err2']);
	}
		if($email !== $email_inuse){
		if($this->duplicate_email($email)){
		$msg[] = e($LANG['usr_email_err3']);
		}
		}
		
	if(!empty($zip) && !is_numeric($zip)){
	$msg[] = e($LANG['usr_pcode_err']);
	}
	
	if(!empty($_POST['pwrd'])){
		$pass 		= pass_code($_POST['pwrd']);
		$cpass		= pass_code($_POST['cpwrd']);
			if($pass != $cpass){
				$msg[] = e($LANG['usr_cpass_err1']);
			}else{
				$pass_Query = "password = '".$pass."',";
			}
	}
		if(empty($msg)){
		if(!mysql_query("UPDATE users Set
		email	 	= '".$email."',
		$pass_Query
		first_name	= '".$fname."',
		last_name	= '".$lname."',
		sex			= '".$gender."',
		level		= '".$level."',
		hometown	= '".$ht."',
		city		= '".$city."',
		country		= '".$country."',
		zip			= '".$zip."'
		Where userid = '".$_GET['userid']."'"))die(mysql_error());
		redirect_to($_SERVER['PHP_SELF'].'?msg='.urlencode($LANG['usr_upd_succ_msg']).'&userid='.$_GET['userid']);
		}

	return $msg;
	
	}
	
	//This Function Is Used To Check Regiseration is allowed or not
	function Registration(){
			if(ALLOW_REGISTERATION == 1 ){
			return true;
			}else{
			return false;
			}
	}
			
	//Validate Form Fields And Add Member
	
	function SignUpUser(){
	global $row,$LANG,$stats;
	if($this->Registration()){
	$uname 		= mysql_clean($_POST['username']);
	$email		= mysql_clean($_POST['email']);
	$pass 		= pass_code(mysql_clean($_POST['password']));
	$cpass 		= pass_code(mysql_clean($_POST['cpassword']));
	$gender		= mysql_clean($_POST['gender']);
	$level		= mysql_clean($_POST['level']);
	$dob		= mysql_clean($_POST['dob']);
	$country 	= mysql_clean($_POST['country']);
	$verify		= clean($_POST['verification']);
	$agree		= mysql_clean($_POST['agree']);
	$signuo_ip	= $_SERVER['REMOTE_ADDR'];
	$avcode		= RandomString(10);
	
	//Check User
	if(empty($uname)){
	$msg[] = e($LANG['usr_uname_err']);
	}elseif(!$this->isValidUsername($uname)){
	$msg[] = e($LANG['usr_uname_err3']);
	}
	if($this->duplicate_user($uname)){
	$msg[] = e($LANG['usr_uname_err2']);
	}
	
	//Check Password
	if(empty($_POST['password']) || empty($_POST['cpassword'])){
	$msg[] = e($LANG['usr_pass_err2']);
	}
	if($pass != $cpass){
	$msg[] = e($LANG['usr_pass_err3']);
	}
	
	//Check Email
	if(empty($email)){
	$msg[] = e($LANG['usr_email_err1']);
	}elseif(!$this->isValidEmail($email)){
	$msg[] = e($LANG['usr_email_err2']);
	}
	if($this->duplicate_email($email)){
	$msg[] = e($LANG['usr_email_err3']);
	}
	
	//Checking Date Of Birth
	$dob = strtotime($dob) ;
	if(date("Y",$dob) < 1960 || date("Y",$dob) > date("Y")-5)
	   $msg[] = "Please enter valid date of birth";
	   
	$dob = date('Y-m-d',$dob);
	
	//Check AgreeMent
	if(empty($agree)){
	$msg[] = e($LANG['usr_ament_err']);
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
			if(EMAIL_VERIFICATION == '1'){
			$usr_status = 'ToActivate';
			}else{
			$usr_status = 'Ok';
			}
		if(!mysql_query("INSERT INTO users (username,password,email,first_name,last_name,sex,level,dob,hometown,city,country,zip,avcode,signup_ip,usr_status)
	VALUES('".$uname."','".$pass."','".$email."','".$fname."','".$lname."','".$gender."','".$level."','".$dob."','".$ht."','".$city."','".$country."','".$zip."','".$avcode."','".$_SERVER['REMOTE_ADDR']."','".$usr_status."')")) die(mysql_error());
	
			
			$stats->UpdateUserRecord(1);
			$username 	= $uname;
			$password 	= mysql_clean($_POST['password']);
			$cur_date	= date("m-d-Y");
			$baseurl	= BASEURL;
			$title		= TITLE;
			$to			= $email;
			
			//Send EMail For Email Verification
			
			if(EMAIL_VERIFICATION == '1'){
			$from		= SUPPORT_EMAIL;
			
			require_once(BASEDIR.'/includes/email_templates/email_verify.template.php');
			require_once(BASEDIR.'/includes/email_templates/email_verify.header.php');
			send_email($from,$to,$subj,nl2br($body));
			
			}else{
			//Send Welcome Email
			$subj 		= $LANG['welcome'].' '.$username.' to '.$title;
			$from		= WELCOME_EMAIL;
			require_once(BASEDIR.'/includes/email_templates/welcome_message.template.php');
			require_once(BASEDIR.'/includes/email_templates/welcome_message.header.php');
			send_email($from,$to,$subj,nl2br($body));

			}
		//$userquery 	= new userquery();
		//$userquery->userlogin($uname,$pass);
		$msg = 'success';
		}
	
		}else{
		$msg = e($LANG['usr_reg_err']);
		}
	return $msg;
		
	}
	
	
}
?>