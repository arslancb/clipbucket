<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.                                    |
 | @ Author   : ArslanHassan                                                                        |
 | @ Software : ClipBucket , (c) PHPBucket.com                                                      |
 ****************************************************************************************************
*/

$snatch_system = 'curl';

require_once('includes/conversion.conf.php');

$ffmpeg = new ffmpeg();
//Uploading File
if(isset($_POST['upload_file'])) {

	$_SESSION['is_upload'] = "Success";
	$Upload->UploadProcess();

	$filename	= $_POST['flvname'];
	$new_name	= substr($filename, 0, strrpos($filename, '.'));
	$ext		= substr($_FILES['filename']['name'], strrpos($_FILES['filename']['name'],'.') + 1);
	$newfilename	= $new_name.".".$ext;

	$path = BASEDIR."/files/temp/".$newfilename;
	copy($_FILES['filename']['tmp_name'], $path);
	setcookie('flv_upload',$filename);
	$php_path = PHP_PATH;
    $ffmpeg->ConvertFile($newfilename,$filename);
	redirect_to(BASEURL.'/videouploadsuccess.php?show=success');
} elseif(isset($_POST['snatch_file'])) {

	$Upload->UploadProcess();
	$file 		= $_POST['file'];
	$flvname	= $_POST['flvname'];
    $php_path = PHP_PATH;
    exec("$php_path cUrldownload.php $file $flvname >> ".BASEDIR."/logs/logs.txt &");
	redirect_to(BASEURL.'/videouploadsuccess.php?show=success');
	
}
?>
