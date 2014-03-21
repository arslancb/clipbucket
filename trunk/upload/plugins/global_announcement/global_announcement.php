<?php
/*
	Plugin Name: Global announcement
	Description: This will let you post a global announcement on your website
	Author: Arslan Hassan
	ClipBucket Version: 1.8
	Plugin Version: 1.0
	Website: http://labguru.com/
*/



if(!function_exists('global_announcement'))
{
	
	function global_announcement()
	{
		echo '<div>'.htmlspecialchars_decode(get_announcement()).'</div>';
	}
	
	
	function get_announcement()
	{
		global $db;
		$exec = $db->Execute('SELECT * FROM '.tbl("global_announcement"));
		$ann = $exec->getrows();
		return $ann[0][0];
	}
	
	//Function used to update announcement
	function update_announcement($text)
	{
		global $db;
		$text = $text;
		$db->Execute("UPDATE ".tbl("global_announcement")." SET announcement='$text'");
	}
	
}

//Function used to get annoucment for smarty
$Smarty->register_function('get_announcement','get_announcement');
register_anchor_function(array('global_announcement'=>'global'));
add_admin_menu('Global Announcement','Edit Announcement','edit_announcement.php');
?>