<?php

include('../includes/config.inc.php');

$vote = $_POST["vote"];
$userid = $_POST["channelId"];

if($vote == "yes"){
	$query = "UPDATE " . tbl("users") . " SET voted = voted + 1, likes = likes + 1 WHERE userid = {$userid}";
}else{
	$query = "UPDATE " . tbl("users") . " SET voted = voted + 1 WHERE userid = {$userid}";
}
$result = $db->Execute($query);