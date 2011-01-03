<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE","edit_video");
define("PARENT_PAGE",'videos');

require 'includes/config.inc.php';
$userquery->login_check('edit_video');

$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));

$vid = mysql_clean($_GET['vid']);
//get video details
$vdetails = $cbvid->get_video_details($vid);

if($vdetails['userid'] != userid())
{
	e(lang('no_edit_video'));
	$Cbucket->show_page = false;
}else{
	
	//Updating Video Details
	if(isset($_POST['update_video'])){
		$Upload->validate_video_upload_form();
		if(empty($eh->error_list))
		{
			$_POST['videoid'] = $vid;
			$cbvid->update_video();
			$cbvid->set_default_thumb($vid,mysql_clean(post('default_thumb')));
			$vdetails = $cbvid->get_video_details($vid);
		}
	}
	
	assign('v',$vdetails);
}

subtitle(lang("vdo_edit_vdo"));
template_files('edit_video.html');
display_it();
?>