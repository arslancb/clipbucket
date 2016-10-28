<?php
/*
 Plugin Name: Documents
 Description: This plugin will add documents to a video.
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2
 Version: 1.0
 Website:
 */
require_once 'document_class.php';

// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('DOCUMENT_BASE',basename(dirname(__FILE__)));
define('DOCUMENT_DIR',PLUG_DIR.'/'.DOCUMENT_BASE);
define('DOCUMENT_URL',PLUG_URL.'/'.DOCUMENT_BASE);
define('DOCUMENT_ADMIN_DIR',DOCUMENT_DIR.'/admin');
define('DOCUMENT_ADMIN_URL',DOCUMENT_URL.'/admin');
define("DOCUMENT_MANAGEPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".DOCUMENT_BASE."/admin&file=manage_documents.php");
assign("document_managepage",DOCUMENT_MANAGEPAGE_URL);
define("DOCUMENT_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".DOCUMENT_BASE."/admin&file=link_documents.php");
assign("document_linkpage",DOCUMENT_LINKPAGE_URL);
define("DOCUMENT_DOWNLOAD_DIR",BASEDIR."/files/documents");

if(!function_exists('externalDocumentList')){
	/**
	 * Define the Anchor to display documents into description of a video main page
	 */
	function externalDocumentList($data){
		global $documentquery;
		$data["selected"]="yes";
		$lnks=$documentquery->getDocumentForVideo($data);
		$str='';
		foreach ($lnks as $lnk) {
			$str.='<li><a target="_blank" href="'.BASEURL.'/files/documents/'.$lnk['storedfilename'].'">'.$lnk['title'] .'</a></li>'; 
		}
		echo $str;	
	}
	// use {ANCHOR place="externalDocumentList" data=$video} to display the formatted list above
	register_anchor_function('externalDocumentList','externalDocumentList');
}	

/**
 * Add a new entry "Link document" into the video manager menu named "Actions" associated to each video
 * 
 * @param int $vid 
 * 		the video id
 * @return string
 * 		the html string to be inserted into the menu
 */
function addDocumentMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.DOCUMENT_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_document").'</a></li>';
}
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("documents")]=='yes')
	$cbvid->video_manager_link[]='addDocumentMenuEntry';

/**
 * Add entries for the plugin in the administration pages
 */
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("documents")]=='yes')
	add_admin_menu(lang('video_addon'),lang('document_manager'),'manage_documents.php',DOCUMENT_BASE.'/admin');
	
?>