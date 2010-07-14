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
	$percent = number_format(50 * $total_users / 100);
	
	if(empty($_GET['continue_from'])) {
		for($i=0;$i<$percent;$i++) {
			$video_count = $db->count($vtbl,$vtbl.".videoid"," $vtbl.userid = ".$users[$i]['userid']." AND $vtbl.active='yes' AND $vtbl.status='Successful'");
			$db->update($utbl,array($utbl.".total_videos"),array($video_count)," $utbl.userid = ".$users[$i]['userid']."");
		}
		redirect_to("?continue_from=".$percent."");
	} else {
		$new_index = $_GET['continue_from'];		
		for($i=$new_index;$i<$total_users;$i++) {
			$video_count = $db->count($vtbl,$vtbl.".videoid"," $vtbl.userid = ".$users[$i]['userid']." AND $vtbl.active='yes' AND $vtbl.status='Successful'");
			$db->update($utbl,array($utbl.".total_videos"),array($video_count)," $utbl.userid = ".$users[$i]['userid']."");
		}
		e(lang("User Indexing Completed"),"m");	
	}

}

//Reindex CB Grous
if(isset($_POST['index_gps'])) {
		
}

subtitle("Re-index Clipbucket");		
template_files('reindex_cb.html');
display_it(); 
?>