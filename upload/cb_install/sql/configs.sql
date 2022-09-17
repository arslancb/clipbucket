-- Configurations

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES
(NULL, 'site_title', 'ClipBucket v2'),
(NULL, 'site_slogan', 'A way to broadcast yourself'),
(NULL, 'baseurl', ''),
(NULL, 'basedir', ''),
(NULL, 'template_dir', 'cb_28'),
(NULL, 'player_file', 'cb_video_js.php'),
(NULL, 'closed', '0'),
(NULL, 'closed_msg', 'We Are Updating Our Website, Please Visit us after few hours.'),
(NULL, 'description', 'Clip Bucket is an ultimate Video Sharing script'),
(NULL, 'keywords', 'clip bucket video sharing website script'),
(NULL, 'ffmpegpath', '/usr/local/bin/ffmpeg'),
(NULL, 'flvtool2path', '/usr/local/bin/flvtool2'),
(NULL, 'mp4boxpath', '/usr/local/bin/MP4Box'),
(NULL, 'vbrate', '300000'),
(NULL, 'srate', '22050'),
(NULL, 'r_height', ''),
(NULL, 'r_width', ''),
(NULL, 'resize', ''),
(NULL, 'mencoderpath', ''),
(NULL, 'keep_original', '1'),
(NULL, 'activation', ''),
(NULL, 'mplayerpath', ''),
(NULL, 'email_verification', '1'),
(NULL, 'allow_registeration', '1'),
(NULL, 'php_path', '/usr/bin/php'),
(NULL, 'videos_list_per_page', '30'),
(NULL, 'channels_list_per_page', '25'),
(NULL, 'videos_list_per_tab', '1'),
(NULL, 'channels_list_per_tab', '1'),
(NULL, 'video_comments', '1'),
(NULL, 'video_rating', '1'),
(NULL, 'comment_rating', '1'),
(NULL, 'video_download', '1'),
(NULL, 'video_embed', '1'),
(NULL, 'groups_list_per_page', '25'),
(NULL, 'seo', 'no'),
(NULL, 'admin_pages', '100'),
(NULL, 'search_list_per_page', '25'),
(NULL, 'recently_viewed_limit', '10'),
(NULL, 'max_upload_size', '1000'),
(NULL, 'sbrate', '128000'),
(NULL, 'thumb_width', '120'),
(NULL, 'thumb_height', '90'),
(NULL, 'user_comment_opt1', ''),
(NULL, 'ffmpeg_type', ''),
(NULL, 'user_comment_opt2', ''),
(NULL, 'user_comment_opt3', ''),
(NULL, 'user_comment_opt4', ''),
(NULL, 'user_rate_opt1', ''),
(NULL, 'captcha_type', '0'),
(NULL, 'allow_upload', ''),
(NULL, 'allowed_types', 'wmv,avi,divx,3gp,mov,mpeg,mpg,xvid,flv,asf,rm,dat,mp4,png,jpg'),
(NULL, 'version', '2.0.1'),
(NULL, 'version_type', 'Alpha'),
(NULL, 'allow_template_change', '1'),
(NULL, 'allow_language_change', '1'),
(NULL, 'default_site_lang', ''),
(NULL, 'video_require_login', ''),
(NULL, 'audio_codec', 'libfdk_aac'),
(NULL, 'con_modules_type', ''),
(NULL, 'remoteUpload', ''),
(NULL, 'embedUpload', ''),
(NULL, 'player_div_id', ''),
(NULL, 'code_dev', '(Powered by ClipBucket)'),
(NULL, 'sys_os', ''),
(NULL, 'debug_level', ''),
(NULL, 'enable_troubleshooter', '1'),
(NULL, 'vrate', '25'),
(NULL, 'num_thumbs', '5'),
(NULL, 'big_thumb_width', '320'),
(NULL, 'big_thumb_height', '240'),
(NULL, 'user_max_chr', '15'),
(NULL, 'disallowed_usernames', 'shit, asshole, fucker'),
(NULL, 'min_age_reg', '0'),
(NULL, 'max_comment_chr', '800'),
(NULL, 'user_comment_own', ''),
(NULL, 'anonym_comments', 'yes'),
(NULL, 'player_dir', 'CB_video_js'),
(NULL, 'player_width', '661'),
(NULL, 'player_height', '360'),
(NULL, 'default_country_iso2', 'PK'),
(NULL, 'channel_player_width', '600'),
(NULL, 'channel_player_height', '281'),
(NULL, 'videos_items_grp_page', '24'),
(NULL, 'videos_items_hme_page', '25'),
(NULL, 'videos_items_columns', '12'),
(NULL, 'videos_items_ufav_page', '25'),
(NULL, 'videos_items_uvid_page', '25'),
(NULL, 'videos_items_search_page', '30'),
(NULL, 'videos_item_channel_page', '25'),
(NULL, 'users_items_subscriptions', '5'),
(NULL, 'users_items_subscibers', '5'),
(NULL, 'users_items_contacts_channel', '5'),
(NULL, 'users_items_search_page', '12'),
(NULL, 'users_items_group_page', '15'),
(NULL, 'cbhash', 'PGRpdiBhbGlnbj0iY2VudGVyIj48IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPg0KUG93ZXJlZCBieSA8YSBocmVmPSJodHRwOi8vY2xpcC1idWNrZXQuY29tLyI+Q2xpcEJ1Y2tldDwvYT4gJXMgfCA8YSBocmVmPSJodHRwOi8vY2xpcC1idWNrZXQuY29tL2Fyc2xhbi1oYXNzYW4iPkFyc2xhbiBIYXNzYW48L2E+DQo8IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPjwvZGl2Pg=='),
(NULL, 'min_video_title', '4'),
(NULL, 'max_video_title', '80'),
(NULL, 'min_video_desc', '5'),
(NULL, 'max_video_desc', '300'),
(NULL, 'video_categories', '4'),
(NULL, 'min_video_tags', '3'),
(NULL, 'max_video_tags', '30'),
(NULL, 'video_codec', 'libx264'),
(NULL, 'date_released', '01-05-2010'),
(NULL, 'date_installed', '01-05-2010'),
(NULL, 'date_updated', '2010-01-09 18:36:16'),
(NULL, 'support_email', 'webmaster@localhost'),
(NULL, 'website_email', 'webmaster@localhost'),
(NULL, 'welcome_email', 'webmaster@localhost'),
(NULL, 'anonymous_id', '99'),
(NULL, 'date_format', 'Y-m-d'),
(NULL, 'default_time_zone', '5'),
(NULL, 'autoplay_video', 'no'),
(NULL, 'default_country_iso2', 'PK'),
(NULL, 'channel_comments', '1'),
(NULL, 'max_profile_pic_size', '2500'),
(NULL, 'max_profile_pic_height', ''),
(NULL, 'max_profile_pic_width', '230'),
(NULL, 'gravatars', ''),
(NULL, 'picture_url', 'yes'),
(NULL, 'picture_upload', 'yes'),
(NULL, 'background_url', 'yes'),
(NULL, 'background_upload', 'yes'),
(NULL, 'max_bg_size', '2500'),
(NULL, 'max_bg_width', '1600'),
(NULL, 'max_bg_height', ''),
(NULL, 'background_color', 'yes'),
(NULL, 'send_comment_notification', 'yes'),
(NULL, 'approve_video_notification', 'yes'),
(NULL, 'keep_mp4_as_is', 'yes'),
(NULL, 'hq_output', 'yes'),
(NULL, 'grp_categories', '3'),
(NULL, 'grps_items_search_page', '25'),
(NULL, 'grp_thumb_height', '140'),
(NULL, 'grp_thumb_width', '140'),
(NULL, 'grp_max_title', '20'),
(NULL, 'grp_max_desc', '500'),
(NULL, 'quick_conv', ''),
(NULL, 'server_friendly_conversion', ''),
(NULL, 'max_conversion', '2'),
(NULL, 'max_time_wait', '7200'),
(NULL, 'allow_unicode_usernames', 'yes'),
(NULL, 'min_username', '3'),
(NULL, 'max_username', '15'),
(NULL, 'youtube_enabled', 'yes'),
(NULL, 'allow_username_spaces', 'yes'),
(NULL, 'use_playlist', 'yes'),
(NULL, 'comments_captcha', 'guests'),
(NULL, 'player_logo_file', 'logo.jpg'),
(NULL, 'logo_placement', 'tl'),
(NULL, 'buffer_time', '5'),
(NULL, 'use_ffmpeg_vf', 'yes'),
(NULL, 'own_photo_rating', ''),
(NULL, 'mail_type', 'mail'),
(NULL, 'smtp_host', ''),
(NULL, 'smtp_user', ''),
(NULL, 'smtp_pass', ''),
(NULL, 'smtp_auth', 'no'),
(NULL, 'smtp_port', ''),
(NULL, 'use_subs', '1'),
(NULL, 'pak_license', ''),
(NULL, 'photo_ratio', '16:10'),
(NULL, 'photo_thumb_width', '600'),
(NULL, 'photo_thumb_height', '75'),
(NULL, 'photo_med_width', '300'),
(NULL, 'photo_med_height', '116'),
(NULL, 'photo_lar_width', '600'),
(NULL, 'photo_crop', '1'),
(NULL, 'photo_multi_upload', '5'),
(NULL, 'photo_download', '1'),
(NULL, 'photo_comments', '1'),
(NULL, 'photo_rating', '1'),
(NULL, 'max_photo_size', '2'),
(NULL, 'watermark_photo', '0'),
(NULL, 'watermark_max_width', '120'),
(NULL, 'watermark_placement', 'left:top'),
(NULL, 'load_upload_form', 'yes'),
(NULL, 'load_remote_upload_form', 'yes'),
(NULL, 'load_embed_form', 'yes'),
(NULL, 'load_link_video_form', 'yes'),
(NULL, 'enable_groups', ''),
(NULL, 'groupsSection', 'yes'),
(NULL, 'videosSection', 'yes'),
(NULL, 'photosSection', 'yes'),
(NULL, 'homeSection', 'yes'),
(NULL, 'signupSection', 'yes'),
(NULL, 'uploadSection', 'yes'),
(NULL, 'collectionsSection', 'yes'),
(NULL, 'channelsSection', 'yes'),
(NULL, 'flvtoolpp', ''),
(NULL, 'normal_resolution', '480'),
(NULL, 'high_resolution', '720'),
(NULL, 'max_video_duration', '320'),
(NULL, 'embed_player_height', '250'),
(NULL, 'embed_player_width', '300'),
(NULL, 'autoplay_embed', 'yes'),
(NULL, 'playlistsSection', 'yes'),
(NULL, 'photo_main_list', '10'),
(NULL, 'photo_home_tabs', '30'),
(NULL, 'photo_search_result', '30'),
(NULL, 'photo_channel_page', '10'),
(NULL, 'photo_user_photos', '20'),
(NULL, 'photo_user_favorites', '20'),
(NULL, 'photo_other_limit', '8'),
(NULL, 'collection_per_page', '30'),
(NULL, 'collection_home_page', '10'),
(NULL, 'collection_search_result', '20'),
(NULL, 'collection_channel_page', '10'),
(NULL, 'collection_user_collections', '20'),
(NULL, 'collection_user_favorites', '20'),
(NULL, 'collection_items_page', '20'),
(NULL, 'reCaptcha_private_key', '6LcQI8ESAAAAALc_oz1xuNsBVRNx554CaJHjcoXt'),
(NULL, 'reCaptcha_public_key', '6LcQI8ESAAAAALN1vYQovst9c6nlU52iHdqWExp8'),
(NULL, 'channel_rating', '1'),
(NULL, 'own_channel_rating', '1'),
(NULL, 'collection_rating', '1'),
(NULL, 'own_collection_rating', '1'),
(NULL, 'own_video_rating', '1'),
(NULL, 'vbrate_hd', '500000'),
(NULL, 'store_guest_session', 'no'),
(NULL, 'delete_mass_upload', 'no'),
(NULL, 'use_crons', 'no'),
(NULL, 'pseudostreaming', 'yes');



