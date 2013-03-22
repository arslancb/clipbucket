ALTER TABLE  `{tbl_prefix}video` ADD  `slug_id` INT( 255 ) NOT NULL AFTER  `videokey`;

CREATE TABLE IF NOT EXISTS `{tbl_prefix}slugs` (
  `slug_id` int(255) NOT NULL AUTO_INCREMENT,
  `object_type` varchar(5) NOT NULL,
  `object_id` int(255) NOT NULL,
  `in_use` enum('yes','no') NOT NULL DEFAULT 'yes',
  `slug` mediumtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`slug_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `{tbl_prefix}video_categories` ADD  `category_icon` VARCHAR( 100 ) NOT NULL AFTER  `category_name`;
ALTER TABLE  `{tbl_prefix}user_categories` ADD  `category_icon` VARCHAR( 100 ) NOT NULL AFTER  `category_name`;
ALTER TABLE  `{tbl_prefix}group_categories` ADD  `category_icon` VARCHAR( 100 ) NOT NULL AFTER  `category_name`;
ALTER TABLE  `{tbl_prefix}collection_categories` ADD  `category_icon` VARCHAR( 100 ) NOT NULL AFTER  `category_name`;

ALTER TABLE  `{tbl_prefix}playlist_items` ADD  `order` BIGINT( 10 ) NOT NULL AFTER  `playlist_id`;
ALTER TABLE  `{tbl_prefix}playlist_items` ADD  `item_note` MEDIUMTEXT NOT NULL AFTER  `order`;
ALTER TABLE  `{tbl_prefix}collection_categories` ADD  `category_icon` VARCHAR( 100 ) NOT NULL AFTER  `category_name`;

CREATE TABLE IF NOT EXISTS `{tbl_prefix}photosmeta` (
  `pmeta_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `photo_id` bigint(255) NOT NULL,
  `meta_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `meta_value` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`pmeta_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

CREATE TABLE IF NOT EXISTS `{tbl_prefix}photo_tags` (
  `ptag_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `ptag_key` varchar(32) CHARACTER SET utf8 NOT NULL,
  `ptag_width` int(10) NOT NULL,
  `ptag_height` int(10) NOT NULL,
  `ptag_top` int(10) NOT NULL,
  `ptag_left` int(10) NOT NULL,
  `ptag_userid` bigint(100) NOT NULL,
  `ptag_username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_isuser` int(4) NOT NULL,
  `ptag_isfriend` int(4) NOT NULL,
  `ptag_by_userid` bigint(100) NOT NULL,
  `ptag_by_username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_by_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_active` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  `date_added` bigint(255) NOT NULL,
  `photo_id` bigint(255) NOT NULL,
  `photo_owner_userid` bigint(255) NOT NULL,
  `photo_owner_username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `photo_owner_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ptag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

ALTER TABLE  `{tbl_prefix}collections` ADD  `last_updated` DATETIME NOT NULL AFTER  `last_commented`;
ALTER TABLE  `{tbl_prefix}collections` ADD  `cover_photo` bigint(20) NOT NULL AFTER  `total_objects`;
ALTER TABLE  `{tbl_prefix}collections` ADD  `is_avatar_collection` enum('yes','no') NOT NULL DEFAULT 'no' AFTER  `type`;

ALTER TABLE  `{tbl_prefix}photos` ADD  `ptags_count` int(255) NOT NULL AFTER  `total_comments`;
ALTER TABLE  `{tbl_prefix}photos` ADD  `exif_data` enum('yes','no') NOT NULL DEFAULT 'yes' AFTER  `ptags_count`;
ALTER TABLE  `{tbl_prefix}photos` ADD  `is_avatar` enum('yes','no') NOT NULL DEFAULT 'no' AFTER  `owner_ip`;
ALTER TABLE  `{tbl_prefix}photos` ADD  `is_mature` enum('yes','no') NOT NULL DEFAULT 'no' AFTER  `is_avatar`;
ALTER TABLE  `{tbl_prefix}photos` ADD  `view_exif` enum('yes','no') NOT NULL DEFAULT 'yes' AFTER  `is_mature`;

ALTER TABLE  `{tbl_prefix}users` ADD  `avatar_collection` bigint(255) NOT NULL AFTER  `avatar_url`;


CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_profiles` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `format` varchar(100) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `suffix` varchar(100) NOT NULL,
  `height` smallint(5) NOT NULL,
  `width` smallint(5) NOT NULL,
  `profile_order` int(10) NOT NULL,
  `verify_dimension` enum('yes','no') NOT NULL DEFAULT 'yes',
  `video_codec` varchar(50) NOT NULL,
  `audio_codec` varchar(50) NOT NULL,
  `audio_bitrate` mediumint(50) NOT NULL,
  `video_bitrate` mediumint(50) NOT NULL,
  `audio_rate` mediumint(50) NOT NULL,
  `video_rate` mediumint(50) NOT NULL,
  `resize` enum('none','max','fit','wxh') NOT NULL DEFAULT 'max',
  `preset` enum('none','low','normal','hq','max') NOT NULL DEFAULT 'normal',
  `2pass` enum('yes','no') NOT NULL DEFAULT 'no',
  `apply_watermark` enum('yes','no') NOT NULL,
  `ffmpeg_cmd` mediumtext NOT NULL,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE  `{tbl_prefix}video_files`;
ALTER TABLE  `{tbl_prefix}conversion_queue` CHANGE  `cqueue_conversion`  `conversion` ENUM(  'yes',  'no',  'p' )  NOT NULL DEFAULT  'no';
ALTER TABLE  `{tbl_prefix}conversion_queue` CHANGE  `cqueue_id`  `queue_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
CHANGE  `cqueue_name`  `queue_name` VARCHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE  `cqueue_ext`  `queue_ext` VARCHAR( 5 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE  `cqueue_tmp_ext`  `queue_tmp_ext` VARCHAR( 3 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE  `{tbl_prefix}conversion_queue` ADD  `status` ENUM(  'u',  's',  'f' ) NOT NULL DEFAULT  'u' ;
ALTER TABLE  `{tbl_prefix}conversion_queue` ADD  `messages` TEXT AFTER  `status`;

DROP TABLE IF EXISTS `{tbl_prefix}video_files`;
CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_files` (
  `file_id` int(255) NOT NULL AUTO_INCREMENT,
  `queue_id` int(255) NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `file_directory` varchar(200) NOT NULL,
  `original_source` varchar(255) NOT NULL,
  `is_original` enum('yes','no') NOT NULL DEFAULT 'no',
  `file_ext` varchar(5) NOT NULL,
  `output_results` text NOT NULL,
  `status` enum('p','s','f') NOT NULL,
  `profile_id` int(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `log` text NOT NULL,
  `date_completed` time NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 7-26-2012
ALTER TABLE  `{tbl_prefix}users` CHANGE  `featured`  `featured` ENUM(  'no',  'yes' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'no';
ALTER TABLE  `{tbl_prefix}users` CHANGE  `usr_status`  `status` ENUM(  'Ok',  'ToActivate' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'ToActivate';
ALTER TABLE  `{tbl_prefix}users` CHANGE  `status`  `status` ENUM(  'Ok',  'ToActivate',  'verified',  'unverified' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'unverified';
UPDATE {tbl_prefix}users SET status='verified' WHERE status='Ok';
UPDATE {tbl_prefix}users SET status='unverified' WHERE status='ToActivate';
--ALTER TABLE  `{tbl_prefix}users` CHANGE  `status`  `status` ENUM(  'verified',  'unverified' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'unverified';

-- 8-31-2012
ALTER TABLE  `{tbl_prefix}conversion_queue` ADD  `file_directory` VARCHAR( 255 ) NOT NULL AFTER  `queue_tmp_ext`;

-- 9-2-2012
CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_meta` (
  `meta_id` int(255) NOT NULL AUTO_INCREMENT,
  `meta_name` varchar(255) NOT NULL,
  `videoid` int(255) NOT NULL,
  `meta_value` text NOT NULL,
  `extras` text NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--9-26-2012
ALTER TABLE  `{tbl_prefix}group_members` ADD  `is_admin` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'no' AFTER  `userid` ,
ADD  `ban` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'no' AFTER  `is_admin`;


--11-29-2102 @author: Fawaz Tahir
ALTER TABLE  `{tbl_prefix}photos` CHANGE  `exif_data`  `has_exif` ENUM(  'yes',  'no' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'no';

--11-28-2012 @author : Arslan
CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_mentions` (
  `mention_id` int(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `who_id` int(255) NOT NULL,
  `who_type` varchar(10) NOT NULL,
  `feed_id` int(255) NOT NULL,
  `comment_id` int(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`mention_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--11-29-2012 @author: Arslan
ALTER TABLE  `{tbl_prefix}comments` ADD  `comment_attributes` TEXT NOT NULL AFTER  `comment`;
ALTER TABLE  `{tbl_prefix}comments` ADD  `has_children` INT( 50 ) NOT NULL AFTER  `parent_id`;
ALTER TABLE  `{tbl_prefix}comments` ADD  `thread_id` INT( 100 ) NOT NULL AFTER  `parent_id`;


--12-7-2012 @author : Arslan

ALTER TABLE  `{tbl_prefix}subscriptions` ADD  `type` VARCHAR( 10 ) NOT NULL DEFAULT  'user' AFTER  `subscribed_to` ,
ADD  `time_added` INT( 11 ) NOT NULL AFTER  `type`;


--12-18-2012 @author : Fawaz
ALTER TABLE  `{tbl_prefix}photos` ADD  `file_directory` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `view_exif`;

--12-20-2012 @author : Arslan [Cancelled]
--ALTER TABLE  `{tbl_prefix}video` ADD  `emails_sent` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'no';

ALTER TABLE  `{tbl_prefix}conversion_queue` ADD  `active` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes' AFTER  `status`;

--12-27-2012 @author : Arslan, @added by : Fawaz
DROP TABLE IF EXISTS `{tbl_prefix}conversion_queue`;
CREATE TABLE `{tbl_prefix}conversion_queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `queue_name` varchar(32) CHARACTER SET latin1 NOT NULL,
  `queue_ext` varchar(5) CHARACTER SET latin1 NOT NULL,
  `queue_tmp_ext` varchar(3) CHARACTER SET latin1 NOT NULL,
  `file_directory` varchar(255) NOT NULL,
  `conversion` enum('yes','no','p') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  `conversion_counts` int(10) NOT NULL,
  `status` enum('u','s','f') NOT NULL DEFAULT 'u',
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `messages` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_started` varchar(32) NOT NULL,
  `time_completed` varchar(32) NOT NULL,
  PRIMARY KEY (`queue_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

DROP TABLE IF EXISTS `{tbl_prefix}playlists`;
CREATE TABLE `{tbl_prefix}playlists` (
  `playlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(225) CHARACTER SET latin1 NOT NULL,
  `userid` int(11) NOT NULL,
  `playlist_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `description` mediumtext NOT NULL,
  `tags` mediumtext NOT NULL,
  `played` bigint(255) NOT NULL,
  `privacy` enum('public','private','unlisted') NOT NULL DEFAULT 'public',
  `allow_comments` enum('yes','no') NOT NULL DEFAULT 'yes',
  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes',
  `total_comments` int(255) NOT NULL,
  `total_items` bigint(200) NOT NULL,
  `rating` bigint(3) NOT NULL,
  `rated_by` int(200) NOT NULL,
  `voters` text NOT NULL,
  `last_update` datetime NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`playlist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

DROP TABLE IF EXISTS `{tbl_prefix}playlist_items`;
CREATE TABLE `{tbl_prefix}playlist_items` (
  `playlist_item_id` int(225) NOT NULL AUTO_INCREMENT,
  `object_id` int(225) NOT NULL,
  `playlist_id` int(225) NOT NULL,
  `item_order` bigint(10) NOT NULL,
  `item_note` mediumtext NOT NULL,
  `playlist_item_type` varchar(10) CHARACTER SET latin1 NOT NULL,
  `userid` int(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`playlist_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111 ;

DROP TABLE IF EXISTS `{tbl_prefix}video_files`;
CREATE TABLE `{tbl_prefix}video_files` (
  `file_id` int(255) NOT NULL AUTO_INCREMENT,
  `queue_id` int(255) NOT NULL,
  `file_name` varchar(32) NOT NULL,
  `file_directory` varchar(200) NOT NULL,
  `original_source` varchar(255) NOT NULL,
  `is_original` enum('yes','no') NOT NULL DEFAULT 'no',
  `file_ext` varchar(5) NOT NULL,
  `output_results` text NOT NULL,
  `status` enum('p','s','f') NOT NULL,
  `profile_id` int(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `log` text NOT NULL,
  `date_completed` int(12) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

ALTER TABLE  `{tbl_prefix}video` ADD  `file_directory` VARCHAR( 25 ) NOT NULL AFTER  `uploader_ip`;

-- 12-24-2012 @ Author Arslan
ALTER TABLE  `{tbl_prefix}users` ADD  `first_name` VARCHAR( 50 ) NOT NULL AFTER  `username` ,
ADD  `last_name` VARCHAR( 50 ) NOT NULL AFTER  `first_name`;

--12-27-2012 @Author Arslan
ALTER TABLE  `{tbl_prefix}playlists` ADD  `category` ENUM(  'normal',  'favorites',  'likes',  'history',  'quicklist',  'watch_later' ) NOT NULL DEFAULT  'normal' AFTER  `playlist_type`;

--12-31-2012 @Author Arslan

DROP TABLE IF EXISTS `{tbl_prefix}messages`;
CREATE TABLE `{tbl_prefix}messages` (
  `message_id` int(225) NOT NULL AUTO_INCREMENT,
  `thread_id` bigint(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `seen_by` mediumtext NOT NULL,
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cb_recipients`
--


DROP TABLE IF EXISTS `{tbl_prefix}recipients`;
CREATE TABLE `{tbl_prefix}recipients` (
  `recipient_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `thread_id` bigint(20) NOT NULL,
  `unread_msgs` int(10) NOT NULL,
  `unseen_msgs` int(20) NOT NULL,
  `last_message_time` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`recipient_id`),
  KEY `thread_id` (`thread_id`),
  KEY `userid` (`userid`),
  KEY `userid_2` (`userid`),
  KEY `userid_3` (`userid`),
  KEY `thread_id_2` (`thread_id`),
  KEY `thread_id_3` (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cb_threads`
--

DROP TABLE IF EXISTS `{tbl_prefix}threads`;
CREATE TABLE IF NOT EXISTS `{tbl_prefix}threads` (
  `thread_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `thread_type` enum('private','public') NOT NULL DEFAULT 'private',
  `userid` int(11) NOT NULL,
  `recipient_md5` varchar(32) NOT NULL,
  `total_recipients` bigint(100) NOT NULL,
  `main_recipients` mediumtext NOT NULL,
  `total_messages` bigint(100) NOT NULL,
  `last_message_id` int(255) NOT NULL,
  `last_message` tinytext NOT NULL,
  `subject` tinytext NOT NULL,
  `last_userid` int(255) NOT NULL,
  `last_message_date` datetime NOT NULL,
  `is_archived` enum('yes','no') NOT NULL DEFAULT 'no',
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`thread_id`),
  UNIQUE KEY `recipient_md5` (`recipient_md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--1-1-2013 @Author Arslan
ALTER TABLE  `{tbl_prefix}users` ADD  `active` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes' AFTER  `status`;

--1-4-2013 @Author Arslan
DROP TABLE IF EXISTS `{tbl_prefix}friend_requests`;
CREATE TABLE IF NOT EXISTS `{tbl_prefix}friend_requests` (
  `req_id` int(100) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `friend_id` int(255) NOT NULL,
  `message` varchar(200) NOT NULL,
  `seen` enum('yes','no') NOT NULL DEFAULT 'no',
  `ignored` enum('yes','no') NOT NULL DEFAULT 'no',
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`req_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- Adding new message structure
DROP TABLE IF EXISTS `{tbl_prefix}messages`;
CREATE TABLE IF NOT EXISTS `cb_messages` (
  `message_id` int(225) NOT NULL AUTO_INCREMENT,
  `thread_id` bigint(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `seen_by` mediumtext NOT NULL,
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `userid` (`userid`),
  KEY `thread_id` (`thread_id`),
  KEY `time_added` (`time_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `{tbl}feeds`;
CREATE TABLE IF NOT EXISTS `{tbl}feeds` (
  `feed_id` int(255) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `message_attributes` mediumtext NOT NULL,
  `userid` int(255) NOT NULL,
  `user` text NOT NULL,
  `content_id` int(255) NOT NULL,
  `content_cached_id` int(255) NOT NULL,
  `content_type` varchar(50) NOT NULL,
  `object_id` int(255) NOT NULL,
  `object_cached_id` int(255) NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `action` varchar(200) NOT NULL,
  `action_group_id` int(11) NOT NULL,
  `is_activity` enum('yes','no') NOT NULL DEFAULT 'no',
  `privacy` varchar(200) NOT NULL,
  `comments_count` bigint(255) NOT NULL,
  `comments` text NOT NULL,
  `likes_count` bigint(255) NOT NULL,
  `likes` text NOT NULL,
  `date_added` datetime NOT NULL,
  `time_added` int(11) NOT NULL,
  `last_commented` datetime NOT NULL,
  `last_updated` varchar(200) NOT NULL,
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cb_notifications`
--

DROP TABLE IF EXISTS `{tbl_prefix}notifications`;
CREATE TABLE IF NOT EXISTS `{tbl}notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `action` varchar(20) NOT NULL DEFAULT 'users',
  `actor` mediumtext NOT NULL,
  `actor_id` int(200) NOT NULL,
  `is_read` enum('yes','no') NOT NULL DEFAULT 'no',
  `time_added` int(11) NOT NULL,
  `elements` text NOT NULL,
  `date_added` datetime NOT NULL,
  `email_sent` enum('yes','no') NOT NULL DEFAULT 'no',
  `send_email` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`notification_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cb_objects_cache`
--

DROP TABLE IF EXISTS `{tbl_prefix}objects_cache`;
CREATE TABLE IF NOT EXISTS `{tbl_prefix}objects_cache` (
  `object_id` int(255) NOT NULL AUTO_INCREMENT,
  `type_id` int(255) NOT NULL,
  `type` varchar(5) NOT NULL,
  `content` text NOT NULL,
  `last_updated` int(11) NOT NULL,
  `time_added` int(11) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `object_type_id` (`type_id`),
  KEY `object_type_id_2` (`type_id`),
  KEY `object_type` (`type`),
  KEY `type_id` (`type_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Altering table structure for table `cb_collections`
-- 1/8/2012
-- @author: Fawaz Tahir
--
ALTER TABLE  `{tbl_prefix}collections` CHANGE  `cover_photo`  `cover_photo` TEXT NOT NULL;

--
-- Table structure for table `cb_user_notifications`
-- 3/21/2012
-- @author: Arslan Hassan
--

CREATE TABLE IF NOT EXISTS `{tbl_prefix}user_notifications` (
  `notification_id` int(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `new_msgs` int(20) NOT NULL,
  `new_notifications` int(20) NOT NULL,
  `new_friend_requests` int(20) NOT NULL,
  `date_updated` datetime NOT NULL,
  `time_updated` int(11) NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




