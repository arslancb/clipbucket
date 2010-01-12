<?php
require_once 'includes/config.inc.php';
$userquery->logout();
redirect_to(cblink(array('name'=>'logout_success')));
?>