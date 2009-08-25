<?php

/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';

//Getting Related Videos List
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
$limit = "LIMIT ".VLISTPT;

$query = "SELECT * FROM video WHERE $query_param ORDER BY RAND() $limit  ";
					
$rs = $db->Execute($query);
$random = $rs->getrows();
$total_random  = $rs->recordcount() + 0;
	for($id=0;$id<$total_random;$id++){
	$flv = $random[$id]['flv'];
	$thumb = GetThumb($flv);
	$random[$id]['thumb'] = $thumb;
	$random[$id]['url'] 		= VideoLink($random[$id]['videokey'],$random[$id]['title']);
	}
Assign('random',$random);

//Getting Newest Added

$query = "SELECT * FROM video WHERE $query_param ORDER BY date_added DESC $limit ";
					
$rs = $db->Execute($query);
$latest = $rs->getrows();
$total_latest = $rs->recordcount() + 0;
	for($id=0;$id<$total_latest;$id++){
	$flv = $latest[$id]['flv'];
	$thumb = GetThumb($flv);
	$latest[$id]['thumb'] = $thumb;
	$latest[$id]['url'] 		= VideoLink($latest[$id]['videokey'],$latest[$id]['title']);
	}
Assign('latest',$latest);

Assign('show',$_GET['show']);

Template('tabs01.html');

?>