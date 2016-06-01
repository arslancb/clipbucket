<?php
/*
 Plugin Name: Video Spreaker
 Description: This plugin is used to attach a list of video speaker to a video
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2
 Version: 1.0
 Website: http://clip-bucket.com/plugin-page
 */
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


if(!function_exists('video_speaker_plugin')){
	
	function video_speaker_plugin(){
		echo '<div style="background-color:#F7F7F7; border:1px solid #999; padding:5px; margin:5px; text-align:center">';
		echo "My Test Announcement Goes here...";
		echo '</div>';	
	}
	function test(){
		$tab= array("toto" => array("name"=>"toto","truc"=>"contenu de truc"));
		return $tab;
	}
	

	register_anchor_function('video_speaker_plugin','global');
	if(test())
		register_custom_form_field(test());
	
	
	add_admin_menu('Speakers','Add new speaker','add_speaker.php',UL_SPEAKER_BASE.'/admin');
	add_admin_menu('Speakers','Manage speakers','manage_speakers.php',UL_SPEAKER_BASE.'/admin');
}
	
?>