<?php

/**
 * License : CBLA
 * Author : Arslan
 */

/**
 * iFrame based embed player ClipBucket
 * reason to use iFrame instead of embed code
 * is to control player with full support of javascript
 */
 
 
 define("THIS_PAGE","watch_video");

include("../includes/config.inc.php"); 
 
$vid = $_GET['vid'];
//gettin video details
$vdetails = get_video_details($vid);

$width = @$_GET['width'];
$height = @$_GET['height'];
$autoplay = @$_GET['autoplay'];


if(!$width)
	$width = '320';
	
if(!$height)
	$height = '240';

if(!$autoplay)
	$autoplay = 'no';


if(!$vdetails)
	exit(json_encode(array("err"=>"no video details found")));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$vdetails['title']?></title>
<?php
Template(STYLES_DIR.'/global/head.html',false);
?>
</head>

<body style="margin:0px; padding:0px">
<?php
flashPlayer(array('vdetails'=>$vdetails,'width'=>$width,'height'=>$height,'autoplay'=>$autoplay));
?>
</body>
</html>
