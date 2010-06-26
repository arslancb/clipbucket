<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE","view_group");
define("PARENT_PAGE","groups");

require 'includes/config.inc.php';
$pages->page_redir();

$url = mysql_clean($_GET['url']);
$mode = $_GET['mode'];

$details = $cbgroup->group_details_url($url);
assign('group',$details);

if($details)
{

	//adding group topic
	if(isset($_POST['add_topic']))
	{
		$array = $_POST;
		$array['group_id'] = $details['group_id'];
		$cbgroup->add_topic($array);
		if(!error()) $_POST = NULL;
	}

	//Joining Group
	if($_GET['join'])
		$cbgroup->join_group($details['group_id'],userid());
	//Leaving
	if($_GET['leave'])
		$cbgroup->leave_group($details['group_id'],userid());
		
	//Getting list of topics
	$topics = $cbgroup->get_topics(array('group'=>$details['group_id']));
	assign('topics',$topics);
	
	subtitle($details['group_name']);
	
	//Calling all functions when a topic is called
	call_view_group_functions($details);
	
	switch($mode)
	{
		case 'topic_del':
		{
				if(!empty($_GET['topic_id'])) {
					$tid = $_GET['topic_id'];
					$cbgroup->delete_topic($tid);
				}
		}
	}
	
}else
{
	e(lang("grp_exist_error"));
	$Cbucket->show_page = false;
}


template_files('view_group.html');
display_it();
?>