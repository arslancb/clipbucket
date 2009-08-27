<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$pages->page_redir();
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";


//Get User Details
	if($_GET['user'] != 'Array'){
	$user = mysql_clean($_GET['user']);
	}
	$data = $userquery->GetUserData_username($user);	
	$data['total_videos'] 	= $userquery->TotalVideos($user);
	//$userquery->UpdateSubscribers($user);
	$data['age'] = $calcdate->age($data['dob']);
	$show_education = array(
	'0'=>$LANG['usr_arr_no_ans'],
	'1'=>$LANG['usr_arr_elementary'],
	'2'=>$LANG['usr_arr_hi_school'],
	'3'=>$LANG['usr_arr_some_colg'],
	'4'=>$LANG['usr_arr_assoc_deg'],
	'5'=>$LANG['usr_arr_bach_deg'],
	'6'=>$LANG['usr_arr_mast_deg'],
	'7'=>$LANG['usr_arr_phd'],
	'8'=>$LANG['usr_arr_post_doc']
	);
	
	$show_relation = array(
	'0'=>$LANG['usr_arr_no_ans'],
	'1'=>$LANG['usr_arr_single'],
	'2'=>$LANG['usr_arr_married'],
	'3'=>$LANG['usr_arr_comitted'],
	'4'=>$LANG['usr_arr_open_marriage'],
	'5'=>$LANG['usr_arr_open_relate']
	);
	
	@$data['show_relation']=$show_relation[$data['relation_status']];
	@$data['show_education']=$show_education[$data['education']];
	$pattern = array('/http:\/\//','/www\./');
	$replace = array('','');
	if(!empty($data['web_url']))
	$data['web_url'] = 'http://'.preg_replace($pattern,$replace,$data['web_url']);
	Assign('data',$data);
	
//Adding Comment
	if(isset($_POST['add_comment'])){
		$comment = mysql_clean($_POST['comment']);
		$msg = $userquery->AddChannelComment($data['username'],$comment);
		}	
		
//Getting Channel Featured Video
	$data['featured_video'];
	$vkey = $data['featured_video'];
	if(!empty($vkey)){
	$f_video = $myquery->GetVideDetails($vkey);
	$f_video['url'] = VideoLink($f_video['videokey'],$f_video['title']);
	$myquery->UpdateVideoViews($vkey);
	Assign('f_video',$f_video);
	}

Assign('subtitle',$data['username'].$LANG['title_view_channel']);
	
//Update Number Of Channel Views

	if(!empty($data['userid'])){
	$userquery->UpdateChannelViews($user);
	}
	
if(empty($data['userid'])){
	$msg = $LANG['usr_exist_err2'];
	}
	
//Getting Video List Of Videos
	$limit 	= VLISTPT;
	$sql  	= "SELECT * FROM video WHERE username='".$user."' AND $query_param ORDER BY views DESC LIMIT $limit";
	$vdo_data = $db->Execute($sql);
	$total_vdo = $vdo_data->recordcount() + 0;
	$videos = $vdo_data->getrows();
	
	for($id=0;$id<$total_vdo;$id++){
	$videos[$id]['thumb'] 		= GetThumb($videos[$id]['flv']);
	$videos[$id]['duration'] 	= SetTime($videos[$id]['duration']);
	$videos[$id]['show_rating'] = pullRating($videos[$id]['videoid'],false,false,false,'novote');
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
	}


	Assign('videos',$videos);
	
//Subscribing User
if(isset($_POST['subscribe'])){
		$userquery->logincheck();
		$sub_user	= $_COOKIE['username'];
		$sub_to		= $user;
		$msg = $userquery->SubscribeUser($sub_user,$sub_to);
	}
	
//Getting Comments
@$limit = $_GET['comments'];
if(empty($limit) || $limit != 'all'){
	$limit = " LIMIT 0,10";
}else{
	$limit = " ";
}

	$sql = "Select * FROM channel_comments WHERE channel_user='".$user."' ORDER BY date_added DESC $limit";
	$rs = $db->Execute($sql);
	$total_comments = $rs->recordcount() + 0;
	$comments = $rs->getrows();
	for($id=0;$id<$total_comments;$id++){
			$user_data = $userquery->GetUserData_username($comments[$id]['username']);
			$thumb = $user_data['avatar'];
			$smallthumb = substr($thumb, 0, strrpos($thumb, '.')).'-small.';
			$smallthumb .= substr($thumb, strrpos($thumb,'.') + 1);
			$smallthumb;
			$comments[$id]['small_thumb'] = $smallthumb;
	}

Assign('total_comments',$total_comments);
Assign('comments',$comments);

//Add Contact to FriendsList
if(isset($_POST['friend_username'])){
	$userquery->logincheck();
	$friend = $_POST['friend_username'];
	$msg = $userquery->AddContact($friend,$_COOKIE['username']);
}

@Assign('msg',$msg);	
Template('header.html');
Template('message.html');	
Template('view_channel.html');
Template('footer.html');

?>