<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.									|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
define('THIS_PAGE','index');
require 'includes/config.inc.php';
$pages->page_redir();

//Get Videos Beign Watched 
$being_watched = array
('limit' => 6,
 'order' => 'last_viewed DESC',
 );

assign('being_watched',$cbvid->get_videos($being_watched));

//GettinG list Of Featured Videos
$featured = array
('limit' => 4,
 'featured' => 'yes',
 'order'	=> ' featured_date DESC ',
 );
assign('featured_videos',$cbvid->get_videos($featured));


//GettinG list Of recently added Videos
$recently_added = array
('limit' => 12,
 'order'	=> ' date_added DESC ',
 );
assign('recently_added',$cbvid->get_videos($recently_added));

//Get Categories
$cats = $db->select("video_categories",'*',NULL,'6');
assign('cat',$cats);

//Top users
$users = $db->select("users",'*',NULL,'10',"total_videos DESC");
assign('topusers',$users);

//Getting Video List
$videos = $db->select("video",'*',NULL,'15',"date_added DESC");
Assign('videos', $videos);	

if(is_installed('editorspick'))
{
	assign('editor_picks',get_ep_videos());
}

//Displaying The Template
template_files('index.html');
display_it();
?>