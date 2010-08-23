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
<playlist autoplay="false" random="false">
  <mainvideo url="<?php if($hd=='yes') echo @get_hq_video_file($v); else echo @get_video_file($v,true,true); ?>" 
             hd="<?php if(@has_hq($v,true)) echo 'true'; else echo 'false'; ?>" 
             hdpath="<?php echo @get_hq_video_file($v);?>"
             id="100"
             thu_image="<?=get_thumb($v)?>"                                                                                 
             Preview=" "
             preroll="false" 
             midroll="false"
             postroll="false" 
             allow_download="false"
             > 
    <tagline targeturl="https://mydomain.com"><![CDATA[Video Description ]]> </tagline>
  </mainvideo>
  
  
  
  <mainvideo url="<?php if($hd=='yes') echo @get_hq_video_file($v); else echo @get_video_file($v,true,true); ?>" 
             hd="<?php if(@has_hq($v,true)) echo 'true'; else echo 'false'; ?>" 
             hdpath="<?php echo @get_hq_video_file($v);?>"
             id="100"
             thu_image="<?=get_thumb($v)?>"                                                                                 
             Preview=" "
             preroll="false" 
             midroll="false"
             postroll="false" 
             allow_download="false"
             > 
    <title><![CDATA[WELCOME]]></title>
<!--Optional-->
<tagline targeturl="https://mydomain.com"><![CDATA[<spanclass='heading'>Tagline - </span> <b>Your
short description goes here for videos.</b>]]</tagline>

  </mainvideo>
</playlist>