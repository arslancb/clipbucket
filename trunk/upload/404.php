<?php
/* 
 ********************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com							
 ********************************************************************
*/

define("THIS_PAGE",'404');
require 'includes/config.inc.php';

if(file_exists(LAYOUT."/404.html")) {
	template_files('404.html');
} else {
	$file_name = "404.html";
	$fh = fopen(LAYOUT."/".$file_name,'w');
	$data = "<h2>404 Error</h2>\n";
	$data .= "<p>Requested page not found.</p>";
	fwrite($fh, $data);
	fclose($fh);
	template_files('404.html');
}

display_it();
?>