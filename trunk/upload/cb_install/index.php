<?php

/**
 * ClipBucket v2.1 Installaer
 */
 
 $modes = array
 ('agreement','precheck','permission','database','dataimport','adminsettings','sitesettings','register','finish');
 
  $mode = @$_POST['mode'];
 
 if(!$mode || !in_array($mode,$modes))
 	$mode = 'agreement';
 
 /**
  * Clipbucket modes
  * modes which requires clipbucket core files so installer
  * function file does not create a conflict
  */
 $cbarray = array('adminsettings','sitesettings','register','finish');

 if(in_array($mode,$cbarray))
 {
	define("THIS_PAGE",'cb_install');
	chdir("..");
	require 'includes/config.inc.php';
	chdir("cb_install");
 }

 include("functions.php");
 
 include('modes/body.php'); 
 
?>