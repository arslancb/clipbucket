<?php
/* 
 ****************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.						
 | @ Author : ArslanHassan													
 | @ Software : ClipBucket , © PHPBucket.com								
 *******************************************************************************
*/

define("THIS_PAGE","add_group_videos");
define("PARENT_PAGE","groups");

require 'includes/config.inc.php';
$pages->page_redir();

$url = mysql_clean($_GET['url']);

$details = $cbgroup->group_details_url($url);
assign('group',$details);


if(!$details)
	e(lang("grp_exist_error"));
elseif(!$cbgroup->is_viewable($details))
	$Cbucket->show_page = false;	
elseif(!$cbgroup->is_member(userid(),$details['group_id']))
	e(lang("you_not_allowed_add_grp_vids"));
else
{	

	///Getting User Videos
	$page = mysql_clean($_GET['page']);
	$get_limit = create_query_limit($page,28);

	$array = array('user'=>userid(),'limit'=>$get_limit);
	$usr_vids = get_videos($array);
	//echo $db->db_query;
	assign('usr_vids',$usr_vids);
	
	$array['count_only'] = true;
	$total_rows  = get_videos($array);
	$total_pages = count_pages($total_rows,28);
	//Pagination
	$pages->paginate($total_pages,$page);


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
	assign('group',$details);
	
	subtitle($details['group_name']);
}
	
template_files('add_group_videos.html');
display_it();
?>