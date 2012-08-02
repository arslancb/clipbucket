<?php
include('../includes/config.inc.php');

sleep(2);

$file = THUMBS_DIR.'/default.jpg';
header("Content-type: image/jpeg");

echo file_get_contents($file);
?>