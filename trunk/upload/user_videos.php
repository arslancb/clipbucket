<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE",'user_videos');
require 'includes/config.inc.php';
$pages->page_redir();
$userquery->perm_check('view_videos',true);

$u = $_GET['user'];
$u = $u ? $u : $_GET['userid'];
$u = $u ? $u : $_GET['username'];
$u = $u ? $u : $_GET['uid'];
$u = $u ? $u : $_GET['u'];

$udetails = $userquery->get_user_details($u);
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);

if($udetails)
{
	assign("u",$udetails);
	$mode = $_GET['mode'];
	
	switch($mode)
	{
		case 'uploads':
		case 'videos':
		default:
		{
			$videos = get_videos(array('user'=>$udetails['userid'],'limit'=>$get_limit));
			$total_rows = get_videos(array('user'=>$udetails['userid'],'count_only'=>true));
		}
	}
	
Assign('videos', $videos);

//Collecting Data for Pagination
$total_pages = count_pages($total_rows,VLISTPP);

//Pagination
$pages->paginate($total_pages,$page);

}else{
	e(lang("usr_exist_err"));
	$Cbucket->show_page = false;
}


template_files('user_videos.html');
display_it();
?>