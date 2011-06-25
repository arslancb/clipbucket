<?php
/* 
 ***********************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.		
 | @ Author 	: ArslanHassan												
 | @ Software 	: ClipBucket , © PHPBucket.com							
 *************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');
$pages->page_redir();

/**
 * Removing Inactive Sessions
 */
if(@$_GET['mode']=='remove_sessions')
{
	$db->Execute("DELETE from ".tbl("sessions")." WHERE 
		TIMESTAMPDIFF(MINUTE,last_active,now()) 
			> 5 AND session_string='guest'");
	$guest_sess = $db->Affected_Rows();
	$db->Execute("DELETE from ".tbl("sessions")." WHERE 
		TIMESTAMPDIFF(MINUTE,last_active,now()) 
			> ". COOKIE_TIMEOUT/60 ." AND session_string='smart_sess'");
	$smart_sess = $db->Affected_Rows();
	
	if($guest_sess)
		e("Removed '".$guest_sess."' inactive guest sessions","m");
	if($smart_sess)
		e("Removed '".$smart_sess."' inactive user sessions","m");
	
	if(!$guest_sess && !$smart_sess)
		e("All inactive sessions are already removed","m");
	
}


/**
 * Removing Old Access log
 */
if(@$_GET['mode']=='remove_access_log')
{
	$days = $_GET['days'];
	$days = mysql_clean($days);
	if(!is_numeric($days) || $days<1)
		$days = 10;
	$days = $days-1;
	$query = "DELETE from ".tbl("action_log")." WHERE 
		DATEDIFF(now(),date_added) > ". $days;
		
	$db->Execute($query);
	
	$rows = $db->Affected_Rows();
	
	$days++;	
	if($rows)
		e($rows. " records removed from action log","m");
	else
		e("Not enough action log found older than ".$days." days","m");
}


if(@$_GET['mode']=='remove_activity_feed')
{
	
	$start_index = $_GET['start_index'] ? $_GET['start_index'] : 0;
	$loop_size = $_GET['loop_size'];
	$loop_size = $loop_size ? $loop_size : 5;
	assign('loop_size',$loop_size);
	$next_index = $start_index+$loop_size;
	assign('next_index',$next_index);
	
	
	//Reindex Videos

	$msg = array();
	$users = get_users(array("usr_status"=>"Ok","limit"=>$start_index.",".$loop_size));
	
	$total_users = get_users(array("count_only"=>true,"usr_status"=>"Ok"));
	$percent = $cbindex->percent(50,$total_users);
	$i = 0;
	
	
	$days = $_GET['days'];
	$days = mysql_clean($days);
	if(!is_numeric($days) || $days<1)
		$days = 10;
	$days = $days-1;
	
	assign('total',$total_users);
	assign('from',$start_index+1);
	assign('days',$days);
	
	$to = $start_index+$loop_size;
	if($to>=$total_users)
	{
		$to = $total_users;
		e($total_users." users have been reindexed successfully.","m");
		assign("stop_loop","yes");
	}
	assign('to',$to);

	while($i < $total_users)
	{
		if($users[$i]['userid'])
		{
			$deleted = 0;
			$user_feed_dir = USER_FEEDS_DIR.'/'.$users[$i]['userid'];
			if($user_feed_dir)
			{
				$feeds = glob($user_feed_dir.'/*.feed');
				if($feeds)
				{
					
					foreach($feeds as $feed)
					{
						$fileName = getName($feed);
						$now = time();
						
						if($now-$fileName >= $days*24*60*60)
						{
							unlink($feed);
							$deleted++;
						}
						
					}
				}
			}
			$msg[] = $users[$i]['userid'].": Removed <em>'".$deleted."'</em> activity feeds of <strong><em>".$users[$i]['username']."</em></strong>"; 	
		}
		$i++;
		
	}
	e($start_index+1 ." - ".$to."  Activity feeds have been deleted.","m");			
	assign("index_msgs",$msg);
	assign("indexing","yes");
	assign('mode','remove_activity_feed');
}


subtitle("Maintenance");
template_files('maintenance.html');
display_it();
?>