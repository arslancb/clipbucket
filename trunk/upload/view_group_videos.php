<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE","view_group_videos");
define("PARENT_PAGE","videos");

require 'includes/config.inc.php';
$pages->page_redir();

$url = mysql_clean($_GET['url']);


$details = $cbgroup->group_details_url($url);
assign('group',$details);

if($details)
{
	///Getting User Videos
	$page = mysql_clean($_GET['page']);
	$get_limit = create_query_limit($page,28);
	
	//Getting List of all videos
	$videos = $cbgroup->get_group_videos($details['group_id'],"yes",$get_limit);
	
	$total_rows  =  $details['total_videos'];
	$total_pages = count_pages($total_rows,28);
	//Pagination
	$pages->paginate($total_pages,$page);
	
	
	assign("videos",$videos);
	assign("mode","view_videos");
	subtitle($details['group_name']);
}

template_files('view_group.html');
display_it();
?>