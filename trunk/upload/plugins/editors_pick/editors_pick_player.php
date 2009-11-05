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
include ("../../includes/config.inc.php");
echo"<?xml version=\"1.0\" encoding=\"utf-8\"?>";
echo"<options>\n\t";
echo"<videos>\n\t";

	$ep_videos = get_ep_videos();
	foreach($ep_videos as $video)
	{
		if(!empty($video['title']))
		{
			echo" <imageName target=\"_self\" html=\"\" flv=\"".get_video_file($video,true,true)."\" autoStart=\"false\">".getthumb($video,'big')."</imageName>\n\t\t";
		}
	}


echo "\n\t";	
echo"</videos>\n";
echo"</options>";

?>