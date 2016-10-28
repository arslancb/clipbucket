<?php
/*
Plugin Name: Disciplines
Description: Adds disciplines to videos
Author: Yannick Bonnaz / Franck Rouze
Author Website: http://semm.univ-lille1.fr/
ClipBucket Version: 2.8
Version: 1.0
Website: 
*/
require_once "disciplines_class.php";
require_once PLUG_DIR.'/common_library/common_library.php';

// Define Plugin's uri constants
define('DISCIPLINE_BASE',basename(dirname(__FILE__)));
define('DISCIPLINE_DIR',PLUG_DIR.'/'.DISCIPLINE_BASE);
define('DISCIPLINE_URL',PLUG_URL.'/'.DISCIPLINE_BASE);
define('DISCIPLINE_ADMIN_DIR',DISCIPLINE_DIR.'/admin');
define('DISCIPLINE_ADMIN_URL',DISCIPLINE_URL.'/admin');
define("SITE_MODE","/admin_area");
define("DISCIPLINES_EDIT_PAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".DISCIPLINE_BASE."/admin/&file=manage_disciplines.php");
assign("disciplines_edit_page",DISCIPLINES_EDIT_PAGE_URL);
define("DISCIPLINE_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".DISCIPLINE_BASE."/admin&file=link_discipline.php");
assign("discipline_linkpage",DISCIPLINE_LINKPAGE_URL);
assign("discipline_thumbdir",BASEURL."/files/thumbs/disciplines");

//cbvid = current video metadata array
global $cbvid;

if(!function_exists("disciplinesMenuOutput")) {
	/**
	 * Add all discipline links where in_menu attribute is set to 1 to the discipline menu in the head template page.
	 */
	function disciplinesMenuOutput(){
		global $disciplinequery;
		$disc = $disciplinequery->getAllDisciplinesForMenu();
		$foo = "";
		foreach($disc as $tmp){
			$url = $tmp['id'];
			$foo .=  "<li><a href=\"".BASEURL."/search_result.php?type=disciplines&query=".$url."\">".$tmp['name']."</a></li>";
		}
		unset($tmp);
		echo $foo;
	}
	// use {ANCHOR place="disciplinesMenuOutput"} to display the formatted list above
	register_anchor_function("disciplinesMenuOutput","disciplinesMenuOutput");
}

if(!function_exists("disciplineThumbOutput")) {
	/**
	 * Add a link to the discipline of each video thumb
	 * 
	 * @param int $vid
	 * 		The video id
	 */
	function disciplineThumbOutput($vid){
		global $disciplinequery;
		$disc = $disciplinequery->getDisciplineOfVideo($vid);
		echo '<a href="'.BASEURL."/search_result.php?type=disciplines&query=".$disc[0]['id'].'" style="color:'.$disc[0]['color'].';border-color:'.$disc[0]['color'].'">'.$disc[0]['name'].'</a>';
	}
	// use {ANCHOR place="disciplineThumbOutput"} to display the link above
	register_anchor_function("disciplineThumbOutput","disciplineThumbOutput");
}


/**
 * Add a label indicating the discipline of each video in the list of videos displayed in the Video Manager page
 * 
 * @param int $vid
 * 		The video id
 * @return string
 * 		The HTML span containing the name of the discipline
 */
function disciplineNameIndicator($vid){
	global $disciplinequery;
	$disc = $disciplinequery->getDisciplineOfVideo($vid['videoid']);
	return '<span class="label label-default">Discipline : '.$disc[0]['name'].'</span>';
}
$cbvid->video_manager_link_new[] = 'disciplineNameIndicator';


/**
 * Add a new entry "Link discipline" into the video manager menu named "Actions" associated to each video
 *
 * @param int $vid
 * 		The video id
 * @return string
 *  	the HTML string to be inserted into the menu
 */
function addLinkDisciplineMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.DISCIPLINE_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_discipline").'</a></li>';
}
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("discipline")]=='yes')
	$cbvid->video_manager_link[]='addLinkDisciplineMenuEntry';



//addadmin menu
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("discipline")]=='yes')
	add_admin_menu(lang('video_addon'),lang("manage_disciplines"),'manage_disciplines.php','disciplines/admin/');
?>