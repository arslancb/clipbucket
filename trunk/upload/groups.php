<?php
/* 
 ***********************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan									
 | @ Software : ClipBucket , © PHPBucket.com				
 ***********************************************************
*/
define("THIS_PAGE","groups");
define("PARENT_PAGE","groups");
require 'includes/config.inc.php';
$pages->page_redir();
$userquery->perm_check('view_groups',true);

//Setting Sort
$sort = $_GET['sort'];
$g_cond = array('category'=>mysql_clean($_GET['cat']),'date_span'=>$_GET['time']);

switch($sort)
{
	case "most_recent":
	default:
	{
		$g_cond['order'] = " date_added DESC ";
	}
	break;
	case "most_viewed":
	{
		$g_cond['order'] = " total_views DESC ";
	}
	break;
	case "featured":
	{
		$g_cond['featured'] = "yes";
	}
	break;
	case "top_rated":
	{
		$g_cond['order'] = " total_members DESC";
	}
	break;
	case "most_commented":
	{
		$g_cond['order'] = " total_topics DESC";
	}
	break;
}


if($_GET['cat'])
{
	$category = $cbgroup->get_category_field(mysql_clean(get('cat')),'category_name');
	assign('category',$category);
}

if($_GET['sort'])
{
	$vsort = mysql_clean(get('sort'));
	$vsort = str_replace('most_comment','comment',$vsort);
	$sort = lang($vsort);
	if($sort!=$vsort)
		assign('sort',$vsort);
	else
		$sort = false;
	
	if($vsort =='most_recent')
		$sort = false;
}

//Getting User List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,GLISTPP);

$glist = $g_cond;

$glist['limit'] = $get_limit;
$groups = $cbgroup->get_groups($glist);
Assign('groups', $groups);	

//Collecting Data for Pagination
$gcount = $g_cond;
$gcount['count_only'] = true;
$total_rows  = $cbgroup->get_groups($gcount);
$total_pages = count_pages($total_rows,GLISTPP);

//Pagination
$pages->paginate($total_pages,$page);
$subtitle = lang('groups');
if($category)
	$subtitle .= " &#8250; ".$category;
if($sort)
	$subtitle .= " &#8226; ".$sort;
	
subtitle($subtitle);

//Displaying The Template
template_files('groups.html');
display_it();
?>