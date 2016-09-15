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
	define('SUB_PAGE', lang('speaker_edition'));
}


// Run after a post action called 'update_speaker'
if(isset($_POST['update_speaker'])){
	if ($speakerquery->update_speaker($_POST)) {	
		e(lang("speaker_updated"),"m");
		$_POST = '';
	}
}
if (error()){
	$details=$_POST;
	$details['id']=$details['speakerid'];
}
else {
	$id = $_GET['id'];
	$details = $speakerquery->get_speaker_details($id);
}

if ($details){
	assign('speak',$details);
}

//Set HTML title
subtitle(lang('speaker_edition'));

template_files('edit_speaker.html',SPEAKER_ADMIN_DIR);
?>