<?php
/*
Plugin Name: LDAP Client
Description: Add features to use an LDAP directory on Clipbucket
Require the php5-ldap module to be activated on php (on debian or variants, simply add the php5-ldap packet)
Author: Adrien Ponchelet
Author Website: https://www.u-picardie.fr
ClipBucket Version: 2.8.1 rc1
Version: 0.1
*/

	// Define Plugin's uri constants
	define("SITE_MODE",'/admin_area');
	
	define('LDAP_CLIENT',basename(dirname(__FILE__)));			// *** Chemin du plugin
	
	assign('ldap_layout_dir', PLUG_DIR.'/ldap_client/admin');

	/**
	 *	@var string $query Search string
	 *	@return array LDAP tree
	 */
	function searchLdap($query){

		if ($query <> ''){
	
			$ldap_config = getLdapClientConfig();
			$tab_retour = array();

			// Get fields correspondence
			if (isset($ldap_config['ldap_fields_connection'])){
				$ldap_fields_connection = detachLdapFieldsConnection($ldap_config['ldap_fields_connection']);
			}
			
			$host = $ldap_config['ldap_host'];
			$port = $ldap_config['ldap_port'];
			$filtre = $ldap_config['ldap_filtre'].$query."*";
			$basedn = $ldap_config['ldap_basedn'];
		

			$ds=ldap_connect($host);  // Must be a valide LDAP server !

			if ($ds) { 
				$r=ldap_bind($ds);     // Anonymous connection, read only mode.
				$sr=ldap_search($ds,$basedn, $filtre);  	// Search
				$info = ldap_get_entries($ds, $sr);

				if (isset($ldap_fields_connection)){
					foreach ($ldap_fields_connection as $key => $value){
						$tab_retour[$key] = $info[0][$value][0];
					}
				}

				$tab_retour['mail'] = $info[0]["mail"][0];	// Add the mail

				ldap_close($ds);		// Close connection

				return $tab_retour;

			} else {
				return '';
			}
		}	// End if empty
	}	// End function


	/**
	 *	@return array La liste des configurations
	 */
	function getLdapClientConfig(){
		global $db;
		$cas_config = $db->_select('SELECT `name`, `value` FROM '.tbl("ldap_client_config"));
	
		$config = array();

		for ($i = 0; $i < count($cas_config); $i++) {
			$config[$cas_config[$i]['name']] = $cas_config[$i]['value'];
		}

		return $config;
	}


	/**
	 *	@var array $tmp_config Tableau associatif des valeurs poste
	 */
	function updateLdapClientConfig($tmp_config){
		global $db;
		
		// Get config
		$org_config = getLdapClientConfig();
		
		// Loop on new config
		foreach ($tmp_config as $key => $value){
			// If value exist, it's an update
			if (array_key_exists($key, $org_config)){
				// update
				$db->update(tbl('ldap_client_config'), array('name','value'), array($key,$tmp_config[$key]), "name='$key'");
			}
			else{
				// Else, insert value
				$db->insert(tbl('ldap_client_config'), array('name','value'), array($key,$tmp_config[$key]));
			}
		}
	}


	/**
	 *	Encode the correspondence string to JSON
	 *		@var array $ldap_attr array of value LDAP Attribute
	 * 		@var array $cb_corresp array of value db ClipBucket
	 */
	function ldapFieldsConnection($ldap_attr, $cb_coresp){
		
		$array = array();
		
		foreach ($ldap_attr as $key => $value){
			if (!empty($cb_coresp[$key])){
				$array[$value] = $cb_coresp[$key];
			}
		}
		return json_encode($array);
		
	}


	/**
	 *	@var string $json String of correspondence in JSON format
	 *	@return array Flipped array value
	 */
	function detachLdapFieldsConnection($json){
	
		$tmp = json_decode(html_entity_decode($json), true);
		$other_tmp = array_flip($tmp);
		return $other_tmp;
	
	}


	/**
	 *	Add entries for the plugin in the administration pages
	 */
	if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("ldap")]=='yes')
	add_admin_menu(lang('Stats And Configurations'),lang('ldap_configuration'),'edit_ldap_client.php',LDAP_CLIENT.'/admin');

?>
