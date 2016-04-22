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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_categories` (
  `category_id` int(255) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL,
  `category_order` int(5) NOT NULL,
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumint(9) NOT NULL,
  `isdefault` enum('yes','no') NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_items` (
  `ci_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `collection_id` bigint(20) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`ci_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



ALTER TABLE `{tbl_prefix}pages` ADD `page_order` BIGINT( 100 ) NOT NULL AFTER `page_id` ,
ADD `display` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'yes' AFTER `page_order` ;

UPDATE `{tbl_prefix}pages` SET `display` = 'no' WHERE `page_id` =5 LIMIT 1 ;
UPDATE `{tbl_prefix}pages` SET `display` = 'no' WHERE`page_id` =6 LIMIT 1 ;

UPDATE `{tbl_prefix}pages` SET `page_order` = '1' WHERE `page_id` =1 LIMIT 1 ;
UPDATE `{tbl_prefix}pages` SET `page_order` = '2' WHERE `page_id` =2 LIMIT 1 ;
UPDATE `{tbl_prefix}pages` SET `page_order` = '3' WHERE `page_id` =3 LIMIT 1 ;
UPDATE `{tbl_prefix}pages` SET `page_order` = '4' WHERE `page_id` =4 LIMIT 1 ;
UPDATE `{tbl_prefix}pages` SET `page_order` = '5' WHERE `page_id` =5 LIMIT 1 ;
UPDATE `{tbl_prefix}pages` SET `page_order` = '6' WHERE `page_id` =6 LIMIT 1 ;

ALTER TABLE  `{tbl_prefix}users` ADD  `voted` TEXT NOT NULL AFTER  `last_commented`;