<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
//$userquery->perm_check('group_moderation',true);

$mode = $_GET['mode'];

//Delete Photo
if(isset($_GET['delete_collect'])){
	$collect = mysql_clean($_GET['delete_collect']);
	$cbcollection->delete_collection($collect);
}

//Deleting Multiple Photos
if(isset($_POST['delete_selected']))
{
	for($id=0;$id<=count($_POST['check_collect']);$id++)
	{
		$cbphoto->delete_photo($_POST['check_collect'][$id]);
	}
	$eh->flush();
	e("Selected collections have been deleted","m");
}

if(isset($_REQUEST['delete_flags']))
{
	$collect = mysql_clean($_GET['delete_flags']);
	$cbcollection->action->delete_flags($collect);
}

//Deleting Multiple Videos
if(isset($_POST['delete_flags']))
{
	for($id=0;$id<=count($_POST['check_collect']);$id++)
	{
		$eh->flush();
		$cbcollection->action->delete_flags($_POST['check_collect'][$id]);
	}
}

switch($mode)
{
	case "view":
	default:
	{
		assign("mode","view");
		//Getting Video List
		$page = mysql_clean($_GET['page']);
		$get_limit = create_query_limit($page,5);
		$collects = $cbcollection->action->get_flagged_objects($get_limit);
		assign('c', $collects);	
		
		//Collecting Data for Pagination
		$total_rows  = $cbcollection->action->count_flagged_objects();
		$total_pages = count_pages($total_rows,5);
		
		//Pagination
		$pages->paginate($total_pages,$page);
	}
	break;
	
	case "view_flags":
	{
		assign("mode","view_flags");
		$cid = mysql_clean($_GET['cid']);
		$cdetails = $cbcollection->get_collection($cid);
		if($cdetails)
		{
			$flags = $cbcollection->action->get_flags($pid);
			assign('flags',$flags);
			assign('collection',$cdetails);
		}else
			e("Collection does not exist");
	}
	
}

subtitle("Flagged Collections");
template_files('flagged_collections.html');
display_it();
?>