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
	$data = "<span>404 Error. Requested page not found./<span>\n";
	if(has_access('admin_access'))
		e(lang("Please create your custom 404 error page in your style/template_name/layout folder. Thanks"),"w");
	e(lang($data));
}

display_it();
?>