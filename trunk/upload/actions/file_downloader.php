<?php

/**
 * This file is used to download files
 * from one server to our server 
 * in prior version, this file was not so reliable
 * this time it has complete set of instruction 
 * and proper downloader
 * @author : Arslan Hassan
 * @license : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @since : 01 July 2009
 * @last_modified: 9th July, 2015 (Implementation of YouTube API 3) 
 */


include("../includes/config.inc.php");
include("../includes/classes/curl/class.curl.php");
//error_reporting(E_ALL ^E_NOTICE);/**/
ini_set('max_execution_time', 3000);
if(isset($_POST['check_url']))
{
	$url = $_POST['check_url'];
	
	$types_array = preg_replace('/,/',' ',strtolower(config('allowed_types')));
	$types_array = explode(' ',$types_array);
	$file_ext = strtolower(getExt(strtolower($url)));
		
	if(checkRemoteFile($url) && in_array($file_ext,$types_array) )
	{
		echo json_encode(array('ok'=>'yes'));
	}else
		echo json_encode(array('err'=>'Invalid remote url'));
	exit();
}
//error_reporting(E_ALL); /**/

/**
 * Call back function of cURL handlers
 * when it downloads a file, it works with php >= 5.3.0
 * @param $download_size total file size of the file
 * @param $downloaded total file size that has been downloaded
 * @param $upload_size total file size that has to be uploaded
 * @param $uploadsed total file size that is uploaded
 *
 * Writes the log in file
 */

if(!isCurlInstalled())
{
	exit(json_encode(array("error"=>"Sorry, we do not support remote upload")));
}

//checking if user is logged in or not
if(!userid())
	exit(json_encode(array('error'=>'You are not logged in')));

