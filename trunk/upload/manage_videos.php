<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 **************************************************************
*/

define("THIS_PAGE",'manage_videos');
define("PARENT_PAGE","videos");

require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);

assign('queryString',queryString(NULL,array('type',
					'makeProfileItem',
					'removeProfileItem',
					'vid_delete')));
switch($mode)
{
	case 'uploaded':
	default:
	{
		assign('mode','uploaded');
		
		//Deleting Video
		if(!empty($_GET['vid_delete']))
		{
			$video = mysql_clean($_GET['vid_delete']);
			$cbvideo->delete_video($video);
		}
		
		//Deleting Videos
		if(isset($_POST['delete_videos']))
		{
			for($id=0;$id<=VLISTPP;$id++)
			{
				$cbvideo->delete_video($_POST['check_vid'][$id]);
			}
			$eh->flush();
			e(lang("vdo_multi_del_erro"),m);
		}
		
		//Setting Profile Video
		if(isset($_GET['makeProfileItem']))
		{
			$item = mysql_clean($_GET['makeProfileItem']);
			$type = mysql_clean($_GET['type']);
			$userquery->setProfileItem($item,$type);
		}
		
		//Removing Profile Item
		if(isset($_GET['removeProfileItem']))
		{
			$userquery->removeProfileItem();
		}
		
		//Getting Video List
		$vid_array = array('user'=>$udetails['userid'],'limit'=>$get_limit);
		if(get('query')!='')
		{
			$vid_array['title'] = mysql_clean(get('query'));
			$vid_array['tags'] =  mysql_clean(get('query'));
		}
			
		$videos = get_videos($vid_array);
		
		Assign('uservids', $videos);	
		
		//Collecting Data for Pagination
		$vid_array['count_only'] = true;
		$total_rows  = get_videos($vid_array);
		$total_pages = count_pages($total_rows,VLISTPP);
		
		//Pagination
		$pages->paginate($total_pages,$page);
		
		subtitle(lang("vdo_manage_vdeos"));
		
	}
	break;
	
	
	case 'favorites':
	{
		assign('mode','favorites');
		//Removing video from favorites
		if(!empty($_GET['vid_delete']))
		{
			$video = mysql_clean($_GET['vid_delete']);
			$cbvideo->action->remove_favorite($video);
		}
		//Removing Multiple Videos
		if(isset($_POST['delete_fav_videos']))
		{
			for($id=0;$id<=VLISTPP;$id++)
			{
				$cbvideo->delete_video($_POST['check_vid'][$id]);
			}
			$eh->flush();
			e(lang("vdo_multi_del_fav_msg"),m);
		}
		if(get('query')!='')
		{
			$cond = " (video.title LIKE '%".mysql_clean(get('query'))."%' OR video.tags LIKE '%".mysql_clean(get('query'))."%' )";
		}
		$params = array('userid'=>userid(),'limit'=>$get_limit,'cond'=>$cond);
		
		$videos = $cbvid->action->get_favorites($params);
		
		Assign('uservids', $videos);	
		
		//Collecting Data for Pagination
		$params['count_only'] = 'yes';
		$favorites_count = $cbvid->action->get_favorites($params);
		$total_pages = count_pages($favorites_count,VLISTPP);
		//Pagination
		$pages->paginate($total_pages,$page);
		
		subtitle(lang("com_manage_fav"));
	}
	break;
}

template_files('manage_videos.html');
display_it();

?>