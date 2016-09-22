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
	define('CAS_BASE',$config['url']);						// Localisation du serveur CAS
	define('CAS_VALIDATION',$config['url_validation']);			// URL de validation du serveur CAS
	
	// propre URL
	$service = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'].'?mode=login';

	// *** Classe de connexion CAS (par UTC)
//	require_once(PLUG_DIR.'/'.AUTH_CAS.'/authcas.class.php');




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
				// *** Uniquement si c'est different
				if ($org_config[$key][$value] != $tmp_config[$key][$value]){
					// update
					$db->update(tbl('auth_cas_config'), array('name','value'), array($key,$tmp_config[$key]), "name='$key'");
				}	// *** Sinon la nouvelle valeur et la meme que l'ancienne, on ne fait rien
			}
			else{
				// *** Sinon, on créé la valeur
				$db->insert(tbl('auth_cas_config'), array('name','value'), array($key,$tmp_config[$key]));
			}
		}
	}

	
	/* ***
	*	 Verifie la presence du ticket et du auth_cas
	*		@ticket 	: fourni par CAS (si authentification ok)
	*		@auth_cas 	: une chaine quelconque
	*/
	function validate_auth_cas() {
		global $service ;
	
		// Récupération du ticket (retour du serveur CAS)
		if ( (!isset($_GET['ticket'])) && (isset($_GET['auth_cas'])) ) {
			// Pas de ticket : on redirige le navigateur web vers le serveur CAS
			header("Location:".CAS_BASE."/login?service=".$service);
		}
	}

	/* ***
	*	Cette simple fonction réalise l’authentification CAS.
	*		@return le login de l’utilisateur authentifié, ou FALSE.
	*/
	function authenticate() {
		global $service ;

		// Un ticket a été transmis, on essaie de le valider auprès du serveur CAS
		$fpage = fopen (CAS_BASE . CAS_VALIDATION . '?service=' . preg_replace('/&/','%26',$service) . '&ticket=' . $_GET['ticket'], 'r');

		if ($fpage) {
			while (!feof ($fpage)) { $page .= fgets ($fpage, 1024); }
			
			// Analyse de la réponse du serveur CAS
			if (preg_match('|<cas:authenticationSuccess>.*</cas:authenticationSuccess>|mis',$page)) {
				if(preg_match('|<cas:user>(.*)</cas:user>|',$page,$match)){
					return $match[1];
					exit();
				}
			}
		}
		// problème de validation
		return FALSE;
	}

	/* ***
	*	Function launch when access the login page
	*	 place this code {ANCHOR place="is_auth_cas"} in your signup layout to run the plugin
	*/
	function is_auth_cas(){
		global $service;
		
		$login = authenticate();
		
		if ($login === FALSE ) {
			// *** Renvoi vers la page CAS
			validate_auth_cas();
			// *** Affiche le lien de connexion 
			//		(le paramètre auth_cas n'a pas d'importance, 
			//		il doit juste contenir une chaine. 
			//		Il est là pour savoir que l'on a cliquer sur le lien 
			//		et faire la redirection)
			echo '<a href="'.$service.'&auth_cas=bycas">Connexion CAS</a>';
		}
		else{
		
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
				$pass =  RandomString(10);		// create a password
				// *** Information to create the user 
				$user_infos = array(
					'username' => $login,
					'email'	=> $login.'@u-picardie.fr',
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
				
				// *** Check the user id and connect
				if ($userid){
					// *** Connecte correctement un utilisateur existant.
					$userquery->login_as_user($login, '');
					header("Location: ".BASEURL);
				}
			}
		}
	}
	
	register_anchor_function("is_auth_cas", "is_auth_cas");
	
	/* ***
	*	Add entries for the plugin in the administration pages
	*/
	add_admin_menu('Authentification CAS','Configuration CAS','edit_auth_cas.php',AUTH_CAS.'/admin');

?>
