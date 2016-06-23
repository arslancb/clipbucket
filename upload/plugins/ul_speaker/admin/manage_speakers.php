<?php
require UL_SPEAKER_DIR.'/speaker_class.php';
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
	define('SUB_PAGE', 'Manage Speakers');
}


// Run after a post action called 'delete_speaker'
if (isset($_GET['delete_speaker'])) {
	$delspeaker = mysql_clean($_GET['delete_speaker']);
	$speakerquery->delete_speaker($delspeaker);
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
	$filtercond=" firstname like '%".$_POST['firstname']."%' AND lastname like '%".$_POST['lastname']."%' ";
	assign('speakfirstname',$_POST['firstname']);
	assign('speaklastname',$_POST['lastname']);
	assign('showfilter',true);
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


template_files('manage_speakers.html',UL_SPEAKER_ADMIN_DIR);
?>