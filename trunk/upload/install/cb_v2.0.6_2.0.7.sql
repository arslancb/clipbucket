-- Language Vars
INSERT INTO `{tbl_prefix}phrases` (`id`, `lang_iso`, `varname`, `text`) VALUES
(NULL, 'en', 'pending_requests', 'Pending requests'),
(NULL, 'en', 'friend_add_himself_error', 'You cannot add yourself as a friend');

-- New Configs

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'allow_username_spaces', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'use_playlist', 'yes');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'comments_captcha', 'all');

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'player_logo_file', 'logo.png');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'logo_placement', 'br');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'buffer_time', '3');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'youtube_enabled', 'yes');

-- Contacts Table Alteration

ALTER TABLE `{tbl_prefix}contacts` ADD `request_type` ENUM( 'in', 'out' ) NOT NULL AFTER `contact_group_id` ;