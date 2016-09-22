<?php
require_once SPEAKER_DIR.'/speaker_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('video_addon'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('speaker_manager'));
}

if (count($_POST)==0){
	// Run after a post action called 'delete_speaker'
	if (isset($_GET['delete_speaker'])) {
		$delspeaker = mysql_clean($_GET['delete_speaker']);
		$speakerquery->delete_speaker($delspeaker);
	}
	
	// Run after a get action called 'edit_speaker'
	if (isset($_GET['edit_speaker'])) {
		if (error()){
			$details=$_POST;
			$details['id']=$details['speakerid'];
		}
		else {
			$id = $_GET['edit_speaker'];
			$details = $speakerquery->get_speaker_details($id);
		}
		if ($details) assign('speak',$details);
		assign('showedit',true);
		assign('showfilter',false);
		assign('showadd',false);
	}
}

// Run after a post action called 'add_speaker'
if(isset($_POST['add_speaker'])){
	if($speakerquery->add_speaker($_POST))	{
		e(lang("new_speaker_added"),"m");
		$_POST = '';
	}
}

// Run after a post action called 'delete_selected' (Deleting Multiple speakers)
if(isset($_POST['delete_selected'])){
	$cnt=count($_POST['check_speaker']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$speakerquery->delete_speaker($_POST['check_speaker'][$id]);
	}
	else
		e(lang("no_speaker_selected"),"w");
}

// Run after a post action called 'filter' (used to filter list of speakers)
if(isset($_POST['filter'])){
	$filtercond=" firstname like '%".$_POST['first_name']."%' AND lastname like '%".$_POST['last_name']."%' ";
	assign('speak_firstname',$_POST['first_name']);
	assign('speak_lastname',$_POST['last_name']);
	assign('showfilter',true);
	assign('showadd',false);
	assign('showedit',false);
	assign('speak',false);
}

// Run after a post action called 'update_speaker'
if(isset($_POST['update_speaker'])){
	if ($speakerquery->update_speaker($_POST)) {
		e(lang("speaker_updated"),"m");
		$_POST = '';
		assign('showfilter',false);
		assign('showadd',false);
		assign('showedit',false);
		assign('speak',false);
	}
}



//Getting speaker List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
//Getting speaker List
$result_array['limit'] = $get_limit;
if ($filtercond) $result_array['cond']=$filtercond;
//pr($result_array,true);
$speakers = $speakerquery->get_speakers($result_array);
Assign('speakers', $speakers);

//Collecting Data for Pagination
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $speakerquery->get_speakers($mcount);
$total_pages = count_pages($total_rows,RESULTS);
//Pagination
$pages->paginate($total_pages,$page);


//Set HTML title
subtitle(lang("speaker_manager"));

template_files('manage_speakers.html',SPEAKER_ADMIN_DIR);
?>