<?php

 function install_customfield()
  {
  global $db;
  $db->Execute(
  'CREATE TABLE IF NOT EXISTS '.tbl("custom_field").' (
  `custom_field_list_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `field_name` text NOT NULL,
  `field_title` text NOT NULL,
  `field_type` text NOT NULL,
  `db_field` text NOT NULL,
  `default_value` text NOT NULL,
  `customfields_flag` text NOT NULL,
  `date_added` DATETIME NOT NULL
  ) ENGINE=MyISAM;'
  );
   //inserting new announcement
  //$db->Execute("INSERT INTO  ".tbl('custom_field')." (field_name,field_title,) VALUES ('Hello World!')");
  }
 install_customfield();
  ?>