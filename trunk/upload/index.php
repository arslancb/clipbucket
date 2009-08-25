<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
$pages->page_redir();


if(isset($_GET['cb_ver']) && $is_admin)
{
$msg = "ClipBucket&nbsp;".CB_VERSION."";
}

//Get user Data, if logged in
	if(isset($_SESSION['username'])){
	$user = $_SESSION['userid'];
	$data = $userquery->GetUserData($user);	
	Assign('data',$data);
	}

//Getting Users With Most Videos
	$sql 		= "SELECT * FROM users WHERE total_videos > 0 ORDER by total_videos DESC LIMIT 5";
	$udata		= $db->Execute($sql);
	$top_user	= $udata->getrows();
	$total_users = $udata->recordcount() + 0;
	for($id=0;$id<$total_users;$id++){
	$sql = "Select * FROM video WHERE username='".$top_user[$id]['username']."'";
	$rs = $db->Execute($sql);
	$top_user[$id]['total_videos'] = $rs->recordcount() + 0;
	}

	Assign('top_user',$top_user);

//Getting List of Featured Videos
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
$show = 'featured';
$limit = "LIMIT ".VLISTPT;
$query = "SELECT * FROM video WHERE featured = 'yes' AND $query_param ORDER BY date_added DESC $limit";
$text = 'Featured';
Assign('text',$text);

$link = BASEURL.videos_link.'?order=fr';
Assign('link',$link);

$data 		= $db->Execute($query);
$videos		= $data->getrows();
$total		= $data->recordcount() + 0;
	
	
Assign('videos',$videos);
Assign('show',$show);
if(isset($msg))
{
Assign('msg',$msg);
}


subtitle('index');
Template('header.html');
Template('message.html');
Template('index.html');
Template('footer.html');
?>