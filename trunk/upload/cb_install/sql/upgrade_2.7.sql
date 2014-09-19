-- Adding new user level permission
-- Author Arslan
ALTER TABLE  `{tbl_prefix}user_levels_permissions` ADD  `allow_manage_user_level` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'no' AFTER  `plugins_perms`;
UPDATE  `{tbl_prefix}user_levels_permissions` SET  `allow_manage_user_level` =  'yes' WHERE  `cb_user_levels_permissions`.`user_level_permission_id` =1;
INSERT INTO `{tbl_prefix}user_permissions` (`permission_id`, `permission_type`, `permission_name`, `permission_code`, `permission_desc`, `permission_default`) VALUES (NULL, '3', 'Allow manage user levels', 'allow_manage_user_level', 'Allow user to edit user levels', 'no');


-- Adding Collection contributors

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_contributors` (
  `contributor_id` int(200) NOT NULL AUTO_INCREMENT,
  `collection_id` int(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `can_edit` enum('yes','no') NOT NULL DEFAULT 'no',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`contributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Adding video views table

CREATE TABLE IF NOT EXISTS `{tbl_prefix}video_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` varchar(255) NOT NULL,
  `video_views` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- Adding File Directory for Photos and videos
ALTER TABLE  `{tbl_prefix}photos` ADD  `file_directory` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `filename`;
ALTER TABLE  `{tbl_prefix}video` ADD  `file_directory` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `file_name`;

-- Updating playlist tables
ALTER TABLE  `{tbl_prefix}playlists` ADD  `catgory` enum('normal','favorites','likes','history','quicklist','watch_later') NOT NULL DEFAULT 'normal' AFTER  `playlist_type`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `description` mediumtext CHARACTER SET utf8 NOT NULL AFTER  `category`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `tags` mediumtext CHARACTER SET utf8 NOT NULL AFTER  `description`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `played` int(255) NOT NULL AFTER  `tags`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `privacy` enum('public','private','unlisted') NOT NULL DEFAULT 'public' AFTER  `tags`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `allow_comments` enum('yes','no') NOT NULL DEFAULT 'yes' AFTER  `privacy`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `allow_rating` enum('yes','no') NOT NULL DEFAULT 'yes' AFTER  `allow_comments`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `total_comments` int(255) NOT NULL AFTER  `allow_rating`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `total_items` int(255) NOT NULL AFTER  `total_comments`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `rating` int(3) NOT NULL AFTER  `total_items`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `rated_by` int(255) NOT NULL AFTER  `rating`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `voters` text CHARACTER SET utf8 NOT NULL AFTER  `rated_by`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `last_update` text CHARACTER SET utf8 NOT NULL AFTER  `voters`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `runtime` int(200) NOT NULL AFTER  `last_update`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `first_item` text CHARACTER SET utf8 NOT NULL AFTER  `runtime`;
ALTER TABLE  `{tbl_prefix}playlists` ADD  `cover` text CHARACTER SET utf8 NOT NULL AFTER  `first_item`;

ALTER TABLE `{tbl_prefix}users` ADD `likes` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `{tbl_prefix}users` CHANGE `voted` `voted` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `{tbl_prefix}photos` ADD `photo_details` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `{tbl_prefix}action_log` DROP `action_link`; 
ALTER TABLE  `{tbl_prefix}video` ADD  `file_directory` VARCHAR( 10 ) NOT NULL AFTER  `file_server_path`
