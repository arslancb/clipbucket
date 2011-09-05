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
$vdo = $cbvid->get_video($vkey);
assign('vdo',$vdo);
if(video_playable($vdo))
{	
	
	/**
	 * Please check http://code.google.com/p/clipbucket/issues/detail?id=168
	 * for more details about following code
	 */
	 
	if(SEO=='yes')
	{
		//Checking if Video URL is Exactly What we have created
		$vid_link = videoLink($vdo);
		$vid_link_seo = explode('/',$vid_link);
		$vid_link_seo = $vid_link_seo[count($vid_link_seo) -1 ];
		
		//What we are getting
		$server_link = $_SERVER['REQUEST_URI'];
		$server_link_seo = explode('/',$server_link);
		$server_link_seo = $server_link_seo[count($server_link_seo) -1 ];
		
		//Now finally Checking if both are equal else redirect to new link
		if($vid_link_seo != $server_link_seo)
		{
			//Redirect to valid link leaving mark 301 Permanent Redirect
			header ('HTTP/1.1 301 Moved Permanently');
  			header ('Location: '.$vid_link);
			exit();
		}
		
	}
	
	//Checking for playlist
	$pid = $_GET['play_list'];
	if(!empty($pid))
	{
		$plist = $cbvid->action->get_playlist($pid,userid());
		if($plist)
			$_SESSION['cur_playlist'] = $pid;
	}	
	//Calling Functions When Video Is going to play
	call_watch_video_function($vdo);
	
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