<?php

//Insert Recaptcha field in configurations

$db->insert(tbl("config"),array("name","value"),array("reCaptcha_public_key","6LcQI8ESAAAAALN1vYQovst9c6nlU52iHdqWExp8"));
$db->insert(tbl("config"),array("name","value"),array("reCaptcha_private_key","6LcQI8ESAAAAALc_oz1xuNsBVRNx554CaJHjcoXt"));

?>