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

error_reporting(E_ALL);

$fileName = (isset($argv[1])) ? $argv[1] : false;
$dosleep = (isset($argv[2])) ? $argv[2] : '';
//$fileName = "/home/sajjad/Desktop/abc.mp4";
$log->newSection("Starting Conversion Log");
$log->writeLine("File to be converted", $fileName, false);

/*
	Getting the videos which are currently in our queue
	waiting for conversion
*/

$queue_details = get_queued_video(TRUE,$fileName);

$fileDir = $queue_details["date_added"];
$dateAdded = explode(" ", $fileDir);
$dateAdded = array_shift($dateAdded);
$fileDir = implode("/", explode("-", $dateAdded));
//logData($fileDir);

/*
	Getting the file information from the queue for conversion
*/

$tmp_file = $queue_details['cqueue_name'];
$tmp_ext =  $queue_details['cqueue_tmp_ext'];
$ext =  $queue_details['cqueue_ext'];
$outputFileName = $tmp_file;
if(!empty($tmp_file)){

$temp_file = TEMP_DIR.'/'.$tmp_file.'.'.$tmp_ext;
$orig_file = CON_DIR.'/'.$tmp_file.'.'.$ext;

/*
	Delete the uploaded file from temp directory 
	and move it into the conversion queue directory for conversion
*/
rename($temp_file,$orig_file);

/*
	Preparing the configurations for video conversion from database
*/

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
	'outputPath' => $fileDir,
);

require_once(BASEDIR.'/ffmpeg.new.class.php');

$ffmpeg = new FFMpeg($configs, $log);
$ffmpeg->convertVideo($orig_file);
	
unlink($orig_file);
}
$status = "Failure";
$duration = 0;
if($ffmpeg->isConversionSuccessful()){
	$videoDetails = $ffmpeg->videoDetails;
	if(count($videoDetails) > 0){
		$duration = $videoDetails["duration"];
		$status = "Successful";
		$log->writeLine("Conversion Result", "Successful");
	}else{
		$log->writeLine("Conversion Result", "Failure");
	}
}else{
	$log->writeLine("Conversion Result", "Failure");
}
$log->writeLog();

// update the video details in the database as successful conversion or not and video duration
$db->update(tbl('video'), array("duration", "status"), array($duration, $status), " file_name = '{$outputFileName}'");