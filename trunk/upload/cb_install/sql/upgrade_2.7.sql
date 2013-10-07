-- Adding new user level permission
-- Author Arslan
ALTER TABLE  `{tbl_prefix}user_levels_permissions` ADD  `allow_manage_user_level` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'no' AFTER  `plugins_perms`;
UPDATE  `{tbl_prefix}user_levels_permissions` SET  `allow_manage_user_level` =  'yes' WHERE  `cb_user_levels_permissions`.`user_level_permission_id` =1;
INSERT INTO `{tbl_prefix}user_permissions` (`permission_id`, `permission_type`, `permission_name`, `permission_code`, `permission_desc`, `permission_default`) VALUES (NULL, '3', 'Allow manage user levels', 'allow_manage_user_level', 'Allow user to edit user levels', 'no');


-- Adding Collection contributors

CREATE TABLE IF NOT EXISTS `{tbl_prefix}collection_contributors` (
  `contributor_id` int(200) NOT NULL AUTO_INCREMENT,
  `collection_id` int(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `can_edit` enum('yes','no') NOT NULL DEFAULT 'no',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`contributor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;