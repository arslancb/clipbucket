<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.									|
 | @ Author	   : ArslanHassan																		|
 | @ Software  : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
define('THIS_PAGE','index');
require 'includes/config.inc.php';
$pages->page_redir();

pr($userquery);

if(isset($_GET['cb_ver']) && $is_admin)
{
$msg = "ClipBucket&nbsp;".CB_VERSION."";
}


//Displaying The Template
template_files('index.html');
display_it();
?>