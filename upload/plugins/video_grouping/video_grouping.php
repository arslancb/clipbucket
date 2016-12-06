<?php
/*
Plugin Name: Video Grouping
Description: This plugin will add a generic video grouping functionnality (discipline, type, category, collection, series...)
Author: Franck Rouze
Author Website: http://semm.univ-lille1.fr/
ClipBucket Version: 2.8.1
Version: 1.0
Website: http://clip-bucket.com/plugin-page
*/

// Define Plugin's uri constants
define('VIDEO_GROUPING_BASE',basename(dirname(__FILE__)));
define('VIDEO_GROUPING_DIR',PLUG_DIR.'/'.VIDEO_GROUPING_BASE);
define('VIDEO_GROUPING_URL',PLUG_URL.'/'.VIDEO_GROUPING_BASE);
define('VIDEO_GROUPING_ADMIN_DIR',VIDEO_GROUPING_DIR.'/admin');
define('VIDEO_GROUPING_ADMIN_URL',VIDEO_GROUPING_URL.'/admin');
define("SITE_MODE","/admin_area");
define("VIDEO_GROUPINGS_MANAGE_PAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".VIDEO_GROUPING_BASE."/admin/&file=manage_video_grouping.php");
assign("video_grouping_manage_page",VIDEO_GROUPINGS_MANAGE_PAGE_URL);
define("VIDEO_GROUPING_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".VIDEO_GROUPING_BASE."/admin&file=link_video_grouping.php");
assign("video_grouping_linkpage",VIDEO_GROUPING_LINKPAGE_URL);
define('VIDEO_GROUPING_UPLOAD',BASEDIR."/files/thumbs/video_grouping");
assign("video_grouping_thumbdir",BASEURL."/files/thumbs/video_grouping");

require_once VIDEO_GROUPING_DIR.'/video_grouping_class.php';

global $cbvid;

// Declare the function only once
if(!function_exists("videoGroupingMenuOutput")) {
	/**
	 * Create anchors that populate the template header menu with video grouping types menues
	 * 
	 * For each grouping type menu the function add all grouping marked as 'in_menu' 
	 *
	 * @uses
	 * 		add {ANCHOR place="groupingMenuOutput"} to display the menu extension
	 */
	function groupingMenuOutput($id){
		global $videoGrouping;
		$gt=$videoGrouping->getGroupingType($id);
		$count=$videoGrouping->countGroupingsOfType($id,false);
		$result = $videoGrouping->getGroupingsOfType($id,true);
		$txt = "";
		foreach($result as $grp){
			$str=$grp['name'];
			if (strlen($str)>30) {
				$str=substr($str, 0,30)."...";
			}
			$txt .=  "<li><a href=\"".BASEURL."/search_result.php?type=videogrouping&query=".$grp['name']."&gtype=".$grp['grouping_type_id']."\">".$str."</a></li>";
		}
		if ($count>sizeof($result)){
			$txt .=  "<li><a href=\"".BASEURL."/search_result.php?type=videogrouping&query=".$gt["name"]."\">Toutes ... </a></li>";
		}
		echo $txt;
	}
	register_anchor_function("groupingMenuOutput","groupingMenuOutput");
}

// Declare the function only once
if(!function_exists("groupingThumbOutput")) {
	/**
	 * Create anchors that display hyperlinks on video grouping in each video thumb
	 * 
	 * the groupings added are only thoses that are marked as in_thumbnail in the grouping manage page
	 * 
	 * @param int $id
	 * 		The video id
	 * @uses
	 * 		add {ANCHOR place="groupingThumbOutput" data=$video.videoid} to display the link in yout video thumbs
	 */
	function groupingThumbOutput($id){
		global $videoGrouping;
		$res = $videoGrouping->getGroupingOfVideo($id);
		$txt="";
		foreach ($res as $r){
			if ($r['in_thumb'])
				$txt.= '<a href="'.BASEURL."/search_result.php?type=videogrouping&query=".$r['id'].'&gtype='.$r['grouping_type_id'].'" style="color:'.$r['color'].';border-color:'.$r['color'].'">'.$r['name'].'</a>';	
				}
		echo $txt;
	}
	register_anchor_function("groupingThumbOutput","groupingThumbOutput");
}


/**
 * Add a label indicating the discipline of each video in the list of videos displayed in the Video Manager page
 * 
 * input $vid : the video id
 */
/*function display_name($vid){
	global $disciplinequery;
	$disc = $disciplinequery->get_discipline_of_video($vid['videoid']);
	return '<span class="label label-default">Discipline : '.$disc[0]['name'].'</span>';
}
$cbvid->video_manager_link_new[] = 'display_name';*/


/**
 * Add a new entry "Link video grouping" into the video manager menu named "Actions" associated to each video
 *
 *  input $vid : the video id
 *  output : the html string to be inserted into the menu
 */
function addLinkVideoGroupingMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.VIDEO_GROUPING_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_video_grouping").'</a></li>';
}
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("videogrouping")]=='yes')
	$cbvid->video_manager_link[]='addLinkVideoGroupingMenuEntry';



//addadmin menu
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("videogrouping")]=='yes')
	add_admin_menu(lang('video_addon'),lang("manage_video_grouping"),'manage_video_grouping.php','video_grouping/admin/');
?>