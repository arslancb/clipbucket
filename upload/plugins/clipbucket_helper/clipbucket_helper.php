<?php

/*
Plugin Name: ClipBucket Helper v1
Description: This will help you set troubleshoot your problems.
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/



//Adding Admin Menu
add_admin_menu("ClipBucket Helper","Cron Jobs",'cron_jobs.php','clipbucket_helper/admin');


//Functions
function cron_call_writer($file)
{
	$tmp_file = TEMP_DIR.'/'.$file;
	$file = fopen($tmp_file,"w");
	fwrite($file,now());
}


//Registering Functions
cb_register_function('cron_call_writer','video_convert_cron','video_convert_cron.txt');
cb_register_function('cron_call_writer','verify_converted_videos_cron','verify_converted_videos_cron.txt');
cb_register_function('cron_call_writer','update_cb_stats_cron','update_cb_stats_cron.txt');

$array = array('video_convert_cron.txt','verify_converted_videos_cron.txt','update_cb_stats_cron.txt');
//Reading and registering Contentings
foreach($array as $arr)
	if(file_exists(TEMP_DIR.'/'.$arr))
		assign(getName($arr),file_get_contents(TEMP_DIR.'/'.$arr));


?>