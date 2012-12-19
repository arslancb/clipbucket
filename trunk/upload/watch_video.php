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
$slug = @$_GET['s'];
$slug = mysql_clean($slug);
if($slug)
{
    $slug = slug_exists($slug,'v');
    if($slug)
        $vkey = $slug['object_id'];
}
else
    $theslug = false;
$vdo = $cbvid->get_video($vkey,$theslug);

assign('vdo',$vdo);
assign('video',$vdo);
if(video_playable($vdo))
{	
	$pid = $_GET['play_list'];
        
	/**
	 * Please check http://code.google.com/p/clipbucket/issues/detail?id=168
	 * for more details about following code
	 */
	 
	if(SEO=='yes' && config('seo_vido_url')=='4' && $vdo['slug'])
	{
            //Now finally Checking if both are equal else redirect to new link
            if($slug['slug'] != $vdo['slug'] )
            {
                    $vid_link = VideoLink($vdo);
                    if($pid)
                        $vid_link.='?play_list='.$pid;
                    //Redirect to valid link leaving mark 301 Permanent Redirect
                    header ('HTTP/1.1 301 Moved Permanently');
                    header ('Location: '.$vid_link);
                    exit();
            }
	}
	
	//Checking for playlist
	
	if(!empty($pid))
	{
		$plist = $cbvid->action->get_playlist($pid,userid());
                
                //Also getting list of playlist items
                $plist_items = $cbvid->get_playlist_items($pid);
                
                $plist['items'] = $plist_items;
                assign('playlist',$plist);
                
		if($plist)
			$_SESSION['cur_playlist'] = $pid;
	}	
	//Calling Functions When Video Is going to play
	call_watch_video_function($vdo);
        
        $vid_files = $cbvid->get_video_files($vdo);
        
        
        assign('video_files',$vid_files);
        
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