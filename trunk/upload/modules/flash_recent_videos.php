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
header ("content-type: text/xml");
function decompose($temps)
{
		$heures = floor($temps / 3600);
		$minutes = floor(($temps - ($heures * 3600)) / 60);
		if ($minutes < 10)
				$minutes = "0" . $minutes;
		$secondes = $temps - ($heures * 3600) - ($minutes * 60);
		if ($secondes < 10)
				$secondes = "0" . $secondes;
		return $minutes . ':' . $secondes;
}

$recent_viewed_limit = RVLIST;
?> 

<ut_response status="ok">
    <video_list>
<?php
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
$query = mysql_query("SELECT videokey,title,flv FROM video WHERE broadcast ='public' AND $query_param ORDER BY last_viewed DESC LIMIT $recent_viewed_limit");
while ($data = mysql_fetch_array($query))
{
 $query_detail = mysql_query("SELECT * FROM video_detail WHERE flv = '".$data['flv']."' AND status ='Successful'");
 if(mysql_num_rows($query) > 0){
 	$data_detail = mysql_fetch_array($query_detail);
	$flv = $data['flv'];
	$file_name 	 = substr($data['flv'], 0, strrpos($data['flv'], '.'));
?>
		<video>
		<title><?php echo $data['title']; ?></title>
		<run_time><?php echo decompose(round($data_detail['duration'])); ?></run_time>
 	    	<url><?php echo VideoLink($data['videokey'],clean($data['title'])); ?></url>
		<thumbnail_url><?php echo BASEURL.'/files/thumbs/'.$file_name.'-1.jpg' ?></thumbnail_url>
        </video>

<?php
}
}
?>
	</video_list>
</ut_response>
