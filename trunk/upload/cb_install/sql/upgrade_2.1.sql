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

ALTER TABLE `{tbl_prefix}collections` ADD `rating` BIGINT( 20 ) NOT NULL;
ALTER TABLE `{tbl_prefix}collections` ADD `rated_by` BIGINT( 20 ) NOT NULL AFTER `rating`  ;

ALTER TABLE `{tbl_prefix}collections` ADD `voters` LONGTEXT NOT NULL AFTER `rated_by` ;
ALTER TABLE `{tbl_prefix}collections` ADD `allow_rating` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'yes' AFTER `allow_comments` ;

ALTER TABLE `{tbl_prefix}user_profile` ADD `profile_item` VARCHAR( 25 ) NOT NULL AFTER `profile_video` ;
ALTER TABLE `{tbl_prefix}video` ADD `last_commented` DATETIME NOT NULL AFTER `comments_count` ;
ALTER TABLE `{tbl_prefix}users` ADD `last_commented` DATETIME NOT NULL AFTER `comments_count` ;
ALTER TABLE `{tbl_prefix}collections` ADD `last_commented` DATETIME NOT NULL AFTER `total_comments` ;
ALTER TABLE `{tbl_prefix}photos` ADD `last_commented` DATETIME NOT NULL AFTER `total_comments` ;

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