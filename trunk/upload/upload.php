<?php
/* 
 *******************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.	
 | @ Author   : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com
 | @ Modified : June 14, 2009 by Arslan Hassan
 *******************************************************************
*/

define("THIS_PAGE","upload");
define("PARENT_PAGE","upload");

require 'includes/config.inc.php';
$userquery->logincheck();
$userquery->login_check('allow_video_upload');

$pages->page_redir();
subtitle('upload');

$step = 1;
if(isset($_POST['submit_data']))
{
	$Upload->validate_video_upload_form();
	if(empty($eh->error_list))
	{
		
		$file_name = time();
		assign('file_name',$file_name);
		$step=2;
	}
}

if(isset($_POST['submit_upload']))
{
	$step=2;
	$Upload->validate_video_upload_form(NULL,TRUE);
	if(empty($eh->error_list))
	{
		$vid = $Upload->submit_upload();
		//Call file so it can activate video
		exec(php_path()." ".BASEDIR."/actions/process_video.php ".$vid);
		$step=3;
	}
}

//Assigning Form Name [RECOMMEND for submitting purpose]
Assign('upload_form_name','UploadForm');
	   
//Adding Uploading JS Files
$Cbucket->add_js(array('swfupload/swfupload.js'=>'uploadactive'));
$Cbucket->add_js(array('swfupload/plugins/swfupload.queue.js'=>'uploadactive'));
$Cbucket->add_js(array('swfupload/plugins/handlers.js'=>'uploadactive'));
$Cbucket->add_js(array('swfupload/plugins/fileprogress.js'=>'uploadactive'));

Assign('step',$step);

subtitle(lang('upload'));
//Displaying The Template
template_files('upload.html');
display_it();

?>