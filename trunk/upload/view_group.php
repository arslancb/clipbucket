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


if($details)
{		
	$shouldView = $cbgroup->is_viewable($details);
	if(!$shouldView)
		$Cbucket->show_page = false;
	else
	{
		assign('isviewable',$shouldView);
		if(isset($_POST['update_group']))
		{
			//pr($_POST,true);
			$_POST['group_id'] = $details['group_id'];
			$cbgroup->update_group();
			$details = $cbgroup->get_group_details($details['group_id']);	
		}		
		//Joining Group
		if($_GET['join'])
			$cbgroup->join_group($details['group_id'],userid());
		//Leaving
		if($_GET['leave'])
			$cbgroup->leave_group($details['group_id'],userid());
						
		//adding group topic
		if(isset($_POST['add_topic']))
		{
			$array = $_POST;
			$array['group_id'] = $details['group_id'];
			$cbgroup->add_topic($array);
			if(!error()) $_POST = NULL;
		}
		
		// Sending invitations
		if(isset($_POST['invite_friends']))
			$cbgroup->invite_members($_POST['friend'],$details['group_id']);
		
		//Adding videos to group
		if(isset($_POST['add_videos']))
		{
			
			$total = count($usr_vids);
			for($i=0;$i<$total;$i++)
			{
				$videoid = $usr_vids[$i]['videoid'];
				if($_POST['check_video_'.$videoid]=='yes')
					$cbgroup->add_group_video($videoid,$details['group_id'],false);
				else
					$cbgroup->remove_group_video($videoid,$details['group_id'],false);
			}
			//Update Group Total Videos
			$cbgroup->update_group_videos_count($details['group_id']);
			
			$eh->flush_msg();
			e(lang("sel_vids_updated"),"m");
		}
			
		//Calling all functions when a topic is called
		call_view_group_functions($details);

		switch($mode)
		{
			case 'view_topics':
			{
				if($_GET['topic_action'] == "delete")
				{
					if(!empty($_GET['topic_id'])) {
						$tid = $_GET['topic_id'];
						$cbgroup->delete_topic($tid);
					}					
				}
			}
			break;
		}
		
		//Getting list of topics
		$topics = $cbgroup->get_topics(array('group'=>$details['group_id']));
		assign('topics',$topics);
		assign('mode',$mode);
		assign('group',$details);
	}
	subtitle($details['group_name']);	
}else
{
	e(lang("grp_exist_error"));
	$Cbucket->show_page = false;
}


template_files('view_group.html');
display_it();
?>