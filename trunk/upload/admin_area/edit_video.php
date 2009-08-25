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

	//Updating Video Details
	if(isset($_POST['update'])){
		$Upload->validate_video_upload_form();
		if(empty($eh->error_list))
		{
			$myquery->update_video();
			$myquery->set_default_thumb($video,$_POST['default_thumb']);
		}
	}


	//Check Video Exists or Not
	if($myquery->VideoExists($video)){
		$data = get_video_details($video);
		Assign('udata',$userquery->get_user_details($data['userid']));
		Assign('data',$data);
	}else{
		$msg[] = $LANG['class_vdo_del_err'];
	}


Assign('msg',@$msg);	
/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('edit_video.html');
Template('footer.html');
*/

template_files('edit_video.html');
display_it();

?>