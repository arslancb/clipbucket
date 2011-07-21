<?php
/* 
 * By Arslan Hassan for reparing video durations
 * it will check which videos are already processed
 * and their video duration is still not fixed
 * it will read files 1 by 1 and fix them all
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');

$params = array('duration'=>'1','duration_op'=>'<=','status'=>'Successful');
$videos = get_videos($params);
$fixed_array = array();
if($_POST['fix_duration']
	|| $_POST['mark_failed']
		|| $_POST['mark_delete'])
{
	foreach($videos as $video)
	{
		$log = get_file_details($video['file_name']);
		
		if($log && $_POST['fix_duration'])
		{
			//$duration = $log['output_duration'];
			//if(!$duration)
			//	$duration = $log['duration'];
			
			$duration = parse_duration(LOGS_DIR.'/'.$video['file_name'].'.log');
			
			if(!$duration)
				e("Can't do anything about \"".$video['title']."\"");	
			else
			{
				$db->update(tbl('video'),array('duration'),array($duration),"videoid='".$video['videoid']."'");
				$fixed_array[$video['file_name']] = 'yes';
				e("Succesfully updated duration of \"".$video['title']."\" to ".SetTime($duration),'m');
			}
		}	
		
		if(!$log && $_POST['mark_failed'])
		{
			$db->update(tbl("video"),array("status","failed_reason"),
			array('Failed',"Unable to get video duration")," file_name='".$video['file_name']."'");
			e("\"".$video['title']."\" status has been changed to Failed","m");
		}
		
		if(!$log && $_POST['mark_delete'])
		{
			$db->update(tbl("video"),array("status","failed_reason"),
			array('Failed',"Unable to get video duration")," file_name='".$video['file_name']."'");
			
			$cbvideo->delete_video($video['videoid']);
		}
	}
	$videos = get_videos($params);
}

subtitle("Repair videos duration");

assign('videos',$videos);
assign('fixed_array',$fixed_array);
template_files('repair_vid_duration.html');
display_it();
?>