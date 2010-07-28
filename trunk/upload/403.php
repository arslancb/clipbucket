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
	$data = "<span>403 Error. Sorry, you cannot access this page.</span>\n";
	if(has_access('admin_access'))
		e(lang("Please create your custom 403 error page in your styles/template_name/layout folder. Thanks"),"w");		
	e(lang($data));
	
}

display_it();
?>