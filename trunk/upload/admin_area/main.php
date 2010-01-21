<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan
 | @ Software : ClipBucket , © PHPBucket.com
 ***************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');

$pages->page_redir();

if(@$_GET['msg']){
$msg = mysql_clean($_GET['msg']);
}


if(isset($_POST['update'])){
	$configs = $Cbucket->configs;
	
	$rows = array(
				  	'allowed_types',
				  	'allow_language_change',
				  	'allow_registeration',
					'allow_template_change',
					'allow_upload',
					'audio_codec',
					'activation',
					
					'big_thumb_width',
					'big_thumb_height',
					
					'closed'	,
					'closed_msg',
					'channels_list_per_page',
					'channels_list_per_tab',
					'captcha_type',
					'con_modules_type',
					'comment_rating'	,
					
					'description',
					'debug_level',
					'disallowed_usernames',
					'default_site_lang',
					
					'embedUpload',
					'email_verification',
					
					'ffmpegpath'	,
					'flvtool2path',
					'ffmpeg_type',
					
					'keywords'	,
					'keep_original',
					
					'r_height',
					'r_width',
					
					'max_video_title',
					'max_video_desc',
					'max_video_tags',
					'min_video_title',
					'min_video_tags',
					'min_video_desc',
					'mp4boxpath',
					'mplayerpath',
					'min_age_reg',
					'max_comment_chr',
					'max_upload_size',
					
					'num_thumbs',
					
					'php_path',
					
					'resize',
					'remoteUpload',
					'recently_viewed_limit',
					
					'site_title'	,
					'sys_os'	,
					'sbrate'	,
					'srate',
					'site_slogan',
					'seo',
					'search_list_per_page',
					'support_email',
					
					'thumb_width',
					'thumb_height',
					
					'user_comment_own',
					'user_rate_opt1'	,
					'users_items_subscriptions',
					'users_items_subscibers',
					'users_items_contacts_channel',
					'users_items_search_page',
					'users_items_group_page',
					'user_max_chr',
					
					'videos_items_grp_page',
					'videos_items_hme_page',
					'videos_items_columns',
					'videos_items_ufav_page',
					'videos_items_uvid_page',
					'videos_items_search_page',
					'videos_item_channel_page',
					'videos_list_per_page',
					'videos_list_per_tab',
					'video_download'	,
					'video_embed',
					'video_comments',
					'video_rating',
					'video_categories',
					'video_codec',
					'vrate',
					'vbrate',
					'video_require_login',
					
					'website_email',
					'welcome_email',
					
					);
	
	//Numeric Array
	$num_array = array(
					'videos_list_per_page',
					'videos_list_per_tab',
					'channels_list_per_page',
					'channels_list_per_tab',
					'search_list_per_page',
					'recently_viewed_limit',
					'max_upload_size',
					'videos_items_grp_page',
					'videos_items_hme_page',
					'videos_items_columns',
					'videos_items_ufav_page',
					'videos_items_uvid_page',
					'videos_items_search_page',
					'videos_item_channel_page',
					'users_items_subscriptions',
					'users_items_subscibers',
					'users_items_contacts_channel',
					'users_items_search_page',
					'users_items_group_page',
					'video_categories',
					'max_video_title',
					'max_video_desc',
					'max_video_tags',
					'min_video_title',
					'min_video_tags',
					'min_video_desc',
					);
	foreach($rows as $field)
	{
		$value = mysql_clean($_POST[$field]);
		if(in_array($field,$num_array))
		{
			if($value <= 0 || !is_numeric($value))
				$value = 1;
		}
		$myquery->Set_Website_Details($field,$value);
	}
	e("Website Settings Have Been Updated",m);

}

$row = $myquery->Get_Website_Details();
Assign('row',$row);
subtitle("Website Configurations");
template_files('main.html');
display_it();
?>