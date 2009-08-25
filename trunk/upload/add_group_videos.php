<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
$manage_vids = TRUE;
require 'includes/config.inc.php';
$userquery->logincheck();
$pages->page_redir();

		$url = clean($_GET['url']);
		include('group_inc.php');
		$group 	 = @$topic_details['group_id'];
		$details = $groups->GetDetails($url);
		$group 	 = $details['group_id'];
		$user	 = $_SESSION['username'];
		include('group_check.php');

//Adding Video To Group
if(isset($_POST['add_videos'])){
if($details['video_type'] == 1){
	$approved = 'no';
}else{
	$approved = 'yes';
}
	$msg = $groups->AddVideos($group,$approved);
}

//Getting User Videos
	$sql = "SELECT * from video WHERE username = '".$user."'";
	$rs = $db->Execute($sql);
	$videos = $rs->getrows();
	$total_vdo = $rs->recordcount()+0;
	
	for($id=0;$id<$total_vdo;$id++){
		$query=mysql_query("SELECT * FROM video_detail WHERE flv='".$videos[$id]['flv']."'");
		$data = mysql_fetch_array($query);
		$query2=mysql_query("SELECT * FROM group_videos WHERE videokey='".$videos[$id]['videokey']."' AND group_id='".$details['group_id']."'");
			if(mysql_num_rows($query2) > 0){
			$videos[$id]['checked'] = 'checked="checked"';
			}
	$videos[$id]['thumb'] 		= GetThumb($videos[$id]['flv']);
	}
	
	Assign('videos',$videos);
	Assign('total_vids',$total_vdo);


Assign('groups',$details);

Assign('subtitle',$details['group_name'].' '.$LANG['grp_add_title']);
@Assign('msg',$msg);
@Assign('show_group',$show_group);
Template('header.html');
Template('message.html');	
Template('group_header.html');
Template('add_group_videos.html');
Template('footer.html');
?>