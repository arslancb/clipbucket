<?php

require_once("../includes/config.inc.php");

if(isset($_POST["vid"])){
	$vid = $_POST["vid"];
	$video = $cbvid->getVideo($vid);
	echo json_encode($video);
}else{
	echo json_encode(array("video" => false));
}