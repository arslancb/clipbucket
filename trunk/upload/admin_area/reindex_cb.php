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

function p_r($array)
{
	echo "<pre>";
		print_r($array);
	echo "</pre>";	
}

//Reindex Videos
if(isset($_POST['index_vids'])) {
	$videos = get_videos(array("active"=>"yes","status"=>"Successful"));
	$total_videos = get_videos(array("count_only"=>true,"active"=>"yes","status"=>"Successful"));
	$percent = number_format(50 * $total_videos / 100);	
	$i = 0;
	
	while($i < $total_videos) {
		$params = array("video_id"=>$videos[$i]['videoid'],"video_comments"=>true,"favs_count"=>true,"playlist_count"=>true);
		$indexes = $cbindex->count_index("vid",$params);
		$fields = $cbindex->extract_fields("vid",$params);
		
		$cbindex->update_index("vid",array("fields"=>$fields,"values"=>$indexes,"video_id"=>$videos[$i]['videoid']));
		
		$i++;	
	}
	
	e($total_videos." videos have been reindexed successfully.","m");
}

//Reindex Users
if(isset($_POST['index_usrs'])) {
	$users = get_users(array("usr_status"=>"Ok"));
	$total_users = get_users(array("count_only"=>true,"usr_status"=>"Ok"));
	$percent = $cbindex->percent(50,$total_users);
	$i = 0;
	
	while($i < $total_users) {
		$params = array("user"=>$users[$i]['userid'],"comment_added"=>true,"subscriptions_count"=>true,"subscribers_count"=>true,
						"video_count"=>true,"groups_count"=>true);
		$indexes = $cbindex->count_index("user",$params);
		$fields = $cbindex->extract_fields("user",$params);
				
		$cbindex->update_index("user",array("fields"=>$fields,"values"=>$indexes,"user"=>$users[$i]['userid']));
		
		$i++;	
	}
	
	e($total_users." users have been reindexed successfully.","m");
}

//Reindex Grous
if(isset($_POST['index_gps'])) {
	$groups = get_groups(array("active"=>"yes"));
	$total_groups = get_groups(array("count_only"=>true,"active"=>"yes"));
	$percent = $cbindex->percent(50,$total_groups);
	$i = 0;

	while ($i < $total_groups) {
		$params = array("group_id"=>$groups[$i]['group_id'],"group_videos"=>true,"group_topics"=>true,"group_members"=>true);
		$indexes = $cbindex->count_index("group",$params);
		$fields = $cbindex->extract_fields("group",$params);
		
		$cbindex->update_index("group",array("fields"=>$fields,"values"=>$indexes,"group_id"=>$groups[$i]['group_id']));
		
		$i++;
	}
	e($total_groups." groups have been reindexed successfully.","m");
}

subtitle("Re-index Clipbucket");		
template_files('reindex_cb.html');
display_it(); 
?>