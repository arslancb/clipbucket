<?php
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("authcas"));

// Assigning page and subpage
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
		
		// Update table auth_cas_config
		updateCasConfig($tmp_config);
	}
	
	// get configuration
	$config = getCasConfig();
	
	// Loop the new config
	foreach ($config as $key => $value){
		// Assign the value to the named key for display in template
		assign($key, $config[$key]);
	}
	
	if(is_installed('ldap_client')) {
		e("Les fonctions LDAP sont disponibles.<br />\n", "m");
	}
	else {
		e("Les fonctions LDAP ne sont pas disponibles.<br />\n", "w");
	}

// Output
template_files(PLUG_DIR.'/auth_cas/admin/edit_auth_cas.html',true);
?>
