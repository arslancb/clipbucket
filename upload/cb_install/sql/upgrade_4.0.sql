-- 4.0
-- Author Awais

-- Addition for Cooporate cb seting bitrates for dash/hls
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'vbrate_240', '192000'),
('', 'vbrate_360', '272000'),
('', 'vbrate_480', '352000'),
('', 'vbrate_720', '432000'),
('', 'vbrate_1080', '512000');

-- Addition for Cooporate cb use video watermark or not
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'use_watermark', 'no');

-- Addition for Cooporate cb stream via hls or dash
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'stream_via', 'hls');
-- Addition for video type
ALTER TABLE `{tbl_prefix}video` ADD `file_type` INT( 10 ) NOT NULL DEFAULT '0' AFTER  `file_name`;
ALTER TABLE `{tbl_prefix}video` ADD `blocked_countries` TEXT( 255 ) NOT NULL AFTER `country`;
ALTER TABLE `{tbl_prefix}video` ADD `sprite_count` INT(11) NOT NULL DEFAULT '0' AFTER `blocked_countries`;
--Addition in users table 
ALTER TABLE `{tbl_prefix}users` ADD `is_live` enum('yes','no') NOT NULL DEFAULT 'no' AFTER `likes`;

--Addition of social media link on channel
ALTER TABLE `{tbl_prefix}user_profile` ADD `fb_url` VARCHAR(200) NOT NULL AFTER `web_url`, ADD `twitter_url` VARCHAR(200) NOT NULL AFTER `fb_url`, ADD `insta_url` VARCHAR(200) NOT NULL AFTER `twitter_url`;


--Updating featured videos limit for new template
UPDATE `{tbl_prefix}config` SET value = 'yes' WHERE name = 'cb_combo_res';

-- Addition for Cooporate cb access to logged in users
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'access_to_logged_in', 'no');

-- Addition for clipbucket license --
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'cb_license', 'CBCORP-XXXXXXXXXXX');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'cb_license_local', '');

--Updating featured videos limit for new template
-- value here is yes because no playlist page added yet in cb-git
UPDATE `{tbl_prefix}config` SET value = 'yes' WHERE name = 'collectionsSection';
-- Addition for Cooporate cb allowing collection and playlist page
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES ('', 'playlistsSection', 'yes');


-- Updating featured videos limit for new template
-- Commented because cb-git version has difference from corporate
-- UPDATE `{tbl_prefix}config` SET value = '3' WHERE name = 'index_featured';
-- Reply email template added
INSERT INTO `{tbl_prefix}email_templates` (`email_template_id`, `email_template_name`, `email_template_code`, `email_template_subject`, `email_template`, `email_template_allowed_tags`) VALUES ('', 'User Reply Email', 'user_reply_email', '[{website_title}] {username} made reply on your comment', '{username} has replied on your comment\r\n"{comment}"\r\n\r\n<a href="{obj_link}">{obj_link}</a>\r\n\r\n{website_title} team', '');
-- Changing schema for plugin prems 4.0
ALTER TABLE `{tbl_prefix}user_levels_permissions` MODIFY COLUMN `plugins_perms` text NOT NULL;