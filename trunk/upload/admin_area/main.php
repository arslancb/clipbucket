<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

if(@$_GET['msg']){
$msg = mysql_clean($_GET['msg']);
}


if(isset($_POST['update'])){
	$configs = $Cbucket->configs;
	
	$rows = array( 'site_title'	,
				   'site_slogan',
				   'description',
				   'keywords'	,
				   'player_file',
				   'ffmpegpath'	,
				   'mencoderpath',
				   'flvpath',
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
					);
	
	//Numeric Array
	$num_array = array('videos_list_per_page',
					   'videos_list_per_tab',
					   'channels_list_per_page',
					   'channels_list_per_tab',
					   'search_list_per_page',
					   'recently_viewed_limit',
					   'max_upload_size',
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
	
	//Setting Lanuage Cookie
	setcookie('sitelang', $rows['default_site_lang'], time()+315360000, '/');
	setcookie('sitestyle', $row['template'], time()+315360000, '/');
	e("Website Settings Have Been Updated",m);

}

$row = $myquery->Get_Website_Details();

//Getting Template List
	$sql = "SELECT * from template";
	$rs = $db->Execute($sql);
	$templates = $rs->getrows();
	Assign('templates', $templates);	
	
//Getting Players List
	$sql = "SELECT * from players";
	$rs = $db->Execute($sql);
	$player = $rs->getrows();
	Assign('players', $player);

//Lanugae Arrays
	
Assign('row',$row);
@Assign('msg',$msg);

/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('main.html');
Template('footer.html');*/

template_files('main.html');
display_it();
?>