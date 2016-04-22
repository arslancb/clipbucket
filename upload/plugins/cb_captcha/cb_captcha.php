<?php
/**
*	Plugin Name: CB Captcha
*	Description: Stop spam signups like a boss with latest Captcha
*	ClipBucket version: 2.7.x
*	Plugin Version: 1.0
*	@author Saqib Razzaq
*	@since 9th July, 2015 (ClipBucket 2.7.4)
*/
	define('CB_CAPTCHA',basename(dirname(__FILE__)));

	// checks user's input
	function validate_user_ans()
	{
		if (isset($_POST['signup']))
		{
			$result = $_POST['g-recaptcha-response'];

			if ($result == '')
			{
				header("Location: http://localhost/cb_svn/upload/signup.php?valid=fail");
			}
		}
	}


	if (isset($_POST['signup']))
	{
		validate_user_ans();
	}

	// displaying the form
	function the_form()
	{
		global $db;
		$key_check = $db->_select('SELECT the_key FROM '.tbl("the_captcha"));
		$the_key = $key_check[0]['the_key'];
		$site_key = $the_key;
		echo '<div class="g-recaptcha" data-sitekey='.$site_key.'></div>';
	}

	register_anchor_function("the_form", "the_form");

	add_admin_menu("CB Captcha" , "reCaptcha Key" , "cb_captcha_admin.php" , CB_CAPTCHA);

	// used for displaying message on failure of captcha
	template_files(PLUG_DIR."/cb_captcha/captcha.html");
?>