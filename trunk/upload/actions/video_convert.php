<?php
 /**
  * Written by : Arslan Hassan
  * Software : ClipBucket v2
  * License : CBLA
  **/
ini_set('mysql.connect_timeout','6000');

include(dirname(__FILE__)."/../includes/config.inc.php");

$SYSTEM_OS = $row['sys_os'] ? $row['sys_os'] : 'linux';
	
//Including FFMPEG CLASS
require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.win32.php');

//Get Vido
$queue_details = get_queued_video();

//Setting up details, moving files
$tmp_file = $queue_details['cqueue_name'];
$tmp_ext =  $queue_details['cqueue_tmp_ext'];
$ext =  $queue_details['cqueue_ext'];

if(!empty($tmp_file)){	
$temp_file = TEMP_DIR.'/'.$tmp_file.'.'.$tmp_ext;
$orig_file = CON_DIR.'/'.$tmp_file.'.'.$ext;

//copy($temp_file,$orig_file);
rename($temp_file,$orig_file);
	$configs = array
	(
		'use_video_rate' => true,
		'use_video_bit_rate' => true,
		'use_audio_rate' => true,
		'use_audio_bit_rate' => true,
		'use_audio_codec' => true,
		'format' => 'flv',
		'video_codec'=>'libx264',
		'audio_codec'=>'libfaac',
		'audio_rate'=>22050,
		'audio_bitrate'=>128000,
		'video_bitrate'=>512000,
		'video_width'=>400,
		'video_height'=>300,
		'resize'=>'max'
	);

	
	/**
	 * Calling Functions before converting Video
	 */
	if(get_functions('before_convert_functions'))
	{
		foreach(get_functions('before_convert_functions') as $func)
		{
			if(@function_exists($func))
				$func();
		}
	}
	
	
	$ffmpeg = new ffmpeg($orig_file);
	$ffmpeg->configs = $configs;
	$ffmpeg->gen_thumbs = TRUE;
	$ffmpeg->gen_big_thumb = TRUE;
	$ffmpeg->output_file = VIDEOS_DIR.'/'.$tmp_file.'.flv';
	$ffmpeg->hq_output_file = VIDEOS_DIR.'/'.$tmp_file.'.mp4';
	$ffmpeg->remove_input=FALSE;
	$ffmpeg->ClipBucket();
	//Converting File In HD Format
	$ffmpeg->convert_to_hd();
	
	$db->update("conversion_queue",
				array("cqueue_conversion"),
				array("yes")," cqueue_id = '".$queue_details['cqueue_id']."'");

	update_processed_video($queue_details);
	
	/**
	 * Calling Functions before converting Video
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


?>