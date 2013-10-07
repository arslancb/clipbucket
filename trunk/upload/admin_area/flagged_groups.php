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
$userquery->perm_check('group_moderation',true);

$mode = $_GET['mode'];


//Delete Video
if(isset($_GET['delete_group'])){
	$group = mysql_clean($_GET['delete_group']);
	$cbgroup->delete_group($group);
}

//Deleting Multiple Videos
if(isset($_POST['delete_selected']))
{
	for($id=0;$id<=count($_POST['check_group']);$id++)
	{
		$cbgroup->delete_group($_POST['check_group'][$id]);
	}
	$eh->flush();
	e("Selected groups have been deleted","m");
}

//Activate / Deactivate
if(isset($_GET['activate'])){
	$group = mysql_clean($_GET['activate']);
	$cbgroup->grp_actions('activate',$group);
}
if(isset($_GET['deactivate'])){
	$group = mysql_clean($_GET['deactivate']);
	$cbgroup->grp_actions('deactivate',$group);
}

//Using Multple Action
if(isset($_POST['activate_selected'])){
	for($id=0;$id<=count($_POST['check_group']);$id++){
		$cbgroup->grp_actions('activate',$_POST['check_group'][$id]);
	}
	e("Selected Groups Have Been Activated","m");
}
if(isset($_POST['deactivate_selected'])){
	for($id=0;$id<=count($_POST['check_group']);$id++){
		$cbgroup->grp_actions('activate',$_POST['check_group'][$id]);
	}
	e("Selected Groups Have Been Dectivated","m");
}



if(isset($_REQUEST['delete_flags']))
{
	$group = mysql_clean($_GET['delete_flags']);
	$cbgroup->action->delete_flags($group);
}

//Deleting Multiple Videos
if(isset($_POST['delete_flags']))
{
	for($id=0;$id<=count($_POST['check_group']);$id++)
	{
		$eh->flush();
		$cbgroup->action->delete_flags($_POST['check_group'][$id]);
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
		$groups = $cbgroup->action->get_flagged_objects($get_limit);
		Assign('groups', $groups);	
		
		//Collecting Data for Pagination
		$total_rows  = $cbgroup->action->count_flagged_objects();
		$total_pages = count_pages($total_rows,5);
		
		//Pagination
		$pages->paginate($total_pages,$page);
	}
	break;
	
	case "view_flags":
	{
		assign("mode","view_flags");
		$gid = mysql_clean($_GET['gid']);
		$gdetails = $cbgroup->get_details($gid);
		if($gdetails)
		{
			$flags = $cbgroup->action->get_flags($gid);
			assign('flags',$flags);
			assign('group',$gdetails);
		}else
			e("Group does not exist");
	}
	
}

subtitle("Flagged Groups");
template_files('flagged_groups.html');
display_it();
?>