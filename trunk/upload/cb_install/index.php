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