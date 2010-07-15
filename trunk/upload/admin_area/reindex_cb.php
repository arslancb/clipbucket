<?php

/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com
 | @ File       : Sometime stats of Clipbucket get messed up.
 | This file will re-index all stats of videos, users and 
 | groups to provide most accurate results. 					
 ***************************************************************
*/
 
//including config file..
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

$vtbl = tbl("video");
$utbl = tbl("users");
$gtbl = tbl("groups");

//Reindex CB Videos
if(isset($_POST['index_vids'])) {
	$videos = get_videos(array("active"=>"yes","status"=>"Successful"));
	$total_videos = get_videos(array("count_only"=>true,"active"=>"yes","status"=>"Successful"));
	$percent = number_format(50 * $total_videos / 100);	
}

//Reindex CB Users
if(isset($_POST['index_usrs'])) {
	$users = get_users(array("usr_status"=>"Ok"));
	$total_users = get_users(array("count_only"=>true,"usr_status"=>"Ok"));
	$percent = $cbindex->percent(25,$total_users);
	$cond = '';
	
	for($i=0;$i<$percent;$i++) {
		$vparams = array("user"=>$users[$i]['userid'],"video_count"=>true);
		$video_count = $cbindex->count_index("user",$vparams);
		if(!empty($cond))
			$cond .= ", ";
		$cond .= "$utbl.total_videos = $video_count";	
			
		$cparams = array("user"=>$users[$i]['userid'],"comment_count"=>true);
		$comment_count = $cbindex->count_index("user",$cparams);
		if(!empty($cond))
			$cond .= ", ";
		$cond .= "$utbl.total_comments = $comment_count";
		
		$cbindex->update_index("user",array("values"=>$cond,"user"=>$users[$i]['userid']));
		
		// After each loop we will empty $cond
		// Why ? If you dont do this it will
		// start to concat every cond. 
		$cond = '';	
			
	}
	

}

//Reindex CB Grous
if(isset($_POST['index_gps'])) {
		
}

subtitle("Re-index Clipbucket");		
template_files('reindex_cb.html');
display_it(); 
?>