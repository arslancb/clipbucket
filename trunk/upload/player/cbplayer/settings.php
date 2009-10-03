<?php
/**
 * ClipBucket Player v2 Settings File
 */
 
include('../../includes/common.php');
$vid = $_GET['vid'] ;
$vid = $vid ? $vid : $_GET['hqid'];
if($_GET['hqid'])
	$hd = 'yes';
	
$v = $cbvideo->get_video($vid);
header ("content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<settings>
	<options>
		<autoResize value="true"/>
		<playerWidth value="500"/>
		<playerHeight value="350"/>
		
		<autoLoad value="true"/>
		<autoPlay value="false"/>
		<loop value="false"/>
		
		<keepAspectRatio value="true"/>
		
		<volume value="50"/>
		<controlsPadding value="10"/>
		
		<showPreviewImage value="true"/>
		<showShareBtn value="false"/>
		<showFullScreenBtn value="true"/>
		<showMiddlePlayBtn value="true"/>
		
		<showWatermark value="true"/>
		<watermarkPos value="BR"/>
	
		<spaceKeyListener value="true"/>
		<clickListener value="true"/>
	</options>
	
	<colors>
		<playerBackground value="0x000000"/>
		<preloader background="0x000000" bckAlpha="70" text="0xCCCCCC" circle="0x559BB5"/>
		<middlePlayBtn arrowNormal="0xFFFFFF" bckNormal="0x000000" alphaNormal="60" arrowOver="0xFFFFFF" bckOver="0x000000" alphaOver="80" arrowDown="0xFFFFFF" bckDown="0x000000" alphaDown="100"/>
		<controllerBackground value="0x000000"/>
		<controllerButtons normal="0xCCCCCC" over="0xFFFFFF" down="0xFFFFFF"/>
		<volume border="0x666666" background="0x000000" speaker="0xCCCCCC" bar="0x559BB5"/>
		<volumeScrubBtn brdNormal="0xCCCCCC" bckNormal="0x000000" brdOver="0xFFFFFF" bckOver="0x000000" brdDown="0xFFFFFF" bckDown="0x000000" />
		<progressBar border="0x666666" background="0x000000" elapse="0x559BB5" download="0x999999"/>
		<progressScrubBtn brdNormal="0xCCCCCC" bckNormal="0x000000" brdOver="0xFFFFFF" bckOver="0x000000" brdDown="0xFFFFFF" bckDown="0x000000" />
		<times value="0xCCCCCC"/>
		<share background="0x000000" bckAlpha="40" boxBck="0x000000" boxBckAlpha="70" labels="0xFFFFFF" errorText="0x00FF00" text="0x000000" textBck="0xFFFFFF" textBrd="0x000000" btnsNormal="0xCCCCCC" btnsOver="0xFFFFFF" btnsDown="0xFFFFFF"/>
	</colors>
	
    
	<video>
		<videoPath value="<?php if($hd=='yes') echo get_hq_video_file($v); else echo get_video_file($v,true,true); ?>"/>
		<stafImage value="images/ratatouilleStaf.jpg"/>
		<aspectRatio value="4:3"/>
		<totalTime value="<?=$v['duration']?>"/>
		<watermarkPath value="<?=get_player_logo()?>"/>
		<watermarkLink value="<?=video_link($v)?>"/>
	</video>
</settings>