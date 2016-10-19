<?php

if (!extension_loaded('ldap')) {
	e("LDAP module is not installed.","m");
}
else{

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
	 *	Create the database configuration table
	 */
	function installLdapClient() {
		global $db;
		$db->Execute(
			'CREATE TABLE '.tbl("ldap_client_config").' ( 
				`id` INT(2) NOT NULL AUTO_INCREMENT , 
				`name` VARCHAR(30) NOT NULL , 
				`value` VARCHAR(255) NOT NULL , 
				PRIMARY KEY (`id`)
			) 
			ENGINE = InnoDB CHARSET=utf8;'
		);
	}


	installLdapClient();
	
}
?>
