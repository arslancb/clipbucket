<?php

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Stats And Configurations');
}

if(!defined('SUB_PAGE')){
    define('SUB_PAGE', lang('cas_configuration'));
}

	if ( (isset($_POST['version'])) and (isset($_POST['url'])) and (isset($_POST['cas_context'])) and (isset($_POST['port'])) ){
		$tmp_config = array();

		if (isset($_POST['version'])) {		$tmp_config['version']		= $_POST['version'];}
		if (isset($_POST['url'])) {			$tmp_config['url']			= $_POST['url'];}
		if (isset($_POST['cas_context'])) {	$tmp_config['cas_context']	= $_POST['cas_context'];}
		if (isset($_POST['port'])) {		$tmp_config['port']			= $_POST['port'];}
		// BUG : je suis passé par un yes/no car le 0/1 réinitialise à 0 à chaque affichage de la page (même sans POST).
		$tmp_config['create_user']	= ($_POST['create_user'] == 'yes') ? $_POST['create_user'] : 'no';	
		
		// *** MAJ la table auth_cas_config
		update_cas_config($tmp_config);
	}
	
	// *** Recupere la config
	$config = get_cas_config();
	
	// *** Parcours la nouvelle config
	foreach ($config as $key => $value){
		// *** Si la valeur existe, c'est un update
		assign($key, $config[$key]);
	}
	
	
	if (function_exists('search_ldap')) {
		e("Les fonctions LDAP sont disponibles.<br />\n", "m");
	}
	else {
		e("Les fonctions LDAP ne sont pas disponibles.<br />\n", "w");
	}

	
	
	
	
template_files(PLUG_DIR.'/auth_cas/admin/edit_auth_cas.html',true);
?>
