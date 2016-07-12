<?php
/*
Plugin Name: Video Date Created
Description: Retrieves "datecreated" field and displays it in thumb.php
Author: YB
Author Website: http://semm.univ-lille1.fr/
ClipBucket Version: 2.8
Version: 1.0
Website: http://clip-bucket.com/plugin-page
*/
if(!function_exists("video_datecreated")) {
	function video_datecreated($data){
		global $db;
		$disc = $db->_select("SELECT videoid, datecreated FROM ".tbl("video")." WHERE videoid = $data");
		if(!empty($disc[0]['datecreated']))
			return $disc[0]['datecreated'];
	}
	register_anchor_function("video_datecreated","datecreated");
}
?>