<?php

	/**
	* File: watch_video
	* Description: FIle used to display watch video page
	* @author: Arslan Hassan, Saqib Razzaq
	* @since: 2007
	* @website: clip-bucket.com
	* @modified: Feb 26, 2016 ClipBucket 2.8.1 [ Saqib Razzaq ]
	*/

	define("THIS_PAGE",'watch_video');
	define("PARENT_PAGE",'videos');
	require 'includes/config.inc.php';
	global $cbvid;
	$userquery->perm_check('view_video',true);
	$pages->page_redir();

	$vkey = @$_GET['v'];
	$vkey = mysql_clean($vkey);
	$vdo = $cbvid->get_video($vkey);
	$cbvid->update_comments_count($vdo['videoid']);
	$assign_arry['vdo'] = $vdo;
	if(video_playable($vdo)) {	
		//Checking for playlist
		$pid = $_GET['play_list'];
		if(!empty($pid)) {
			$plist = get_playlist( $pid );
			if ( $plist ) {
	            $assign_arry['playlist'] = $plist;
	        }
		}
		//Calling Functions When Video Is going to play
		call_watch_video_function($vdo);
		subtitle(ucfirst($vdo['title']));
	} else {
		return $Cbucket->show_page = false;

	}

	//Return category id without '#'
	$v_cat = $vdo['category'];
	if($v_cat[2] =='#') {
	$video_cat = $v_cat[1];
	} else {
	$video_cat = $v_cat[1].$v_cat[2];}
	$vid_cat = str_replace('%#%','',$video_cat);
	#assign('vid_cat',$vid_cat);
	$assign_arry['vid_cat'] = $vid_cat;
	$title = $vdo['title'];
	$tags = $vdo['tags'];
	$videoid = $vdo['videoid'];
	$related_videos = get_videos(array('title'=>$title,'tags'=>$tags,
	'exclude'=>$videoid,'show_related'=>'yes','limit'=>12,'order'=>'date_added DESC'));
	if(!$related_videos){
		$relMode = "ono";
		$related_videos  = get_videos(array('exclude'=>$videoid,'limit'=>12,'order'=>'date_added DESC'));
	}
	$playlist = $cbvid->action->get_playlist($pid,userid());
	$assign_arry['playlist'] = $playlist;
				//Getting Playlist Item
				$items = $cbvid->get_playlist_items( $pid, 'playlist_items.date_added DESC' );
				$assign_arry['items'] = $items;
	$assign_arry['videos'] = $related_videos;
	$assign_arry['relMode'] = $relMode;
	# assigning all variables
	array_val_assign($assign_arry);
	template_files('watch_video.html');
	display_it();
?> 