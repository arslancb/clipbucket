<?php
require_once 'includes/config.inc.php';
$userquery->logout();
setcookie('is_logout','yes',time()+3600,'/');
redirect_to(BASEURL);
?>