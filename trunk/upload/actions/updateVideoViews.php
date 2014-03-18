<?php

require_once("../includes/config.inc.php");

$query = "SELECT * FROM " . tbl("video_views") . " ORDER BY last_updated DESC LIMIT 100";
$result = $db->Execute($query);
while($row = $result->fetch_assoc()){
	$query = "update " . tbl("video") . " set views = {$row['video_views']} where videoid = {$row['video_id']}";
	$db->Execute($query);
}