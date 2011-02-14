<?php

	/**
	* @Software : ClipBucket
	* @License : CBLA
	* @version :ClipBucket v2.1
	*/

	$BDTYPE = 'mysql';
	//Database Host
	$DBHOST = '_DB_HOST_';
	//Database Name
	$DBNAME = '_DB_NAME_';
	//Database Username
	$DBUSER = '_DB_USER_';
	//Database Password
	$DBPASS = '_DB_PASS_';
	//Setting Table Prefix
	define('TABLE_PREFIX','_TABLE_PREFIX_');

	require 'adodb/adodb.inc.php';

	$db             = ADONewConnection($BDTYPE);
	$db->debug      = false;
	$db->charpage   = 'cp_utf8';
	$db->charset    = 'utf8';
	if(!$db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME))
	{
	    exit($db->ErrorMsg());
	}
	$db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME);
	
	$db->Execute('SET NAMES utf8');
	$db->Execute('SET CHARACTER SET utf8');
	$db->Execute('SET COLLATION_CONNECTION="utf8_general_ci"');

?>