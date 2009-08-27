<?php
require_once('includes/conversion.conf.php');

// Get arguments from the argv array 
$newfilename	= $_SERVER['argv'][1];
$filename 		= $_SERVER['argv'][2]; 

if(!empty($filename)){			
	$ffmpeg = new ffmpeg();
	$ffmpeg->ConvertFile($newfilename,$filename);
}

?>