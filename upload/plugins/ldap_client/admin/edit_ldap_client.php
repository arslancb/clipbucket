<?php

// Assigning page and subpage
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Stats And Configurations');
}

if(!defined('SUB_PAGE')){
    define('SUB_PAGE', lang('ldap_configuration'));
}

	/**
	 *	Test Tab 1 : configtab
	 */
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
		
		// *** Update table ldap_client_config
		updateLdapClientConfig($tmp_config);
	}
	
	
	/**
	 *	Test Tab 2 : datatab
	 */
	if ( 
		(isset($_POST['cle_ldap']))
		and ($_POST['cle_ldap'][0] != '')
		and (isset($_POST['cle_cb'])) 
		and ($_POST['cle_cb'][0] != '')
	) {
		assign('post_clecb_result', $_POST['cle_cb']);
		assign('post_cleldap_result', $_POST['cle_ldap']);
		
		$tmp['ldap_fields_connection'] = ldapFieldsConnection($_POST['cle_ldap'], $_POST['cle_cb']);
		
		// Update table ldap_client_config
		updateLdapClientConfig($tmp);
	}

	// If POST, assign the var in order to display the active tab
	if ( (isset($_POST['cle_ldap'])) or (isset($_POST['cle_cb'])) ) {
		assign('tabactive', 'datatab');
	}

	/**
	 *	Test Tab 3 : testtab
	 */
	if (isset($_POST['search'])) {
		$plop = searchLdap($_POST['search']);
		assign('ldap_result', $plop);
		
		assign('tabactive', 'testtab');		// *** assign active tab
	}

/**
 *	Before output, we assign all config value for the form
 */
$config = getLdapClientConfig();
assign('ldapconfig',$config);

// Loop the new config
foreach ($config as $key => $value){
	assign($key, $config[$key]);
}

// Output
template_files(PLUG_DIR.'/ldap_client/admin/edit_ldap_client.html',true);
?>
