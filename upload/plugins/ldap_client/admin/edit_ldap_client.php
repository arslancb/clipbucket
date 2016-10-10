<?php

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Stats And Configurations');
}

if(!defined('SUB_PAGE')){
    define('SUB_PAGE', lang('ldap_configuration'));
}

	if ( 
		(isset($_POST['ldap_host'])) and 
		(isset($_POST['ldap_port'])) and 
		(isset($_POST['ldap_filtre'])) and 
		(isset($_POST['ldap_basedn'])) 
	){
		$tmp_config = array();

		if (isset($_POST['ldap_host'])) {
			$tmp_config['ldap_host'] = $_POST['ldap_host'];
		}
		
		if (isset($_POST['ldap_port'])) {
			$tmp_config['ldap_port'] = $_POST['ldap_port'];
		}
		
		
		if (isset($_POST['ldap_filtre'])) {
			$tmp_config['ldap_filtre'] = $_POST['ldap_filtre'];
		}
		
		if (isset($_POST['ldap_basedn'])) {
			$tmp_config['ldap_basedn'] = $_POST['ldap_basedn'];
		}
		
		// *** MAJ la table auth_cas_config
		update_ldap_client_config($tmp_config);
	}
	
	if (isset($_POST['search'])) {
		$plop = search_ldap($_POST['search']);
		assign('ldap_result', $plop);
	}


/* ***
*	Avant le rendu on assigne toutes les variables 
*	de configuration pour le formulaire
* */

// *** Recupere la config
$config = get_ldap_client_config();

assign('ldapconfig',$config);

// *** Parcours la nouvelle config
foreach ($config as $key => $value){
	// *** Si la valeur existe, c'est un update
	assign($key, $config[$key]);
}

template_files(PLUG_DIR.'/ldap_client/admin/edit_ldap_client.html',true);
?>
