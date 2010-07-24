<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'watch_video');
define("PARENT_PAGE",'videos');
require 'includes/config.inc.php';
$userquery->perm_check('view_video',true);
$pages->page_redir();

//Getting Video Key
$vkey = @$_GET['v'];

if(video_playable($vkey))
{
	$vdo = $cbvid->get_video($vkey);
	
	//Checking for playlist
	$pid = $_GET['play_list'];
	if(!empty($pid))
	{
		$plist = $cbvid->action->get_playlist($pid,userid());
		if($plist)
			$_SESSION['cur_playlist'] = $pid;
	}
	
	if(post('send_content'))
	{
		//Sending Video
		$cbvid->set_share_email($vdo);
		$cbvid->action->share_content($vdo['videoid']);
	}
	
	//Calling Functions When Video Is going to play
	call_watch_video_function($vdo);
	
	//adding Comment
	if(isset($_POST['add_comment']))
	{
		$cbvideo->add_comment($_POST['comment'],$vdo['videoid']);
		$vdo['comments_count'] = $cbvid->count_video_comments;
	}
	
	//Adding Video To Favorites
	if(isset($_REQUEST['favorite']))
	{
		$cbvideo->action->add_to_fav($vdo['videoid']);
	}
	
	assign('vdo',$vdo);
	assign('user',$userquery->get_user_details($vdo['userid']));
	
	subtitle($vdo['title']);

}else
	$Cbucket->show_page = false;

//Return category id without '#'
$v_cat = $vdo['category'];
if($v_cat[2] =='#') {
$video_cat = $v_cat[1];
}else{
$video_cat = $v_cat[1].$v_cat[2];}
$vid_cat = str_replace('%#%','',$video_cat);

assign('vid_cat',$vid_cat);

//Displaying The Template
template_files('watch_video.html');
display_it();
?>