<?php
/*
 Plugin Name: Video Spreaker
 Description: This plugin will add a list of video speakers to a video.
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2
 Version: 1.0
 Website:
 */
require_once 'speaker_class.php';

// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('SPEAKER_BASE',basename(dirname(__FILE__)));
define('SPEAKER_DIR',PLUG_DIR.'/'.SPEAKER_BASE);
define('SPEAKER_URL',PLUG_URL.'/'.SPEAKER_BASE);
define('SPEAKER_ADMIN_DIR',SPEAKER_DIR.'/admin');
define('SPEAKER_ADMIN_URL',SPEAKER_URL.'/admin');
define("SPEAKER_MANAGEPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".SPEAKER_BASE."/admin&file=manage_speakers.php");
assign("speaker_managepage",SPEAKER_MANAGEPAGE_URL);
define("SPEAKER_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".SPEAKER_BASE."/admin&file=link_speaker.php");
assign("speaker_linkpage",SPEAKER_LINKPAGE_URL);


/**
 * Défine the Anchor to display speakers into description of a video main page 
 */
if(!function_exists('speaker_list')){
	function speaker_list($data){
		global $speakerquery;
		$data["selected"]="yes";
		$spk=$speakerquery->get_speaker_and_roles($data);
		$str='';
		foreach ($spk as $sp) {
			$url=BASEURL.'/'.'search_result.php?type=videos&query='.$sp['slug'];
			$str.='<li><a href="'.$url.'">'.$sp['firstname'] .' '. $sp['lastname'].'</a><span>,'.$sp['description'].'</span></li>'; 
		}
		echo $str;	
	}
	// use {ANCHOR place="speaker_list" data=$video} to display the formatted list above
	register_anchor_function('speaker_list','speaker_list');
}	

/**
 * Connect Speaker Plugin to extend_search plugin if extend_search is installed
 */
global $cbplugin;
if ($cbplugin->is_installed('extend_search.php')){
	require_once PLUG_DIR.'/extend_search/extend_search.php';
	global $cbvidext;
	//add tables for this plugin in extended search plugin
	$cbvidext->reqTbls[]='speaker';
	$cbvidext->reqTbls[]='speakerfunction';
	$cbvidext->reqTbls[]='video_speaker';
	//add tables associations for this plugin in extended search plugin
	$cbvidext->reqTblsJoin[]=array('table1'=>'speaker', 'field1'=>'id','table2'=>'speakerfunction','field2'=>'speaker_id');
	$cbvidext->reqTblsJoin[]=array('table1'=>'speakerfunction', 'field1'=>'id','table2'=>'video_speaker','field2'=>'speakerfunction_id');
	$cbvidext->reqTblsJoin[]=array('table1'=>'video_speaker', 'field1'=>'video_id','table2'=>'video','field2'=>'videoid');
	//add search fields for this plugin in extended search plugin
	$cbvidext->columns[]=array('table'=>'speaker', 'field'=>'firstname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR');
	$cbvidext->columns[]=array('table'=>'speaker', 'field'=>'lastname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR');
	$cbvidext->columns[]=array('table'=>'speaker', 'field'=>'slug','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR');
}

/**_____________________________________
 * addLinkSpeakerMenuEntry
 * ____________________________________
 * Add a new entry "Link speaker" into the video manager menu named "Actions" associated to each video
 * 
 *  input $vid : the video id
 *  output : the html string to be inserted into the menu
 */
function addLinkSpeakerMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.SPEAKER_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("speaker_link").'</a></li>';
}
$cbvid->video_manager_link[]='addLinkSpeakerMenuEntry';

/**
 * Add entries for the plugin in the administration pages
 */
add_admin_menu(lang('video_addon'),lang('speaker_manager'),'manage_speakers.php',SPEAKER_BASE.'/admin');
	
?>