-- Addition for 2.8.2
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'store_guest_session', 'no');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'pseudostreaming', 'yes');
INSERT INTO `{tbl_prefix}config`(`name`, `value`) VALUES ('popup_video','yes');

-- These fields are being partially used in this version. 
ALTER TABLE `{tbl_prefix}video` ADD `re_conv_status` text(33) NOT NULL;
ALTER TABLE `{tbl_prefix}video` ADD `conv_progress` int(33) NOT NULL;
/*ALTER TABLE `{tbl_prefix}video` ADD `sprite_count` INT(11) NOT NULL DEFAULT '0' AFTER `conv_progress`;*/