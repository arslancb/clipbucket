-- enabling Fulltext search with InnoDB as mysql engine
ALTER TABLE `{tbl_prefix}video` ADD FULLTEXT INDEX (`title`,`tags`);

-- Addition for Cooporate pick default sign up country geologically
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'pick_geo_country', 'yes');

-- enabling Fulltext search with InnoDB as mysql engine
ALTER TABLE `{tbl_prefix}photos` ADD FULLTEXT INDEX (`photo_title`,`photo_tags`);