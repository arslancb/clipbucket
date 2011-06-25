<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

if(@$_GET['msg']){
$msg[] = clean($_GET['msg']);
}	

$video = mysql_clean($_GET['video']);


//Check Video Exists or Not
if($myquery->VideoExists($video)){
	
	# Setting Default thumb
	if(isset($_POST['update_default_thumb']))
	{
		$myquery->set_default_thumb($video,$_POST['default_thumb']);
	}
	
	$data = get_video_details($video);;
	$vid_file = VIDEOS_DIR.'/'.get_video_file($data,false,false);
	
	# Uploading Thumbs
	if(isset($_POST['upload_thumbs'])){
		$Upload->upload_thumbs($data['file_name'],$_FILES['vid_thumb']);
	}
	
//	# Uploading Big Thumb
//	if(isset($_POST['upload_big_thumb'])) {
//		$Upload->upload_big_thumb($data['file_name'],$_FILES['big_thumb']);
//	}
	
	# Delete Thumb
	if(isset($_GET['delete']))
		delete_video_thumb($_GET['delete']);
	
	# Generating more thumbs
	if(isset($_GET['gen_more']))
	{
		$num = config('num_thumbs');
		$dim = config('thumb_width').'x'.config('thumb_height');
		$big_dim = config('big_thumb_width').'x'.config('big_thumb_height');
		require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.class.php');
		$ffmpeg = new ffmpeg($vid_file);
		//Generating Thumbs
		$ffmpeg->generate_thumbs($vid_file,$data['duration'],$dim,$num,true);
		//Generating Big Thumb
		$ffmpeg->generate_thumbs($vid_file,$data['duration'],$big_dim,$num,true,true);
	}
	
	Assign('data',$data);
	Assign('rand',rand(44,444));
			
}else{
	$msg[] = lang('class_vdo_del_err');
}
	



subtitle("Video Thumbs Manager");		
template_files('upload_thumbs.html');
display_it();
?>