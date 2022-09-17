<?php

//Insert Recaptcha field in configurations

$db->delete(tbl("config"),array("name"),array("recaptcha_v2_site_key"));
$db->delete(tbl("config"),array("name"),array("recaptcha_v2_secret_key"));

?>