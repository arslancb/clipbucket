<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author   : ArslanHassan																		|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$userquery->logincheck();
$pages->page_redir();


// Creating Group if button is pressed
if(isset($_POST['create_group'])) {
	$cbgroup->create_group($_POST,userid(),true);	
}
	

template_files('create_group.html');
display_it();

?>