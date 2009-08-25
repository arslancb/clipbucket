<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$show=$_GET['show'];
Assign('show',$show);
$user=$_GET['user'];
Assign('user',$user);
$userdetails = $userquery->GetUserData_username($user);
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
if($show=='contacts'){
	//Listing Contacts
	$limit  = CLISTPT;	
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	$orderby	  = " ORDER BY date_added DESC";

	$sql 	= "SELECT * FROM contacts WHERE username='".$user."' $orderby $query_limit";
	
	$contacts_data = $db->Execute($sql);
	$total_contacts = $contacts_data->recordcount() + 0;
	$contacts = $contacts_data->getrows();
	
	for($id=0;$id<$total_contacts;$id++){
	$contact_details = $userquery->GetUserData_username($contacts[$id]['friend_username']);
	$contacts[$id]['avatar'] = $contact_details['avatar'];
	$contacts[$id]['views'] = $contact_details['profile_hits'];
	$contacts[$id]['videos'] = $contact_details['total_videos'];
	$contacts[$id]['comments'] = $contact_details['total_comments'];
	$contacts[$id]['doj'] = $contact_details['doj'];
	}
	
	Assign('total_contacts',$total_contacts);
	Assign('contacts',$contacts);
	
}

if($show=='fav_videos'){
	//Listing Contacts
	$limit  = VLISTPT;	
	Assign('limit',$limit);
	@$page   = clean($_GET['page']);
	if(empty($page) || $page == 0 || !is_numeric($page)){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	$query_limit  = "limit $from,$limit";
	$orderby	  = " ORDER BY date_added DESC";

	$sql 	= "SELECT * FROM video_favourites WHERE userid='".$userdetails['userid']."' $orderby $query_limit";
	
	$video_Data = $db->Execute($sql);
	$total_videos = $video_Data->recordcount() + 0;
	$videos = $video_Data->getrows();
	
	for($id=0;$id<$total_videos;$id++){
	$videos_details = $myquery->GetVideoDetails($videos[$id]['videoid']);
	$flv =	$videos_details['flv'];
	$videos[$id]['thumb'] 		= GetThumb($flv);
	$videos[$id]['show_rating'] = pullRating($videos_details['videoid'],false,false,false,@novote);
	$videos[$id]['title'] 		= $videos_details['title'];
	$videos[$id]['description'] = $videos_details['description'];
	$videos[$id]['videokey'] 	= $videos_details['videokey'];
	$videos[$id]['views']		= $videos_details['views'];
	$videos[$id]['duration'] 	= SetTime($videos_details['duration']);
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
	}
	
	Assign('total_videos',$total_videos);
	Assign('videos',$videos);
	
}
Template('tabs02.html');
?>