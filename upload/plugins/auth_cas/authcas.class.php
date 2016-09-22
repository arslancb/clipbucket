<?php

class AuthCAS {
	var $lib_dir   = "includes/phpCAS"; // Chemin (relatif à la base du site) vers le répertoire de phpCAS
	var $server    = "cas.utc.fr";      // Addresse du serveur CAS
	var $port      = 443;               // Port du serveur CAS
	var $dir       = "/cas";            // Sous-répertoire du serveur CAS pour l'authentification
	var $version   = "2.0";             // Version du CAS
	static $ready  = FALSE;             // Statut de l'initialisation du CAS
	var $logged_in = NULL;
	var $username  = NULL;
	//var $debug     = null;             	// Enregistrer les logs de debug ? => remplacé par un test dans init()
	var $log_file  = "/tmp/cas.debug";  // Chemin du fichier de logs de debug
	var $handle_session = false; 		// Est-ce que cette classe gère les sessions elle-même ?
	var $email_domain = "utc.fr";
	var $proxy_host = ''; //"proxyweb.utc.fr:3128"; 	// host:port
	var $proxy_ident = ''; 						// username:password

	/**
	 * Initialise la connexion au serveur CAS
	 * @return void
	 */
	function init(){
        
        if(self::$ready) return;

		# Charge la librairie phpCAS
		require_once(BASEDIR ."/{$this->lib_dir}/CAS.php");

		# Enregistre les infos de debug
		if (isset($_SERVER['DEVELOPMENT'])) phpCAS::setDebug($this->log_file);

		# Connexion au serveur CAS
		phpCAS::client($this->version, $this->server, $this->port, $this->dir, $this->handle_session);
		if($this->proxy_host){
			phpCAS::setExtraCurlOption(CURLOPT_PROXY, $this->proxy_host);
			if($this->proxy_ident){
				phpCAS::setExtraCurlOption(CURLOPT_PROXYUSERPWD, $this->proxy_ident);
			}
		}

		phpCAS::setNoCasServerValidation();

		self::$ready = TRUE;
	}

	/**
	 * Renvoie TRUE si l'utilisateur est identifié sur le CAS, FALSE sinon
	 * @return bool
	 */
	function logged_in($force_check=false){
		$this->init();
        if( $this->logged_in === NULL ) {
            if ($force_check)
                $this->logged_in = phpCAS::checkAuthentication();
            else 
                $this->logged_in = phpCAS::isAuthenticated();
        }
		return $this->logged_in;
	}

	/**
	 * Renvoie le pseudo de l'utilisateur. FALSE s'il n'est pas identifié.
	 * @return mixed
	 */
	function username(){
		if( $this->username === NULL ) $this->username = ( $this->logged_in() ) ? phpCAS::getUser() : FALSE;
		return $this->username;
	}

	/**
	 * Redirige l'utilisateur vers le serveur CAS si l'utilisateur n'est pas identifié.
	 * S'il est identifié, renvoie le 'username' de la personne connectée.
	 * /
	function login(){
        
		$this->init();
        
		# Si l'utilisateur n'est pas identifié, il est redirigé vers le CAS
		phpCAS::forceAuthentication();

		# Si on est ici, l'utilisateur est forcément loggé
		$this->logged_in = TRUE;
		$this->username = phpCAS::getUser();

        return $this->username;
	}
    /**/
    
	function login_url(){
        # Pour éviter un appel au CAS à chaque fois, on crée le lien manuellement.
        // $this->init();
		// return phpCAS::getServerLoginURL();
        
        # Cette construction fonctionne avec la configuration du CAS de l'UTC,
        # mais il est possible que ça soit différent ailleurs.
        $url = 'https://'. $this->server;
        if ($this->port != 443) $url .= ':'. $this->port;
        $url .= '/'. $this->dir .'/login?service='. urlencode(curPageURL());
        return $url;
	}

   /**
    * Déconnecte l'utilisateur du CAS.
    * Si $redirect vaut TRUE, l'utilisateur sera automatiquement renvoyé sur la page
    * désignée par $destination
    * Si $redirect vaut FALSE, le serveur CAS affichera une page de déconnexion
    * avec un lien vers $destination, ou sans lien si $destination vaut NULL
    * @param bool $redirect
    * @param string $destination
    * @return void
    */
   function logout( $destination = '', $redirect = FALSE){
      $this->init();
      
      if (! $this->logged_in()) return true;
      
      if( $destination !== NULL ) $destination = BASEURL."/$destination";

      # Si $redirect est TRUE, on envoie l'utilisateur directement sur $destination après le logout
      if ($redirect && $destination) phpCAS::logoutWithRedirectService( $destination );

      # Sinon, si $destination n'est pas vide, on le déconnecte en affichant un lien vers $destination
      elseif ($destination) phpCAS::logout( array('url'=>$destination) );

      # Sinon on le déconnecte tout court
      else phpCAS::logout();
   }

}