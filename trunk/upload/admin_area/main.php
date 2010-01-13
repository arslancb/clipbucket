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
$pages->page_redir();

if(@$_GET['msg']){
$msg = mysql_clean($_GET['msg']);
}


if(isset($_POST['update'])){
	$configs = $Cbucket->configs;
	
	$rows = array(
					'site_title'	,
					'site_slogan',
					'description',
					'keywords'	,
					'player_file',
					'ffmpegpath'	,
					'flvtool2path',
					'mp4boxpath',
					'closed'	,
					'closed_msg',
					'resize',
					'r_height',
					'r_width',
					'vbrate',
					'srate',
					'keep_original',
					'activation',
					'mplayerpath',
					'email_verification',
					'allow_registeration',
					'php_path',
					'videos_list_per_page',
					'videos_list_per_tab',
					'channels_list_per_page',
					'channels_list_per_tab',
					'video_rating',
					'comment_rating'	,
					'video_download'	,
					'video_embed',
					'video_comments',
					'seo',
					'search_list_per_page',
					'recently_viewed_limit',
					'max_upload_size',
					'sbrate'	,
					'thumb_width',
					'thumb_height',
					'ffmpeg_type',
					'user_comment_own',
					'user_rate_opt1'	,
					'captcha_type',
					'allow_upload',
					'allowed_types',
					'default_site_lang',
					'allow_language_change',
					'allow_template_change',
					'video_require_login',
					'con_modules_type',
					'audio_codec',
					'remoteUpload',
					'embedUpload',
					'sys_os'	,
					'debug_level',
					'num_thumbs',
					'big_thumb_width',
					'big_thumb_height',
					'user_max_chr',
					'disallowed_usernames',
					'min_age_reg',
					'max_comment_chr',
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
					'video_codec',
					'vrate',
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