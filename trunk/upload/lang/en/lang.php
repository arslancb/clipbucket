<?php
# ClipBucket Language Pack
# Language : English
# Author : Arslan Hassan
# Special Thanks To : FRANK , MOMO
# Website: htp://clip-bucket.com/

//Including Required Files
include('common.php');
include('class.php');
include('global.php');
include('menu.php');
include('titles.php');
include('rss_lang.php');
include('sub_titles.php');

$folders  = array('admin','groups','user','videos');
foreach($folders as $folder)
{
	include($folder.'/files.php');
	include($folder.'/template.php');
}
//Merging Arrays To Pack Language In One Array
$LANG = array_merge(
$admin_lang,
$group_lang,
$user_lang,
$title_lang,
$video_lang,
$class_lang,
$comm_temp_array,
$rss_lang_array,
$global_lang,
$video_temp_lang
);

$TEMPLATE_LANG = array_merge(
$grp_temp_lang,
$video_temp_lang,
$user_temp_lang,
$comm_temp_array,
$global_lang,
$menu_lang,
$rss_lang_array
);


?>
