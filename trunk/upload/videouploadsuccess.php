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
subtitle('videouploadsuccess');


print_r($_POST);
exit();

$category01 = $_POST['category01'];
$category02 = $_POST['category02'];
$category03 = $_POST['category03'];

if(empty($_GET['show'])){
	if(empty($_POST['title'])){
	redirect_to(BASEURL);
	}
$Upload->UploadProcess();
}

$flv = @$_COOKIE['flv_upload'];
Assign('flv',$flv);

@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('videouploadsuccess.html');
Template('footer.html');
?>