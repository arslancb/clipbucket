<?php

/**
#########################################################################################################
# Copyright (c) 2008 - 2009 ClipBucket / PHPBucket.com. All Rights Reserved.
# [url]http://clip-bucket.com[/url]
# Function:         Database Connection
# Language:         PHP
# License:          Public Domain
# Version:          1.7.x SVN
# Last Modified:    March 21, 2009 / 03:37 PM GMT+1 (fwhite)
# Notice:           Please maintain this section
#########################################################################################################
*/

//Database Connection

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