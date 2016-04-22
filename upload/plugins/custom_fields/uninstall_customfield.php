<?php
//Function used to uninstall Plugin
	function un_install_customfield()
	{
		global $db;
		$db->Execute(
		'DROP TABLE '.tbl("custom_field")
		);
	}

	un_install_customfield();
?>