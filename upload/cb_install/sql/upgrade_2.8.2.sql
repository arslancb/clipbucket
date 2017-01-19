-- Addition for 2.8.2
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'store_guest_session', 'no');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'pseudostreaming', 'yes');

ALTER TABLE `{tbl_prefix}video` ADD `re_conv_status` text(33) NOT NULL;
#ALTER TABLE `{tbl_prefix}video` ADD `conv_progress` int(33) NOT NULL;