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
