<?php

include('../includes/config.inc.php');

$vote = $_POST["vote"];
$userid = $_POST["channelId"];
//if($userquery->login_check('',true)){
if($vote == "yes"){
	$query = "UPDATE " . tbl("users") . " SET voted = voted + 1, likes = likes + 1 WHERE userid = {$userid}";
}else{
	//$query = "UPDATE " . tbl("users") . " SET likes = likes (- 1) WHERE userid = {$userid}";
	$sel = "Select userid,username,likes From ".tbl("users")." WHERE userid = {$userid}";
	$result = $db->Execute($sel);
	 foreach ($result as $row ) 
		$current_likes = $row['likes'];
		$decremented_like = $current_likes-1;
	 $query = "Update ".tbl("users")." Set likes = $decremented_like Where userid = $userid";
}//}
$result = $db->Execute($query);