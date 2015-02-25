-- For Sql performance improvements
-- @Author : <jozo@jozo.sk>

ALTER TABLE  `{tbl_prefix}plugins` ADD INDEX (  `plugin_active` );
ALTER TABLE  `{tbl_prefix}sessions` ADD INDEX (  `session` );
ALTER TABLE  `{tbl_prefix}languages` ADD INDEX (  `language_default` );
ALTER TABLE  `{tbl_prefix}conversion_queue` ADD INDEX (  `cqueue_conversion` );
ALTER TABLE  `{tbl_prefix}video` ADD INDEX (  `status`,`active`,`broadcast`,`userid` );
ALTER TABLE  `{tbl_prefix}user_levels_permissions` ADD INDEX (  `user_level_id` );
ALTER TABLE  `{tbl_prefix}video` ADD INDEX (  `userid` );
ALTER TABLE  `{tbl_prefix}users` ADD INDEX (  `username`(255),`userid` );
ALTER TABLE  `{tbl_prefix}pages` ADD INDEX (  `active`,`display` );
ALTER TABLE  `{tbl_prefix}video` ADD INDEX (  `videoid`,`videokey`(255) );
ALTER TABLE  `{tbl_prefix}languages` ADD INDEX (  `language_code`,`language_id` );
ALTER TABLE  `{tbl_prefix}video_categories` ADD INDEX (  `parent_id` );