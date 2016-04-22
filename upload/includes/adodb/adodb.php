<?php
include('adodb.inc.php');
	$db = ADONewConnection($dbdriver); # eg 'mysql' or 'postgres'
	$db->debug = true;
	@$db->Connect($server, $user, $password, $database);

?>