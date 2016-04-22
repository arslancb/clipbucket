<?php
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Stats And Configurations');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Manage Comments');
}

/*
delete comments
*/
if(isset($_POST['delete_selected'])){
	foreach ($_POST['check_comments'] as $key => $value) {
		$myquery->delete_comment($value);
	}
	//for($id=0;$id<=count($_POST['check_comments']);$id++)
}

/*
Make spam 
*/
if(isset($_POST['not_spam'])){
	
	for($id=0;$id<=count($_POST['check_comments']);$id++)
		$myquery->spam_comment($_POST['check_comments'][$id]);
}
/*
Remove Make spam 
*/
if(isset($_POST['mark_spam'])){
	
	for($id=0;$id<=count($_POST['check_comments']);$id++)
		$myquery->remove_spam($_POST['check_comments'][$id]);
}


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

	case "u":
	{
		$comment_cond['type'] = "u";
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

    case "u":
    {
        $comment_cond['type'] = "u";
        $comment_cond['type_id'] = "userid";
        $comment_cond['sectionTable'] = "users";
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