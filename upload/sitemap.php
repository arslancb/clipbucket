<?php
/* 
 *****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.	
 | @ Author : tangi											
 | @ Software : ClipBucket , &#169; PHPBucket.com						
 ******************************************************************
*/
require 'includes/config.inc.php';
header("Content-Type: text/xml charset=utf-8");

$limit = 100;
$videos = get_videos(array('limit'=>$limit,'active'=>'yes','order'=>'date_added DESC'));

?>
<urlset 
	xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
	xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" 
	xmlns:image="https://www.google.com/schemas/sitemap-image/1.1">
	
	<url>
      		<loc><?=BASEURL ?></loc>
	</url>
<!-- by fjulio ( tangi @ clipbucket dev. ) -->
<?php
    foreach($videos as $video)
	{
?>
	
<url>
<loc><?=video_link($video)?></loc>
<video:video>
<video:thumbnail_loc><?=get_thumb($video)?></video:thumbnail_loc>
<video:title><![CDATA[<?=substr($video['title'],0,500)?>]]></video:title>
<video:description><![CDATA[<?=substr($video['description'],0,300)?>]]></video:description>
<video:player_loc allow_embed="yes"><?=get_video_file($video)?></video:player_loc>
<video:duration>
<?php echo round($video['duration']);
?></video:duration>
<video:rating><?php 
$vrating = $video['rating'] / 2;
$findcond = strpos($vrating,'.');
if ($findcond == "1"){
    $firstrating = str_replace(',','.',$vrating);
    echo $firstrating;
}
else { echo $vrating.'.0'; }?>
</video:rating>
<video:view_count>
<?=$video['views']?></video:view_count>
<video:publication_date><?php
echo cbdate("Y-m-d\TH:i:s",strtotime($video['date_added'])).'+00:00';
?></video:publication_date>
<?php
$vtags = strip_tags(tags($video['tags'],'video'));
$vtableau = explode (",",$vtags);
for($i=0;$i<sizeof($vtableau) && $i<32;$i++)
    {
    echo '<video:tag><![CDATA['.trim($vtableau[$i]).']]></video:tag>';
    }
?>
<video:category><?=strip_tags(categories($video['category'],'video'))?></video:category>
</video:video>
</url>
<?php
	}
?>
</urlset>
