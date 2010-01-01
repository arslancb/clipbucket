<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Activating Page
if(isset($_GET['activate']))
{
	$pid = mysql_clean($_GET['activate']);
	$cbpage->page_actions('activate',$pid);
}

//Dectivating Page
if(isset($_GET['deactivate']))
{
	$pid = mysql_clean($_GET['deactivate']);
	$cbpage->page_actions('deactivate',$pid);
}
//Deleting
if(isset($_GET['delete']))
{
	$pid = mysql_clean($_GET['delete']);
	$cbpage->page_actions('delete',$pid);
}
if(isset($_POST['activate_selected'])){
	for($id=0;$id<=count($_POST['check_page']);$id++){
		$cbpage->page_actions('activate',$_POST['check_page'][$id]);
	}
	$eh->flush();
	e("Selected pages have been activated","m");
}

if(isset($_POST['deactivate_selected'])){
	for($id=0;$id<=count($_POST['check_page']);$id++){
		$cbpage->page_actions('deactivate',$_POST['check_page'][$id]);
	}
	$eh->flush();
	e("Selected pages have been deactivated","m");
}
if(isset($_POST['delete_selected'])){
	for($id=0;$id<=count($_POST['check_page']);$id++){
		$cbpage->page_actions('delete',$_POST['check_page'][$id]);
	}
	$eh->flush();
	e("Selected pages have been deleted","m");
}

$mode = $_GET['mode'];

if(isset($_POST['add_page']))
{
	if($cbpage->create_page($_POST))
		$mode = 'view';
}

switch($mode)
{
	case "new":
	{
		assign("mode","new");
	}
	break;
	
	case "view":
	default:
	{
		assign("mode","manage");
		assign("cbpages",$cbpage->get_pages());
	}
	break;
	case "edit":
	{
		if(isset($_POST['update_page']))
		{
			$_POST['page_id'] = $_GET['pid'];
			$cbpage->edit_page($_POST);
		}

		assign("mode","edit");
		$page = $cbpage->get_page(mysql_clean($_GET['pid']));
		assign('page',$page);
		if(!$page)
			e("Page does not exist");
	}
	
}

template_files('manage_pages.html');
display_it();


?>