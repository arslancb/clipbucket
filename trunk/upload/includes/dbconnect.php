<?php

	/**
	* @Software : ClipBucket
	* @License : CBLA
	* @version :ClipBucket v2
	*/

	$BDTYPE = 'mysql';
    //Database Host
 	$DBHOST = 'localhost';
    //Database Name
 	$DBNAME = 'clipbucket_svn';
    //Database Username
	$DBUSER = 'root';
    //Database Password
	$DBPASS = '';

	require 'adodb/adodb.inc.php';

    $db = ADONewConnection($BDTYPE);
    $db->debug = false;
    $db->charpage = 'cp_utf8';
    $db->charset = 'utf8';
    if(!$db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME))
    {
    exit($db->ErrorMsg());
    }
    $db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME);
?>