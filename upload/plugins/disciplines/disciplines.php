<?php
/*
Plugin Name: Disciplines
Description: Adds disciplines to videos
Author: Yannick Bonnaz / Franck Rouze
Author Website: http://semm.univ-lille1.fr/
ClipBucket Version: 2.8
Version: 1.0
Website: http://clip-bucket.com/plugin-page
*/
require_once "disciplines_class.php";

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

/**_____________________________________
 * disciplines_menu_output
 * _____________________________________
 * Add all discipline links where in_menu attribute is set to 1 to the discipline menu in the head template page. 
 */
if(!function_exists("disciplines_menu_output")) {
	function disciplines_menu_output(){
		global $disciplinequery;
		$disc = $disciplinequery->get_all_disciplines_for_menu();
		$foo = "";
		foreach($disc as $tmp){
			$url = $tmp['id'];
			//$foo .=  "<li><a href=\"".BASEURL."/discipline/".$url."\">".$tmp['name']."</a></li>";
			$foo .=  "<li><a href=\"".BASEURL."/search_result.php?type=disciplines&query=".$url."\">".$tmp['name']."</a></li>";
		}
		unset($tmp);
		echo $foo;
	}
	// use {ANCHOR place="disciplines_list"} to display the formatted list above
	register_anchor_function("disciplines_menu_output","disciplines_list");
}

/**_____________________________________
 * discipline_thumb_output
 * _____________________________________
 * Add a link to the discipline of each video thumb 
 */
if(!function_exists("discipline_thumb_output")) {
	function discipline_thumb_output($data){
		global $disciplinequery;
		$disc = $disciplinequery->get_discipline_of_video($data);
		echo '<a href="'.BASEURL."/search_result.php?type=disciplines&query=".$disc[0]['id'].'" style="color:'.$disc[0]['color'].';border-color:'.$disc[0]['color'].'">'.$disc[0]['name'].'</a>';
	}
	// use {ANCHOR place="discipline"} to display the link above
	register_anchor_function("discipline_thumb_output","discipline");
}


/**_____________________________________
 * display_name
 * _____________________________________
 * Add a label indicating the discipline of each video in the list of videos displayed in the Video Manager page
 * 
 * input $vid : the video id
 */
function display_name($vid){
	global $disciplinequery;
	$disc = $disciplinequery->get_discipline_of_video($vid['videoid']);
	return '<span class="label label-default">Discipline : '.$disc[0]['name'].'</span>';
}
$cbvid->video_manager_link_new[] = 'display_name';


/**_____________________________________
 * addLinkDisciplineMenuEntry
 * ____________________________________
 * Add a new entry "Link discipline" into the video manager menu named "Actions" associated to each video
 *
 *  input $vid : the video id
 *  output : the html string to be inserted into the menu
 */
function addLinkDisciplineMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.DISCIPLINE_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_discipline").'</a></li>';
}
$cbvid->video_manager_link[]='addLinkDisciplineMenuEntry';



//addadmin menu
add_admin_menu('Videos',lang("manage_disciplines"),'manage_disciplines.php','disciplines/admin/');
?>