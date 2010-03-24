-- $Id$

INSERT INTO `{tbl_prefix}config` (`configid` ,`name` ,`value`) VALUES
(NULL , 'quick_conv', 'yes'),
(NULL, 'server_friendly_conversion', 'yes'),
(NULL, 'max_conversion', '2'),
(NULL, 'max_time_wait', '7200'),
(NULL , 'allow_unicode_usernames', 'yes'),
(NULL, 'min_username', '3'),
(NULL, 'max_username', '15');

INSERT INTO `{tbl_prefix}phrases` (`lang_iso`, `varname`, `text`) VALUES
('en', 'no_results_found', 'No results found'),
('en', 'please_enter_val_bw_min_max', 'Please enter ''%s'' value between ''%s'' and ''%s'''),
('en', 'no_new_subs_video', 'No new videos found in subscriptions'),
('en', 'inapp_content', 'Inappropriate Content'),
('en', 'copyright_infring', 'Copyright infringement'),
('en', 'sexual_content', 'Sexual Content'),
('en', 'violence_replusive_content', 'Violence or repulsive content'),
('en', 'disturbing', 'Disturbing'),
('en', 'other', 'Other');

UPDATE `{tbl_prefix}config` SET `name` =  'allow_registration'  WHERE `name` = 'allow_registeration' LIMIT 1 ;