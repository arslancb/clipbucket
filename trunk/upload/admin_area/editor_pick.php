<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Removing
if(isset($_GET['remove'])){
	$id = mysql_clean($_GET['remove']);
	remove_vid_editors_pick($id);
}

if(isset($_POST['delete_selected']))
{
	for($id=0;$id<=count($_POST['check_video']);$id++)
	{
		remove_vid_editors_pick($_POST['check_video'][$id]);
	}
	$eh->flush();
	e("Selected videos have been removed from editors pick","m");
}


$ep_videos = get_ep_videos();
if(isset($_POST['update_order']))
{
	if(is_array($ep_videos))
	{
		foreach($ep_videos as $epvid)
		{
			$order = $_POST['ep_order_'.$epvid['pick_id']];
			move_epick($epvid['videoid'],$order);
		}
	}
	$ep_videos = get_ep_videos();

}


assign('videos',$ep_videos);
assign('max',get_highest_sort_number());
assign('min',get_lowest_sort_number());
	
	
subtitle("Editor's Pick");
template_files('editor_pick.html');
display_it();
?>