<?php

/*
Plugin Name: Provides ReCaptcha for your ClipBucket Website
Description: This will enabled recaptcha for your clipbucket on variuos areas such as signup, comment, forgot password etc..
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/


include("recaptchalib.php");

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = $Cbucket->configs['reCaptcha_public_key'];
$privatekey = $Cbucket->configs['reCaptcha_private_key'];

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

function cbRecaptcha(){ global $publickey, $privatekey, $error; return recaptcha_get_html($publickey, $error);}

function validateCbRecaptcha($val=NULL)
{
	global $privatekey;
	if ($_POST["recaptcha_response_field"])
	{
		$resp = recaptcha_check_answer ($privatekey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

		if ($resp->is_valid)
				return true;
		else
				return false;
	}
}

register_cb_captcha('cbRecaptcha','validateCbRecaptcha',false);
register_anchor('; Recaptcha.reload ();','onClickAddComment');
add_header(PLUG_DIR.'/recaptcha/reCaptcha_header.html');
?>