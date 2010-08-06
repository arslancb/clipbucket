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
					'allow_unicode_usernames',
				  	'allow_registeration',
					'allow_template_change',
					'allow_upload',
					'anonymous_id',
					'approve_video_notification',
					'audio_codec',
					'activation',
					
					'background_color',
					'background_upload',
					'background_url',
					'big_thumb_width',
					'big_thumb_height',
					
					'closed',
					'closed_msg',
					'channel_comments',
					'anonym_comments',
					'channels_list_per_page',
					'channels_list_per_tab',
					'captcha_type',
					'con_modules_type',
					'comments_captcha',
					'comment_rating',
					
					'date_format',
					'description',
					'debug_level',
					'default_country_iso2',
					'default_time_zone',
					'disallowed_usernames',
					'use_subs',
					
					'embedUpload',
					'email_verification',
					
					'ffmpegpath'	,
					'flvtool2path',
					'ffmpeg_type',
					
					'gravatars',
					'grp_categories',
					'groups_list_per_page',
					'grps_items_search_page',
					'grp_thumb_height',
					'grp_thumb_width',
					'grp_max_title',
					'grp_max_desc',

					'hq_output',
					
					'keywords'	,
					'keep_original',
					'keep_mp4_as_is',
					
					'r_height',
					'r_width',
					
					'max_bg_width',
					'max_bg_size',
					'max_conversion',
					'max_profile_pic_height',
					'max_profile_pic_size',
					'max_profile_pic_width',
					'max_video_title',
					'max_video_desc',
					'max_video_tags',
					'max_username',
					'min_video_title',
					'min_video_tags',
					'min_video_desc',
					'mp4boxpath',
					'mplayerpath',
					'min_age_reg',
					'min_username',
					'max_comment_chr',
					'max_time_wait',
					'max_upload_size',
					
					'num_thumbs',
					
					'php_path',
					'picture_url',
					'picture_upload',
					
					'quick_conv',
					
					'resize',
					'remoteUpload',
					'recently_viewed_limit',
					
					'send_comment_notification',
					'site_title'	,
					'sys_os'	,
					'sbrate'	,
					'srate',
					'site_slogan',
					'seo',
					'search_list_per_page',
					'server_friendly_conversion',
					'support_email',
					
					'thumb_width',
					'thumb_height',
					
					'use_ffmpeg_vf',
					'use_crons',
					'user_comment_own',
					'user_rate_opt1'	,
					'users_items_subscriptions',
					'users_items_subscibers',
					'users_items_contacts_channel',
					'users_items_search_page',
					'users_items_group_page',
					'user_max_chr',
					
					'vid_categories',
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
					'video_comments3',
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
					'anonymous_id',
					
					'channels_list_per_page',
					'channels_list_per_tab',

					'max_upload_size',
					'max_video_title',
					'max_video_desc',
					'max_video_tags',
					'min_video_title',
					'min_video_tags',
					'min_video_desc',
					
					'recently_viewed_limit',
					
					'search_list_per_page',
					
					'users_items_subscriptions',
					'users_items_subscibers',
					'users_items_contacts_channel',
					'users_items_search_page',
					'users_items_group_page',
					
					'videos_items_grp_page',
					'videos_items_hme_page',
					'videos_items_columns',
					'videos_items_ufav_page',
					'videos_items_uvid_page',
					'videos_items_search_page',
					'videos_item_channel_page',
					'videos_list_per_page',
					'videos_list_per_tab',
					'video_categories',
					);
	foreach($rows as $field)
	{
		$value = ($_POST[$field]);
		if(in_array($field,$num_array))
		{
			if($value <= 0 || !is_numeric($value))
				$value = 1;
		}
		$myquery->Set_Website_Details($field,$value);
	}
	e("Website Settings Have Been Updated",'m');

}

$row = $myquery->Get_Website_Details();
Assign('row',$row);
subtitle("Website Configurations");
template_files('main.html');
display_it();
?>