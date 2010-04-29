<?php

require_once('../includes/common.php');

$db->Execute("CREATE TABLE ".tbl("jw_smart")." (
`jw_config_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`jw_config_name` VARCHAR( 225 ) NOT NULL ,
`jw_config_value` TEXT NOT NULL ,
`jw_config_description` TEXT NOT NULL
) ENGINE = MYISAM ;
");

$db->Execute("INSERT INTO ".tbl("jw_smart")." (`jw_config_id`, `jw_config_name`, `jw_config_value`, `jw_config_description`) VALUES
(1, 'auto_play', 'no', 'Auto Play Jw Player'),
(2, 'logo_placement', 'top-left', 'Logo Placement of on Jw Player'),
(3, 'youtube', 'yes', 'Play youtube videos in our player'),
(4, 'jw_skin', 'snel_skin.swf', ''),
(5, 'plugin_var', '', 'Plugin Variables, please check jw player doucmentation for how to add more and more variables in plugin field, sperate by commas'),
(6, 'custom_variables', '', 'Custom Flash Variables that are passed to JW Player'),
(7, 'longtail_enabled', 'yes', 'Enagle longtail advertisment solution'),
(8, 'longtail_id', '', '');
");
?>