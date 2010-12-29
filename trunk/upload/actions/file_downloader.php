<?php

/**
 * This file is used to download files
 * from one server to our server 
 * in prior version, this file was not so reliable
 * this time it has complete set of instruction 
 * and proper downloader
 
 * @Author : Arslan Hassan
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : 01 July 2009
 */


include("../includes/config.inc.php");
include("../includes/classes/curl/class.curl.php");
error_reporting(E_ALL ^E_NOTICE);
	
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

if($_POST['youtube'])
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
			
	$content = file_get_contents($youtube_url);
	$match_arr = 
	array
	(
		"title"=>"/<meta name=\"title\" content=\"(.*)\">/",
		"description"=>"/<meta name=\"description\" content=\"(.*)\">/",
		"tags" =>"/<meta name=\"keywords\" content=\"(.*)\">/",
		"embed_code" => "/<meta name=\"keywords\" content=\"(.*)\">/",
		"duration" => "/<span class=\"video-time\">(.*)<\/span>/"
	);
	
	$vid_array = array();
	foreach($match_arr as $title=> $match)
	{
		preg_match($match,$content,$matches);
		$vid_array[$title] = $matches[1];
	}
	
	$vid_array['thumbs'] = 
	array('http://i3.ytimg.com/vi/'.$YouTubeId.'/1.jpg','http://i3.ytimg.com/vi/'.
	$YouTubeId.'/2.jpg','http://i3.ytimg.com/vi/'.$YouTubeId.'/3.jpg',
	'big'=>'http://i3.ytimg.com/vi/'.$YouTubeId.'/2.jpg');
	$vid_array['embed_code'] = '<object width="425" height="344">
	<param name="movie" value="http://www.youtube.com/v/'.$YouTubeId.'">
	</param><param name="allowFullScreen" value="true"></param>
	<param name="allowscriptaccess" value="always"></param>
	<embed src="http://www.youtube.com/v/'.$YouTubeId.'" 
	type="application/x-shockwave-flash" 
	width="425" height="344" 
	allowscriptaccess="always"
	 allowfullscreen="true">
	</embed></object>';
	
	$vid_array['category'] = array($cbvid->get_default_cid());
	$vid_array['file_name'] = $filename;
	$vid_array['userid'] = userid();
	
	$duration = $vid_array['duration'];
	$duration = explode(":",$duration);
	$sep = count($duration);
	if($sep==3)
		$duration = ($duration[0]*60*60)+($duration[1]*60)+($duration[2]);
	else
		$duration = ($duration[0]*60)+($duration[1]);

	$vid = $Upload->submit_upload($vid_array);
	
	if(error())
	{
		exit(json_encode(array('error'=>error('single'))));
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
		snatch_it(urlencode($thumb),THUMBS_DIR,$filename."-$thumbId.jpg");
	}
	
	exit(json_encode(array('vid'=>$vid,
	'title'=>$vid_array['title'],'desc'=>$vid_array['description'],
	'tags'=>$vid_array['tags'])));	
}

 
 
function callback($download_size, $downloaded, $upload_size, $uploaded)
{
	global $curl,$log_file,$file_name,$ext;
	
	$fo = fopen($log_file,'w+');
	//Elapsed time CURLINFO_TOTAL_TIME
	
	$info = curl_getinfo($curl->m_handle);
	
	$download_bytes = $download_size - $downloaded;
	$cur_speed = $info['speed_download'];
	if($cur_speed>0)
	$time_eta = $download_bytes/$cur_speed;
	else
	$time_eta = 0;
	
	$time_took = $info['total_time'];
	
	$curl_info = 
	array(
	'total_size' => $download_size,
	'downloaded' => $downloaded,
	'speed_download' => $info['speed_download'],
	'time_eta' => $time_eta,
	'time_took'=> $time_took,
	'file_name' => $file_name.'.'.$ext
	);
	fwrite($fo,json_encode($curl_info));
	fclose($fo);
}




$file = $_POST['file'];
$file_name = $_POST['file_name'];

$log_file = TEMP_DIR.'/'.$file_name.'_curl_log.cblog';
//For PHP < 5.3.0
$dummy_file = TEMP_DIR.'/'.$file_name.'_curl_dummy.cblog';

$ext = getExt($file);
$svfile = TEMP_DIR.'/'.$file_name.'.'.$ext;

//Checking for the url
if(empty($file))
{
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

echo json_encode(array('msg'=>"done"));
?>