if(isset($_POST['youtube']))
{
	$youtube_url = $_POST['file'];
	$filename = $_POST['file_name'];	
	
	$ParseUrl = parse_url($youtube_url);
	parse_str($ParseUrl['query'], $youtube_url_prop);
	$YouTubeId = isset($youtube_url_prop['v']) ? $youtube_url_prop['v'] : '';
	
	if(!$YouTubeId)
	{
		exit(json_encode(array("error"=>"Invalid youtube url")));
	}
	

	########################################
	# YouTube Api 3 Starts 				   #
	########################################
	/**
	* After deprecation of YouTube Api 2, ClipBucket is now evolving as
	* it allos grabbing of youtube videos with API 3 now. 
	* @author Saqib Razzaq
	*
	* Tip: Replace part between 'key' to '&' to use your own key
	*/ 

	// grabs video details (snippet, contentDetails)
	$youtube_content = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$YouTubeId.'&key=AIzaSyDOkg-u9jnhP-WnzX5WPJyV1sc5QQrtuyc&part=snippet,contentDetails');
	$content = json_decode($youtube_content);
	
	//$content = xml2array('http://gdata.youtube.com/feeds/api/videos/'.$YouTubeId);

	// getting things such as title out of Snippet
	$data = $content->items[0]->snippet;

	// getting time out of contentDetails
	$time = $content->items[0]->contentDetails->duration;

	/**
	* Converting YouTube Time in seconds
	*/

	function getStringBetween($str,$from,$to)
	{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
	}

	$str = $time;
	$str = str_replace("P", "", $str);
	$from = "T";
	$to = "H";

	$hours = getStringBetween($str,$from,$to);

	$from = "H";
	$to = "M";

	$mins = getStringBetween($str,$from,$to);

	$from = "M";
	$to = "S";

	$secs = getStringBetween($str,$from,$to);

	$hours = $hours * 3600;
	$mins = $mins * 60;
	$total = $hours + $mins + $secs;

	/*	$match_arr = 
	array
	(
		"title"=>"/<meta name=\"title\" content=\"(.*)\">/",
		"description"=>"/<meta name=\"description\" content=\"(.*)\">/",
		"tags" =>"/<meta name=\"keywords\" content=\"(.*)\">/",
		"embed_code" => "/<meta name=\"keywords\" content=\"(.*)\">/",
		"duration" => "/<span class=\"video-time\">([0-9\:]+)<\/span>/"
	);
	
	$vid_array = array();
	foreach($match_arr as $title=> $match)
	{
		preg_match($match,$content,$matches);
		$vid_array[$title] = $matches[1];
	}*/
	
	$vid_array['title'] 		= $data->title;
	$vid_array['description'] 	= $data->description;
	$vid_array['tags'] 			= $data->title;
	$vid_array['duration'] 		= $total;
	
	
	$vid_array['thumbs'] = 
	array('http://i3.ytimg.com/vi/'.$YouTubeId.'/1.jpg','http://i3.ytimg.com/vi/'.
	$YouTubeId.'/2.jpg','http://i3.ytimg.com/vi/'.$YouTubeId.'/3.jpg',
	'big'=>'http://i3.ytimg.com/vi/'.$YouTubeId.'/0.jpg');
	


	$vid_array['embed_code'] = '<iframe width="560" height="315"';
	$vid_array['embed_code'] .= ' src="//www.youtube.com/embed/'.$YouTubeId.'" ';
	$vid_array['embed_code'] .= 'frameborder="0" allowfullscreen></iframe>';
	$file_directory = createDataFolders();
	$vid_array['file_directory'] = $file_directory;
	$vid_array['category'] = array($cbvid->get_default_cid());
	$vid_array['file_name'] = $filename;
	$vid_array['userid'] = userid();
	
	$duration = $vid_array['duration'];
	/*	$duration = explode(":",$duration);
		$sep = count($duration);
		if($sep==3)
			$duration = ($duration[0]*60*60)+($duration[1]*60)+($duration[2]);
		else
			$duration = ($duration[0]*60)+($duration[1]);
	*/
	$vid = $Upload->submit_upload($vid_array);
	
	if(error())
	{
		//exit(json_encode(array('error'=>error('single'))));
	}
	
	if(!function_exists('get_refer_url_from_embed_code'))
	{
		exit(json_encode(array('error'=>"Clipbucket embed module is not installed")));
	}
	
	$ref_url = get_refer_url_from_embed_code(unhtmlentities(stripslashes($vdetails['embed_code'])));
	$ref_url = $ref_url['url'];
	$db->update(tbl("video"),array("status","refer_url","duration"),array('Successful',$ref_url,$duration)," videoid='$vid'");
	
	//Downloading thumb
	foreach($vid_array['thumbs'] as $tid => $thumb)
	{
		if($tid!='big')
			$thumbId = $tid+1;
		else
			$thumbId = 'big';
		snatch_it(urlencode($thumb),THUMBS_DIR.'/'.$file_directory,$filename."-$thumbId.jpg");
	}
	
	exit(json_encode(array('vid'=>$vid,
	'title'=>$vid_array['title'],'desc'=>$vid_array['description'],
	'tags'=>$vid_array['tags'])));	
}

$logDetails = array();


/*
A callback accepting five parameters. The first is the cURL resource, 
the second is the total number of bytes expected to be downloaded in 
this transfer, the third is the number of bytes downloaded so far, 
the fourth is the total number of bytes expected to be uploaded in 
this transfer, and the fifth is the number of bytes uploaded so far. 
*/

function callback($resource, $download_size, $downloaded, $upload_size, $uploaded){
	global $curl,$log_file,$file_name,$ext, $logDetails;
	
	$fo = fopen($log_file,'w+');
	
	$info = curl_getinfo($resource);

	/*$download_bytes = $download_size - $downloaded;
	$cur_speed = $info['speed_download'];
	if($cur_speed > 0)
		$time_eta = $download_bytes/$cur_speed;
	else
		$time_eta = 0;
	//$download_size = (int) $download_size;
	$time_took = $info['total_time'];*/

	if(is_object($resource)){
		$curl_info = array(
		'total_size' => $download_size,
		'downloaded' => $downloaded,
		//'speed_download' => $info['speed_download'],
		//'time_eta' => $time_eta,
		//'time_took'=> $time_took,
		//'file_name' => ($file_name.'.'.$ext),
		);
	}else{
		// for some curl extensions
		$curl_info = array(
		'total_size' => $resource,
		'downloaded' => $download_size,
		//'speed_download' => $info['speed_download'],
		//'time_eta' => $time_eta,
		//'time_took'=> $time_took,
		//'file_name' => ($file_name.'.'.$ext),
		);
	}
	fwrite($fo,json_encode($curl_info));
	$logDetails = $curl_info;
	//echo $log_file;
	fclose($fo);
	//file_put_contents($log_file, json_encode($curl_info));
}




