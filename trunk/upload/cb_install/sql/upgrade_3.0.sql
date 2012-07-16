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

ALTER TABLE  `{tbl_prefix}conversion_queue` ADD  `status` ENUM(  'u',  's',  'f' ) NOT NULL DEFAULT  'u' AFTER  `conversion_counts`;
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
