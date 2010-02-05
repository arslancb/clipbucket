<?php

/**
 * This file is used to verify weather video is converted or not
 * if it is converted then activate it and let it go
 */
 

include(dirname(__FILE__)."/../includes/config.inc.php");

$files = get_video_being_processed();
if(is_array($files))
foreach($files as $file)
{
	$file_details = get_file_details($file['cqueue_name']);
	pr($file_details);
	if($file_details['conversion_status']=='failed')
	{
		
		$db->update(tbl("conversion_queue"),
					array("cqueue_conversion"),
					array("yes")," cqueue_id = '".$file['cqueue_id']."'");
		update_processed_video($file,'Failed');
		
		/**
		 * Calling Functions after converting Video
		 */
		if(get_functions('after_convert_functions'))
		{
			foreach(get_functions('after_convert_functions') as $func)
			{
				if(@function_exists($func))
					$func();
			}
		}
		
		
	}elseif($file_details['conversion_status']=='completed')
	{
		
		$db->update(tbl("conversion_queue"),
					array("cqueue_conversion"),
					array("yes")," cqueue_id = '".$file['cqueue_id']."'");
		update_processed_video($file,'Successful');
		
		/**
		 * Calling Functions after converting Video
		 */
		if(get_functions('after_convert_functions'))
		{
			foreach(get_functions('after_convert_functions') as $func)
			{
				if(@function_exists($func))
					$func();
			}
		}
	}
}
?>