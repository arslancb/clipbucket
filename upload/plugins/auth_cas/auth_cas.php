<?php
/*
Plugin Name: Central Authentication System
Description: Add system authentication
Author: Adrien Ponchelet
Author Website: https://www.u-picardie.fr
ClipBucket Version: 2.8.1 rc1
Version: 1.0
*/

	// Define Plugin's uri constants
	define("SITE_MODE",'/admin_area');
	
	define('AUTH_CAS',basename(dirname(__FILE__)));			// *** Chemin du plugin

	$config = get_cas_config();
	define('CAS_VERSION',$config['version']);				// Version du serveur CAS
	define('CAS_BASE',$config['url']);						// Localisation du serveur CAS
	define('CAS_CONTEXT',$config['cas_context']);			// Suite de l'URL du serveur CAS
	define('CAS_PORT',$config['port']);						// Port du serveur CAS
	define('CAS_CREATE_USER',$config['create_user']);		// Option de creation de l'utilisateur
	
//	require_once(BASEURL.'/includes/common.php');
	
	// *** Inclusion librairie phpCAS
	require(PLUG_DIR."/".AUTH_CAS."/CAS-1.3.4/CAS.php");
	

//	phpCAS::setDebug();			// Enable debugging
//	phpCAS::setVerbose(true);	// Enable verbose error messages. Disable in production!

	/* ***
	*	Function launch when access the login page
	*	 place this code {ANCHOR place="is_auth_cas"} in your signup layout to run the plugin
	*/
	function is_auth_cas(){
		global $LANG,$Cbucket;
		
		// *** Initialise
		switch (CAS_VERSION){
			case "CAS_VERSION_1_0":
				phpCAS::client(CAS_VERSION_1_0,CAS_BASE,intval(CAS_PORT),'');
			break;
			case "CAS_VERSION_2_0":
				phpCAS::client(CAS_VERSION_2_0,CAS_BASE,intval(CAS_PORT),'');
			break;
			case "CAS_VERSION_3_0":
				phpCAS::client(CAS_VERSION_3_0,CAS_BASE,intval(CAS_PORT),'');
			break;
			default:
				phpCAS::client(CAS_VERSION_2_0,CAS_BASE,intval(CAS_PORT),'');
			break;
		}

		phpCAS::setNoCasServerValidation();
	
		if ($_GET['auth_cas'] == 'bycas'){
		
			$isAuth = check_if_auth();
		
			if ( ($isAuth) or (phpCAS::isAuthenticated()) ){
				$login = phpCAS::getUser();
				// *** Connexion
				login_and_create($login);
			}
			else{
				//phpCAS::setFixedServiceURL(BASEURL.'/signup.php?mode=login&auth_cas=bycas');
				phpCAS::forceAuthentication();
			}
	
		}
		else{
			echo '<a href="'.BASEURL.'/signup.php?mode=login&auth_cas=bycas">'.lang('cas_connexion_link').'</a><br>';
		}
	} // is_auth_cas
		

	/* ***
	*	Function that check if already CAS connected
	*/
	function check_if_auth(){
		return (phpCAS::checkAuthentication()) ? true : false;
	}


	/* ***
	*	Function launch when access the login page
	*	 place this code {ANCHOR place="is_auth_cas"} in your signup layout to run the plugin
	*/
	function login_and_create($login){

		// *** Etablir la connexion via correspondance en BDD
		$userquery = new userquery();
		$udetails = $userquery->get_user_details($login);
		
		// *** User already exist
		if (isset($udetails["userid"])){
			// *** Connecte correctement un utilisateur existant.
			$userquery->login_as_user($login, '');
			header("Location: ".BASEURL);
		}
		else{
			// *** Not yet inserted in db
			if (intval(CAS_CREATE_USER) == 1){
				$userid = create_user($login);
			}
			
			// *** Check the user id and connect
			if ($userid){
				// *** Connecte correctement un utilisateur existant.
				$userquery->login_as_user($login, '');
				header("Location: ".BASEURL);
			}
		}
	}


	/* ***
	*	Créer l'utilisateur dans la base Clipbucket
	* */
	function create_user($login){
		$userquery = new userquery();

		if (function_exists('search_ldap')) {
			e("Les fonctions LDAP sont disponibles.<br />\n", "m");
			$email = search_ldap($login);
		}
		else {
			e("Les fonctions LDAP ne sont pas disponibles.<br />\n", "m");
			$email = '';
		}
	
		$pass =  RandomString(10);		// create a password
		// *** Information to create the user 
		$user_infos = array(
			'username' => $login,
			'email'	=> $email,
			'password' => $pass,
			'cpassword' => $pass,
			'country' => get_country(config('default_country_iso2')),
			'gender' => 'Male',
			'dob'	=> '2000-10-10',
			'category' => '1',
			'level' => '6',
			'active' => 'yes',
			'agree' => 'yes',
		);

		// *** Insert id if user was created...
		$userid = $userquery->signup_user($user_infos, false);
		
		return $userid;
	}


	/* ***
	*	Recupere la config actuelle
	* */
	function get_cas_config(){
		global $db;
		$cas_config = $db->_select('SELECT `name`, `value` FROM '.tbl("auth_cas_config"));
	
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
	function update_cas_config($tmp_config){
		global $db;
		
		// *** Recupere la config
		$org_config = get_cas_config();
		
		// *** Parcours la nouvelle config
		foreach ($tmp_config as $key => $value){
			// *** Si la valeur existe, c'est un update
			if (array_key_exists($key, $org_config)){
				// *** Uniquement si c'est different - pose probleme sur le : CAS_VERSION_X_0
//				if ($org_config[$key][$value] != $tmp_config[$key][$value]){
					// update
					$db->update(tbl('auth_cas_config'), array('name','value'), array($key,$tmp_config[$key]), "name='$key'");
//				}	// *** Sinon la nouvelle valeur et la meme que l'ancienne, on ne fait rien
			}
			else{
				// *** Sinon, on créé la valeur
				$db->insert(tbl('auth_cas_config'), array('name','value'), array($key,$tmp_config[$key]));
			}
		}
	}


	register_anchor_function("is_auth_cas", "is_auth_cas");
	/* ***
	*	Add entries for the plugin in the administration pages
	*/
	add_admin_menu('Authentification CAS','Configuration CAS','edit_auth_cas.php',AUTH_CAS.'/admin');

?>
