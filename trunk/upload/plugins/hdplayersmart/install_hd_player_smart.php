<?php

require_once('../includes/common.php');

$db->Execute("CREATE TABLE ".tbl("hd_smart")." (
`hd_config_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`hd_config_name` VARCHAR( 225 ) NOT NULL ,
`hd_config_value` TEXT NOT NULL ,
`hd_config_description` TEXT NOT NULL
) ENGINE = MYISAM ;
");

$db->Execute("INSERT INTO ".tbl("hd_smart")." (`hd_config_id`, `hd_config_name`, `hd_config_value`, `hd_config_description`) VALUES
(1, 'auto_play', 'yes', ''),
(2, 'logo_placement', 'BR', ''),
(3, 'hd_skin', 'skin_black.swf', ''),
(4, 'custom_variables', '', ''),
(5, 'license', '', ''),
(6, 'embed_visible', 'true', '');");

?>