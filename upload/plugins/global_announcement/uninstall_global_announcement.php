<?php
//Function used to uninstall Plugin
	function un_install_global_announcement()
	{
		global $db;
		$db->Execute(
		'DROP TABLE '.tbl("global_announcement").''
		);
	}
	
	un_install_global_announcement();
?>