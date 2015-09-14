<?php

	// This script runs only via command line

	include(dirname(__FILE__)."/../includes/config.inc.php");
	require_once(dirname(dirname(__FILE__))."/includes/classes/sLog.php");
	define("MP4Box_BINARY",get_binaries('MP4Box'));
	define("FLVTool2_BINARY",get_binaries('flvtool2'));
	define('FFMPEG_BINARY', get_binaries('ffmpeg'));

	/*
		getting the aguments
		$argv[1] => first argument, in our case its the path of the file
	*/
	$log = new SLog();

	//error_reporting(E_ALL);
	logData(json_encode($argv),"argvs");
	$fileName = (isset($argv[1])) ? $argv[1] : false;
	$_filename = (isset($argv[2])) ? $argv[2] : false;
	$file_directory = (isset($argv[3])) ? $argv[3] : false;
	$file_directory = $file_directory.'/';


	logData("1.Initializing Conversion of Video Filename : ".$_filename,'checkpoints');
	//$fileName = "/home/sajjad/Desktop/abc.mp4";
	$log->newSection("Starting Conversion Log");
	$log->writeLine("File to be converted", $fileName, false);
	$status = "Successful";
	/*
		Getting the videos which are currently in our queue
		waiting for conversion
	*/

	if(isset($_GET['test']))
		$queue_details = get_queued_video(false,$fileName);
	else
		$queue_details = get_queued_video(TRUE,$fileName);


	

	$fileDir 	= $queue_details["date_added"];
	$dateAdded 	= explode(" ", $fileDir);
	$dateAdded 	= array_shift($dateAdded);
	$fileDir 	= implode("/", explode("-", $dateAdded));
	//logData($fileDir);

	/*
		Getting the file information from the queue for conversion
	*/

	$tmp_file 	= $queue_details['cqueue_name'];
	$tmp_ext 	=  $queue_details['cqueue_tmp_ext'];
	$ext 		=  $queue_details['cqueue_ext'];
	$outputFileName = $tmp_file;
	if(!empty($tmp_file)){

	$temp_file 	= TEMP_DIR.'/'.$tmp_file.'.'.$tmp_ext;
	$orig_file 	= CON_DIR.'/'.$tmp_file.'.'.$ext;

	/*
		Delete the uploaded file from temp directory 
		and move it into the conversion queue directory for conversion
	*/

	if(isset($_GET['test']))
		copy($temp_file,$orig_file);
	else
		rename($temp_file,$orig_file);

	/*
		Preparing the configurations for video conversion from database
	*/
	logData('Preparing configuration to parse in ffmpeg class','checkpoints');

	$configs = array(
		'format' => 'mp4',
		'video_codec'=> config('video_codec'),
		'audio_codec'=> config('audio_codec'),
		'audio_rate'=> config("srate"),
		'audio_bitrate'=> config("sbrate"),
		'video_rate'=> config("vrate"),
		'video_bitrate'=> config("vbrate"),
		'video_bitrate_hd'=> config("vbrate_hd"),
		'normal_res' => config('normal_resolution'),
		'high_res' => config('high_resolution'),
		'max_video_duration' => config('max_video_duration'),
		'resize'=>'max',
		'outputPath' => $fileDir
	);

	logData('Inlcuding FFmpeg Class','checkpoints');
	require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.class.php');
	
	$ffmpeg = new FFMpeg($configs, $log);
	$ffmpeg->ffmpeg($orig_file);
	$ffmpeg->cb_combo_res = config('cb_combo_res');
	$ffmpeg->res_configurations = array('gen_240'  => config('gen_240'),
										'gen_360'  => config('gen_360'),
										'gen_480'  => config('gen_480'),
										'gen_720'  => config('gen_720'),
										'gen_1080' => config('gen_1080')
										);
	logData($ffmpeg->res_configurations,'checkpoints');
	$ffmpeg->file_name = $tmp_file;
	$ffmpeg->filetune_directory = $file_directory;
	$ffmpeg->raw_path = VIDEOS_DIR.'/'.$file_directory.$_filename;
	//$ffmpeg->logs = $log;
	logData('Going to call ClipBucket Function','checkpoints');
	$ffmpeg->ClipBucket();
	

	if (stristr(PHP_OS, 'WIN'))
	{
		exec(php_path()." -q ".BASEDIR."/actions/verify_converted_videos.php $orig_file $dosleep");
	}elseif(stristr(PHP_OS, 'darwin'))
	{
		exec(php_path()." -q ".BASEDIR."/actions/verify_converted_videos.php $orig_file $dosleep </dev/null >/dev/null &");
		
	} else {
		exec(php_path()." -q ".BASEDIR."/actions/verify_converted_videos.php $orig_file $dosleep &> /dev/null &");
	}

	if(!isset($_GET['test']))
	unlink($orig_file);


}


