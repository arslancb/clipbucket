<?php
require_once SPEAKER_DIR.'/speaker_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("speaker"));

$pages->page_redir();

/** Assigning page and subpage */
if(!defined('MAIN_PAGE'))
	define('MAIN_PAGE', lang('video_addon'));
if(!defined('SUB_PAGE'))
	define('SUB_PAGE', lang('speaker_manager'));

/**
 * Manage $_GET messages only if no POST is made.
 */
if (count($_POST)==0){
	/** Action run after a post action called 'deleteSpeaker' */
	if (isset($_GET['deleteSpeaker'])) {
		$delspeaker = mysql_clean($_GET['deleteSpeaker']);
		$speakerquery->deleteSpeaker($delspeaker);
	}
	
	/** Action run after a get action called 'editSpeaker' */
	if (isset($_GET['editSpeaker'])) {
		if (error()){
			$details=$_POST;
			$details['id']=$details['speakerid'];
		}
		else {
			$id = $_GET['editSpeaker'];
			$details = $speakerquery->getSpeakerDetails($id);
		}
		if ($details) assign('speak',$details);
		assign('showedit',true);
		assign('showfilter',false);
		assign('showadd',false);
	}
}
/** Action run after a post action called 'add_speaker' */
else if(isset($_POST['add_speaker'])){
	if($speakerquery->addSpeaker($_POST))	{
		e(lang("new_speaker_added"),"m");
		$_POST = '';
	}
}
/** Run after a post action called 'deleteSelected' (Deleting Multiple speakers) */
else if(isset($_POST['deleteSelected'])){
	$cnt=count($_POST['check_speaker']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$speakerquery->deleteSpeaker($_POST['check_speaker'][$id]);
	}
	else
		e(lang("no_speaker_selected"),"w");
}
/** Run after a post action called 'filter' (used to filter list of speakers) */
else if(isset($_POST['filter'])){
	$filtercond=" firstname like '%".$_POST['first_name']."%' AND lastname like '%".$_POST['last_name']."%' ";
	assign('speak_firstname',$_POST['first_name']);
	assign('speak_lastname',$_POST['last_name']);
	assign('showfilter',true);
	assign('showadd',false);
	assign('showedit',false);
	assign('speak',false);
}
/** Run after a post action called 'update_speaker' */
else if(isset($_POST['update_speaker'])){
	if ($speakerquery->updateSpeaker($_POST)) {
		e(lang("speaker_updated"),"m");
		$_POST = '';
		assign('showfilter',false);
		assign('showadd',false);
		assign('showedit',false);
		assign('speak',false);
	}
}



/** Prepare page */
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);

$array=[];
$result_array = $array;
/** Getting speaker List */
$result_array['limit'] = $get_limit;
if ($filtercond) $result_array['cond']=$filtercond;
//pr($result_array,true);
$speakers = $speakerquery->getSpeakers($result_array);
Assign('speakers', $speakers);

/** Collecting Data for Pagination */
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $speakerquery->getSpeakers($mcount);
$total_pages = count_pages($total_rows,RESULTS);
/** Pagination **/
$pages->paginate($total_pages,$page);


/** Set HTML title */
subtitle(lang("speaker_manager"));

/** Set HTML template */
template_files('manage_speakers.html',SPEAKER_ADMIN_DIR);
?>