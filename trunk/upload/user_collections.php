<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE",'user_collections');
define("PARENT_PAGE",'collections');

require 'includes/config.inc.php';
$pages->page_redir();
//$userquery->perm_check('view_videos',true);

$u = $_GET['user'];
$u = $u ? $u : $_GET['userid'];
$u = $u ? $u : $_GET['username'];
$u = $u ? $u : $_GET['uid'];
$u = $u ? $u : $_GET['u'];

$user = $userquery->get_user_details($u);
$page = mysql_clean($_GET['page']);

if($user)
{
	assign('u',$user);
	assign('p',$userquery->get_user_profile($udetails['userid']));
	
	$mode = $_GET['mode'];
	
	switch($mode)
	{
		case "collections":
		case "uploaded":
		default:
		{
			$limit = create_query_limit($page,config('collection_user_collections'));
			assign("the_title",$user['username']." ".lang('collections'));
			$collections = get_collections(array("limit"=>$limit,"user"=>$user['userid']));
			$total_rows = get_collections(array("count_only"=>true,"user"=>$user['userid']));
			$total_pages = count_pages($total_rows,config('collection_user_collections'));
		}
		break;
		
		case "favorites":
		case "fav_collections":
		case "favorite":
		{
			$limit = create_query_limit($page,config('collection_user_favorites'));
			assign("the_title",$user['username']." ".lang('favorite')." ".lang('collections'));
			$favC = array("user"=>$user['userid'],"limit",$limit);
			$collections = $cbcollection->action->get_favorites($favC);
			$favC['count_only'] = true;
			$total_rows = $cbcollection->action->get_favorites($favC);
			$total_pages = count_pages($total_rows,config('collection_user_favorites'));
		}
		break;
	}
	
	assign('collections',$collections);
	
	$pages->paginate($total_pages,$page);	
} else {
	e(lang("usr_exist_err"));
	$Cbucket->show_page = false;
}

if($Cbucket->show_page)
Template('user_collections.html');
else
display_it();
?>