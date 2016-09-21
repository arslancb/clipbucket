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
	define('SUB_PAGE', lang('add_new_link'));
}

// Run after a post action called 'add_link'
if(isset($_POST['add_link'])){
	if($linkquery->add_link($_POST))	{
		e(lang("new_link_added"),"m");
		$_POST = '';
	}
}

// Run after a post action called 'search_link'
if(isset($_POST['search_link'])){
	$linkquery->search_link($_POST);	
}

//Set HTML title
subtitle(lang("add_new_link"));

template_files('add_link.html',LINK_ADMIN_DIR);
?>