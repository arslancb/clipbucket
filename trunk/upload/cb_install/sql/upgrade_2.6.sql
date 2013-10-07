ALTER TABLE  `{tbl_prefix}video` CHANGE  `category`  `category` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0';
ALTER TABLE `{tbl_prefix}collections` CHANGE `category` `category` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'facebook_embed', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'seo_vido_url', '0');

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'use_cached_pagin', 'yes'),
(NULL, 'cached_pagin_time', '5');

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


ALTER TABLE `{tbl_prefix}user_levels_permissions` ADD `photos_moderation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' ,
ADD `collection_moderation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `photos_moderation` ,
ADD `plugins_moderation` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `collection_moderation` ,
ADD `tool_box` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no' AFTER `plugins_moderation` ,
ADD `plugins_perms` TEXT NOT NULL AFTER `tool_box` ;

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

UPDATE {tbl_prefix}video SET rating='10' WHERE rating>10;

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

ALTER TABLE  `{tbl_prefix}photos` ADD  `photo_details` TEXT NOT NULL AFTER  `photo_tags`;