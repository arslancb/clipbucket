<?php

require_once('../includes/common.php');

//Creating Table for anncoument if not exists
function install_global_announcement()
{
	global $db;
	$db->Execute(
	'CREATE TABLE IF NOT EXISTS '.tbl("global_announcement").' (
	  `announcement` text NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;;'
	);
	
	//inserting new announcment
	$db->Execute("INSERT INTO  ".tbl('global_announcement')." (announcement) VALUES ('')");
}


//This will first check if plugin is installed or not, if not this function will install the plugin details
install_global_announcement();

?>