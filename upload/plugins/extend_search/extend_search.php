<?php
/*
 Plugin Name: Extended Search
 Description: This plugin will overwrite the video-class.php to add search on other fields than title and tags.
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2
 Version: 1.0
 Website:
 */
 
// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('EXTEND_SEARCH_BASE',basename(dirname(__FILE__)));
define('EXTEND_SEARCH_DIR',PLUG_DIR.'/'.EXTEND_SEARCH_BASE);
define('EXTEND_SEARCH_URL',PLUG_URL.'/'.EXTEND_SEARCH_BASE);
require EXTEND_SEARCH_DIR.'/extend_video_class.php';

/**
 * This global variable will replace $cbucket search object for video objects
 */ 
$cbvidext="";

/**
 * Initialize the plugin
 */
if(!function_exists('extend_search')){
	
	function extend_search(){
		global $cbvidext;
		$cbvidext = new extend_video();
		$cbvidext->init();
	}
	
	extend_search();
}	
	
?>