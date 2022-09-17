<?php

/*
Plugin Name: ReCaptcha V2
Description: Advanced recaptcha varification plugin integrated with google's Recaptcha V2 API
Author: Awais Fiaz
Author Website: http://clip-bucket.com/
ClipBucket Version: 2.8.x
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/

// Get a key from https://www.google.com/recaptcha/admin


define("_RECAPTCHA_V2_",basename(dirname(__FILE__)));
define("RECAPTCHA_V2_DIR",PLUG_DIR.'/'._RECAPTCHA_V2_);
define("RECAPTCHA_V2_URL",PLUG_URL.'/'._RECAPTCHA_V2_);
assign("recaptcha_v2_dir",RECAPTCHA_V2_DIR);
assign("recaptcha_v2_url",RECAPTCHA_V2_URL);

require_once("classes/reCaptchav2.class.php");

$recv2 = new reCaptchav2();
assign('recv2',$recv2);

$sitekey = $Cbucket->configs['recaptcha_v2_site_key'];
$privatekey = $Cbucket->configs['recaptcha_v2_secret_key'];

# the response from reCAPTCHA v2
$resp = null;
# the error code from reCAPTCHA v2, if any
$error = null;



function cbrecaptcha_v2(){ 
    
	global $sitekey, $privatekey, $error; 
	return "<div class='g-recaptcha' data-sitekey='".$sitekey."'></div>";
}



function validrecaptcha_v2()
{
    global $privatekey;
	$ch = curl_init(sprintf(
        'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s&remoteip=%s',
        $privatekey,
        $_POST['g-recaptcha-response'],
        $_SERVER['REMOTE_ADDR']
    ));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $resp = curl_exec($ch);
    
    curl_close($ch);

    if (($answer = json_decode($resp)) !== null) {
        return $answer->success;
    }

    return false;
}



register_cb_captcha('cbrecaptcha_v2','validrecaptcha_v2',false);
register_anchor('; Recaptcha.reload ();','onClickAddComment');
add_admin_menu("reCaptcha v2","Configurations",'recaptcha_v2_configs.php',_RECAPTCHA_V2_.'/admin');
add_admin_menu("reCaptcha v2","ReCaptcha v2 docs",'recaptchav2_doc.php',_RECAPTCHA_V2_.'/admin');

?>