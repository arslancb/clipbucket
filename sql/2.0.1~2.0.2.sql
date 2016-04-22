ALTER TABLE `video` ADD `uploader_ip` VARCHAR( 20 ) NOT NULL AFTER `embed_code`
DROP TABLE IF EXISTS `user_levels`;
CREATE TABLE `user_levels` (
  `user_level_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_level_active` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes',
  `user_level_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `user_level_is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `user_levels`
--

INSERT INTO `user_levels` (`user_level_id`, `user_level_active`, `user_level_name`, `user_level_is_default`) VALUES
(4, 'yes', 'Guest', 'yes'),
(2, 'yes', 'Registered User', 'yes'),
(3, 'yes', 'Inactive User', 'yes'),
(1, 'yes', 'Administrator', 'yes'),
(5, 'yes', 'Global Moderator', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_levels_permissions`
--

DROP TABLE IF EXISTS `user_levels_permissions`;
CREATE TABLE `user_levels_permissions` (
  `user_level_permission_id` int(22) NOT NULL AUTO_INCREMENT,
  `user_level_id` int(22) NOT NULL,
  `admin_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `allow_video_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_channel` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_group` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_videos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `video_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `member_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `ad_manager_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `manage_template_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `group_moderation` enum('yes','no') NOT NULL DEFAULT 'no',
  `web_config_access` enum('yes','no') NOT NULL DEFAULT 'no',
  `view_channels` enum('yes','no') NOT NULL DEFAULT 'yes',
  `view_groups` enum('yes','no') NOT NULL DEFAULT 'yes',
  `playlist_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_channel_bg` enum('yes','no') NOT NULL DEFAULT 'yes',
  `private_msg_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  `edit_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `admin_del_access` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`user_level_permission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `user_levels_permissions`
--

INSERT INTO `user_levels_permissions` (`user_level_permission_id`, `user_level_id`, `admin_access`, `allow_video_upload`, `view_video`, `view_channel`, `view_group`, `view_videos`, `avatar_upload`, `video_moderation`, `member_moderation`, `ad_manager_access`, `manage_template_access`, `group_moderation`, `web_config_access`, `view_channels`, `view_groups`, `playlist_access`, `allow_channel_bg`, `private_msg_access`, `edit_video`, `admin_del_access`) VALUES
(5, 5, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(2, 2, 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(3, 3, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(1, 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),
(4, 4, 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (
  `permission_id` int(225) NOT NULL AUTO_INCREMENT,
  `permission_type` int(225) NOT NULL,
  `permission_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `permission_code` varchar(225) CHARACTER SET latin1 NOT NULL,
  `permission_desc` mediumtext CHARACTER SET latin1 NOT NULL,
  `permission_default` enum('yes','no') CHARACTER SET latin1 NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_code` (`permission_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`permission_id`, `permission_type`, `permission_name`, `permission_code`, `permission_desc`, `permission_default`) VALUES
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
(32, 3, 'Admin Delete Access', 'admin_del_access', 'User can delete comments if has admin access', 'no'); 