<?php
//Function used to uninstall Plugin
	function un_install_global_announcement()
	{
		global $db;
		$db->Execute(
		'DROP TABLE `cb_global_announcement`'
		);
	}
	
	un_install_global_announcement();
?>