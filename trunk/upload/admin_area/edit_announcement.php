<?php
/*
 * @since : 2009
 * @author : Arslan Hassahn
 */
require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

if(isset($_POST['update']))
{
	$text = mysql_clean($_POST['text']);
	update_announcement($text);
	$msg = e("Annoucment has been updated",m);
}

Assign('msg', @$msg);	
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('edit_announcemnent.html');
Template('footer.html');
?>