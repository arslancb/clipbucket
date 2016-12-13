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

	$config = getCasConfig();
	define('CAS_VERSION',$config['version']);				// Version du serveur CAS
	define('CAS_BASE',$config['url']);						// Localisation du serveur CAS
	define('CAS_CONTEXT',$config['cas_context']);			// Suite de l'URL du serveur CAS
	define('CAS_PORT',$config['port']);						// Port du serveur CAS
	define('CAS_CREATE_USER',$config['create_user']);		// Option de creation de l'utilisateur
	
	// Include phpCAS library
	require(PLUG_DIR."/".AUTH_CAS."/CAS-1.3.4/CAS.php");
	

//	phpCAS::setDebug();		// Enable debugging
//	phpCAS::setVerbose(true);	// Enable verbose error messages. Disable in production!

	/**
	 *	Function launch when access the login page
	 *	 place this code {ANCHOR place="is_auth_cas"} in your signup layout to run the plugin
	 *
	 *	@param bool $urlonly
	 */
	function is_auth_cas($urlonly=true){
		global $LANG,$Cbucket;
		
		// Initialise
		switch (CAS_VERSION){
			case "CAS_VERSION_1_0":
				phpCAS::client(CAS_VERSION_1_0,CAS_BASE,intval(CAS_PORT),CAS_CONTEXT);
			break;
			case "CAS_VERSION_2_0":
				phpCAS::client(CAS_VERSION_2_0,CAS_BASE,intval(CAS_PORT),CAS_CONTEXT);
			break;
			case "CAS_VERSION_3_0":
				phpCAS::client(CAS_VERSION_3_0,CAS_BASE,intval(CAS_PORT),CAS_CONTEXT);
			break;
			default:
				phpCAS::client(CAS_VERSION_2_0,CAS_BASE,intval(CAS_PORT),CAS_CONTEXT);
			break;
		}

		phpCAS::setNoCasServerValidation();
	
		if ($_GET['auth_cas'] == 'bycas'){
		
			$isAuth = checkIfAuth();
		
			if ( ($isAuth) or (phpCAS::isAuthenticated()) ){
				$login = phpCAS::getUser();
				// Connexion
				loginAndCreate($login);
			}
			else{
				//phpCAS::setFixedServiceURL(BASEURL.'/signup.php?mode=login&auth_cas=bycas');
				phpCAS::forceAuthentication();
			}
	
		}
		else{
			$url=BASEURL.'/signup.php?mode=login&auth_cas=bycas';
			echo $url;
		}
		
	} // End is_auth_cas
		

	/**
	 *	Function that check if already CAS connected
	 *
	 *	@return bool CAS user state
	 */
	function checkIfAuth(){
		return (phpCAS::checkAuthentication()) ? true : false;
	}


	/**
	 *	Make the connexion
	 *	@param string $login User login
	 */
	function loginAndCreate($login){

		// Get user information
		$userquery = new userquery();
		$udetails = $userquery->get_user_details($login);
		
		// User already exist
		if (isset($udetails["userid"])){
			// Connect the user
			$userquery->login_as_user($login, '');
			header("Location: ".BASEURL);
		}
		else{
			// Not yet inserted in db
			if (CAS_CREATE_USER == 'yes'){
				// Create the user
				$userid = createUser($login);
			}
			
			// Check the user id and connect
			if ($userid){
				// Connect the user
				$userquery->login_as_user($login, '');
				header("Location: ".BASEURL);
			}
		}
	}


	/**
	 *	Create user in Clipbucket database
	 *
	 *	@param string $login User Login
	 *	@return string User Id
	 */
	function createUser($login){
		global $cbplugin;
		
		$userquery = new userquery();
		$pass =  RandomString(10);		// create a random password

		if($cbplugin->is_installed('ldap_client.php')) {
			e("Les fonctions LDAP sont disponibles.<br />\n", "m");
			$ldap_corresp = searchLdap($login);
		}
		else {
			e("Les fonctions LDAP ne sont pas disponibles.<br />\n", "m");
			$ldap_corresp = '';
		}
	
		// Information to create the user 
		$user_infos = array(
			'username' => $login,
			'email'	=> $ldap_corresp['mail'],
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

		// Insert id if user was created...
		$userid = $userquery->signup_user($user_infos, false);
		// Populate user details
		$user_details = array(
			"userid" => $userid
		);
		// Loop according to LDAP attributes
		if (!empty($ldap_corresp)){
			foreach ($ldap_corresp as $key => $value){
				$user_details[$key] = $value;
			}
		}
		// Update
		$userquery->update_user($user_details);
		return $userid;
	}


	/**
	 *	Get the configuration information
	 *
	 *	@return array The key and value of config
	 */
	function getCasConfig(){
		global $db;
		$cas_config = $db->_select('SELECT `name`, `value` FROM '.tbl("auth_cas_config"));
	
		$config = array();

		for ($i = 0; $i < count($cas_config); $i++) {
			$config[$cas_config[$i]['name']] = $cas_config[$i]['value'];
		}

		return $config;
	}


	/**
	 *	Update CAS config entries
	 *		@var array $tmp_config Associated array of post value
	 */
	function updateCasConfig($tmp_config){
		global $db;
		
		// Get configuration information
		$org_config = getCasConfig();
		
		// Loop on config value
		foreach ($tmp_config as $key => $value){
			// If value doesn't exist, it's an update
			if (array_key_exists($key, $org_config)){
				// Update value
				$db->update(tbl('auth_cas_config'), array('name','value'), array($key,$tmp_config[$key]), "name='$key'");
			}
			else{
				// Insert value
				$db->insert(tbl('auth_cas_config'), array('name','value'), array($key,$tmp_config[$key]));
			}
		}
	}


	register_anchor_function("is_auth_cas", "is_auth_cas");
	/**
	 *	Add entries for the plugin in the administration pages
	 */
	if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("authcas")]=='yes')
	add_admin_menu('Stats And Configurations','Configuration CAS','edit_auth_cas.php',AUTH_CAS.'/admin');

?>
