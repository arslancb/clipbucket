<?php
/**
 * this file is used to fetch file size
 */
include("../includes/config.inc.php");

$file_name = $_POST['file'];
$file = TEMP_DIR.'/'.$file_name;


if(!empty($file_name) && file_exists($file))
	echo filesize($file);
?>