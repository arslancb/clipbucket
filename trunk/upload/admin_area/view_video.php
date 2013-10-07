<?php
/* 
 ***********************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.		
 | @ Author 	: ArslanHassan												
 | @ Software 	: ClipBucket , © PHPBucket.com							
 *************************************************************************
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
		//Deleting Comment
		$cid = mysql_clean($_GET['delete_comment']);
		if(!empty($cid))
		{
			$myquery->delete_comment($cid);
		}
		
		//Get Video Details
		$data = get_video_details($video);
		Assign('udata',$userquery->get_user_details($data['userid']));
		Assign('data',$data);
	}else{
		$msg[] = lang('class_vdo_del_err');
	}
	
	
	

Assign('msg',@$msg);	
/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('view_video.html');
Template('footer.html');*/


subtitle("View Video");
template_files('view_video.html');
display_it();


?>