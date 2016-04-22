<?php

/**
 * This file is used to install custom fields mod
 */

require_once('../includes/common.php');



function install_custom_fields()
{
	global $db;
	$db->Execute(
"CREATE TABLE `clipbucket_svn`.`".tbl('custom_fields')."` (
`custom_field_list_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`custom_field_title` TEXT NOT NULL ,
`custom_field_type` TEXT NOT NULL ,
`custom_field_name` TEXT NOT NULL ,
`custom_field_id` TEXT NOT NULL ,
`custom_field_value` TEXT NOT NULL ,
`custom_field_hint_1` TEXT NOT NULL ,
`custom_field_db_field` TEXT NOT NULL ,
`custom_field_required` ENUM( 'yes', 'no' ) NOT NULL DEFAULT 'no',
`custom_field_validate_function` TEXT NOT NULL ,
`custom_field_invalid_err` TEXT NOT NULL ,
`custom_field_display_function` TEXT NOT NULL ,
`custom_field_anchor_before` TEXT NOT NULL ,
`custom_field_anchor_after` TEXT NOT NULL ,
`custom_field_hint_2` TEXT NOT NULL ,
`date_added` DATETIME NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;");
	
	$db->Execute(
"INSERT INTO `".tbl('phrases')."` (
`id` ,
`lang_iso` ,
`varname` ,
`text`
)
VALUES (
NULL , 'en', 'cust_field_err', 'Invalid \'%s\' field value'
)");
	
}
install_custom_fields();

?>