<?php
require UL_SPEAKER_DIR.'/speaker_class.php';
$userquery->admin_login_check();
// Controle de permission probablement non fonctionnel sur les plugins
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Speaker');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Add Speaker');
}


if(isset($_POST['add_speaker'])){
	if($speakerquery->add_speaker($_POST))	{
		e(lang("new_speaker_added"),"m");
		$_POST = '';
	}
}

if(isset($_POST['search_speaker'])){
	$speakerquery->search_speaker($_POST);	
}

//error_reporting(E_ERROR & E_WARNING & E_STRING);
//ini_set('display_errors', True);
template_files('add_speaker.html',UL_SPEAKER_ADMIN_DIR);
?>