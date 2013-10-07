INSERT INTO `{tbl_prefix}config` (`configid` ,`name` ,`value`) VALUES
(NULL , 'quick_conv', 'yes'),
(NULL, 'server_friendly_conversion', 'yes'),
(NULL, 'max_conversion', '2'),
(NULL, 'max_time_wait', '7200'),
(NULL , 'allow_unicode_usernames', 'yes'),
(NULL, 'min_username', '3'),
(NULL, 'max_username', '15');
UPDATE `{tbl_prefix}config` SET `name` =  'allow_registration'  WHERE `name` = 'allow_registeration' LIMIT 1 ;