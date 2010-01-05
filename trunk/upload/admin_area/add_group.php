<?php

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
header("location:".cblink(array("name"=>"create_group")));
?>