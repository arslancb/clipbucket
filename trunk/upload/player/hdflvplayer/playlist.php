<?php
error_reporting(0);
require('../../includes/common.php');

echo '<?xml version=\'1.0\' encoding=\'UTF-8\'?>'."\n";
?>

<playlist  random="false">
  <?php
  $playlist = $_GET['playlist'];
  $playlist = explode('_',$playlist);
  $pid = $playlist[0];
  $vid = $playlist[1];
  $playlist = $cbvid->get_playlist_items(mysql_clean($pid));
  
  if($playlist)
  {
	  foreach($playlist as $video)
	  {
		  if($vid!=$video['videoid'])
		  {
  ?>
  <mainvideo url="<?=videoLink($video)?>" 
             id="<?=$video['videoid']?>"
             thu_image="<?=getThumb($video)?>"                                                                                
             Preview="" 
             preroll="true" 
             midroll="true" 
             postroll="true" 
             allow_download="true"
             streamer=""
             isLive="false" > 
             <title><![CDATA[<?=$video['title']?>]]></title> 
  </mainvideo>
  <?php
		  }
	  }
  }
  ?>
</playlist>
