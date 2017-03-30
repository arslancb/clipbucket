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
define("VIDEO_EXTENSIONS_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".VIDEO_EXTENSIONS_BASE."/admin&file=link_pending_video.php");
assign("video_extensions_linkpage",VIDEO_EXTENSIONS_LINKPAGE_URL);


/**
 * Add labels into the Video Manager next to the video name and indicating which videos are effectivly 
 * in the video folder. This is usefull to show if all formats are encoded 
 *
 * @param array $vid
 * 		the selected video object
 * @return string
 * 		A concatenated html <span> containing which video file is present in the file system
 */
function displayExistingVideoFiles($vid){
	$str='';
	global $db;
	$result = $db->_select("SELECT videoid, file_name, file_directory FROM ".tbl("video")." WHERE videoid = ".$vid["videoid"]);

	if(is_array($result)) {
		$filename=$result[0]["file_name"];
		$file_directory=$result[0]["file_directory"];
		$videodir = BASEDIR."/files/videos/".$file_directory;
		//$str.='<span class="label label-default">'.$filename.'</span>';
		$files = glob($videodir.'/'.$filename.'*'); // get all file names
		foreach($files as $file){ // iterate files
			$path_parts = pathinfo($file);
			$ext=$path_parts['extension'];
			$parts=explode("_",$path_parts['filename']);
			if (count($parts)>1) {
				$size=$parts[count($parts)-1];
			}
			$str.= '<span  class="label label-success">'.$ext.' '.$size.'</span> ';
		}
	}
	return $str;
}
$cbvid->video_manager_link_new[] = 'displayExistingVideoFiles';


/**
 * 
 */
if(!function_exists('duplicateVideoData')) {
	function duplicateVideoData(){
		global $videoExtension;
		if($_GET['duplicateVideo'])	{
			$vid = mysql_clean($_GET['duplicateVideo']);
			$videoExtension->duplicateVideo($vid);
		}
	}
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
}
if ($cbplugin->is_installed('common_library.php') && $userquery->permission[getStoredPluginName("video_extensions")]=='yes')
	$cbvid->video_manager_link[]='addCopyVideoMenuEntry';

	
	
/**
 * Call of addEmptyVideo function if the user has the admin permissions and "newvideo" is requested
 */	
global $videoExtension;
if ($cbplugin->is_installed('common_library.php') &&
		$userquery->permission[getStoredPluginName("video_extensions")]=='yes' &&
		substr($_SERVER['SCRIPT_NAME'], -17, 17) == "video_manager.php" && $_GET['newvideo'])	{
	$videoExtension->addEmptyVideo();
}
	
/**
* insert js code into the HEADER of the video_manager.php page.
* This header will add a button "Add Empty Video" into the Video Manager main page 
*/
	if ($cbplugin->is_installed('common_library.php') &&
			$userquery->permission[getStoredPluginName("video_extensions")]=='yes' &&
			substr($_SERVER['SCRIPT_NAME'], -17, 17) == "video_manager.php"){
	$Cbucket->add_admin_header(PLUG_DIR . '/video_extensions/admin/header.html', 'global');
}
	

/**
 * Add a new entry "Link Pending video" into the video manager menu named "Actions" associated to each video
 * This command is used to connect a pending video to an existing video data.
 *
 * @param int $vid
 * 		The video id
 * @return string
 *  	the HTML string to be inserted into the menu
 */
function addLinkPendingVideoMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.VIDEO_EXTENSIONS_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_pending_video").'</a></li>';
}
if ($cbplugin->is_installed('common_library.php') && $userquery->permission[getStoredPluginName("video_extensions")]=='yes')
	$cbvid->video_manager_link[]='addLinkPendingVideoMenuEntry';



?>