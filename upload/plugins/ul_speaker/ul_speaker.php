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
 
// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('UL_SPEAKER_BASE',basename(dirname(__FILE__)));
define('UL_SPEAKER_DIR',PLUG_DIR.'/'.UL_SPEAKER_BASE);
define('UL_SPEAKER_URL',PLUG_URL.'/'.UL_SPEAKER_BASE);
define('UL_SPEAKER_ADMIN_DIR',UL_SPEAKER_DIR.'/admin');
define('UL_SPEAKER_ADMIN_URL',UL_SPEAKER_URL.'/admin');
define("UL_SPEAKER_EDITPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".UL_SPEAKER_BASE."/admin&file=edit_speaker.php");
assign("ul_speaker_editpage",UL_SPEAKER_EDITPAGE_URL);
define("UL_SPEAKER_MANAGEPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".UL_SPEAKER_BASE."/admin&file=manage_speakers.php");
assign("ul_speaker_managepage",UL_SPEAKER_MANAGEPAGE_URL);
define("UL_SPEAKER_LINKPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".UL_SPEAKER_BASE."/admin&file=link_speaker.php");
assign("ul_speaker_linkpage",UL_SPEAKER_LINKPAGE_URL);
require UL_SPEAKER_DIR.'/speaker_class.php';

// Anchor used to display speakers into a video description
if(!function_exists('speaker_list')){
	
	function speaker_list($data){
		global $speakerquery;
		$data["selected"]="yes";
		$spk=$speakerquery->get_speaker_and_roles($data);
		$str='<strong>'.lang('speakers').'</strong>: ';
		foreach ($spk as $sp) {
			$str.= $sp['firstname'] .' '. $sp['lastname'] . ' ['. $sp['description'].'], ';
		}
		$str=substr($str,0,-2);
		echo $str;	
	}
	// use {ANCHOR place="speaker_list" data=$video} to display the formatted list above
	register_anchor_function('speaker_list','speaker_list');
	/*if(test())
		register_custom_form_field(test());*/
}	

// NewEntry for video administration menu
function addLinkSpeakerMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.UL_SPEAKER_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("speaker_link").'</a></li>';
}
	
// Add entries for the plugin in the administration pages
add_admin_menu(lang('speakers'),lang('add_new_speaker'),'add_speaker.php',UL_SPEAKER_BASE.'/admin');
add_admin_menu(lang('speakers'),lang('manage_speakers'),'manage_speakers.php',UL_SPEAKER_BASE.'/admin');
$cbvid->video_manager_link[]='addLinkSpeakerMenuEntry';
	
?>