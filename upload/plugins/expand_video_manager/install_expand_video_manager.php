<?php


require_once('../includes/common.php');

/**
  *	Create the database configuration table
  */
function installExpandVideoManager() {
	global $db;
	$db->Execute(
		'CREATE TABLE '.tbl("expand_video_manager").' (
		  `evm_id` int(11) NOT NULL,
		  `evm_plugin_url` varchar(255) NOT NULL,
		  `evm_zone` varchar(255) NOT NULL,
		  `evm_is_new_tab` tinyint(1) NOT NULL,
		  `evm_tab_title` varchar(100) NOT NULL,
		  PRIMARY KEY (`evm_id`)
		) 
		ENGINE=InnoDB DEFAULT CHARSET=utf8;'
	);
}

installExpandVideoManager();


?>
