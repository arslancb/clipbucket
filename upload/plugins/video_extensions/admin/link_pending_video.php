<?php
require_once VIDEO_EXTENSIONS_DIR.'/video_extensions_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("video_extensions"));
$pages->page_redir();

/** Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('videos'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('link_video'));
}

//get video object
$video = $cbvid->getVideo($_GET['video']);

// Run after a post action called 'validate' (link the selected pending video to the video)
if(isset($_POST['validate'])){
	$videoExtension->setVideoFile($video['videoid'],$_POST['checked_videos'][0]);
	header("location:".BASEURL.SITE_MODE.'/video_manager.php');
	display_it();
}
Assign('video',$video);

//Getting speaker List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];


$pendingVideos=$videoExtension->getPendingVideos();
assign("pendingVideos",$pendingVideos);
//$nbVideos=$videoExtension->getPendingVideoCount();
assign("nbPendingFiles",count($pendingVideos));


/** Set HTML title */
subtitle(lang('link_video'));

template_files('link_pending_video.html',VIDEO_EXTENSIONS_ADMIN_DIR);
?>