<?php
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

/**
 * Install locales for this plugin and set admin permissions
 */
global $cbplugin;
if ($cbplugin->is_installed('common_library.php')){
	require_once PLUG_DIR.'/common_library/common_library.php';
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	importLangagePack($folder,'en');
	importLangagePack($folder,'fr');
	installPluginAdminPermissions("importCSV", "ImportCSV administration", "Allow importCSV management");
}

/**
 * Creating Table for importCSV if not exists 
 */
function install_importCSV() {
	$uploaddir = BASEDIR."/files/importCSV";
	if (is_dir($uploaddir)){ 
		$files = glob($uploaddir.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
		rmdir($uploaddir);
	}
	$folder = mkdir($uploaddir);
	global $db;
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("importCSV_mapping").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`import_table_name` varchar(128) NOT NULL ,
	  		`import_field_name` varchar(128) NOT NULL ,
	  		`static_value` varchar(2048) NOT NULL ,
			`cb_table_name` varchar(128) NOT NULL ,
	  		`cb_field_name` varchar(128) NOT NULL ,
			`cb_field_type` varchar(128) NULL,
			`search_value` varchar(1024) NULL,
			`replace_value` varchar(1024) NULL,
			`comment` varchar(128) NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
			);
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("importCSV_join").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`import_table_name` varchar(128) NOT NULL ,
			`import_field1` varchar(128) NOT NULL ,
			`cb_table1_name` varchar(128) NOT NULL ,
			`cb_table1_field_search` varchar(128) NOT NULL ,
			`cb_table1_field` varchar(128) NOT NULL ,
			`import_field2` varchar(128) NOT NULL ,
			`cb_table2_name` varchar(128) NOT NULL ,
			`cb_table2_field_search` varchar(128) NOT NULL ,
			`cb_table2_field` varchar(128) NOT NULL ,
			`cb_tablejoin_name` varchar(128) NOT NULL ,
			`cb_tablejoin_field1` varchar(128) NOT NULL ,
			`cb_tablejoin_field2` varchar(128) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
			);
}



install_importCSV();
?>