<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com							
 ***************************************************************
*/
define("THIS_PAGE",'videos');
define("PARENT_PAGE",'videos');
require 'includes/config.inc.php';
$pages->page_redir();
$userquery->perm_check('view_videos',true);

//Setting Sort
$sort = $_GET['sort'];
$child_ids = "";
$cat = mysql_clean('cat');
if(!is_numeric($cat))
    $cat = 'all';

$time = mysql_clean(get('time'));

if($cat && $cat!='all')
{
	$childs = $cbvid->get_sub_categories($cat);
	$child_ids = array();
	if($childs)
		foreach($childs as $child)
		{
			$child_ids[] = $child['category_id'];
			$subchilds = $childs = $cbvid->get_sub_categories($child['category_id']);
			if($subchilds)
			foreach($subchilds as $subchild)
			{
				$child_ids[] = $subchild['category_id'];
			}
		}
	$child_ids[] = $cat;
}

$vid_cond = array('category'=>$child_ids,'date_span'=>$time,'sub_cats');

switch($sort)
{
	case "most_recent":
	default:
	{
		$vid_cond['order'] = " date_added DESC ";
	}
	break;
	case "most_viewed":
	{
		$vid_cond['order'] = " views DESC ";
		$vid_cond['date_span_column'] = 'last_viewed';
	}
	break;
	case "most_viewed":
	{
		$vid_cond['order'] = " views DESC ";
	}
	break;
	case "featured":
	{
		$vid_cond['featured'] = "yes";
	}
	break;
	case "top_rated":
	{
		$vid_cond['order'] = " rating DESC, rated_by DESC";
	}
	break;
	case "most_commented":
	{
		$vid_cond['order'] = " comments_count DESC";
	}
	break;
}

//Getting Video List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);
$vlist = $vid_cond;
$count_query = $vid_cond;
$vlist['limit'] = $get_limit;
$videos = get_videos($vlist);
Assign('videos', $videos);	

if($_GET['cat'])
{
	$category = $cbvid->get_category_field(mysql_clean(get('cat')),'category_name');
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

//Collecting Data for Pagination
$vcount = $vid_cond;
$counter = get_counter('video',$count_query);
if(!$counter)
{
	$vcount['count_only'] = true;
	$total_rows  = get_videos($vcount);
	$total_pages = count_pages($total_rows,VLISTPP);
	$counter = $total_rows;
	update_counter('video',$count_query,$counter);
}

$total_pages = count_pages($counter,VLISTPP);
//Pagination
$pages->paginate($total_pages,$page);

$subtitle = lang('videos');
if($category)
	$subtitle .= " &#8250; ".$category;
if($sort)
	$subtitle .= " &#8226; ".$sort;
	
subtitle($subtitle);

//Displaying The Template
template_files('videos.html');
display_it();
?>