$file = $_POST['file'];
$file_name = mysql_clean($_POST['file_name']);
// $file = "http://clipbucket.dev/abc.mp4";
// $file_name = "abc";

$log_file = TEMP_DIR.'/'.$file_name.'_curl_log.cblog';

//For PHP < 5.3.0
$dummy_file = TEMP_DIR.'/'.$file_name.'_curl_dummy.cblog';


$ext = getExt($file);
$svfile = TEMP_DIR.'/'.$file_name.'.'.$ext;

//Checking for the url
if(empty($file))
{
	echo "error";
	$array['error'] = "Please enter file url";
	echo json_encode($array);
	exit();
}
//Checkinf if extension is wrong
$types = strtolower($Cbucket->configs['allowed_types']);
$types_array = preg_replace('/,/',' ',$types);
$types_array = explode(' ',$types_array);
	
$extension_whitelist = $types_array;
if(!in_array($ext,$extension_whitelist))
{
	$array['error'] = "This file type is not allowed";
	echo json_encode($array);
	exit();
}

$curl = new curl($file);
$curl->setopt(CURLOPT_FOLLOWLOCATION, true) ;


//Checking if file size is not that goood
if(!is_numeric($curl->file_size) || $curl->file_size == '')
{
	$array['error'] = "Unknown file size";
	echo json_encode($array);
	exit();
}

if(phpversion() < '5.3.0')
{
	echo "in less than 5.3";
	//Here we will get file size and write it in a file
	//called dummy_log
	$darray = array(
	'file_size' => $curl->file_size,
	'file_name' => $file_name.'.'.$ext,
	'time_started'=>time(),
	'byte_size' => 0
	);
	$do = fopen($dummy_file,'w+');
	fwrite($do,json_encode($darray));
	fclose($do);	
}

//Opening video file
$temp_fo = fopen($svfile,'w+');
$curlOpt = "";
$curl->setopt(CURLOPT_FILE, $temp_fo);

// Set up the callback
if(phpversion() >= '5.3.0')
{
	$curl->setopt(CURLOPT_NOPROGRESS, false);
	$curl->setopt(CURLOPT_PROGRESSFUNCTION, 'callback');
}

$curl->exec();

if ($theError = $curl->hasError())
{
	$array['error'] = $theError ;
	echo json_encode($array);
}

//Finish Writing File
fclose($temp_fo);
//var_dump($curlOpt);

sleep(2);
$details =  $logDetails;//file_get_contents($log_file);
//$details = json_decode($details,true);
$targetFileName = $file_name . '.' . $ext;
$Upload->add_conversion_queue($targetFileName);

if(file_exists($log_file))
unlink($log_file);
if(file_exists($dummy_file))
	unlink($dummy_file);
$quick_conv = config('quick_conv');
$use_crons = config('use_crons');


//Inserting data
$title 	= urldecode(mysql_clean(getName($file)));
$title = $title ? $title : "Untitled";

$vidDetails = array
(
	'title' => $title,
	'description' => $title,
	'duration' => $total,
	'tags' => genTags(str_replace(' ',', ',$title)),
	'category' => array($cbvid->get_default_cid()),
	'file_name' => $file_name,
	'userid' => userid(),
	'file_directory' => createDataFolders()
);



$vid = $Upload->submit_upload($vidDetails);

echo json_encode(array('vid'=>$vid));


if($quick_conv=='yes' || $use_crons=='no')
{
	//exec(php_path()." -q ".BASEDIR."/actions/video_convert.php &> /dev/null &");
	if (stristr(PHP_OS, 'WIN')) {
			exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $targetFileName sleep");
		} else {
			exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $targetFileName sleep&> /dev/null &");
	}
}

?>