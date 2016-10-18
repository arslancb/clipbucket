<?php

require_once('../includes/common.php');

/**
 * Install locales for this plugin
 */
global $cbplugin;
if ($cbplugin->is_installed('common_library.php')){
	require_once PLUG_DIR.'/common_library/common_library.php';
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	importLangagePack($folder,'en');
	importLangagePack($folder,'fr');
}


/**
 *Create Table for disciplines if not exists 
 */
function install_disciplines() {	
	$uploaddir = BASEDIR."/files/thumbs/disciplines";
	if (is_dir($uploaddir)){ 
		$files = glob($uploaddir.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
		rmdir($uploaddir);
	}
	$folder = mkdir($uploaddir,0777);
	if ($folder){
		//$handle = fopen(PLUG_DIR."/disciplines/default.png", "r");
		if(copy(PLUG_DIR."/disciplines/default.png", $uploaddir."/default.png")){
			global $db;
			$db->Execute(
			// WARNING ! Use `` instead of '' for fields - SMARTY restriction
			"CREATE TABLE IF NOT EXISTS ".tbl('disciplines')." (
			`id` int(225) NOT NULL AUTO_INCREMENT,
			`name` varchar(128) NOT NULL,
			`discipline_order` bigint(5) NOT NULL DEFAULT '1',
			`description` varchar(2048) NOT NULL DEFAULT 'Default discipline',
			`is_default` BOOLEAN NOT NULL DEFAULT '0',
			`in_menu` BOOLEAN NOT NULL DEFAULT '1',
			`color` varchar(50) NOT NULL DEFAULT '#999999',
			`thumb` BOOLEAN DEFAULT '1',
			`thumb_url` varchar(255) NOT NULL DEFAULT 'default.png',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			);
			// Add a default value
			$db->Execute("INSERT INTO  ".tbl('disciplines')." (name, is_default) VALUES ('Default',1)");
			// Add a field in video table
			$db->Execute("ALTER TABLE ".tbl('video')." ADD `discipline` varchar(255) NOT NULL DEFAULT '1'");
		} else {
			die(lang("unable_to_copy_default_image"));
		}
	} else {
		die(lang("unable_to_create_folder"));
	}
}



/** install the plugin */
install_disciplines();

?>