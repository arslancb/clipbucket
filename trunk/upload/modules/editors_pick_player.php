<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Module : Editors Pick Player																	|
 | @ Module File : editors_pick_player.xml.php {PLAYLIST FILE}										|
 | @ Date : Jan, 21 2008																			|
 | @ License: Addon With ClipBucket																	|
 ****************************************************************************************************
*/
include ("../includes/config.inc.php");
echo"<?xml version=\"1.0\" encoding=\"utf-8\"?>";
echo"<options>\n\t";
echo"<videos>\n\t";

	$query	= mysql_query("SELECT * FROM editors_picks WHERE status = '1' ORDER BY sort ASC LIMIT 0,10 ");
	while($data = mysql_fetch_array($query)){
	
	$videos		= $myquery->GetVideDetails($data['videokey']);
	$filename 	= GetName($videos['flv']);
	$image 		= BASEURL."/files/thumbs/$filename-big.jpg";
	$thumb		= BASEURL."/files/thumbs/$filename-1.jpg";
	$flv 		= BASEURL."/files/videos/".$videos['flv'];
	$title		= $videos['title'];
	if(!empty($title)){
	echo" <imageName target=\"_self\" html=\"\" flv=\"$flv\" autoStart=\"false\">$image</imageName>\n\t\t";
	}
	
	}

echo "\n\t";	
echo"</videos>\n";
echo"</options>";

?>