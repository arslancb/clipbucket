<?php

/**
 * This file is used to install Mass Embed mod
 */

require_once('../includes/common.php');



function install_cb_mass_embed()
{
	global $db;
	$db->Execute(
	"CREATE TABLE IF NOT EXISTS ".tbl("mass_embed")." (
	  `mass_embed_id` int(200) NOT NULL AUTO_INCREMENT,
	  `mass_embed_website` varchar(225) NOT NULL,
	  `mass_embed_url` text NOT NULL,
	  `mass_embed_unique_id` varchar(225) NOT NULL,
	  `date_added` datetime NOT NULL,
	  PRIMARY KEY (`mass_embed_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
	$db->Execute(
	"CREATE TABLE IF NOT EXISTS ".tbl("mass_embed_configs")." (
	`config_id` int(225) NOT NULL AUTO_INCREMENT,
	`config_name` text NOT NULL,
	`config_value` text NOT NULL,
	PRIMARY KEY (`config_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	
	$db->Execute(
	"INSERT INTO ".tbl("mass_embed_configs")." (`config_id`, `config_name`, `config_value`) VALUES
	(1, 'apis', 'youtube'),
	(2, 'results', '20'),
	(3, 'keywords', 'music,videos,sports'),
	(4, 'sort_type', 'views'),
	(5, 'time', 'this_month'),
	(6, 'category_keywords', ''),
	(7, 'license_key', 'CBMExxxxxxxxxx'),
	(8, 'license_local_key', 'xxxxxxxxxx'),
	(9, 'import_stats', 'no'),	
	(10, 'import_comments', 'no');
	");
	
	/**
	 * V2 Configs
	 */
	$db->Execute(
	"INSERT INTO ".tbl("cb_mass_embed_configs")." (`config_name`, `config_value`) VALUES
	('max_tries', '8'),
	('cb_wp_secret_key', 'some-secret-key'),
	('enable_wp_integeration', 'no'),
	('wp_blog_url', 'http://path-to-wordpress.tld/'),
	('synced_cats', ''),
	('category_synced', 'no'),
	('category_synced_date', ''),
	('result_type', 'as_whole'),
	('categorization', 'keywords'),
	('mass_category', '1');
	"); 
	
	$db->Execute(
	"ALTER TABLE ".tbl("video")." ADD `mass_embed_status` ENUM( 'no', 'pending', 'approved' ) NOT NULL DEFAULT 'no' AFTER `uploader_ip` ");
	
	$db->Execute(
	"ALTER TABLE ".tbl("video")." ADD `unique_embed_code` VARCHAR(50) NULL ");

}
install_cb_mass_embed();

?>