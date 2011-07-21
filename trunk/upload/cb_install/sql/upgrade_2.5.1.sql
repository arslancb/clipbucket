ALTER TABLE `{tbl_prefix}groups` ADD `group_admins` TEXT NOT NULL AFTER `userid`;

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'embed_type', 'iframe');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'playlistsSection', 'yes');

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'vid_cat_height', '120');
INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'vid_cat_width', '120');

ALTER TABLE  `{tbl_prefix}photos` ADD  `photo_details` TEXT NOT NULL AFTER  `photo_tags`;