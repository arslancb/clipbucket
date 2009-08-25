<?php
require_once 'includes/config.inc.php';
$userquery->logout(BASEURL.logout_success);
header('location:'.BASEURL.logout_success);
?>