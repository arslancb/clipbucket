DROP TABLE IF EXISTS `{tbl_prefix}sessions`;
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

ALTER TABLE `{tbl_prefix}users` ADD `album_privacy` ENUM( 'public', 'private', 'friends' ) NOT NULL DEFAULT 'private' ;