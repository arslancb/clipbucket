<?php
// require_once('../includes/common.php');


	/**
	* Install db table of CAS configuration
	*/
// 	function installAuthCas() {
// 		global $db;
// 		$db->Execute(
// 			'CREATE TABLE '.tbl("auth_cas_config").' ( 
// 				`id` INT(2) NOT NULL AUTO_INCREMENT , 
// 				`name` VARCHAR(30) NOT NULL , 
// 				`value` VARCHAR(255) NOT NULL , 
// 				PRIMARY KEY (`id`)
// 			) 
// 			ENGINE = InnoDB CHARSET=utf8;'
// 		);
// 	}


	/**
	* Install locales for this plugin
	*/
// 	global $cbplugin;
// 	if ($cbplugin->is_installed('common_library.php')){
// 		require_once PLUG_DIR.'/common_library/common_library.php';
// 		$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
// 		importLangagePack($folder,'en');
// 		importLangagePack($folder,'fr');
// 		installPluginAdminPermissions("authcas", "CAS Athentication administration", "Allow CAS Authentication management");
// 	}


// 	installAuthCas();
?>