INSERT INTO `{tbl_prefix}languages` (`language_id`, `language_code`, `language_name`, `language_regex`, `language_active`, `language_default`) VALUES
(5, 'en', 'English', '/^en/i', 'yes', 'yes');


INSERT INTO `{tbl_prefix}validation_re` (`re_id`, `re_name`, `re_code`, `re_syntax`) VALUES
(1, 'Username', 'username', '^^[a-zA-Z0-9_]+$'),
(2, 'Email', 'email', '^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,10})$'),
(3, 'Field Text', 'field_text', '^^[_a-z0-9-]+$');


INSERT INTO `{tbl_prefix}config` (`configid` ,`name` ,`value`)VALUES 
(NULL , 'comments_per_page', '15'),
(NULL, 'embed_type', 'iframe');

-- Addition for 2.7
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'feedsSection', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'enlarge_button', 'no');




INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'max_topic_length', '1500');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'max_topic_title', '300');


-- Addition for 2.6
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'facebook_embed', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'seo_vido_url', '1');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'use_cached_pagin', 'yes'),
(NULL, 'cached_pagin_time', '5');

-- Addition for 2.7.4
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'player_logo_url', 'http://clip-bucket.com/');

-- Addition for 2.8
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'cb_combo_res', 'no');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'ffprobe_path', '/usr/bin/ffprobe');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'media_info', '/usr/bin/mediainfo');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'i_magick', '/usr/bin/convert');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'gen_240', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'gen_360', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'gen_480', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'gen_720', 'no');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'gen_1080', 'no');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'photo_activation', '1');

-- Addition for 2.8.1
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('index_recent','6');
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('index_featured','2');
UPDATE `{tbl_prefix}config` SET value = 'cb_28' WHERE name = 'template_dir';
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('stay_mp4','');
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('youtube_api_key','');

-- Addition for 2.8.2
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('popup_video','yes');

-- Addition for 2.8.3
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'video_round_views', 'yes');

-- Addition for 4.0
-- Addition for Cooporate cb seting bitrates for dash/hls
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'vbrate_240', '192000'),
('', 'vbrate_360', '272000'),
('', 'vbrate_480', '352000'),
('', 'vbrate_720', '432000'),
('', 'vbrate_1080', '512000');

-- Addition for Cooporate cb use video watermark or not
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'use_watermark', 'no');

-- Addition for Cooporate cb stream via hls or dash
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'stream_via', 'hls');

-- Addition for Cooporate cb access to logged in users
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'access_to_logged_in', 'no');

-- Addition for clipbucket license --
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'cb_license', 'CBCORP-XXXXXXXXXXX');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'cb_license_local', '');

-- Addition for Cooporate cb allowing collection and playlist page
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'playlistsSection', 'yes');

-- Addition for Cooporate pick default sign up country geologically
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'pick_geo_country', 'yes');