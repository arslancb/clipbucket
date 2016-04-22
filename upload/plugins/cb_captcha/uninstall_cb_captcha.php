<?php
//Function used to uninstall Plugin
	function un_install_cb_captcha()
	{
		global $db;
		$db->Execute(
		'DROP TABLE '.tbl("captcha").''
		);
	}
	
	un_install_cb_captcha();
?>