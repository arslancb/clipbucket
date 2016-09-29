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


	/* ***
	*	Fonction de recherche
	*		Retourne uniquement l'email s'il est trouvé
	* */
	function search_ldap($query){

		if ($query <> ''){
	
			$ldap_config = get_ldap_client_config();

			$host = $ldap_config['ldap_host'];
			$port = $ldap_config['ldap_port'];
			$filtre = $ldap_config['ldap_filtre'].$query."*";
			$basedn = $ldap_config['ldap_basedn'];
		
			$test = array();

			$ds=ldap_connect($host);  // doit être un serveur LDAP valide !

			if ($ds) { 
				$r=ldap_bind($ds);     // Connexion anonyme, typique pour un accès en lecture seule.
				$sr=ldap_search($ds,$basedn, $filtre);  	// Recherche
				$info = ldap_get_entries($ds, $sr);
		
				$email = $info[0]["mail"][0];

				ldap_close($ds);		// Fermeture de la connexion

				return $email;

			} else {
				return '';
			}
		}	// *** Fin si non vide
	}	// *** Fin fonction





	/* ***
	*	Recupere la config actuelle
	* */
	function get_ldap_client_config(){
		global $db;
		$cas_config = $db->_select('SELECT `name`, `value` FROM '.tbl("ldap_client_config"));
	
		$config = array();

		for ($i = 0; $i < count($cas_config); $i++) {
			$config[$cas_config[$i]['name']] = $cas_config[$i]['value'];
		}

		return $config;
	}


	/* ***
	*	Met à jour la config CAS
	*		@tmp_config : array associatif des valeurs poste
	* */
	function update_ldap_client_config($tmp_config){
		global $db;
		
		// *** Recupere la config
		$org_config = get_ldap_client_config();
		
		// *** Parcours la nouvelle config
		foreach ($tmp_config as $key => $value){
			// *** Si la valeur existe, c'est un update
			if (array_key_exists($key, $org_config)){
				// *** Uniquement si c'est different - pose probleme sur le : CAS_VERSION_X_0
//				if ($org_config[$key][$value] != $tmp_config[$key][$value]){
					// update
					$db->update(tbl('ldap_client_config'), array('name','value'), array($key,$tmp_config[$key]), "name='$key'");
//				}	// *** Sinon la nouvelle valeur et la meme que l'ancienne, on ne fait rien
			}
			else{
				// *** Sinon, on créé la valeur
				$db->insert(tbl('ldap_client_config'), array('name','value'), array($key,$tmp_config[$key]));
			}
		}
	}

	/* ***
	*	Add entries for the plugin in the administration pages
	*/
	add_admin_menu(lang('ldap_client'),lang('ldap_configuration'),'edit_ldap_client.php',LDAP_CLIENT.'/admin');

?>
