<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE",'channels');
define("PARENT_PAGE",'channels');

require 'includes/config.inc.php';
$userquery->perm_check('view_channels',true);

//Setting Sort
$sort = $_GET['sort'];
$u_cond = array('category'=>mysql_clean($_GET['cat']),'date_span'=>$_GET['time']);

switch($sort)
{
	case "most_recent":
	default:
	{
		$u_cond['order'] = " doj DESC ";
	}
	break;
	case "most_viewed":
	{
		$u_cond['order'] = " profile_hits DESC ";
	}
	break;
	case "featured":
	{
		$u_cond['featured'] = "yes";
	}
	break;
	case "top_rated":
	{
		$u_cond['order'] = " rating DESC";
	}
	break;
	case "most_commented":
	{
		$u_cond['order'] = " total_comments DESC";
	}
	break;
}

//Getting User List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,CLISTPP);
$count_query = $ulist = $u_cond;
$ulist['limit'] = $get_limit;
$users = get_users($ulist);
Assign('users', $users);	


if($_GET['cat'])
{
	$category = $userquery->get_category_field(mysql_clean(get('cat')),'category_name');
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

$counter = get_counter('channel',$count_query);

if(!$counter)
{
	//Collecting Data for Pagination
	$ucount = $u_cond;
	$ucount['count_only'] = true;
	$total_rows  = get_users($ucount);
	$counter = $total_rows;
	update_counter('channel',$count_query,$counter);
}

$total_pages = count_pages($counter,CLISTPP);
//Pagination
$pages->paginate($total_pages,$page);

$subtitle = lang('Channels');
if($category)
	$subtitle .= " &#8250; ".$category;
if($sort)
	$subtitle .= " &#8226; ".$sort;
	
subtitle($subtitle);

template_files('channels.html');
display_it();


?>