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

$pages->page_redir();
subtitle('upload');

//Checking if user is guest 
if(userid())
	$verify_logged_user = true;
else
	$verify_logged_user = false;

if(has_access('allow_video_upload',false,$verify_logged_user))
{
	$file_name = time().RandomString(5);
	assign('file_name',$file_name);
			
	$step = 1;
	if(isset($_POST['submit_data']))
	{
		$Upload->validate_video_upload_form();
		if(empty($eh->error_list))
		{
			$step=2;
		}
	}
	
	if(isset($_POST['submit_upload']))
	{
		if(!$_POST['file_name'])
			$_POST['file_name'] = time().RandomString(5);

		//$Upload->validate_video_upload_form(NULL,TRUE);
		if(empty($eh->error_list))
		{
			$vid = $Upload->submit_upload();
			//echo $db->db_query;
			//Call file so it can activate video
			$Upload->do_after_video_upload($vid);
			
			if(!error())
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
}else
{
	$userquery->logincheck('allow_video_upload',true);
}

Assign('step',$step);

subtitle(lang('upload'));
//Displaying The Template
template_files('upload.html');
display_it();

?>