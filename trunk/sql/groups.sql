CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(255) NOT NULL AUTO_INCREMENT,
  `group_name` mediumtext NOT NULL,
  `group_description` mediumtext NOT NULL,
  `group_category` int(255) NOT NULL,
  `group_tags` mediumtext NOT NULL,
  `group_url` mediumtext NOT NULL,
  `group_image` mediumtext NOT NULL,
  `group_type` enum('0','1','2') NOT NULL,
  `group_owner` mediumtext NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` enum('yes','no') NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

CREATE TABLE IF NOT EXISTS `group_categories` (
  `category_id` int(225) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_desc` text NOT NULL,
  `date_added` mediumtext NOT NULL,
  `category_thumb` mediumtext NOT NULL,
  `isdefault` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

CREATE TABLE IF NOT EXISTS `group_members` (
  `group_mid` int(225) NOT NULL AUTO_INCREMENT,
  `group_id` int(225) NOT NULL,
  `ownerid` int(255) NOT NULL,
  `userid` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`group_mid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

CREATE TABLE IF NOT EXISTS `group_stats` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `group_id` int(255) NOT NULL,
  `total_members` int(255) NOT NULL,
  `total_topics` int(255) NOT NULL,
  `total_posts` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;