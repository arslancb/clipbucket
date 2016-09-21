<?php
require_once LINK_DIR.'/link_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('external_links'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('link_edition'));
}


// Run after a post action called 'update_link'
if(isset($_POST['update_link'])){
	if ($linkquery->update_link($_POST)) {	
		e(lang("link_updated"),"m");
		$_POST = '';
	}
}
if (error()){
	$details=$_POST;
	$details['id']=$details['linkid'];
}
else {
	$id = $_GET['id'];
	$details = $linkquery->get_link_details($id);
}

if ($details){
	assign('link',$details);
}

//Set HTML title
subtitle(lang('link_edition'));

template_files('edit_link.html',LINK_ADMIN_DIR);
?>