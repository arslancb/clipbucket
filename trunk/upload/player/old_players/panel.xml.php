<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

function decompose($temps)
{
		$heures = floor($temps / 3600);
		$minutes = floor(($temps - ($heures * 3600)) / 60);
		if ($minutes < 10)
				$minutes = "0" . $minutes;
		$secondes = $temps - ($heures * 3600) - ($minutes * 60);
		if ($secondes < 10)
				$secondes = "0" . $secondes;
		return $minutes . ":" . $secondes;
}

$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
print "<ratings>\n";
include ("../includes/common.php");
$myquery 	= new myquery();
$row = $myquery->Get_Website_Details();
define('BASEURL',$row['baseurl']);
define('SEO',$row['seo']); //Set yes / no
$query = mysql_query("SELECT * FROM video WHERE $query_param AND videoid >= FLOOR( RAND( ) * ( SELECT MAX( videoid ) FROM video ) )
	ORDER BY videoid ASC");
while ($data = mysql_fetch_array($query))
{
		$flvurl = $data["flv"];
		$note = $data["rating"];
		$rating = $note / 2;
		$duration = SetTime($data["duration"]);
		print "<video file=\"" . $flvurl . "\">\n";
		print "\t<title>" . $data["title"] . "</title>\n";
		print "\t<runtime>" . $duration . "</runtime>\n";
		print "\t<author>" . $data["username"] . "</author>\n";
		print "\t<rating>" . $rating . "</rating>\n";
		print "\t<views>" . $data["views"] . "</views>\n";
		print "\t<key>" . VideoLink($data['videokey'],clean($data['title'])) . "</key>\n";
		print "</video>\n";

}
mysql_close();
print "</ratings>";
?>