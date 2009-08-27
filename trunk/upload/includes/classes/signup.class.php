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
	
	var $signup_plugins = array(); //Signup Plugins
	var $custom_signup_fields = array();
	
	function load_signup_fields($default=NULL)
	{
		global $LANG,$Cbucket;
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
			$default = $_POST;
			
		$username = $default['username'];
		$email = $default['email'];
		$dcountry = $default['country'];
		$dob = $default['dob'];

		 $dob =  $dob ? date("d-m-Y",strtotime($dob)) : '14-14-1989';
		 
		 $user_signup_fields = array
		 (
		  'username' => array(
							  'title'=> $LANG['username'],
							  'type'=> "textfield",
							  'name'=> "username",
							  'id'=> "username",
							  'value'=> $username,
							  'hint_2'=> $LANG['user_allowed_format'],
							  'db_field'=>'username',
							  'required'=>'yes',
							  'syntax_type'=> 'username',
							  'validate_function'=> 'username_check',
							  'function_error_msg' => $LANG['user_contains_disallow_err'],
							  'db_value_check_func'=> 'user_exists',
							  'db_value_exists'=>false,
							  'db_value_err'=>$LANG['usr_uname_err2']
							  ),
		  'email' => array(
							  'title'=> $LANG['email'],
							  'type'=> "textfield",
							  'name'=> "email",
							  'id'=> "email",
							  'value'=> $email,
							  'db_field'=>'email',
							  'required'=>'yes',
							  'syntax_type'=> 'email',
							  'db_value_check_func'=> 'email_exists',
							  'db_value_exists'=>false,
							  'db_value_err'=>$LANG['usr_email_err3']
							  ),
		  'password' => array(
							  'title'=> $LANG['password'],
							  'type'=> "password",
							  'name'=> "password",
							  'id'=> "password",
							  'db_field'=>'password',
							  'required'=>'yes',
							  'invalid_err'=>$LANG['usr_pass_err2'],
							  'relative_to' => 'cpassword',
							  'relative_type' => 'exact',
							  'relative_err' => $LANG['usr_pass_err3'],
							  'validate_function' => 'pass_code',
							  'use_func_val'=>true
							  ),
		  'cpassword' => array(
							  'title'=> $LANG['user_confirm_pass'],
							  'type'=> "password",
							  'name'=> "cpassword",
							  'id'=> "cpassword",
							  'required'=>'no',
							  'invalid_err'=>$LANG['usr_cpass_err'],
							  ),
		  'country'	=> array(
							 'title'=> $LANG['country'],
							 'type' => 'dropdown',
							 'value' => $Cbucket->get_countries(iso2),
							 'id'	=> 'country',
							 'name'	=> 'country',
							 'checked'=> $dcountry,
							 'db_field'=>'country',
							 'required'=>'yes',
							 ),
		  'gender' => array(
							'title' => $LANG['gender'],
							'type' => 'radiobutton',
							'name' => 'gender',
							'id' => 'gender',
							'value' => array('Male'=>$LANG['male'],'Female'=>$LANG['female']),
							'sep'=> '&nbsp;',
							'checked'=>'female',
							'db_field'=>'sex',
							'required'=>'yes',
							),
		  'dob'	=> array(
						 'title' => $LANG['user_date_of_birth'],
						 'type' => 'textfield',
						 'name' => 'dob',
						 'id' => 'dob',
						 'class'=>'date_field',
						 'anchor_after' => 'date_picker',
						 'value'=> $dob,
						 'db_field'=>'dob',
						 'required'=>'yes',
						 )
		  );
		 
		 return $user_signup_fields;
	}
	
	
	/**
	 * Function used to validate Signup Form
	 */
	function validate_form_fields($array=NULL)
	{
		$fields = $this->load_signup_fields($array);

		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);

		//Mergin Array
		$signup_fields = array_merge($fields,$this->custom_signup_fields);
				
		//Now Validating Each Field 1 by 1
		foreach($signup_fields as $field)
		{
			$field['name'] = formObj::rmBrackets($field['name']);
			
			//pr($field);
			$title = $field['title'];
			$val = $array[$field['name']];
			$req = $field['required'];
			$invalid_err =  $field['invalid_err'];
			$function_error_msg = $field['function_error_msg'];
			$length = strlen($val);
			$min_len = $field['min_length'];
			$min_len = $min_len ? $min_len : 0;
			$max_len = $field['max_length'] ;
			$rel_val = $array[$field['relative_to']];
			
			if(empty($invalid_err))
				$invalid_err =  sprintf("Invalid '%s'",$title);
			if(is_array($array[$field['name']]))
				$invalid_err = '';
				
			//Checking if its required or not
			if($req == 'yes')
			{
				if(empty($val) && !is_array($array[$field['name']]))
				{
					e($invalid_err);
					$block = true;
				}else{
					$block = false;
				}
			}
			$funct_err = is_valid_value($field['validate_function'],$val);
			if($block!=true)
			{
				//Checking Syntax
				if(!$funct_err)
				{
					if(!empty($function_error_msg))
						e($function_error_msg);
					elseif(!empty($invalid_err))
						e($invalid_err);
				}elseif(!is_valid_syntax($field['syntax_type'],$val))
				{
					if(!empty($invalid_err))
						e($invalid_err);
				}
				elseif(isset($max_len))
				{
					if($length > $max_len || $length < $min_len)
					e(sprintf(" please enter '%s' value between '%s' and '%s'",
							  $title,$field['min_length'],$field['max_length']));
				}elseif(function_exists($field['db_value_check_func']))
				{
					$db_val_result = $field['db_value_check_func']($val);
					if($db_val_result != $field['db_value_exists'])
						if(!empty($field['db_value_err']))
							e($field['db_value_err']);
						elseif(!empty($invalid_err))
							e($invalid_err);
				}elseif($field['relative_type']!='')
				{
					switch($field['relative_type'])
					{
						case 'exact':
						{
							if($rel_val != $val)
							{
								if(!empty($field['relative_err']))
									e($field['relative_err']);
								elseif(!empty($invalid_err))
									e($invalid_err);
							}
						}
						break;
					}
				}
			}	
		}
		
	}
	
	
	/**
	 * Function used to validate signup form
	 */
	function signup_user($array=NULL)
	{
		global $LANG,$db;
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		$this->validate_form_fields($array);
		
		//checking terms and policy agreement
		if($_POST['agree']!='yes')
			e($LANG['usr_ament_err']);
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
					$val = apply_func($field['clean_func'],$val);
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}
			
			// Setting Verification type
			if(EMAIL_VERIFICATION == '1'){
				$usr_status = 'ToActivate';
			}else{
				$usr_status = 'Ok';
			}
			$query_field[] = "	usr_status";
			$query_val[] = $usr_status;
			
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
			
			$query = "INSERT INTO users (";
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
			
			return $insert_id;
		}
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