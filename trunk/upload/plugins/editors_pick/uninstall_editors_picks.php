<?php
	//Function used to uninstall Plugin
	function un_install_editors_pick()
	{
		global $db;
		$db->Execute(
		'DROP TABLE '.tbl("editors_picks")
		);


		$db->Execute("ALTER TABLE ".tbl('video')." DROP `in_editor_pick` ");
	}
	
	un_install_editors_pick();
?>