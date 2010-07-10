<?php
/* 
 ********************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com							
 ********************************************************************
*/

define("THIS_PAGE",'403');
require 'includes/config.inc.php';

if(file_exists(LAYOUT."/403.html")) {
	template_files('403.html');
} else {
	$file_name = "403.html";
	$fh = fopen(LAYOUT."/".$file_name,'w');
	$data = "<h2>403 Error</h2>\n";
	$data .= "<p>Sorry, you cannot access this page.</p>";
	fwrite($fh, $data);
	fclose($fh);
	template_files('403.html');
}

display_it();
?>