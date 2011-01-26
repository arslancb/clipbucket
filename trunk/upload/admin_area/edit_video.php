<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

if(@$_GET['msg']){
$msg[] = clean($_GET['msg']);
}	


	$video = mysql_clean($_GET['video']);

	//Updating Video Details
	if(isset($_POST['update'])){
		$Upload->validate_video_upload_form();
		if(empty($eh->error_list))
		{
			$myquery->update_video();
			$myquery->set_default_thumb($video,$_POST['default_thumb']);
		}
	}
	
	//Performing Video Acttions
	if($_GET['mode']!='')
	{
		$cbvid->action($_GET['mode'],$video);
	}
	
	//Check Video Exists or Not
	if($myquery->VideoExists($video)){
		
		//Deleting Comment
		$cid = mysql_clean($_GET['delete_comment']);
		if(!empty($cid))
		{
			$myquery->delete_comment($cid);
		}
		
		
		$data = get_video_details($video);
		Assign('udata',$userquery->get_user_details($data['userid']));
		Assign('data',$data);
	}else{
		$msg[] = lang('class_vdo_del_err');
	}

subtitle("Edit Video");
template_files('edit_video.html');
display_it();

?>