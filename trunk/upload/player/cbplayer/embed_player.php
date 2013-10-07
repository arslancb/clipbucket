<?php


define("CLEAN_BASEURL","/\/player\/cbplayer/");
include("../../includes/config.inc.php");


$vid = $_GET['vid'];
//gettin video details
$vdetails = get_video_details($vid);

if(!$vdetails)
	exit(json_encode(array("err"=>"no video details found")));

header('Content-Type: text/xml');

$video = $vdetails;


//Loading video files
$vid_file = get_video_file($vdetails,true,true);
$hd_file = get_hq_video_file($vdetails,false);
			
/* Checking for youtube */
$ref = $video['refer_url'];
//Trying other method
if(function_exists('get_refer_url_from_embed_code'))
{
	$ref_details = get_refer_url_from_embed_code(unhtmlentities(stripslashes($video['embed_code'])));
	$ytcode = $ref_details['ytcode'];
}
?>
<config>

  <?php
  if(!$ytcode):
  ?>
  <file><?=$vid_file?></file>
  <?php
  else:
  ?>
   <file>http://youtube.com/watch?v=<?=$ytcode?></file>
  <?php
  endif;
  ?>
  
  <autostart><?php
  if($_GET['autoplay']=='yes') echo 'true'; else echo 'false';
  ?></autostart>
  
  <image><?=get_thumb($video,'big');?></image>
  <repeat>true</repeat>
  <skin><?=CB_PLAYER_URL?>/skins/glow/glow.xml</skin>
  <plugins><?php if($hd_file || $ytcode){ ?>hd-2<?php } ?>,plugins/related/related.swf</plugins>
  
  <?php if($hd_file): ?>
  <hd.file><?=$hd_file?></hd.file>
  <?php endif; ?>
  <bufferlength><?=config('buffer_time')?></bufferlength>
  
  <?php if(config('pseudostreaming')=='yes'): ?>
  
	  <?php
      if(!$ytcode):
      ?>
      <provider>http</provider>
      <?php
      else:
      ?>
      <provider>youtube</provider>
      <?php
      endif;
      ?>
  
  <?php endif; ?>
  
  <!-- Setting Related videos -->
  <related.file><?=CB_PLAYER_URL;
  ?>/plugins/related/related_videos.php?vid=<?=$video['videoid'];
  ?>&title=<?=urlencode($video['title']);
  ?>&tags=<?=urlencode($video['tags']);
  ?></related.file>
  <related.usedock>false</related.usedock>
  <related.heading>More suggested videos</related.heading>
  <!-- Setting related video end -->
  
  
  <!-- For Licensensed Players -->
  <!-- Setting Logo -->
  <logo.file><?=website_logo()?></logo.file>
  <logo.link><?=BASEURL?></logo.link>
  <logo.margin><?=config('logo_padding')?></logo.margin>
  <logo.position><?=cb_player_logo_position()?></logo.position>
  <logo.timeout>3</logo.timeout>
  <logo.over>1</logo.over>
  <logo.out>0.5</logo.out>
  <!-- Ending Logo Settings-->
  
  <!-- Setting context menu -->
  <abouttext><?=config('pakplayer_contextmsg')?></abouttext>
  <aboutlink><?=BASEURL?></aboutlink>
  <!-- Setting context menu ends -->
  
</config>