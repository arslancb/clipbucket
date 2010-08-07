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
	$data = "404_error";
	if(has_access('admin_access'))
		e(sprintf(lang("err_warning"),"404","http://lol.com.pk"),"w");	
	e(lang($data));
}

display_it();
?>