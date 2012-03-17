<?php
require_once 'includes/config.inc.php';

$userquery->logout();
if(cb_get_functions('logout')) cb_call_functions('logout'); 

setcookie('is_logout','yes',time()+3600,'/');
redirect_to(BASEURL);
?>