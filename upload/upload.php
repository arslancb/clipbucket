<?php
/* 
 *******************************************************************
 | Copyright (c) 2007-2016 Clip-Bucket.com. All rights reserved.	
 | @ Author   : ArslanHassan										
 | @ Software : ClipBucket
 | @ Modified : June 9, 2016 by Saqib Razzaq
 *******************************************************************
*/

	define("THIS_PAGE","upload");
	define("PARENT_PAGE","upload");
	require 'includes/config.inc.php';
	$pages->page_redir();
	subtitle('upload');

	//Checking if user is guest 
	if(userid()) {
		$verify_logged_user = true;
	} else {
		$verify_logged_user = false;
	}

	if(has_access('allow_video_upload',false,$verify_logged_user)) {
		#pre_upload();
		$file_name = time().RandomString(5);
		assign('file_name',$file_name);
				
		$step = 1;
		if(isset($_POST['submit_data'])) {
			$Upload->validate_video_upload_form();
			if(empty($eh->error_list)) {
				$step=2;
			}
		}
		
		if(isset($_POST['submit_upload'])) {
			if(!$_POST['file_name'])
				$_POST['file_name'] = time().RandomString(5);
			{
				$file_directory = create_dated_folder(NULL,$_REQUEST['time_stamp']);
				
				$vid = $Upload->submit_upload();
				$Upload->do_after_video_upload($vid);

				echo '<div class="alert alert-success embed_video">
	   			Video has been Embeded succesfully ..
	    			</div>';
	    			
				if(!error())
				$step=3;
			}
		}
		
		//assigning Form Name [RECOMMEND for submitting purpose]
		assign('upload_form_name','UploadForm');
			   
		//Adding Uploading JS Files
		$Cbucket->add_js(array('swfupload/swfupload.js'=>'uploadactive'));
		$Cbucket->add_js(array('swfupload/plugins/swfupload.queue.js'=>'uploadactive'));
		$Cbucket->add_js(array('swfupload/plugins/handlers.js'=>'uploadactive'));
		$Cbucket->add_js(array('swfupload/plugins/fileprogress.js'=>'uploadactive'));
	} else {
		$userquery->logincheck('allow_video_upload',true);
	}

	assign('step',$step);
	assign('extensions', $Cbucket->get_extensions());
	subtitle(lang('upload'));
	//Displaying The Template
	if ( !userid() ) {
		echo '<div id="notlogged_err" class="container alert alert-danger" style="margin-top:70px">You must login to be able to upload content. Login if you have account or register</div>';
	} else {
		template_files('upload.html');
	}

	display_it();

?>