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

//When we need 
define(TEMP_DIR,dirname(__FILE__).'/../files/temp');
error_reporting(E_ALL ^E_NOTICE);

$file_name = $_POST['file_name'];
$log_file = TEMP_DIR.'/'.$file_name.'_curl_log.cblog';
//For PHP < 5.3.0
$dummy_file = TEMP_DIR.'/'.$file_name.'_curl_dummy.cblog';


include("include_functions.php");


if(file_exists($dummy_file))
{
	//Read the data
	$data = file_get_contents($dummy_file);
	$data = json_decode($data,true);
	
	$file = $data['file_name'];
	$started = $data['time_started'];
	
	//Total File Size
	$total_size = $data['file_size'];
	
	$byte_size = $data['byte_size'];
	
	//Let check whats the file size right now
	$data['byte_size'] = $now_file_size = filesize(TEMP_DIR.'/'.$file);
	
	//Bytes Transfered
	$cur_speed = $now_file_size - $byte_size;
	
	//Time Eta
	$download_bytes = $total_size - $now_file_size;
	if($cur_speed>0)
	$time_eta = $download_bytes/$cur_speed;
	else
	$time_eta = 0;
	
	//Time Took 
	$time_took = time() - $started;
	
	$curl_info = 
	array(
	'total_size' => $total_size,
	'downloaded' => $now_file_size,
	'speed_download' => $cur_speed,
	'time_eta' => $time_eta,
	'time_took'=> $time_took,
	'file_name'=> $file
	);
	
	$fo = fopen($log_file,'w+');
	fwrite($fo,json_encode($curl_info));
	fclose($fo);
	
	$fo = fopen($dummy_file,'w+');
	fwrite($fo,json_encode($data));
	fclose($fo);
	
	if($total_size==$now_file_size)
	unlink($dummy_file);
}

if(file_exists($log_file))
{
	$details =  file_get_contents($log_file);
	$details = json_decode($details,true);
	$details['total_size_fm'] = formatfilesize($details['total_size']);
	$details['downloaded_fm'] = formatfilesize($details['downloaded']);
	$details['time_eta_fm'] = SetTime($details['time_eta']);
	$details['time_took_fm'] = SetTime($details['time_took']);
	echo json_encode($details);
}
?>