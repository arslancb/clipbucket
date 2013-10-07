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
if(isset($_GET['delete_photo'])){
	$photo = mysql_clean($_GET['delete_photo']);
	$cbphoto->delete_photo($photo);
}

//Deleting Multiple Photos
if(isset($_POST['delete_selected']))
{
	for($id=0;$id<=count($_POST['check_photo']);$id++)
	{
		$cbphoto->delete_photo($_POST['check_photo'][$id]);
	}
	$eh->flush();
	e("Selected photos have been deleted","m");
}

if(isset($_REQUEST['delete_flags']))
{
	$photo = mysql_clean($_GET['delete_flags']);
	$cbphoto->action->delete_flags($photo);
}

//Deleting Multiple Videos
if(isset($_POST['delete_flags']))
{
	for($id=0;$id<=count($_POST['check_photo']);$id++)
	{
		$eh->flush();
		$cbphoto->action->delete_flags($_POST['check_photo'][$id]);
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
		$photos = $cbphoto->action->get_flagged_objects($get_limit);
		assign('photos', $photos);	
		
		//Collecting Data for Pagination
		$total_rows  = $cbphoto->action->count_flagged_objects();
		$total_pages = count_pages($total_rows,5);
		
		//Pagination
		$pages->paginate($total_pages,$page);
	}
	break;
	
	case "view_flags":
	{
		assign("mode","view_flags");
		$pid = mysql_clean($_GET['pid']);
		$pdetails = $cbphoto->get_photo($pid);
		if($pdetails)
		{
			$flags = $cbphoto->action->get_flags($pid);
			assign('flags',$flags);
			assign('photo',$pdetails);
		}else
			e("Photo does not exist");
	}
	
}

subtitle("Flagged Photos");
template_files('flagged_photos.html');
display_it();

?>