<?php
/*
 Plugin Name: External links
 Description: This plugin will add external links to a video.
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2.8
 Version: 1.0
 Website:
 */
require_once 'link_class.php';
if (!$cbplugin->is_installed('common_library.php'))
	e(sprintf(lang("plugin_not_installed : %s"),"Common Library"));
else
	require_once PLUG_DIR.'/common_library/common_library.php';

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


if(!function_exists('externalLinkList')){
	/**
	 * Define the Anchor to display links into description of a video main page
	 * 
	 * @param array $data
	 * 		a dictionary containing information about the requested documents
	 * 	@see Link.getLinkForVideo() function for more details
	 */
	function externalLinkList($data){
		global $linkquery;
		$data["selected"]="yes";
		$lnks=$linkquery->getLinkForVideo($data);
		$str='';
		foreach ($lnks as $lnk) {
			$str.='<li><a target="_blank" href="'.$lnk['url'].'">'.$lnk['title'] .'</a></li>'; 
		}
		echo $str;	
	}
	// use {ANCHOR place="externalLinkList" data=$video} to display the formatted list above
	register_anchor_function('externalLinkList','externalLinkList');
}	

/**
 * Remove associate between any external links and a video
 *
 * @param int $vid
 * 		the video's id
 */
function unlinksAllLinks($vid){
	global $linkquery;
	if(is_array($vid))
		$vid = $vid['videoid'];
		$linkquery->unlinkAllLinks($vid);
}

/** Remove external links associated a video when video is deleted */
register_action_remove_video("unlinksAllLinks");


/**
 * Add a new entry "Link external link" into the video manager menu named "Actions" associated to each video
 * 
 *  @param int $vid 
 *  	the video id
 *  @return string
 *  	the html string to be inserted into the menu
 */
function addExternalLinkMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.LINK_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_external_link").'</a></li>';
}
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("links")]=='yes')
	$cbvid->video_manager_link[]='addExternalLinkMenuEntry';

/**
 * Add entries for the plugin in the administration pages
 */
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("links")]=='yes')
	add_admin_menu(lang('video_addon'),lang('external_links_manager'),'manage_links.php',LINK_BASE.'/admin');
	
?>