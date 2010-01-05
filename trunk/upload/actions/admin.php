<?php

/**
 * This file is used to update
 * Comment
 * Author : Arslan Hassan
 * Since : Jan 02 2009
 */
 
 
require'../includes/admin_config.php';
$userquery->admin_login_check();

$mode = $_POST['mode'];
switch($mode)
{
	case 'add_note':
	{
		$value = mysql_clean($_POST['note']);
		$myquery->insert_note($value);
		$array['note'] = mysql_clean($value);
		$array['id'] = $db->insert_id();
		
		echo json_encode($array);
	}
	break;
	case 'delete_note':
	{
		$id = mysql_clean($_POST['id']);
		$myquery->delete_note($id);
	}
	break;
}
?>
