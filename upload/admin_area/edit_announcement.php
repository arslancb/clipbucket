<?php
/*
 * @since : 2009
 * @author : Arslan Hassan
 */

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Global Announcement');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Edit Announcement');
}


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

global $db;
$ann_array = $db->_select('SELECT * FROM '.tbl("global_announcement"));

if(is_array($ann_array))
assign('an', $ann_array[0]['announcement']);
else
assign('an', '');

subtitle("Annoucment Manager");
template_files('edit_announcemnent.html');
display_it();

?>