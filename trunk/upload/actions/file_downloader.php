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

//Get File
//$url = 'http://farm3.static.flickr.com/2327/1791102470_1479de524c.jpg';
$url = $_POST['file_url'];

//Checking Extension
$ext = getExt($url);

//Load Class
$curl = new curl($url);
//$curl->setopt(CURLOPT_FOLLOWLOCATION, true) ;

if(empty($url))
{
	$array['err'] = "No url specified";
	echo json_encode($array);
	exit();
}

//Checking File size
if(!is_numeric($curl->file_size) || $curl->file_size == '')
{
	$array['err'] = "Unknown file size";
	echo json_encode($array);
	exit();
}


if(isset($_POST['check_url']))
{
	$array['size'] = $curl->file_size;
	$array['ext'] = $ext;
	echo json_encode($array);
	exit();
}

//Get File Extension

//Get File Name
//$file_name = time();
$file_name = $_POST['file_name'];
$file_path = TEMP_DIR.'/'.$file_name.'.'.$ext;
//Opening File
$file = fopen($file_path,"w");
//Reading Content
$content = $curl->exec();
//writing File
fwrite($file,$content);
$Upload->add_conversion_queue($file_name.'.'.$ext);

if ($theError = $curl->hasError())
{
  $array['err'] = $theError ;
  echo json_encode($array);
}

//Closing Curl Session
$curl->close() ;
echo "done";
?>