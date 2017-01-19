<?php

/**
 *************************************************************
 | Copyright (c) 2007-2017 Clip-Bucket.com. All rights reserved.
 | @ Author	   : Arslan Hassan								
 | @ Software  : ClipBucket
 | @ Modified : { January 10th, 2017 } { Saqib Razzaq } { Updated copyright date }
 *************************************************************
*/

	define("THIS_PAGE",'403');
	require 'includes/config.inc.php';

	if(file_exists(LAYOUT."/403.html")) {
		template_files('403.html');
		$videos = $cbvid->get_videos(array("order"=>"date_added DESC","limit"=>4));
		assign('videos',$videos);
	} else {
		$data = "403_error";
		if(has_access('admin_access')) {
			e(sprintf(lang("err_warning"),"403","http://docs.clip-bucket.com/?p=154"),"w");		
		}
		e(lang($data));
		
	}

	display_it();

?>
