<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

if(isset($_GET['make_feature']))
{
	$id = mysql_clean($_GET['make_feature']);
	$cbcollection->collection_actions('mcf',$id);	
}

if(isset($_GET['make_unfeature']))
{
	$id = mysql_clean($_GET['make_unfeature']);
	$cbcollection->collection_actions('mcuf',$id);	
}

if(isset($_GET['activate']))
{
	$id = mysql_clean($_GET['activate']);
	$cbcollection->collection_actions('ac',$id);	
}

if(isset($_GET['deactivate']))
{
	$id = mysql_clean($_GET['deactivate']);
	$cbcollection->collection_actions('dac',$id);	
}

if(isset($_GET['delete_collection']))
{
	$id = mysql_clean($_GET['delete_collection']);
	$cbcollection->delete_collection($id);	
}
//pr($cbcollection->collection_delete_functions,TRUE);

/* ACTIONS ON MULTI ITEMS */
if(isset($_POST['activate_selected']))
{
	$total = count($_POST['check_collection']);
	for($i=0;$i<$total;$i++)
	{
		$cbcollection->collection_actions('ac',$_POST['check_collection'][$i]);	
	}
	$eh->flush();
	e($total." collections has been activated","m");
}

if(isset($_POST['deactivate_selected']))
{
	$total = count($_POST['check_collection']);
	for($i=0;$i<$total;$i++)
	{
		$cbcollection->collection_actions('dac',$_POST['check_collection'][$i]);	
	}
	$eh->flush();
	e($total." collections has been deactivated","m");
}

if(isset($_POST['make_featured_selected']))
{
	$total = count($_POST['check_collection']);
	for($i=0;$i<$total;$i++)
	{
		$cbcollection->collection_actions('mcf',$_POST['check_collection'][$i]);	
	}
	$eh->flush();
	e($total." collections has been marked as <strong>Featured</strong>","m");
}

if(isset($_POST['make_unfeatured_selected']))
{
	$total = count($_POST['check_collection']);
	for($i=0;$i<$total;$i++)
	{
		$cbcollection->collection_actions('mcuf',$_POST['check_collection'][$i]);	
	}
	$eh->flush();
	e($total." collections has been marked as <strong>Unfeatured</strong>","m");
}

if(isset($_POST['make_unfeatured_selected']))
{
	$total = count($_POST['check_collection']);
	for($i=0;$i<$total;$i++)
	{
		$cbcollection->collection_actions('mcuf',$_POST['check_collection'][$i]);	
	}
	$eh->flush();
	e($total." collections has been marked as <strong>Unfeatured</strong>","m");
}

if(isset($_POST['delete_selected']))
{
	$total = count($_POST['check_collection']);
	for($i=0;$i<$total;$i++)
	{
		$cbcollection->delete_collection($_POST['check_collection'][$i]);	
	}
	$eh->flush();
	e($total." collection(s) has been deleted successfully","m");
}

/* IF SEARCH EXISTS */
if($_GET['search'])
{
	$array = array(
		'name'	=> $_GET['title'],
		'tags'	=> $_GET['tags'],
		'cid'	=> $_GET['collectionid'],
		'type'	=> $_GET['collection_type'],
		'user'	=> $_GET['userid'],
		'order'	=> $_GET['order'],
		'broadcast'	=> $_GET['broadcast'],
		'featured'	=> $_GET['featured'],
		'active'	=> $_GET['active']
	);	
}

$carray = $array;

/* CREATING LIMIT */
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);

$carray['limit'] = $get_limit;
if(!empty($carray['order']))
	$carray['order'] = $carray['order']." DESC";
else
	$carray['order'] = " collection_id DESC";	

$collections = $cbcollection->get_collections($carray);
assign('c',$collections);

/* COUNTING ALL COLLECTIONS */
$ccount = $carray;
$ccount['count_only'] = TRUE;
$total_rows = $cbcollection->get_collections($ccount);
$total_pages = count_pages($total_rows,RESULTS);
$pages->paginate($total_pages,$page);

subtitle("Collection Manager");
template_files('collection_manager.html');
display_it();
?>