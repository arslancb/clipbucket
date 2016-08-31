<?php

	/**
	* File: Home Ajax
	* Description: ClipBucket home page will now be Ajax based to imporve page loading
	* and to enhance user experience. This file will handle all ajax requests
	* for ClipBucket's home page
	* @since: 14th March, 2016, ClipBucket 2.8.1 
	* @author: Saqib Razzaq
	* @modified: 8th April, 2016
	*/

	require '../includes/config.inc.php';
	$params = array();
	if (isset($_POST['load_type'])) {
		$load_type = $_POST['load_type'];
		if (isset($_POST['load_mode'])) {
			$load_mode = $_POST['load_mode'];
			if ($load_mode == 'featured') {
				$params['featured'] = "yes";
			}
			if ($load_mode == 'recent') {
				$params['order'] = 'date_added DESC';
			} else {
				$params['order'] = 'views';
			}
		}
		if (isset($_POST['load_limit'])) {
			$load_limit = $_POST['load_limit'];
		} else {
			$load_limit = "6";
		}
		if (isset($_POST['load_hit'])) {
			$cur_load_hit = $_POST['load_hit'];
			$start = $load_limit * $cur_load_hit - $load_limit;
		} else {
			$start = "0";
		}

		$params['limit'] = "$start,$load_limit";
		
		if ($cur_load_hit == 1) {
			$params['count_only'] = true;
			$total_vids = get_videos($params);
		}

		switch ($load_type) {
			case 'video':
				$params['count_only'] = false;
				$data = get_videos($params);
				break;
			case 'users':
				$data = get_users($params);
				break;
			case 'photos':
				$data = get_photos($params);
				break;
			case 'collections':
				$data = get_collections($params);
				break;
			
			default:
				$data = get_videos($params);
				break;
		}
		#pr($params,true);
		if (is_array($data)) {
			if (count($data) < 1) {
				$msg = array();
				$msg['notice'] = "You've reached end of list";
				#echo json_encode($msg);
				return false;
			}
			$json_string['loadhit'] = $cur_load_hit + 1;
			$json_string['array_meta'] = $data;
			if ($load_mode == 'recent') {
				$display_type = 'ajaxHome';
			} else {
				$display_type = 'featuredHome';
			}
			$quicklists = $_COOKIE['fast_qlist'];
			$clean_cookies = str_replace(array("[","]"), "", $quicklists);
        	$clean_cookies = explode(",", $clean_cookies);
			$clean_cookies = array_filter($clean_cookies);
			assign("qlist_vids", $clean_cookies);
			$count = 0;
			foreach ($data as $key => $video) {
				if ($cur_load_hit == 1 && $count == 0) {
					assign("total_vids", $total_vids);
				} else {
					assign("total_vids","");
				}
				assign("video",$video);
				assign("display_type",$display_type);
				Template('blocks/videos/video.html');
				$count = $count + 1;
			}
		}
	} else {
		$msg = array();
		$msg['error'] = "Invalid request made";
		echo json_encode($msg);
	}

	
	
	

?>