<?php

//Insert Recaptcha field in configurations

$db->delete(tbl("config"),array("name"),array("reCaptcha_public_key"));
$db->delete(tbl("config"),array("name"),array("reCaptcha_private_key"));

?>