<?php
require_once SPEAKER_DIR.'/speaker_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('speakers'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('add_new_speaker'));
}

// Run after a post action called 'add_speaker'
if(isset($_POST['add_speaker'])){
	if($speakerquery->add_speaker($_POST))	{
		e(lang("new_speaker_added"),"m");
		$_POST = '';
	}
}

// Run after a post action called 'search_speaker'
if(isset($_POST['search_speaker'])){
	$speakerquery->search_speaker($_POST);	
}

//Set HTML title
subtitle(lang("add_new_speaker"));

template_files('add_speaker.html',SPEAKER_ADMIN_DIR);
?>