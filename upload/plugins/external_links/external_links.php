<?php
/*
 Plugin Name: External links
 Description: This plugin will add external links to a video.
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2
 Version: 1.0
 Website:
 */
require_once 'link_class.php';

// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('LINK_BASE',basename(dirname(__FILE__)));
define('LINK_DIR',PLUG_DIR.'/'.LINK_BASE);
define('LINK_URL',PLUG_URL.'/'.LINK_BASE);
define('LINK_ADMIN_DIR',LINK_DIR.'/admin');
define('LINK_ADMIN_URL',LINK_URL.'/admin');
define("LINK_EDITPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".LINK_BASE."/admin&file=edit_link.php");
assign("link_editpage",LINK_EDITPAGE_URL);
define("LINK_MANAGEPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".LINK_BASE."/admin&file=manage_links.php");
assign("link_managepage",LINK_MANAGEPAGE_URL);
define("LINK_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".LINK_BASE."/admin&file=link_links.php");
assign("link_linkpage",LINK_LINKPAGE_URL);


/**
 * Défine the Anchor to display links into description of a video main page 
 */
if(!function_exists('external_link_list')){
	function external_link_list($data){
		global $linkquery;
		$data["selected"]="yes";
		$lnks=$linkquery->get_link_for_video($data);
		$str='';
		foreach ($lnks as $lnk) {
			$str.='<li><a target="blanck" href="'.$lnk['url'].'">'.$lnk['title'] .'</a></li>'; 
		}
		echo $str;	
	}
	// use {ANCHOR place="external_link_list" data=$video} to display the formatted list above
	register_anchor_function('external_link_list','external_link_list');
}	

/**_____________________________________
 * addExternalLinkMenuEntry
 * ____________________________________
 * Add a new entry "Link external link" into the video manager menu named "Actions" associated to each video
 * 
 *  input $vid : the video id
 *  output : the html string to be inserted into the menu
 */
function addExternalLinkMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.LINK_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_external_link").'</a></li>';
}
$cbvid->video_manager_link[]='addExternalLinkMenuEntry';

/**
 * Add entries for the plugin in the administration pages
 */
add_admin_menu(lang('video_addon'),lang('external_links_manager'),'manage_links.php',LINK_BASE.'/admin');
	
?>