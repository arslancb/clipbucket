<?php

/**
 * Configuration Installer
 */

include("../includes/functions.php");

define("installer","ClipBucket v2 Installer");
define("VERSION","2");
define("STATE","pre-alpha");
define("RELEASED","12-25-2009");


	/**
	 * Function used to get ClipBucket License (CBLA)
	 */

	function get_cbla()
	{
		//$license	= @file_get_contents('http://clip-bucket.com/get_contents.php?mode=cbla');
		if(!$license)
		{
			$license	= file_get_contents('../LICENSE');
			$license	= str_replace("\n",'<BR>',$license);
		}
		return $license;
	}
	
	
	
	function GetServerProtocol()
	{
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
		{
			return 'https://';
		}
		else
		{
			$protocol = preg_replace('/^([a-z]+)\/.*$/', '\\1', strtolower($_SERVER['SERVER_PROTOCOL']));
			$protocol .= '://';
			return $protocol;
		}
	}
	
	function GetServerURL()
    {
    	return GetServerProtocol().$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    }

    function GetBaseDir()
    {
		$pathParts 	    = pathinfo($_SERVER['SCRIPT_FILENAME']);
		$basedirPath 	= $pathParts['dirname'];
		return $basedirPath;
    }


?>