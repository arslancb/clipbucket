<?php
 /**
  * Written by : Arslan Hassan
  * Software : ClipBucket v2
  * License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
  * 
  * I have done MANY experiments on video conversion
  * so these results are the best possible output
  * you can further discuss about it on forums.clip-bucket.com
  **/

	

	ini_set('mysql.connect_timeout','6000');

	$in_bg_cron = true;

	include(dirname(__FILE__)."/../includes/config.inc.php");
	//Calling Cron Functions
	cb_call_functions('video_convert_cron');

	$server_friendly = config('server_friendly_conversion');
	$use_crons = config('use_crons');
	
	if($server_friendly=='yes' && $use_crons=='yes')
	{
		/**
		 *
		 * Thanks to Erickson Reyes ercbluemonday at yahoo dot com | so processes dont overlap
		 * ref : http://www.php.net/manual/en/function.getmypid.php#94531*/	
		 
		// Initialize variables
		$found            = 0;
		$file                 = basename(__FILE__);
		$commands    = array();
		
			// Get running processes.
		exec("ps w", $commands);
		
			// If processes are found
		if (count($commands) > 0) {
		
			foreach ($commands as $command) {
				if (strpos($command, $file) === false) {
								   // Do nothin'
				}
				else {
								   // Let's count how many times the file is found.
					$found++;
				}
			}
		}
		
			// If the instance of the file is found more than once.
		if ($found > 1) {
			echo "Another process is running.\n";
			die();
		}
	}

$SYSTEM_OS = $row['sys_os'] ? $row['sys_os'] : 'linux';
	
//Including FFMPEG CLASS
require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.class.php');

if($argv[1])
	$fileName = $argv[1];
else
	$fileName = false;

if($argv[2] == 'sleep')
	$dosleep = 'sleep';
else
	$dosleep = '';

//Get Vido
$queue_details = get_queued_video(TRUE,$fileName);
if(!$queue_details)
	exit("Nothing to do");
	
	
//Setting up details, moving files
$tmp_file = $queue_details['cqueue_name'];
$tmp_ext =  $queue_details['cqueue_tmp_ext'];
$ext =  $queue_details['cqueue_ext'];

if(!empty($tmp_file)){	


$temp_file = TEMP_DIR.'/'.$tmp_file.'.'.$tmp_ext;
$orig_file = CON_DIR.'/'.$tmp_file.'.'.$ext;

	//copy($temp_file,$orig_file);
	rename($temp_file,$orig_file);

	$res169 = array();
	$res169['240'] = array('427','240');
	$res169['360'] = array('640','360');
	$res169['480'] = array('853','480');
	$res169['720'] = array('1280','720');
	$res169['1080'] = array('1920','1080');
	
	$res43 = array();
	$res43['240'] = array('320','240');
	$res43['360'] = array('480','360');
	$res43['480'] = array('640','480');
	$res43['720'] = array('960','720');
	$res43['1080'] = array('1440','1080');
		
	$configs = array
	(
		'use_video_rate' => true,
		'use_video_bit_rate' => true,
		'use_audio_rate' => true,
		'use_audio_bit_rate' => true,
		'use_audio_codec' => true,
		'use_video_codec'	=> true,
		'format' => 'flv',
		'video_codec'=> config('video_codec'),
		'audio_codec'=> config('audio_codec'),
		'audio_rate'=> config("srate"),
		'audio_bitrate'=> config("sbrate"),
		'video_rate'=> config("vrate"),
		'video_bitrate'=> config("vbrate"),
		'normal_res' => config('normal_resolution'),
		'high_res' => config('high_resolution'),
		'max_video_duration' => config('max_video_duration'),
		'res169' => $res169,
		'res43' => $res43,
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
	$ffmpeg->num_of_thumbs = config('num_thumbs');
	$ffmpeg->thumb_dim = config('thumb_width')."x".config('thumb_height');
	$ffmpeg->big_thumb_dim = config('big_thumb_width')."x".config('big_thumb_height');
	$ffmpeg->tmp_dir = TEMP_DIR;
	$ffmpeg->input_ext = $ext;
	$ffmpeg->output_file = VIDEOS_DIR.'/'.$tmp_file.'.flv';
	$ffmpeg->hq_output_file = VIDEOS_DIR.'/'.$tmp_file.'.mp4';
	$ffmpeg->log_file = LOGS_DIR.'/'.$tmp_file.'.log';
	//$ffmpeg->remove_input = TRUE;
	$ffmpeg->keep_original = config('keep_original');
	$ffmpeg->original_output_path = ORIGINAL_DIR.'/'.$tmp_file.'.'.$ext;
	$ffmpeg->ClipBucket();
	
	
	
		
	////exec(php_path()." -q ".BASEDIR."/actions/verify_converted_videos.php &> /dev/null &");
	if (stristr(PHP_OS, 'WIN'))
	{
		exec(php_path()." -q ".BASEDIR."/actions/verify_converted_videos.php $fileName $dosleep");
	} else {
		exec(php_path()." -q ".BASEDIR."/actions/verify_converted_videos.php $fileName $dosleep &> /dev/null &");
	}


	//Converting File In HD Format
	if($ffmpeg->input_details['video_height']>'719')
	$hq_output = config('hq_output');
	else 
	$hq_output = 'no';
	
	
	if($hq_output=='yes' && !$ffmpeg->failed_reason)
	{
		while(1)
		{
			$use_crons = config('use_crons');
			
			if(!$ffmpeg->isLocked(PROCESSESS_AT_ONCE) || $use_crons=='yes' || !$ffmpeg->set_conv_lock)
			{
				
				$ffmpeg->convert_to_hd();			
				if($ffmpeg->lock_file && file_exists($ffmpeg->lock_file))
				unlink($ffmpeg->lock_file);
	
				break;
			}
			
			
			if($use_crons=='no')
				sleep(10);
			else
				break;
		}
	}

	
	unlink($ffmpeg->input_file);
}


?>