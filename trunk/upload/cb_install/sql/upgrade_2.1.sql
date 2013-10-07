CREATE TABLE IF NOT EXISTS `{tbl_prefix}collections` (
  `collection_id` bigint(25) NOT NULL AUTO_INCREMENT,
  `collection_name` varchar(225) NOT NULL,
  `collection_description` text NOT NULL,
  `collection_tags` text NOT NULL,
  `category` varchar(20) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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


CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_items` (
  `ci_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `collection_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`ci_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'photo_ratio', '16:10'), (NULL, 'photo_thumb_width', '120'), (NULL, 'photo_thumb_height', '75'), (NULL, 'photo_med_width', '185'), (NULL, 'photo_med_height', '116'), (NULL, 'photo_lar_width', '600'), (NULL, 'photo_crop', '1'), (NULL, 'photo_multi_upload', '5'), (NULL, 'photo_download', '1'), (NULL, 'photo_comments', '1'), (NULL, 'photo_rating', '1');

INSERT into {tbl_prefix}config (name,value) VALUES
('load_upload_form','yes'),
('load_remote_upload_form','yes'),
('load_embed_form','yes'),
('load_link_video_form','yes'),
('groupsSection','yes'),
('videosSection','yes'),
('photosSection','yes'),
('homeSection','yes'),
('uploadSection','yes'),
('signupSection','yes'),
('collectionsSection','yes'),
('channelsSection','yes');

INSERT into {tbl_prefix}config (name,value) VALUES
('flvtool++',''),
('normal_resolution', '480'),
('high_resolution', '720'),
('max_video_duration','320');

ALTER TABLE `{tbl_prefix}video` ADD `aspect_ratio` VARCHAR( 10 ) NOT NULL AFTER `default_thumb` ;
ALTER TABLE `{tbl_prefix}video` ADD `failed_reason` ENUM( 'max_duration', 'max_file', 'invalid_format', 'invalid_upload' ) NOT NULL AFTER `status` ;
ALTER TABLE `{tbl_prefix}users` CHANGE `subscribers` `subscribers` BIGINT( 225 ) NOT NULL DEFAULT '0';

INSERT INTO `{tbl_prefix}config` (`configid` ,`name` ,`value`)
VALUES (NULL , 'embed_player_height', '250'),
(NULL , 'embed_player_width', '300');

INSERT into {tbl_prefix}config (name,value) VALUES
('photo_main_list','20'),
('photo_home_tabs','10'),
('photo_search_result','20'),
('photo_channel_page','10'),
('photo_user_photos','20'),
('photo_user_favorites','20'),
('photo_other_limit','8'),
('collection_per_page','20'),
('collection_home_page','10'),
('collection_search_result','20'),
('collection_channel_page','10'),
('collection_user_collections','20'),
('collection_user_favorites','20'),
('collection_items_page','20'),
('channel_rating','1'),
('own_channel_rating','1'),
('collection_rating','1'),
('own_collection_rating','1'),
('own_video_rating','1');

ALTER TABLE `{tbl_prefix}users` ADD `total_photos` BIGINT( 255 ) NOT NULL AFTER `total_comments` ,
ADD `total_collections` BIGINT( 255 ) NOT NULL AFTER `total_photos`;


ALTER TABLE `{tbl_prefix}user_profile` ADD `profile_item` VARCHAR( 25 ) NOT NULL AFTER `profile_video` ;
ALTER TABLE `{tbl_prefix}video` ADD `last_commented` DATETIME NOT NULL AFTER `comments_count` ;
ALTER TABLE `{tbl_prefix}users` ADD `last_commented` DATETIME NOT NULL AFTER `comments_count` ;

ALTER TABLE  `{tbl_prefix}user_profile` ADD `allow_subscription` ENUM(  'yes', 'no' ) NOT NULL DEFAULT  'yes' AFTER `allow_ratings`;

ALTER TABLE  `{tbl_prefix}user_profile` ADD  `show_my_videos` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes',
ADD  `show_my_photos` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes',
ADD  `show_my_subscriptions` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes',
ADD  `show_my_subscribers` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes',
ADD  `show_my_friends` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes';

ALTER TABLE  `{tbl_prefix}users` ADD  `voters` TEXT NOT NULL AFTER  `rating`;

ALTER TABLE  `{tbl_prefix}user_profile` ADD `show_my_collections` ENUM(  'yes', 'no' ) NOT NULL DEFAULT  'yes' AFTER `user_profile_id`;

ALTER TABLE  `{tbl_prefix}users` DROP  `rating` ,
DROP  `voters` ,
DROP  `rated_by` ;

ALTER TABLE  `{tbl_prefix}user_profile` ADD  `rating` TINYINT( 2 ) NOT NULL AFTER  `profile_item` ,
ADD  `voters` TEXT NOT NULL AFTER  `rating` ,
ADD  `rated_by` INT( 150 ) NOT NULL AFTER  `voters`;

ALTER TABLE `{tbl_prefix}comments` CHANGE `date_added` `date_added` DATETIME NOT NULL ;

INSERT INTO `{tbl_prefix}collection_categories` (`category_id`, `category_name`, `category_order`, `category_desc`, `date_added`, `category_thumb`, `isdefault`) VALUES
(1, 'Uncategorized', 0, 'Uncategorized', now(), 0, 'yes');
