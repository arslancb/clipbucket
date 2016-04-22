<?php

/**
 * This file is just for our memorable code
 * Happy Coding ^_^
 */
 
include('../includes/config.inc.php');
include('../includes/classes/swfObj.class.php');

$flv = mysql_clean($_GET['flv']);
$code = mysql_clean($_GET['code']);
$embeded = mysql_clean($_GET['embed']);
$key = mysql_clean($_GET['key']);
$flv_url = mysql_clean($_GET['flv_url']);
$player = FLVPLAYER;
$Div = $row['player_div_id'];
if(isset($_GET['width']))
{
$width = mysql_clean($_GET['width']);
}
else
{
$width = "500";
}
if(isset($_GET['height']))
{
$height = mysql_clean($_GET['height']);
}
else
{
$height = "400";
}
if(isset($_GET['bgcolor']))
{
$bgcolor = mysql_clean($_GET['bgcolor']);
}
else
{
$bgcolor = "#FFFFFF";
}

//if(empty($player)) $player = 'flvplayer';

if($Div!='') $swfObj->DivId=$Div; else $swfObj->DivId = $row['player_div_id'];
if($bgcolor!='') $swfObj->bgcolor=$bgcolor;
if($width!='') $swfObj->width=$width;
if($height!='') $swfObj->height=$height;

//Setting FOr Embed Code
if($embeded=='yes')
$player = 'embedCode';

//Setting Basic Player
if($player == 'clipbucketblue.swf' || $player == 'youtube.swf' || $player == 'youtube_glossy.swf'){
	$swfObj->playerFile = BASEURL.'/player/'.FLVPLAYER;
	$swfObj->FlashObj();
	//Writing Param
	$swfObj->addParam('allowfullscreen','true');
	$swfObj->addParam('allowscriptaccess','always');
	$swfObj->addParam('quality','high');
	$swfObj->addVar('baseurl',BASEURL);
	$swfObj->addVar('video',$flv);
	if(!empty($flv_url) && GetExt($flv_url)=='flv'){
	$swfObj->addVar('file_url',urldecode($flv_url));
	}else{
	$swfObj->addVar('file_url',BASEURL.'/files/videos/'.$flv);
	}
	$swfObj->CreatePlayer();
}

//Embed Code
if($player == 'embedCode'){
$swfObj->EmbedCode($code);
}

//Including Flv Player Plugin file
include('../includes/flv_player.php');

header ("Content-type: text/javascript; charset=utf-8");
print($swfObj->code);

?>