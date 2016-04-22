<?php
/**
 * @since : 2015
 * @author : Saqib Razzaq
 */

// Pages and Sub Pages
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'CB Captcha');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'reCaptcha Key');
}

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('admin_access');
$pages->page_redir();

if(isset($_POST['update']))
{
	$text = mysql_clean($_POST['recaptcha_key']);

	function update_recaptcha_key($text)
	{
		global $db;
		$text = $text;
		$db->Execute("UPDATE ".tbl("the_captcha")." SET the_key='$text'");
	}

	update_recaptcha_key($text);
	$msg = e("Key has been updated",'m');
}

global $db;
$ann_array = $db->_select('SELECT * FROM '.tbl("the_captcha"));

if(is_array($ann_array))
assign('recaptcha_key', $ann_array[0]['the_key']);
else
assign('an', '');

subtitle("Add Key");

template_files(PLUG_DIR.'/cb_captcha/cb_captcha_admin.html');


?>