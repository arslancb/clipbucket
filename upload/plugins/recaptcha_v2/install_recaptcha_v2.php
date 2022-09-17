<?php

//Insert Recaptcha field in configurations

$db->insert(tbl("config"),array("name","value"),array("recaptcha_v2_site_key",""));
$db->insert(tbl("config"),array("name","value"),array("recaptcha_v2_secret_key",""));

?>