exit();
$str = "/".date("Y")."/".date("m")."/".date("d")."/";
$orig_file1 = BASEDIR.'/files/videos'.$str.$tmp_file.'-sd.'.$ext;

if($orig_file1)
{
	$status = "Successful";
	if(PHP_OS == "Linux")
	{
		$ffMpegPath = FFMPEG_BINARY;
		file_put_contents('test.txt', $ffMpegPath." -i ".$orig_file1." -acodec copy -vcodec copy -y -f null /dev/null 2>&1");
		$out = shell_exec($ffMpegPath." -i ".$orig_file1." -acodec copy -vcodec copy -y -f null /dev/null 2>&1");
		
		sleep(1);
		
		$log->writeLog();
		$len = strlen($out);
		$findme = 'Duration';
		$findme1 = 'start';
		$pos = strpos($out, $findme);
		$pos = $pos + 10;
		$pos1 = strpos($out, $findme1);
		$bw = $len - ($pos1 - 5);
		$rest = substr($out, $pos, -$bw);
		$duration = explode(':',$rest);
		//Convert Duration to seconds
		$hours = $duration[0];
		$minutes = $duration[1];
		$seconds = $duration[2];
			
		$hours = $hours * 60 * 60;
		$minutes = $minutes * 60;
					
		$duration = $hours+$minutes+$seconds;
		//$duration =  (int) $ffmpeg->videoDetails['duration'];
		if($duration > 0)
		{

				$status = "Successful";
				$log->writeLine("Conversion Result", "Successful");
		}
		else
		{
			$status = "Failure";
			$log->writeLine("Conversion Result", "Failure");
		}
	}
	else
	{
		$ffMpegPath = FFMPEG_BINARY;
		$out = shell_exec($ffMpegPath." -i ".$orig_file1." -acodec copy -vcodec copy -y -f null /dev/null 2>&1");
		sleep(1);
		
		$log->writeLog();
		$len = strlen($out);
		$findme = 'Duration';
		$findme1 = 'start';
		$pos = strpos($out, $findme);
		$pos = $pos + 10;
		$pos1 = strpos($out, $findme1);
		$bw = $len - ($pos1 - 5);
		$rest = substr($out, $pos, -$bw);
		$duration = explode(':',$rest);
		//Convert Duration to seconds
		$hours = $duration[0];
		$minutes = $duration[1];
		$seconds = $duration[2];
			
		$hours = $hours * 60 * 60;
		$minutes = $minutes * 60;
					
		$duration = $hours+$minutes+$seconds;
		//$duration =  (int) $ffmpeg->videoDetails['size'];
		if($duration > "0")
		{

				$status = "Successful";
				
				$db->update(tbl('video'), array("duration"), array($duration), " file_name = '{$outputFileName}'");
				$db->update(tbl('video'), array("status"), array($status), " file_name = '{$outputFileName}'");
			
				$log->writeLine("Conversion Result", "Successful");
		}
		else
		{
			$status = "Failed";
			$db->update(tbl('video'), array("duration"), array($duration), " file_name = '{$outputFileName}'");
			$db->update(tbl('video'), array("status"), array($status), " file_name = '{$outputFileName}'");
			$log->writeLine("Conversion Result", "Failed");
		}
	}
}
// update the video details in the database as successful conversion or not and video duration
$myfile = fopen("123.txt", "w");
$txt = " file_name = '{$outputFileName}'";
fwrite($myfile, $duration.$status.$txt);
fclose($myfile);

