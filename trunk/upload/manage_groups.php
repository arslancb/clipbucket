<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , � PHPBucket.com														|
 ****************************************************************************************************
*/

define("THIS_PAGE",'manage_videos');

require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);

switch($mode)
{
	case 'manage':
	default:
	{
		if($_GET['gid_delete'])
		{
			$gid = $_GET['gid_delete'];
			$cbgroup->delete_group($gid);
		}
		
		assign('mode','manage');
		$usr_groups = $cbgroup->get_groups(array('user'=>userid()));
		assign('usr_groups',$usr_groups);
	}
	break;
	
	case 'manage_members':
	{
		assign('mode','manage_members');
		$gid = mysql_clean($_GET['gid']);
		$gdetails = $cbgroup->get_group_details($gid);
		
		//Activating Member Members
		if(isset($_POST['activate_pending']))
		{
			$total = count($_POST['users']);
			for($i=0;$i<$total;$i++)
			{
				if($_POST['users'][$i]!='')
					$cbgroup->member_actions($gid,$_POST['users'][$i],'activate');
			}
		}
		//Deactivation Members
		if(isset($_POST['disapprove_members']))
		{
			$total = count($_POST['users']);
			for($i=0;$i<$total;$i++)
			{
				if($_POST['users'][$i]!='')
					$cbgroup->member_actions($gid,$_POST['users'][$i],'deactivate');
			}
		
		}
		//Deleting Members
		if(isset($_POST['delete_members']))
		{
			$total = count($_POST['users']);
			for($i=0;$i<$total;$i++)
			{
				if($_POST['users'][$i]!='')
					$cbgroup->member_actions($gid,$_POST['users'][$i],'delete');
			}
		
		}
		
		if($gdetails)
		{
			assign("group",$gdetails);
			//Getting Group Members (Active Only)
			$gp_mems = $cbgroup->get_members($gdetails['group_id'],"yes");
			assign('gp_mems',$gp_mems);
			
		}else
			e("Group does not exist");
	}
	break;
	case 'manage_videos':
	{
		assign('mode','manage_videos');
		$gid = mysql_clean($_GET['gid']);
		$gdetails = $cbgroup->get_group_details($gid);
		
		
		//Activating Member Members
		if(isset($_POST['activate_videos']))
		{
			$total = count($_POST['check_vid']);
			for($i=0;$i<$total;$i++)
			{
				if($_POST['check_vid'][$i]!='')
					$cbgroup->video_actions($gid,$_POST['check_vid'][$i],'activate');
			}
		}
		//Deactivation Members
		if(isset($_POST['disapprove_videos']))
		{
			$total = count($_POST['check_vid']);
			for($i=0;$i<$total;$i++)
			{
				if($_POST['check_vid'][$i]!='')
					$cbgroup->video_actions($gid,$_POST['check_vid'][$i],'deactivate');
			}
		
		}
		//Deleting Members
		if(isset($_POST['delete_videos']))
		{
			$total = count($_POST['check_vid']);
			for($i=0;$i<$total;$i++)
			{
				if($_POST['check_vid'][$i]!='')
					$cbgroup->video_actions($gid,$_POST['check_vid'][$i],'delete');
			}
		
		}
		
		
		if($gdetails)
		{
			assign("group",$gdetails);
			//Getting Group Videos (Active Only)
			$grp_vids = $cbgroup->get_group_videos($gid,"yes");
			assign('grp_vids',$grp_vids);
		}else
			e("Group does not exist");
		
	}
	break;
	
	case 'joined':
	{
		
		//Leaving Groups
		if(isset($_POST['leave_groups']))
		{
			$total = count($_POST['check_gid']);
			for($i=0;$i<$total;$i++)
				$cbgroup->leave_group($_POST['check_gid'][$i],userid());
		}
		
		assign('mode','joined');
		$mem_grps = $cbgroup->user_joined_groups(userid());
		assign('usr_groups',$mem_grps);
		
	}
	break;
}


template_files('manage_groups.html');
display_it();
?>