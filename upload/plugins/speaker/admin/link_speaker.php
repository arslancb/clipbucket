<?php
require_once SPEAKER_DIR.'/speaker_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("speaker"));
$pages->page_redir();

/** Assigning page and subpage */
if(!defined('MAIN_PAGE'))
	define('MAIN_PAGE', lang('speakers'));
if(!defined('SUB_PAGE'))
	define('SUB_PAGE', lang('speaker_link'));

/** get video object */
$video = $cbvid->getVideo($_GET['video']);
Assign('video',$video);

/** Run after a post action called 'link_selected' (link and unlink multiple speaker to the selectedvideo) */
if(isset($_POST['link_selected'])){
	//remove unselected link from the first list
	$eh->flush();
	$cnt=count($_POST['checked_speaker']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$speakerquery->unlinkSpeaker($_POST['checked_speaker'][$id],$video['videoid']);
	}
	//add selected link from the second list
	$cnt=count($_POST['check_speaker']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$speakerquery->linkSpeaker($_POST['check_speaker'][$id],$video['videoid']);
	}
}
/** Run after a post action called 'filter' (used to filter list of speakers) */
else if(isset($_POST['filter'])){
	$filtercond=" firstname like '%".$_POST['firstname']."%' AND lastname like '%".$_POST['lastname']."%' ";
	assign('speakfirstname',$_POST['firstname']);
	assign('speaklastname',$_POST['lastname']);
	assign('showfilter',true);
}


/** Prepare page */
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
/** Getting speaker List */
$result_array['limit'] = $get_limit;
$result_array['videoid'] = $video['videoid'];
$result_array['selected'] = 'yes';
$result_array['assign'] = 'linkedspeakers';
//pr($result_array,true);
$linkedspeakers = $speakerquery->getSpeakerAndRoles($result_array);
if ($filtercond) $result_array['cond']=$filtercond;
$result_array['selected'] = 'no';
$result_array['assign'] = 'unlinkedspeakers';
$unlinkedspeakers = $speakerquery->getSpeakerAndRoles($result_array);


/** Collecting Data for Pagination */
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $speakerquery->getSpeakerAndRoles($mcount);
$total_pages = count_pages($total_rows,RESULTS);
/** Pagination */
$pages->paginate($total_pages,$page);

/** Set HTML title */
subtitle(lang('speaker_link'));

/** Set HTML template */
template_files('link_speaker.html',SPEAKER_ADMIN_DIR);
?>