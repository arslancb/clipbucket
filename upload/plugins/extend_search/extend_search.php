<?php
/*
 Plugin Name: Extended Search
 Description: This plugin will overwrite the video-class.php to add search on other fields than title and tags.
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2.8
 Version: 1.0
 Website:
 */
 
// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('EXTEND_SEARCH_BASE',basename(dirname(__FILE__)));
define('EXTEND_SEARCH_DIR',PLUG_DIR.'/'.EXTEND_SEARCH_BASE);
define('EXTEND_SEARCH_URL',PLUG_URL.'/'.EXTEND_SEARCH_BASE);
require_once EXTEND_SEARCH_DIR.'/extend_video_class.php';
require_once EXTEND_SEARCH_DIR.'/multi_categories_class.php';

/**
 * This global variable will replace $cbucket search object for video objects
 */ 
$cbvidext="";
$multicategories="";

/**
 * Initialize the plugin
 */
if(!function_exists('extendSearch')){
	
	function extendSearch(){
		global $cbvidext,$Cbucket;
		$cbvidext = new ExtendVideo();
		$cbvidext->init();
		
		global $multicategories;
		$multicategories = new MultiCategories();
		$multicategories->init();
		$multicategories->addSearchObject("cbvidext");
		
		$Cbucket->search_types['videos'] = "cbvidext";
	}
	
	extendSearch();
}	
	
?>