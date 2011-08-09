ALTER TABLE  `{tbl_prefix}video` CHANGE  `category`  `category` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0';
ALTER TABLE `{tbl_prefix}collections` CHANGE `category` `category` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'facebook_embed', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'seo_vido_url', '0');