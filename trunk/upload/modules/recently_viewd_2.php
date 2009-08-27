<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Module : Flash Recent Videos																	|
 | @ Module File : flash_recent_videos.php {PLAYLIST FILE}											|
 | @ Date : Jan, 02 2008																			|
 | @ License: Addon With ClipBucket																	|
 ****************************************************************************************************
*/


include ("../includes/config.inc.php");
?> 
<spins>
<?php

$query = mysql_query("SELECT videokey,title,flv FROM video WHERE broadcast ='public' AND active = 'yes' ORDER BY last_viewed DESC LIMIT 0,10");
while ($data = mysql_fetch_array($query))
{
 $query_detail = mysql_query("SELECT * FROM video_detail WHERE flv = '".$data['flv']."' AND status ='Successful'");
 if(mysql_num_rows($query) > 0){
	$file_name 	 = substr($data['flv'], 0, strrpos($data['flv'], '.'));
?>
<spin image="<?php echo BASEURL.'/files/thumbs/'.$file_name.'-1.jpg' ?>" tooltip="<?php echo $data['title']; ?>" link="<?php echo VideoLink($data['videokey'],clean($data['title'])); ?>"/>
<?php
}
}
?>
</spins>