<?php
/*
Plugin Name: Define chapters
Description: Add a tab into the edit_video page that enable video chapter edition
Author: Franck Rouze
Author Website: http://semm.univ-lille1.fr/
ClipBucket Version: 2.8.1
Version: 1.0
*/

/**
 * Define Plugin's uri constants. These constants represents folders or urls
 */
define("SITE_MODE",'/admin_area');
define('CHAPTER_BASE',basename(dirname(__FILE__)));
define('CHAPTER_DIR',PLUG_DIR.'/'.CHAPTER_BASE);
define('CHAPTER_URL',PLUG_URL.'/'.CHAPTER_BASE);
define('CHAPTER_ADMIN_DIR',CHAPTER_DIR.'/admin');
define('CHAPTER_ADMIN_URL',CHAPTER_URL.'/admin');

if(!function_exists('getVTTFile')){
	/**
	 * Define the Anchor for adding vtt file into the videojs player if it exists
	 */
	function getVTTFile(){
		global $db;
		$query = " SELECT * FROM ".tbl('video')." WHERE `videoid`='".$_GET["v"]."'";
		$respons = select( $query );
		$str="";
		if (count($respons)>0) {
			$filename=$respons[0]["file_name"];
			$fileDirectory=$respons[0]['file_directory'];
			$dstFullpath=dirname(__FILE__)."/../../files/videos/".$fileDirectory."/track_".$filename.'.vtt';
			$fileurl=VIDEOS_URL.'/'.$fileDirectory."/track_".$filename.'.vtt';
			if (file_exists($dstFullpath)) {
				$str='<track kind="chapters" src="'.$fileurl.'" srclang="fr" label="French" default/>';
			}
		}
		echo $str;
	}
	// use {ANCHOR place="getVTTFile" data=$video} to add the HTML string into the file.
	register_anchor_function('getVTTFile','getVTTFile');
}

?>