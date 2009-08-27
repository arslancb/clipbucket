<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
	require_once 'includes/config.inc.php';

$vkey 	= mysql_clean($_GET['v']);
//Checking Video Key
if(!empty($vkey) && $myquery->CheckVideoExists($vkey)){
	$videos 	= $myquery->GetVideDetails($vkey);
	$query	= mysql_query("SELECT * FROM video_detail WHERE flv ='".$videos['flv']."'");
	$data	= mysql_fetch_array($query);
	$flv_folder = 'files/videos';
	$original_folder = 'files/original';
	$flv = $flv_folder.'/'.$data['flv'];
	$original = $original_folder.'/'.$data['original'];
	if(!empty($data['original']) && file_exists($original)){
		$file = $original;
	}elseif(file_exists($flv)){
		$file = $flv;
	}else{
		$msg = $LANG['class_vdo_del_err'];
	}
	if(VIDEO_DOWNLOAD != 1){
		$msg = $LANG['vdo_download_allow_err'];
	}
	
	if(empty($msg)){
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
    header("Content-type: application/force-download");
    header("Content-Length: ".filesize($file)); 
    header("Content-Disposition: attachment; filename=\"".basename($file)."\""); 
    readfile("$file"); 
	}
}
if(!empty($msg) || empty($vkey) || !$myquery->CheckVideoExists($vkey)){
Assign('msg',$msg);
if(empty($msg))
Assign('msg',$LANG['class_vdo_del_err']);
Assign('subtitle',$LANG['class_vdo_del_err']);
Template('header.html');
Template('message.html');
Template('footer.html');
}
?>