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

/**
 * Défine the Anchor to display documents into description of a video main page 
 */
if(!function_exists('external_document_list')){
	function external_document_list($data){
		global $documentquery;
		$data["selected"]="yes";
		$lnks=$documentquery->get_document_for_video($data);
		$str='';
		foreach ($lnks as $lnk) {
			$str.='<li><a target="blanck" href="'.BASEURL.'/files/documents/'.$lnk['storedfilename'].'">'.$lnk['title'] .'</a></li>'; 
		}
		echo $str;	
	}
	// use {ANCHOR place="external_document_list" data=$video} to display the formatted list above
	register_anchor_function('external_document_list','external_document_list');
}	

/**_____________________________________
 * addDocumentMenuEntry
 * ____________________________________
 * Add a new entry "Link document" into the video manager menu named "Actions" associated to each video
 * 
 *  input $vid : the video id
 *  output : the html string to be inserted into the menu
 */
function addDocumentMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.DOCUMENT_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("link_document").'</a></li>';
}
$cbvid->video_manager_link[]='addDocumentMenuEntry';

/**
 * Add entries for the plugin in the administration pages
 */
add_admin_menu(lang('video_addon'),lang('document_manager'),'manage_documents.php',DOCUMENT_BASE.'/admin');
	
?>