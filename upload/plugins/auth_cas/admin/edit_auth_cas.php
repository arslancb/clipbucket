<?php

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Central Authentication Service');
}

if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Configuration');
}



	if (isset($_POST)) {
		$tmp_config = array();

		if (isset($_POST['version'])) {				$tmp_config['version'] 			= $_POST['version'];}
		if (isset($_POST['url'])) {					$tmp_config['url'] 				= $_POST['url'];}
		if (isset($_POST['url_validation'])) {		$tmp_config['url_validation'] 	= $_POST['url_validation'];}
		if (isset($_POST['port'])) {				$tmp_config['port'] 			= $_POST['port'];}
		
		update_cas_config($tmp_config);
	}
	
	// *** Recupere la config
	$config = get_cas_config();
	
	// *** Parcours la nouvelle config
	foreach ($config as $key => $value){
		// *** Si la valeur existe, c'est un update
		assign($key, $config[$key]);
	}

template_files(PLUG_DIR.'/auth_cas/admin/edit_auth_cas.html',true);
?>
