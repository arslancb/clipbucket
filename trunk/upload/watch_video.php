<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'watch_video');
require 'includes/config.inc.php';
$userquery->perm_check('view_video',true);

$pages->page_redir();

//Getting Video Key
$vkey = @$_GET['v'];

if(video_playable($vkey))
{
	$vdo = $cbvid->get_video($vkey);
	assign('vdo',$vdo);
	
	if(post('send_content'))
	{
		//Sending Video
		$cbvid->set_share_email($vdo);
		$cbvid->action->share_content($vdo['videoid']);
	}
	
	//Calling Functions When Video Is Called
	call_watch_video_function($vdo);
	
	//Addming Comment
	if(isset($_POST['add_comment']))
	{
		$cbvideo->add_comment($_POST['comment'],$vdo['videoid']);
	}
}

//Displaying The Template
template_files('watch_video.html');
display_it();
?>