-- Adding extras column for future
-- Adding video version column for clipbucket versions
ALTER TABLE  `{tbl_prefix}video` ADD  `video_version` varchar(30)  NOT NULL DEFAULT  "2.6";
ALTER TABLE  `{tbl_prefix}video` ADD  `extras` varchar(225)  NOT NULL;