<?php

require_once('../includes/common.php');

//Creating Table for anncoument if not exists
function install_editors_pick()
{
	global $db;
	$db->Execute(
	"CREATE TABLE IF NOT EXISTS ".tbl('editors_picks')." (
  `pick_id` int(225) NOT NULL AUTO_INCREMENT,
  `videoid` int(225) NOT NULL,
  `sort` bigint(5) NOT NULL DEFAULT '1',
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pick_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
	);
	
	//inserting new announcment
	$db->Execute("INSERT INTO  ".tbl('global_announcement')." (announcement) VALUES ('')");
}


//This will first check if plugin is installed or not, if not this function will install the plugin details
install_editors_pick();

?>