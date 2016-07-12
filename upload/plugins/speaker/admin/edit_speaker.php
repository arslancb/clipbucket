<?php
require_once UL_SPEAKER_DIR.'/speaker_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Speaker');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Edit Speaker');
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

//error_reporting(E_ERROR & E_WARNING & E_STRING);
//ini_set('display_errors', True);
template_files('edit_speaker.html',UL_SPEAKER_ADMIN_DIR);
?>