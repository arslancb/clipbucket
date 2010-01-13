<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
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
	$vid_file = get_video_file($data);
	
	# Uploading Thumbs
	if(isset($_POST['upload_thumbs'])){
		$Upload->upload_thumbs($data['file_name'],$_FILES['vid_thumb']);
	}
	
	# Delete Thumb
	if(isset($_GET['delete']))
		delete_video_thumb($_GET['delete']);
	
	# Generating more thumbs
	if(isset($_GET['gen_more']))
	{
		require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.win32.php');
		$ffmpeg = new ffmpeg($file_details['output_path']);
		$ffmpeg->generate_thumbs($vid_file,$data['duration'],$dim='120x90',$num=3,$rand=true);
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