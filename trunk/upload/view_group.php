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

		$url = $_GET['url'];
		$details = $groups->group_details_url($url);
		$userdata = $userquery->GetUserData($details['group_owner']);
		$user = $_SESSION['userid'];
		include('group_check.php');
		
		//Assign Variables
		assign('groupdata',$details);
		assign('ownerdata',$userdata);
		
template_files('view_group.html');
display_it();
//		include('group_inc.php');
//		$details = $groups->group_details_url($url);
//		$group 	= 	$details['group_id'];
//		@$user 	= 	$_SESSION['username'];
//		if(empty($user)){
//		@$user 	= 	$_COOKIE['username'];
//		}
//		$MustJoin = 'No';
//		
//		
////assigning Tags
//$tags = $details['group_tags'];
//$tags = explode(" ", $tags );
//Assign("tags",$tags);
//
////Delete Topic
//if(isset($_POST['delete_topic'])){
//	$msg = $groups->DeleteTopic($_POST['delete_topic']);
//}
//
////Approving Topics
//if(isset($_POST['approve_topic'])){
//	$topic = $_POST['approve_topic'];
//	$groups->ApproveTopic($topic);
//}
////Getting Group Other Details
//$details['members'] = $groups->CountMembers($group);
//$details['videos'] = $groups->CountVideos($group);
//$details['topics'] = $groups->CountTopics($group);
//$details['posts'] = $groups->CountGroupPosts($group);
//
////Adding Topics
//if(isset($_POST['add_topic'])){
//$userquery->logincheck();
//$topic 		= mysql_clean($_POST['topic']);
//$group 		= $details['group_id'];
//$video		= mysql_clean($_POST['video']);
//if($details['post_type'] == '1'){
//$approved = 'no';
//}else{
//$approved = 'yes';
//}
//$msg = $groups->AddTopic($topic,$group,$video,$approved);
//}
//
////Getting Topic List
//$sql = "Select * FROM group_topics WHERE group_id = '".$details['group_id']."' AND approved='yes' ORDER BY last_reply DESC";
//if(@$_SESSION['username'] == $details['username']){
//$sql = "Select * FROM group_topics WHERE group_id = '".$details['group_id']."' ORDER BY last_reply DESC";
//}
//$data = $db->Execute($sql);
//$total_topic = $data->recordcount() + 0;
//$topics = $data->getrows();
//	for($id=0;$id<$total_topic;$id++){
//	$topics[$id]['replies'] = $groups->CountReplies($topics[$id]['topic_id']);
//	}
//Assign('topics',$topics);
//
////Getting User Videos
//	$sql = "SELECT * from video WHERE username = '".@$_SESSION['username']."'";
//	$rs = $db->Execute($sql);
//	$videos = $rs->getrows();
//	Assign('videos',$videos);
//
////Setting Up Topic Form
//if($details['post_type'] == '0' || $details['post_type'] == '1' && !empty($_SESSION['username'])){
//	Assign('show_form','yes');
//	Assign('user_name',@$_SESSION['username']);
//}else{
//	Assign('user_name',@$_SESSION['username']);
//	Assign('show_form','no');
//}
//if(empty($_SESSION['username']) || !$groups->is_joined($user,$group) || !$groups->is_active($user,$group)){
//Assign('show_form','no');
//	if(!$groups->is_active($user,$group)){
//		Assign('active','no');
//	}
//	if(!$groups->is_joined($user,$group)){
//		Assign('joined','no');
//	}
//}
//if($details['username'] == @$_SESSION['username'] && !empty($_SESSION['username'])){
//	Assign('user_name',$_SESSION['username']);
//	Assign('show_form','yes');
//}
//
////Getting Group Videos
//$sql = "Select * FROM group_videos WHERE group_id = '".$details['group_id']."' AND approved='yes' ORDER BY date_added DESC LIMIT 0,6";
//$data = $db->Execute($sql);
//$total_videos = $data->recordcount() + 0;
//$videos = $data->getrows();
//	for($id=0;$id<$total_videos;$id++){
//	$data 	= $myquery->GetVideDetails($videos[$id]['videokey']);
//	$flv 	= $data['flv'];
//	$videos[$id]['thumb'] 	= GetThumb($flv);
//	$videos[$id]['title'] 	= $data['title'];
//	$videos[$id]['url'] 	= VideoLink($data['videokey'],$data['title']);
//	}
//Assign('videos',$videos);
//
//
////Getting Category
//$query = mysql_query("SELECT * FROM category WHERE categoryid='".$details['group_category']."'");
//$data = mysql_fetch_array($query);
//$details['category'] = $data['category_name'];
//
////Chceking Logged in user is group user or not
//	if(!$groups->is_joined(@$_SESSION['username'],$group)){
//		Assign('join','yes');
//	}else{
//		Assign('join','no');
//	}
//Assign('groups',$details);
//
//
//
//Assign('subtitle',$details['group_name'].' Group');
//@Assign('msg',$msg);
//@Assign('show_group',$show_group);
//Template('header.html');
//Template('message.html');	
//Template('group_header.html');
//Template('view_group.html');
//Template('footer.html');
?>