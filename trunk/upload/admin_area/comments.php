<?php
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

if(empty($_GET['type']))
	$type = "v";
else
	$type = $_GET['type'];
$comment_cond = array();
$comment_cond['order'] = " comment_id DESC";

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$comment_cond['limit'] = $get_limit;
assign('type',$type);
switch($type)
{
	case "v":
	default:
	{
		$comment_cond['type'] = "v";
		$comment_cond['type_id'] = "videoid";
		$comment_cond['sectionTable'] = "video";	
	}
	break;
	
	case "c":
	{
		$comment_cond['type'] = "c";
		$comment_cond['type_id'] = "userid";
		$comment_cond['sectionTable'] = "users";
	}
	break;
	
	case "t":
	{
		$comment_cond['type'] = "t";
		$comment_cond['type_id'] = "topic_id";
		$comment_cond['sectionTable'] = "group_topics";
	}
	break;
	
	case "cl":
	{
		$comment_cond['type'] = "cl";
		$comment_cond['type_id'] = "collection_id";
		$comment_cond['sectionTable'] = "collections";
	}
	break;
	
	case "p":
	{
		$comment_cond['type'] = "p";
		$comment_cond['type_id'] = "photo_id";
		$comment_cond['sectionTable'] = "photos";
	}
	break;
}
$comments = getComments($comment_cond);
assign("comments",$comments);

$comment_cond['count_only'] = TRUE;
$total_rows  = getComments($comment_cond);
$total_pages = count_pages($total_rows,RESULTS);
$pages->paginate($total_pages,$page);

subtitle("Comments");
template_files('comments.html');
display_it();
?>