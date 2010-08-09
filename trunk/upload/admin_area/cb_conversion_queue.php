<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();


if($_GET['delete_lock'])
{
	if(conv_lock_exists())
	{
		unlink(TEMP_DIR.'/conv_lock.loc');
		e("Conversion lock has been deleted","m");
	}else
		e("There is no conversion lock");
}
if(isset($_POST['delete_selected']))
{
	$total = count($_POST['check_queue']);
	for($i=0;$i<=$total;$i++)
	{
		$myquery->queue_action("delete",$_POST['check_queue'][$i]);
	}
	e("Selected items have been deleted","m");
}
if(isset($_POST['processed']))
{
	$total = count($_POST['check_queue']);
	for($i=0;$i<=$total;$i++)
	{
		$myquery->queue_action("processed",$_POST['check_queue'][$i]);
	}
	e("Selected items have been set changed to processed","m");
}
if(isset($_POST['pending']))
{
	$total = count($_POST['check_queue']);
	for($i=0;$i<=$total;$i++)
	{
		$myquery->queue_action("pending",$_POST['check_queue'][$i]);
	}
	e("Selected items have been set changed to processed","m");
}


//Getting List of Conversion Queue
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$queue_list = $myquery->get_conversion_queue(NULL,$get_limit);
assign('queues',$queue_list);
$total_rows  = get_videos($vcount);
$total_pages = count_pages($db->count(tbl('conversion_queue'),"cqueue_id"),RESULTS);
$pages->paginate($total_pages,$page);
	
subtitle("Conversion Queue Manager");
template_files("cb_conversion_queue.html");
display_it();
?>