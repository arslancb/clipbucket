ALTER TABLE `{tbl_prefix}groups` ADD `group_admins` TEXT NOT NULL AFTER `userid`;

INSERT INTO `{tbl_prefix}config` (`configid`, `name`, `value`) VALUES (NULL, 'embed_type', 'iframe');