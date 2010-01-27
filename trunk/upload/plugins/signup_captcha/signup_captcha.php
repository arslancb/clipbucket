<?php

/*
Plugin Name: Signup Captcha
Description: Security Captcha for signup form
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/
													   


if(!function_exists("signup_captcha"))
{
	require "captcha/class.img_validator.php";
	
	function signup_captcha()
	{
		$rand_id = RandomString(3);
		return '<img src="'.PLUG_URL.'/signup_captcha/captcha.php" border=1 name="captcha" id="captcha_img_'.$rand_id.'"/><br />
               <a href="javascript:void(0)" onclick="javascript:reloadImage(\''.PLUG_URL.'/signup_captcha/captcha.php\',\'captcha_img_'.$rand_id.'\');"> Refresh</a>';
	}
	
	$signup_captcha['signup_captcha'] = 
	array(
		  'title'=> 'Varification Code',
		  'type'=> "textfield",
		  'name'=> "vcode",
		  'id'=> "vcode",
		  'required'=>'yes',
		  'validate_function'=>'signup_captcha_check',
		  'anchor_after'=>'signup_captcha',
		  'invalid_err'=>lang('usr_ccode_err')
		  );
		  
	
	function signup_captcha_check($val)
	{
		$img = new img_validator();
		return $img->checks_word($val);
	}
	
	register_anchor(signup_captcha(),"signup_captcha");
	//register_signup_field($signup_captcha);
	register_cb_captcha('signup_captcha','signup_captcha_check',TRUE);
}
?>