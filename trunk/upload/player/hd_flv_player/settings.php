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
             thu_image=""                                                                                 
             Preview="" 
             preroll="false" 
             midroll="false"
             postroll="false" 
             allow_download="false"> 
  </mainvideo>
</playlist>
