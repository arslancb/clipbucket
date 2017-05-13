<?php
/*
Plugin Name: Get video field
Description: Retrieves and return any video field using it's videoid
Author: YB
Author Website: http://semm.univ-lille1.fr/
ClipBucket Version: 2.8
Version: 1.0
Website: http://clip-bucket.com/plugin-page
*/
if(!function_exists("get_video_field")) {
	function get_video_field($videoid, $fieldname){
		global $db;
		$disc = $db->_select("SELECT videoid, ". $fieldname. " FROM ".tbl("video")." WHERE videoid = $videoid");
		if(!empty($disc[0][$fieldname]))
			return $disc[0][$fieldname];
	}
	register_anchor_function("get_video_field","get_video_field");
}
?>