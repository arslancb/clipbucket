<?php

/**
 * This file is used to verify weather video is converted or not
 * if it is converted then activate it and let it go
 */
 
//Sleeping..
//sometimes video is inserted after video conversion so in this case, video can get lost
//if($argv[2]=='sleep')
sleep(10);

$in_bg_cron = true;

include(dirname(__FILE__)."/../includes/config.inc.php");

cb_call_functions('verify_converted_videos_cron');

if($argv[1])
	$fileName = $argv[1];
else
	$fileName = false;

$files = get_video_being_processed($fileName);

if(is_array($files))
foreach($files as $file)
{
	$file_details = get_file_details($file['cqueue_name']);
	//pr($file_details);
	if($file_details['conversion_status']=='failed')
	{
		
		$db->update(tbl("conversion_queue"),
					array("cqueue_conversion"),
					array("yes")," cqueue_id = '".$file['cqueue_id']."'");
		update_processed_video($file,'Failed',$ffmpeg->failed_reason);
		
		/**
		 * Calling Functions after converting Video
		 */
		if(get_functions('after_convert_functions'))
		{
			foreach(get_functions('after_convert_functions') as $func)
			{
				if(@function_exists($func))
					$func($file_details);
			}
		}
		
	}elseif($file_details['conversion_status']=='completed')
	{
		
		$db->update(tbl("conversion_queue"),
					array("cqueue_conversion","time_completed"),
					array("yes",time())," cqueue_id = '".$file['cqueue_id']."'");
		update_processed_video($file,'Successful');
		
		/**
		 * Calling Functions after converting Video
		 */
		if(get_functions('after_convert_functions'))
		{
			foreach(get_functions('after_convert_functions') as $func)
			{
				if(@function_exists($func))
					$func($file_details);
			}
		}
	}
}
?>