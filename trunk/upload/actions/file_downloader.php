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

////Get File
////$url = 'http://farm3.static.flickr.com/2327/1791102470_1479de524c.jpg';
//$url = $_POST['file_url'];
//
////Checking Extension
//$ext = getExt($url);
//
////Load Class
//$curl = new curl($url);
////$curl->setopt(CURLOPT_FOLLOWLOCATION, true) ;
//
//if(empty($url))
//{
//	$array['err'] = "No url specified";
//	echo json_encode($array);
//	exit();
//}
//
////Checking File size
//if(!is_numeric($curl->file_size) || $curl->file_size == '')
//{
//	$array['err'] = "Unknown file size";
//	echo json_encode($array);
//	exit();
//}
//
//
//if(isset($_POST['check_url']))
//{
//	$array['size'] = $curl->file_size;
//	$array['ext'] = $ext;
//	echo json_encode($array);
//	exit();
//}
//
////Get File Extension
//
////Get File Name
////$file_name = time();
//$file_name = $_POST['file_name'];
//$file_path = TEMP_DIR.'/'.$file_name.'.'.$ext;
////Opening File
//$file = fopen($file_path,"w");
////Reading Content
//$content = $curl->exec();
////writing File
//fwrite($file,$content);
//$Upload->add_conversion_queue($file_name.'.'.$ext);
//
//if ($theError = $curl->hasError())
//{
//  $array['err'] = $theError ;
//  echo json_encode($array);
//}
//
////Closing Curl Session
//$curl->close() ;
//echo "done";
	
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