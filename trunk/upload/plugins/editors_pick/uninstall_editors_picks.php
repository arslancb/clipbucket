<?php
	//Function used to uninstall Plugin
	function un_install_editors_pick()
	{
		global $db;
		$db->Execute(
		'DROP TABLE '.tbl("editors_picks")
		);
	}
	
	un_install_editors_pick();
?>