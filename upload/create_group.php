<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author   : ArslanHassan									
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

define("THIS_PAGE","create_group");
define("PARENT_PAGE","groups");

require 'includes/config.inc.php';
$userquery->logincheck();
$pages->page_redir();


// Creating Group if button is pressed
if(isset($_POST['create_group'])) {
	$cbgroup->create_group($_POST,userid(),true);	
}

subtitle(lang('grp_crt_grp'));

template_files('create_group.html');
display_it();

?>