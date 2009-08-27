<?php

error_reporting(0);
require_once('includes/conversion.conf.php');

//Rename File
$filename 	 = $_GET['flv'];
$new_name 	 = substr($filename, 0, strrpos($filename, '.'));
$ext 		 = substr($_FILES['Filedata']['name'], strrpos($_FILES['Filedata']['name'],'.') + 1);
$newfilename = $new_name.".".$ext;


//Upload Path
$filepath = getcwd()."/files/temp/";
if(!file_exists($filepath)) {
	mkdir($filepath,0777);
}

//CHMOD File
chmod(BASEDIR."/files/temp", 0777);
chmod(BASEDIR."/files/original", 0777);
chmod(BASEDIR."/files/videos", 0777);

//Copying File..
if(move_uploaded_file($_FILES['Filedata']['tmp_name'], $filepath.$newfilename)){

$php_path = PHP_PATH;
$ffmpeg = new ffmpeg();
$ffmpeg->ConvertFile($newfilename,$filename);
}

?>