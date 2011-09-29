-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}action_log` (
  `action_id` int(255) NOT NULL AUTO_INCREMENT,
  `action_type` varchar(60) CHARACTER SET utf8 NOT NULL,
  `action_username` varchar(60) CHARACTER SET utf8 NOT NULL,
  `action_userid` int(30) NOT NULL,
  `action_useremail` varchar(200) CHARACTER SET utf8 NOT NULL,
  `action_userlevel` int(11) NOT NULL,
  `action_ip` varchar(15) CHARACTER SET utf8 NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action_success` enum('yes','no') CHARACTER SET utf8 NOT NULL,
  `action_details` text CHARACTER SET utf8 NOT NULL,
  `action_link` text NOT NULL,
  `action_obj_id` int(255) NOT NULL,
  `action_done_id` int(255) NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2687 ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notes`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}admin_notes` (
  `note_id` int(225) NOT NULL AUTO_INCREMENT,
  `note` text CHARACTER SET ucs2 NOT NULL,
  `date_added` datetime NOT NULL,
  `userid` int(225) NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Table structure for table `ads_data`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}ads_data` (
  `ad_id` int(50) NOT NULL AUTO_INCREMENT,
  `ad_name` mediumtext NOT NULL,
  `ad_code` mediumtext NOT NULL,
  `ad_placement` varchar(50) NOT NULL DEFAULT '',
  `ad_category` int(11) NOT NULL DEFAULT '0',
  `ad_status` enum('0','1') NOT NULL DEFAULT '0',
  `ad_impressions` bigint(255) NOT NULL DEFAULT '0',
  `last_viewed` datetime NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `ads_placements`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}ads_placements` (
  `placement_id` int(20) NOT NULL AUTO_INCREMENT,
  `placement` varchar(26) NOT NULL,
  `placement_name` varchar(50) NOT NULL,
  `disable` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`placement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collections` (
  `collection_id` bigint(25) NOT NULL AUTO_INCREMENT,
  `collection_name` varchar(225) NOT NULL,
  `collection_description` text NOT NULL,
  `collection_tags` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `userid` int(10) NOT NULL,
  `views` bigint(20) NOT NULL,
  `date_added` datetime NOT NULL,
  `featured` varchar(4) NOT NULL DEFAULT 'no',
  `broadcast` varchar(10) NOT NULL,
  `allow_comments` varchar(4) NOT NULL,
  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes',
  `total_comments` bigint(20) NOT NULL,
  `last_commented` datetime NOT NULL,
  `total_objects` bigint(20) NOT NULL,
  `rating` bigint(20) NOT NULL,
  `rated_by` bigint(20) NOT NULL,
  `voters` longtext NOT NULL,
  `active` varchar(4) NOT NULL,
  `public_upload` varchar(4) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`collection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `collection_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_categories` (
  `category_id` int(255) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  `category_order` int(5) NOT NULL,
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumint(9) NOT NULL,
  `isdefault` enum('yes','no') NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `collection_items`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_items` (
  `ci_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `collection_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`ci_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}comments` (
  `comment_id` int(60) NOT NULL AUTO_INCREMENT,
  `type` varchar(3) NOT NULL,
  `comment` text NOT NULL,
  `userid` int(60) NOT NULL,
  `anonym_name` varchar(255) NOT NULL,
  `anonym_email` varchar(255) NOT NULL,
  `parent_id` int(60) NOT NULL,
  `type_id` int(225) NOT NULL,
  `type_owner_id` int(255) NOT NULL,
  `vote` varchar(225) NOT NULL,
  `voters` text NOT NULL,
  `spam_votes` bigint(20) NOT NULL,
  `spam_voters` text NOT NULL,
  `date_added` datetime NOT NULL,
  `comment_ip` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=535 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}config` (
  `configid` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`configid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=225 ;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}contacts` (
  `contact_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(225) NOT NULL,
  `contact_userid` int(225) NOT NULL,
  `confirmed` enum('yes','no') NOT NULL DEFAULT 'no',
  `contact_group_id` int(225) NOT NULL,
  `request_type` enum('in','out') NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `conversion_queue`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}conversion_queue` (
  `cqueue_id` int(11) NOT NULL AUTO_INCREMENT,
  `cqueue_name` varchar(32) CHARACTER SET utf8 NOT NULL,
  `cqueue_ext` varchar(5) CHARACTER SET utf8 NOT NULL,
  `cqueue_tmp_ext` varchar(3) CHARACTER SET utf8 NOT NULL,
  `cqueue_conversion` enum('yes','no','p') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `time_started` varchar(32) NOT NULL,
  `time_completed` varchar(32) NOT NULL,
  PRIMARY KEY (`cqueue_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=262 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}countries` (
  `country_id` int(80) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) CHARACTER SET utf8 NOT NULL,
  `name` varchar(80) CHARACTER SET utf8 NOT NULL,
  `name_en` varchar(80) CHARACTER SET utf8 NOT NULL,
  `iso3` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;

-- --------------------------------------------------------

--
-- Table structure for table `editors_picks`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}editors_picks` (
  `pick_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(225) NOT NULL,
  `sort` bigint(5) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pick_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}email_templates` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_name` varchar(225) CHARACTER SET utf8 NOT NULL,
  `email_template_code` varchar(225) CHARACTER SET utf8 NOT NULL,
  `email_template_subject` mediumtext CHARACTER SET utf8 NOT NULL,
  `email_template` text CHARACTER SET utf8 NOT NULL,
  `email_template_allowed_tags` mediumtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`email_template_id`),
  UNIQUE KEY `email_template_code` (`email_template_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}favorites` (
  `favorite_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) CHARACTER SET utf8 NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`favorite_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `flags`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}flags` (
  `flag_id` int(225) NOT NULL AUTO_INCREMENT,
  `type` varchar(4) CHARACTER SET utf8 NOT NULL,
  `id` int(225) NOT NULL,
  `userid` int(225) NOT NULL,
  `flag_type` bigint(25) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`flag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}groups` (
  `group_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_name` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(255) NOT NULL,
  `group_description` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_tags` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_url` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group_privacy` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `video_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `post_type` enum('0','1','2') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  `featured` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `total_views` bigint(225) NOT NULL,
  `total_videos` int(225) NOT NULL,
  `total_members` int(225) NOT NULL,
  `total_topics` int(225) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_invitations`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_invitations` (
  `invitation_id` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `invited` int(225) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invitation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_members` (
  `group_mid` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`group_mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_posts`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_topics`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_topics` (
  `topic_id` int(225) NOT NULL AUTO_INCREMENT,
  `topic_title` text NOT NULL,
  `userid` int(225) NOT NULL,
  `group_id` int(225) NOT NULL,
  `topic_post` text NOT NULL,
  `date_added` datetime NOT NULL,
  `last_poster` int(225) NOT NULL,
  `last_post_time` datetime NOT NULL,
  `total_views` bigint(225) NOT NULL,
  `total_replies` bigint(225) NOT NULL,
  `topic_icon` varchar(225) NOT NULL,
  `approved` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_videos`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}group_videos` (
  `group_video_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(255) NOT NULL,
  `group_id` int(225) NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_video_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}languages` (
  `language_id` int(9) NOT NULL AUTO_INCREMENT,
  `language_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `language_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `language_regex` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `language_active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `language_default` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}messages` (
  `message_id` int(225) NOT NULL AUTO_INCREMENT,
  `message_from` int(20) NOT NULL,
  `message_to` varchar(200) NOT NULL,
  `message_content` mediumtext NOT NULL,
  `message_type` enum('pm','notification') NOT NULL DEFAULT 'pm',
  `message_attachments` mediumtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message_subject` mediumtext NOT NULL,
  `message_status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `reply_to` int(225) NOT NULL DEFAULT '0',
  `message_box` enum('in','out') NOT NULL DEFAULT 'in',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}modules` (
  `module_id` int(25) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(25) NOT NULL,
  `module_file` varchar(60) NOT NULL,
  `active` varchar(5) NOT NULL,
  `module_include_file` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(225) NOT NULL,
  `page_title` varchar(225) NOT NULL,
  `page_content` text NOT NULL,
  `userid` int(225) NOT NULL,
  `active` enum('yes','no') NOT NULL,
  `delete_able` enum('yes','no') NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}photos` (
  `photo_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `photo_key` mediumtext NOT NULL,
  `photo_title` mediumtext NOT NULL,
  `photo_description` mediumtext NOT NULL,
  `photo_tags` mediumtext NOT NULL,
  `userid` int(255) NOT NULL,
  `collection_id` int(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_viewed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `views` bigint(255) NOT NULL,
  `allow_comments` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_embedding` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_tagging` enum('yes','no') NOT NULL DEFAULT 'yes',
  `featured` enum('yes','no') NOT NULL DEFAULT 'no',
  `reported` enum('yes','no') NOT NULL DEFAULT 'no',
  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes',
  `broadcast` enum('public','private') NOT NULL DEFAULT 'public',
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `total_comments` int(255) NOT NULL,
  `last_commented` datetime NOT NULL,
  `total_favorites` int(255) NOT NULL,
  `rating` int(15) NOT NULL,
  `rated_by` int(25) NOT NULL,
  `voters` mediumtext NOT NULL,
  `filename` varchar(100) NOT NULL,
  `ext` char(5) NOT NULL,
  `downloaded` bigint(255) NOT NULL,
  `server_url` text NOT NULL,
  `owner_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `phrases`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}phrases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_iso` varchar(5) NOT NULL DEFAULT 'en',
  `varname` varchar(250) NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9392 ;

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}playlists` (
  `playlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(225) CHARACTER SET utf8 NOT NULL,
  `userid` int(11) NOT NULL,
  `playlist_type` varchar(10) CHARACTER SET utf8 NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `playlist_items`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}playlist_items` (
  `playlist_item_id` int(225) NOT NULL AUTO_INCREMENT,
  `object_id` int(225) NOT NULL,
  `playlist_id` int(225) NOT NULL,
  `playlist_item_type` varchar(10) CHARACTER SET utf8 NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}plugins` (
  `plugin_id` int(255) NOT NULL AUTO_INCREMENT,
  `plugin_file` text NOT NULL,
  `plugin_folder` text NOT NULL,
  `plugin_version` float NOT NULL,
  `plugin_license_type` varchar(10) NOT NULL DEFAULT 'GPL',
  `plugin_license_key` varchar(5) NOT NULL,
  `plugin_license_code` text NOT NULL,
  `plugin_active` enum('yes','no') NOT NULL,
  PRIMARY KEY (`plugin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_config`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}plugin_config` (
  `plugin_config_id` int(223) NOT NULL AUTO_INCREMENT,
  `plugin_id_code` varchar(25) CHARACTER SET utf8 NOT NULL,
  `plugin_config_name` text CHARACTER SET utf8 NOT NULL,
  `plugin_config_value` text CHARACTER SET utf8 NOT NULL,
  `player_type` enum('built-in','plugin') CHARACTER SET utf8 NOT NULL DEFAULT 'built-in',
  `player_admin_file` text CHARACTER SET utf8 NOT NULL,
  `player_include_file` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`plugin_config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--
CREATE TABLE `{tbl_prefix}sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(100) NOT NULL,
  `session_user` int(11) NOT NULL,
  `session_string` varchar(60) NOT NULL,
  `session_value` varchar(32) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `session_date` datetime NOT NULL,
  `current_page` text NOT NULL,
  `referer` text NOT NULL,
  `agent` text NOT NULL,
  `last_active` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}stats` (
  `stat_id` int(255) NOT NULL AUTO_INCREMENT,
  `date_added` date NOT NULL,
  `video_stats` text NOT NULL,
  `user_stats` text NOT NULL,
  `group_stats` text NOT NULL,
  PRIMARY KEY (`stat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}subscriptions` (
  `subscription_id` int(225) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `subscribed_to` mediumtext NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscription_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}template` (
  `template_id` int(20) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(25) NOT NULL,
  `template_dir` varchar(30) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}users` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` int(20) NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `username` text NOT NULL,
  `user_session_key` varchar(32) NOT NULL,
  `user_session_code` int(5) NOT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(80) NOT NULL DEFAULT '',
  `usr_status` enum('Ok','ToActivate') NOT NULL DEFAULT 'ToActivate',
  `msg_notify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `avatar` varchar(225) NOT NULL DEFAULT '',
  `avatar_url` text NOT NULL,
  `sex` enum('male','female') NOT NULL DEFAULT 'male',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `country` varchar(20) NOT NULL DEFAULT 'PK',
  `level` int(6) NOT NULL DEFAULT '2',
  `avcode` mediumtext NOT NULL,
  `doj` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_logged` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_visits` bigint(20) NOT NULL DEFAULT '0',
  `session` varchar(32) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `signup_ip` varchar(15) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `featured` enum('No','Yes') NOT NULL DEFAULT 'No',
  `featured_date` datetime NOT NULL,
  `profile_hits` bigint(20) DEFAULT '0',
  `total_watched` bigint(20) NOT NULL DEFAULT '0',
  `total_videos` bigint(20) NOT NULL,
  `total_comments` bigint(20) NOT NULL,
  `total_photos` bigint(255) NOT NULL,
  `total_collections` bigint(255) NOT NULL,
  `comments_count` bigint(20) NOT NULL,
  `last_commented` datetime NOT NULL,
  `ban_status` enum('yes','no') NOT NULL DEFAULT 'no',
  `upload` varchar(20) NOT NULL DEFAULT '1',
  `subscribers` bigint(225) NOT NULL DEFAULT '0',
  `total_subscriptions` bigint(255) NOT NULL,
  `background` mediumtext NOT NULL,
  `background_color` varchar(25) NOT NULL,
  `background_url` text NOT NULL,
  `background_repeat` enum('no-repeat','repeat','repeat-x','repeat-y') NOT NULL DEFAULT 'repeat',
  `background_attachement` enum('yes','no') NOT NULL DEFAULT 'no',
  `total_groups` bigint(20) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `banned_users` text NOT NULL,
  `welcome_email_sent` enum('yes','no') NOT NULL DEFAULT 'no',
  `total_downloads` bigint(255) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `ind_status_doj` (`doj`),
  KEY `ind_status_id` (`userid`),
  KEY `ind_hits_doj` (`profile_hits`,`doj`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;
ALTER TABLE `{tbl_prefix}users` ADD `album_privacy` ENUM( 'public', 'private', 'friends' ) NOT NULL DEFAULT 'private' ;
-- --------------------------------------------------------

--
-- Table structure for table `user_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_levels` (
  `user_level_id` int(20) NOT NULL AUTO_INCREMENT,
  `user_level_active` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `user_level_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_level_is_default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_levels_permissions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_levels_permissions` (
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
  `download_video` enum('yes','no') NOT NULL DEFAULT 'yes',
  `admin_del_access` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`user_level_permission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_permissions` (
  `permission_id` int(225) NOT NULL AUTO_INCREMENT,
  `permission_type` int(225) NOT NULL,
  `permission_name` varchar(225) CHARACTER SET utf8 NOT NULL,
  `permission_code` varchar(225) CHARACTER SET utf8 NOT NULL,
  `permission_desc` mediumtext CHARACTER SET utf8 NOT NULL,
  `permission_default` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_code` (`permission_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_permission_types`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_permission_types` (
  `user_permission_type_id` int(225) NOT NULL AUTO_INCREMENT,
  `user_permission_type_name` varchar(225) CHARACTER SET utf8 NOT NULL,
  `user_permission_type_desc` mediumtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`user_permission_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_profile` (
  `user_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `show_my_collections` enum('yes','no') NOT NULL DEFAULT 'yes',
  `userid` bigint(20) NOT NULL,
  `profile_title` mediumtext NOT NULL,
  `profile_desc` mediumtext NOT NULL,
  `featured_video` mediumtext NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(225) NOT NULL DEFAULT 'no_avatar.jpg',
  `show_dob` enum('no','yes') DEFAULT 'no',
  `postal_code` varchar(20) NOT NULL DEFAULT '',
  `time_zone` tinyint(4) NOT NULL DEFAULT '0',
  `profile_tags` mediumtext,
  `web_url` varchar(200) NOT NULL DEFAULT '',
  `hometown` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `online_status` enum('online','offline','custom') NOT NULL DEFAULT 'online',
  `show_profile` enum('all','members','friends') NOT NULL DEFAULT 'all',
  `allow_comments` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `allow_ratings` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `allow_subscription` enum('yes','no') NOT NULL DEFAULT 'yes',
  `content_filter` enum('Nothing','On','Off') NOT NULL DEFAULT 'Nothing',
  `icon_id` bigint(20) NOT NULL DEFAULT '0',
  `browse_criteria` mediumtext,
  `about_me` mediumtext NOT NULL,
  `education` varchar(3) DEFAULT NULL,
  `schools` mediumtext NOT NULL,
  `occupation` mediumtext NOT NULL,
  `companies` mediumtext NOT NULL,
  `relation_status` varchar(15) DEFAULT NULL,
  `hobbies` mediumtext NOT NULL,
  `fav_movies` mediumtext NOT NULL,
  `fav_music` mediumtext NOT NULL,
  `fav_books` mediumtext NOT NULL,
  `background` mediumtext NOT NULL,
  `profile_video` int(255) NOT NULL,
  `profile_item` varchar(25) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `voters` text NOT NULL,
  `rated_by` int(150) NOT NULL,
  `show_my_videos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_photos` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_subscriptions` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_subscribers` enum('yes','no') NOT NULL DEFAULT 'yes',
  `show_my_friends` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`user_profile_id`),
  KEY `ind_status_id` (`userid`),
  FULLTEXT KEY `profile_tags` (`profile_tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `validation_re`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}validation_re` (
  `re_id` int(25) NOT NULL AUTO_INCREMENT,
  `re_name` varchar(60) NOT NULL,
  `re_code` varchar(60) NOT NULL,
  `re_syntax` text NOT NULL,
  PRIMARY KEY (`re_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video` (
  `videoid` bigint(20) NOT NULL AUTO_INCREMENT,
  `videokey` mediumtext NOT NULL,
  `username` text NOT NULL,
  `userid` int(11) NOT NULL,
  `title` text,
  `flv` mediumtext NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `description` text,
  `tags` mediumtext NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT '0',
  `broadcast` varchar(10) NOT NULL DEFAULT '',
  `location` mediumtext,
  `datecreated` date DEFAULT NULL,
  `country` mediumtext,
  `allow_embedding` char(3) NOT NULL DEFAULT '',
  `rating` int(15) NOT NULL DEFAULT '0',
  `rated_by` varchar(20) NOT NULL DEFAULT '0',
  `voter_ids` mediumtext NOT NULL,
  `allow_comments` char(3) NOT NULL DEFAULT '',
  `comment_voting` char(3) NOT NULL DEFAULT '',
  `comments_count` int(15) NOT NULL DEFAULT '0',
  `last_commented` datetime NOT NULL,
  `featured` char(3) NOT NULL DEFAULT 'no',
  `featured_date` datetime NOT NULL,
  `featured_description` mediumtext NOT NULL,
  `allow_rating` char(3) NOT NULL DEFAULT '',
  `active` char(3) NOT NULL DEFAULT '0',
  `favourite_count` varchar(15) NOT NULL DEFAULT '0',
  `playlist_count` varchar(15) NOT NULL DEFAULT '0',
  `views` bigint(22) NOT NULL DEFAULT '0',
  `last_viewed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `flagged` varchar(3) NOT NULL DEFAULT 'no',
  `duration` varchar(20) NOT NULL DEFAULT '00',
  `status` enum('Successful','Processing','Failed') NOT NULL DEFAULT 'Processing',
  `failed_reason` enum('none','max_duration','max_file','invalid_format','invalid_upload') NOT NULL DEFAULT 'none',
  `flv_file_url` text,
  `default_thumb` int(3) NOT NULL DEFAULT '1',
  `aspect_ratio` varchar(10) NOT NULL,
  `embed_code` text NOT NULL,
  `refer_url` text NOT NULL,
  `downloads` bigint(255) NOT NULL,
  `uploader_ip` varchar(20) NOT NULL,
  `mass_embed_status` enum('no','pending','approved') NOT NULL DEFAULT 'no',
  `is_hd` enum('yes','no') NOT NULL DEFAULT 'no',
  `ebay_epn_keywords` varchar(255) NOT NULL,
  `ebay_pre_desc` text NOT NULL,
  `unique_embed_code` varchar(50) NOT NULL,
  `mature_content` enum('yes','no') NOT NULL DEFAULT 'no',
  `remote_play_url` text NOT NULL,
  `server_ip` varchar(20) NOT NULL,
  `file_server_path` text NOT NULL,
  `files_thumbs_path` text NOT NULL,
  `file_thumbs_count` varchar(30) NOT NULL,
  `has_hq` enum('yes','no') NOT NULL DEFAULT 'no',
  `has_mobile` enum('yes','no') NOT NULL DEFAULT 'no',
  `filegrp_size` varchar(30) NOT NULL,
  `process_status` bigint(30) NOT NULL DEFAULT '0',
  `has_hd` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`videoid`),
  FULLTEXT KEY `description` (`description`,`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=960 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_categories`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `parent_id` int(255) NOT NULL DEFAULT '0',
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_order` int(5) NOT NULL DEFAULT '1',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_favourites`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_favourites` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `videoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_files`
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL,
  `file_conversion_log` text CHARACTER SET utf8 NOT NULL,
  `encoder` char(16) CHARACTER SET utf8 NOT NULL,
  `command_used` text CHARACTER SET utf8 NOT NULL,
  `src_path` text CHARACTER SET utf8 NOT NULL,
  `src_name` char(64) CHARACTER SET utf8 NOT NULL,
  `src_ext` char(8) CHARACTER SET utf8 NOT NULL,
  `src_format` char(32) CHARACTER SET utf8 NOT NULL,
  `src_duration` char(10) CHARACTER SET utf8 NOT NULL,
  `src_size` char(10) CHARACTER SET utf8 NOT NULL,
  `src_bitrate` char(6) CHARACTER SET utf8 NOT NULL,
  `src_video_width` char(5) CHARACTER SET utf8 NOT NULL,
  `src_video_height` char(5) CHARACTER SET utf8 NOT NULL,
  `src_video_wh_ratio` char(10) CHARACTER SET utf8 NOT NULL,
  `src_video_codec` char(16) CHARACTER SET utf8 NOT NULL,
  `src_video_rate` char(10) CHARACTER SET utf8 NOT NULL,
  `src_video_bitrate` char(10) CHARACTER SET utf8 NOT NULL,
  `src_video_color` char(16) CHARACTER SET utf8 NOT NULL,
  `src_audio_codec` char(16) CHARACTER SET utf8 NOT NULL,
  `src_audio_bitrate` char(10) CHARACTER SET utf8 NOT NULL,
  `src_audio_rate` char(10) CHARACTER SET utf8 NOT NULL,
  `src_audio_channels` char(16) CHARACTER SET utf8 NOT NULL,
  `output_path` text CHARACTER SET utf8 NOT NULL,
  `output_format` char(32) CHARACTER SET utf8 NOT NULL,
  `output_duration` char(10) CHARACTER SET utf8 NOT NULL,
  `output_size` char(10) CHARACTER SET utf8 NOT NULL,
  `output_bitrate` char(6) CHARACTER SET utf8 NOT NULL,
  `output_video_width` char(5) CHARACTER SET utf8 NOT NULL,
  `output_video_height` char(5) CHARACTER SET utf8 NOT NULL,
  `output_video_wh_ratio` char(10) CHARACTER SET utf8 NOT NULL,
  `output_video_codec` char(16) CHARACTER SET utf8 NOT NULL,
  `output_video_rate` char(10) CHARACTER SET utf8 NOT NULL,
  `output_video_bitrate` char(10) CHARACTER SET utf8 NOT NULL,
  `output_video_color` char(16) CHARACTER SET utf8 NOT NULL,
  `output_audio_codec` char(16) CHARACTER SET utf8 NOT NULL,
  `output_audio_bitrate` char(10) CHARACTER SET utf8 NOT NULL,
  `output_audio_rate` char(10) CHARACTER SET utf8 NOT NULL,
  `output_audio_channels` char(16) CHARACTER SET utf8 NOT NULL,
  `hd` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  `hq` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `src_bitrate` (`src_bitrate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Alterations

ALTER TABLE `{tbl_prefix}video` ADD `video_password` VARCHAR( 255 ) NOT NULL AFTER `videokey` ;
ALTER TABLE `{tbl_prefix}video` ADD `video_users` TEXT NOT NULL AFTER `video_password`;
ALTER TABLE `{tbl_prefix}video` ADD `category_parents` TEXT NOT NULL AFTER `category` ;

ALTER TABLE `{tbl_prefix}_video` ADD `subscription_email` ENUM( "pending", "sent" ) NOT NULL DEFAULT 'pending' AFTER `last_commented` ;
ALTER TABLE `{tbl_prefix}_groups` ADD `group_admins` TEXT NOT NULL AFTER `userid` ;

-- Alterations for 2.4.5

ALTER TABLE `{tbl_prefix}pages` ADD `page_order` BIGINT( 100 ) NOT NULL AFTER `page_id` ,
ADD `display` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'yes' AFTER `page_order` ;
ALTER TABLE  `{tbl_prefix}users` ADD  `voted` TEXT NOT NULL AFTER  `last_commented`;

-- Alterations for 2.5.5
ALTER TABLE `{tbl_prefix}groups` ADD `group_admins` TEXT NOT NULL AFTER `userid`;

-- Alterations for 2.6
ALTER TABLE  `{tbl_prefix}video` CHANGE  `category`  `category` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0';
ALTER TABLE `{tbl_prefix}collections` CHANGE `category` `category` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

CREATE TABLE IF NOT EXISTS `{tbl_prefix}counters` (
  `counter_id` int(100) NOT NULL AUTO_INCREMENT,
  `section` varchar(200) NOT NULL,
  `query` text NOT NULL,
  `query_md5` varchar(200) NOT NULL,
  `counts` bigint(200) NOT NULL,
  `date_added` varchar(200) NOT NULL,
  PRIMARY KEY (`counter_id`),
  UNIQUE KEY `query_md5` (`query_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `{tbl_prefix}user_levels_permissions` ADD `photos_moderation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no',
ADD `collection_moderation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `photos_moderation` ,
ADD `plugins_moderation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `collection_moderation` ,
ADD `tool_box` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `plugins_moderation` ,
ADD `plugins_perms` TEXT NOT NULL AFTER `tool_box` ;

CREATE TABLE IF NOT EXISTS `{tbl_prefix}mass_emails` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `email_subj` varchar(255) NOT NULL,
  `email_from` varchar(255) NOT NULL,
  `email_msg` text NOT NULL,
  `configs` text NOT NULL,
  `sent` bigint(255) NOT NULL,
  `total` bigint(20) NOT NULL,
  `users` text NOT NULL,
  `start_index` bigint(255) NOT NULL,
  `method` enum('browser','background') NOT NULL,
  `status` enum('completed','pending','sending') NOT NULL,
  `date_added` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;