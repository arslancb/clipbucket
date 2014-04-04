<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.									|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , � PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Dashboard');
}

//	$latest = get_latest_cb_info();
	$Cbucket->cbinfo['latest'] = $latest;
	if($Cbucket->cbinfo['version'] < $Cbucket->cbinfo['latest']['version'])
		$Cbucket->cbinfo['new_available'] = true;


$result_array = $array;
//Getting Video List
$result_array['limit'] = $get_limit;
if(!$array['order'])
    $result_array['order'] = " doj DESC LIMIT 5  ";

$users = get_users($result_array);

Assign('users', $users);


if(!$array['order'])
    $result_array['order'] = " views DESC LIMIT 8 ";
$videos = get_videos($result_array);

Assign('videos', $videos);


$comments = getComments($comment_cond);
assign("comments",$comments);

$get_limit = create_query_limit($page,5);
$videos = $cbvid->action->get_flagged_objects($get_limit);
Assign('flagedVideos', $videos);


$get_limit = create_query_limit($page,5);
$users = $userquery->action->get_flagged_objects($get_limit);
Assign('flagedUsers', $users);


$get_limit = create_query_limit($page,5);
$photos = $cbphoto->action->get_flagged_objects($get_limit);
assign('flagedPhotos', $photos);

//$numbers = array(100,1000,15141,3421);
//function format_number($number) {
//    if($number >= 1000) {
//        return $number/1000 . "k";   // NB: you will want to round this
//    }
//    else {
//        return $number;
//    }
//}



template_files('index.html');
display_it();
?>





