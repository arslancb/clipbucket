<?php

	define("THIS_PAGE","cb_install");
	include('clipbucket.php');
	include("upgradeable.php");
 
 $modes = array
 ('agreement','precheck','permission','database','dataimport','adminsettings','sitesettings','register','finish','upgrade'
 ,'finish_upgrade');
 
 $mode = @$_POST['mode'];
 
 if(!$mode || !in_array($mode,$modes))
 {
 	if(!$upgrade)
 		$mode = 'agreement';
	else
		$mode = 'upgrade';
 }
 
 $configIncluded = false;
 /**
  * Clipbucket modes
  * modes which requires clipbucket core files so installer
  * function file does not create a conflict
  */
 $cbarray = array('adminsettings','sitesettings','register','finish');
 $baseDir = dirname(dirname(__FILE__));
 if($version = is_upgradeable()){
 	if((int)$version <= 2.6){
 		$originalDbFile = file_get_contents($baseDir.'/includes/dbconnect.php');
	 	preg_match("/DBHOST = '(.*)';/", $originalDbFile, $matches);
	 	$host = (array_pop($matches));
	 	preg_match("/DBNAME = '(.*)';/", $originalDbFile, $matches);
	 	$name = (array_pop($matches));
	 	preg_match("/DBUSER = '(.*)';/", $originalDbFile, $matches);
	 	$user = (array_pop($matches));
	 	preg_match("/DBPASS = '(.*)';/", $originalDbFile, $matches);
	 	$pass = (array_pop($matches));
	 	preg_match("/define\('TABLE_PREFIX','(.*)'\);/", $originalDbFile, $matches);
	 	$prefix = (array_pop($matches));
		$dbconnect = file_get_contents($baseDir.'/cb_install/dbconnect.php');
		$dbconnect = str_replace('_DB_HOST_', $host, $dbconnect);
		$dbconnect = str_replace('_DB_NAME_', $name, $dbconnect);
		$dbconnect = str_replace('_DB_USER_', $user, $dbconnect);
		$dbconnect = str_replace('_DB_PASS_', $pass, $dbconnect);
		$dbconnect = str_replace('_TABLE_PREFIX_', $prefix, $dbconnect);

		$fp = fopen($baseDir.'/includes/dbconnect.php', 'w');
		fwrite($fp, $dbconnect);
		fclose($fp);
 	}
}
 
 if(in_array($mode,$cbarray) || $upgrade)
 {
	chdir("..");
	$configIncluded = true;
	require_once 'includes/config.inc.php';
	chdir("cb_install");
 }
 
 include("functions.php");
 include('modes/body.php');
 
?>