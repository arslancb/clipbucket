ALTER TABLE  `{tbl_prefix}video` ADD  `slug_id` INT( 255 ) NOT NULL AFTER  `videokey`;

CREATE TABLE IF NOT EXISTS `{tbl_prefix}slugs` (
  `slug_id` int(255) NOT NULL AUTO_INCREMENT,
  `object_type` varchar(5) NOT NULL,
  `object_id` int(255) NOT NULL,
  `in_use` enum('yes','no') NOT NULL DEFAULT 'yes',
  `slug` mediumtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`slug_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;