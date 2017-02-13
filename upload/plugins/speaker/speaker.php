<?php
/*
 Plugin Name: Video Speaker
 Description: This plugin will add a list of video speakers to a video with their specific role in the video.
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2.8
 Version: 1.0
 Website:
 */
require_once 'speaker_class.php';
global $cbplugin;
if (!$cbplugin->is_installed('common_library.php'))
	e(sprintf(lang("plugin_not_installed : %s"),"Common Library"));
else
	require_once PLUG_DIR.'/common_library/common_library.php';

if (!$cbplugin->is_installed('extend_search.php'))
	e(sprintf(lang("plugin_not_installed : %s"),"Extended  Search"));
else
	require_once PLUG_DIR.'/extend_search/extend_search.php';
		

/**
 * Define Plugin's uri constants. These constants represents folders or urls
 */
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


// Connect the speaker search ngine to the mulitisearch object in order to extend the relust of the video search result to speakers.
if ($cbplugin->is_installed('extend_search.php')) { 
	global $multicategories;
	$multicategories->addSearchObject("speakerquery");
}
$Cbucket->search_types['speaker'] = "speakerquery";


if(!function_exists('speakerList')){
	/**
	 * Define the Anchor to display speakers into description of a video main page
	 */
	function speakerList($data){
		global $speakerquery;
		$data["selected"]="yes";
		$spk=$speakerquery->getSpeakerAndRoles($data);
		$str='';
		foreach ($spk as $sp) {
			$url=BASEURL.'/'.'search_result.php?type=speaker&query='.$sp['slug'];
			$str.='<li><a href="'.$url.'">'.$sp['firstname'] .' '. $sp['lastname'].'</a><span>,'.$sp['description'].'</span></li>'; 
		}
		echo $str;	
	}
	// use {ANCHOR place="speakerList" data=$video} to display the formatted list above
	register_anchor_function('speakerList','speakerList');
}	


/**
 * Connect the plugin to the video manager
 * 
 * Add a new entry "Link speaker" into the video manager menu named "Actions" for each video
 * 
 *  @param CBvideo $vid 
 *  	the CBVideo object returned by the video manager when senected "Actions" on a specific video
 *  @return  string
 *  	the html string to be inserted into the menu
 */
function addLinkSpeakerMenuEntry($vid){
	$idtmp=$vid['videoid'];
	return '<li><a role="menuitem" href="'.SPEAKER_LINKPAGE_URL.'&video='.$idtmp.'">'.lang("speaker_link").'</a></li>';
}

/** Add the previous function in the list of entries into the video manager "Actions" button */
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("speaker")]=='yes')
	$cbvid->video_manager_link[]='addLinkSpeakerMenuEntry';


/**
 * Remove associate between any linked speaker's role and a video
 *
 * @param int $vid
 * 		the video's id
 */
function unlinksSpeakers($vid){
	global $speakerquery;
	if(is_array($vid))
		$vid = $vid['videoid'];
	$speakerquery->unlinkAllSpeaker($vid);
}

/** Remove speaker's associated a video when video is deleted */
register_action_remove_video("unlinksSpeakers");

/**Add entries for the plugin in the administration pages */
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("speaker")]=='yes')
	add_admin_menu(lang('video_addon'),lang('speaker_manager'),'manage_speakers.php',SPEAKER_BASE.'/admin');
		
?>