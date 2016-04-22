<?php

require_once('../includes/common.php');

//Creating Table for anncoument if not exists
function install_cb_captcha()
{
	global $db;
	$db->Execute(
	'CREATE TABLE IF NOT EXISTS '.tbl("the_captcha").' (
	  `the_key` text NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;;'
	);
	
	//inserting new announcment
	$db->Execute("INSERT INTO  ".tbl('the_captcha')." (the_key) VALUES ('')");

}


//This will first check if plugin is installed or not, if not this function will install the plugin details
install_cb_captcha();

?>