
--
-- Dumping data for table `user_levels`
--

INSERT INTO `{tbl_prefix}user_levels` (`user_level_id`, `user_level_active`, `user_level_name`, `user_level_is_default`) VALUES
(4, 'yes', 'Guest', 'yes'),
(2, 'yes', 'Registered User', 'yes'),
(3, 'yes', 'Inactive User', 'yes'),
(1, 'yes', 'Administrator', 'yes'),
(5, 'yes', 'Global Moderator', 'yes'),
(6, 'yes', 'Anonymous', 'yes');

--
-- Dumping data for table `user_levels_permissions`
--

INSERT INTO `{tbl_prefix}user_levels_permissions` (`user_level_permission_id`, `user_level_id`, `admin_access`, `allow_video_upload`, `view_video`, `view_channel`, `view_group`, `view_videos`, `avatar_upload`, `video_moderation`, `member_moderation`, `ad_manager_access`, `manage_template_access`, `group_moderation`, `web_config_access`, `view_channels`, `view_groups`, `playlist_access`, `allow_channel_bg`, `private_msg_access`, `edit_video`, `download_video`, `admin_del_access`) VALUES
(1, 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(2, 2, 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(3, 3, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(4, 4, 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(5, 5, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no'),
(6, 6, 'no', 'yes', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'no', 'no', 'yes', 'no');

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `{tbl_prefix}user_permissions` (`permission_id`, `permission_type`, `permission_name`, `permission_code`, `permission_desc`, `permission_default`) VALUES
(12, 3, 'Admin Access', 'admin_access', 'User can access admin panel', 'no'),
(13, 1, 'View Video', 'view_video', 'User can view videos', 'yes'),
(11, 2, 'Allow Video Upload', 'allow_video_upload', 'Allow user to upload videos', 'yes'),
(14, 1, 'View Channel', 'view_channel', 'User Can View Channel', 'yes'),
(15, 1, 'View Group', 'view_group', 'User Can View Group', 'yes'),
(16, 1, 'View Videos Page', 'view_videos', 'User Can view videos page', 'yes'),
(17, 2, 'Allow Avatar Upload', 'avatar_upload', 'User can upload video', 'yes'),
(19, 3, 'Video Moderation', 'video_moderation', 'User Can Moderate Videos', 'no'),
(20, 3, 'Member Moderation', 'member_moderation', 'User Can Moderate Members', 'no'),
(21, 3, 'Advertisment Manager', 'ad_manager_access', 'User can change advertisment', 'no'),
(22, 3, 'Manage Templates', 'manage_template_access', 'User can manage website templates', 'no'),
(23, 3, 'Groups Moderation', 'group_moderation', 'User can moderate group', 'no'),
(24, 3, 'Website Configurations', 'web_config_access', 'User can change website settings', 'no'),
(25, 1, 'View channels', 'view_channels', 'User can channels', 'yes'),
(26, 1, 'View Groups', 'view_groups', 'User can view groups', 'yes'),
(28, 4, 'Playlist Access', 'playlist_access', 'User can access playlists', 'yes'),
(29, 2, 'Allow Channel Background', 'allow_channel_bg', 'Allow user to change channel background', 'yes'),
(30, 4, 'Private Messages', 'private_msg_access', 'User can use private messaging system', 'yes'),
(31, 4, 'Edit Video', 'edit_video', 'User can edit video', 'yes'),
(32, 4, 'Download Video', 'download_video', 'User can download videos', 'yes'),
(33, 3, 'Admin Delete Access', 'admin_del_access', 'User can delete comments if has admin access', 'no');

--
-- Dumping data for table `user_permission_types`
--

INSERT INTO `{tbl_prefix}user_permission_types` (`user_permission_type_id`, `user_permission_type_name`, `user_permission_type_desc`) VALUES
(1, 'Viewing Permission', ''),
(2, 'Uploading Permission', ''),
(3, 'Administrator Permission', ''),
(4, 'General Permission', '');

-- 2.6

INSERT INTO `{tbl_prefix}user_permissions` (
`permission_id` ,
`permission_type` ,
`permission_name` ,
`permission_code` ,
`permission_desc` ,
`permission_default`
)VALUES (
NULL , '3', 'Allow photo moderation', 'photos_moderation', 'Allow user to moderation photos from admin panel', 'yes'), (
NULL , '3', 'Collection modetaion', 'collection_moderation', 'Allow users to moderate collection', 'yes'), (
NULL , '3', 'Plugins moderation', 'plugins_moderation', 'Allow user to moderate plugins', 'yes'), (
NULL , '3', 'Tool Box', 'tool_box', 'Allow users to access tool box', 'yes');

UPDATE `{tbl_prefix}user_levels_permissions` SET `photos_moderation` = 'yes',
`collection_moderation` = 'yes',
`plugins_moderation` = 'yes',
`tool_box` = 'yes' WHERE `user_level_permission_id` =1;