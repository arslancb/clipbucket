<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/
define('THIS_PAGE','index');
require 'includes/config.inc.php';

$pages->page_redir();

if(is_installed('editorspick'))
{
	assign('editor_picks',get_ep_videos());
}

//i love coding :)

/**
 * Custom Top users query. Specially for sauditube.org
 * SELECT cb_video.userid,cb_video.views,cb_users.*, SUM(cb_video.views) AS total_views FROM cb_video,cb_users 
 * WHERE cb_video.userid = cb_users.userid GROUP BY cb_users.userid
 
$result = $db->select(tbl('video,users'),tbl('video.userid,video.views,users.*').", SUM(".tbl('video.views').") AS total_views"
			,' '.tbl('video.userid').' = '.tbl('users.userid').' GROUP BY '.tbl('users.userid').'',10,' total_views DESC');			
if($db->num_rows > 0)
	assign('topusers',$result);
*/		

//Displaying The Template
template_files('index.html');
display_it();

?>