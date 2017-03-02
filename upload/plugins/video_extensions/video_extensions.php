<?php
/*
 Plugin Name: Video Extensions
 Description: Add an empty video form or duplicate a video form in the video_manager, Link a video data to pending video file encoded externally. 
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2.8.1
 Version: 1.0
 Website:
 */
require_once 'video_extensions_class.php';
if (!$cbplugin->is_installed('common_library.php'))
	e(sprintf(lang("plugin_not_installed : %s"),"Common Library"));
else
	require_once PLUG_DIR.'/common_library/common_library.php';

// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('VIDEO_EXTENSIONS_BASE',basename(dirname(__FILE__)));
define('VIDEO_EXTENSIONS_DIR',PLUG_DIR.'/'.VIDEO_EXTENSIONS_BASE);
define('VIDEO_EXTENSIONS_URL',PLUG_URL.'/'.VIDEO_EXTENSIONS_BASE);
define('VIDEO_EXTENSIONS_ADMIN_DIR',VIDEO_EXTENSIONS_DIR.'/admin');
define('VIDEO_EXTENSIONS_ADMIN_URL',VIDEO_EXTENSIONS_URL.'/admin');
//define("VIDEO_EXTENSIONS_MANAGEPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".VIDEO_EXTENSIONS_BASE."/admin&file=manage_documents.php");
//assign("video_extensions_managepage",VIDEO_EXTENSIONS_MANAGEPAGE_URL);
define("VIDEO_EXTENSIONS_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".VIDEO_EXTENSIONS_BASE."/admin&file=link_pending_video.php");
assign("video_extensions_linkpage",VIDEO_EXTENSIONS_LINKPAGE_URL);
define("VIDEO_EXTENSIONS_DOWNLOAD_DIR",BASEDIR."/files/documents");


if(!function_exists('duplicateVideoData')) {
	function duplicateVideoData(){
		global $videoExtension;
		if($_GET['duplicateVideo'])	{
			$vid = mysql_clean($_GET['duplicateVideo']);
			$videoExtension->duplicateVideo($vid);
		}
	}
}

global $videoExtension;
if ($cbplugin->is_installed('common_library.php') &&
		$userquery->permission[getStoredPluginName("video_extensions")]=='yes' &&
		substr($_SERVER['SCRIPT_NAME'], -17, 17) == "video_manager.php" && $_GET['newvideo'])	{
	$videoExtension->addEmptyVideo();
}

//Calling Editor Picks Function
global $cbvid;
$cbvid->video_manager_funcs[] = 'duplicateVideoData';
//Adding Anchor Function
register_anchor_function('duplicateVideoData','duplicateVideoData');


/**
 * Add a new entry "Copy Video data" into the video manager menu named "Actions" associated to each video
 * 
 * @param int $vid 
 * 		the video id
 * @return string
 * 		the html string to be inserted into the menu
 */
function addCopyVideoMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="?duplicateVideo='.$vid['videoid'].'">'.lang("duplicate_video").'</a><li>'; 
			
			
//			href="'.VIDEO_EXTENSIONS_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_document").'</a></li>';
}
if ($cbplugin->is_installed('common_library.php') && $userquery->permission[getStoredPluginName("video_extensions")]=='yes')
	$cbvid->video_manager_link[]='addCopyVideoMenuEntry';

/**
* insert js code into the HEADER of the video_manager.php page
*/
	if ($cbplugin->is_installed('common_library.php') &&
			$userquery->permission[getStoredPluginName("video_extensions")]=='yes' &&
			substr($_SERVER['SCRIPT_NAME'], -17, 17) == "video_manager.php"){
	$Cbucket->add_admin_header(PLUG_DIR . '/video_extensions/admin/header.html', 'global');
}
		

?>