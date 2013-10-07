<?php
/*
 * @since : 2009
 * @author : Arslan Hassan
 */
require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('admin_access');
$pages->page_redir();

if(isset($_POST['update']))
{
	$text = mysql_clean($_POST['text']);
	update_announcement($text);
	$msg = e("Announcement has been updated",'m');
}

subtitle("Annoucment Manager");
template_files('edit_announcemnent.html');
display_it();

?>