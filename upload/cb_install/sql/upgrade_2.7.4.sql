-- Addition for 2.7.4



INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'player_logo_url', 'http://clip-bucket.com/');

--
-- Table structure for table `cb_admin_todo`
--
CREATE TABLE IF NOT EXISTS `{tbl_prefix}admin_todo` (
  `todo_id` int(225) NOT NULL AUTO_INCREMENT,
  `todo` text CHARACTER SET ucs2 NOT NULL,
  `date_added` datetime NOT NULL,
  `userid` int(225) NOT NULL,
  PRIMARY KEY (`todo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;