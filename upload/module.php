<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require 'includes/config.inc.php';

define("IN_MODULE",true);
define("PARENT_PAGE",$_GET['s']);
define("THIS_PAGE",$_GET['p']);


$pages->page_redir();

//Loading Module Files
load_modules();

if(!defined("THIS_PAGE"))
	e("Invalid module");
	
display_it();

?>