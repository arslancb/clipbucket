<?php
include('../../../../includes/config.inc.php');
header('Content-Type: text/xml');
$title = mysql_clean($_GET['title']);
$tags = mysql_clean($_GET['tags']);
$videoid = mysql_clean($_GET['vid']);

$related_videos = get_videos(array('title'=>$title,'tags'=>$tags,
'exclude'=>$videoid,'show_related'=>'yes','limit'=>8,'order'=>'date_added DESC'));
if(!$related_videos)
	$related_videos  = get_videos(array('exclude'=>$videoid,'limit'=>12,'order'=>'date_added DESC'));
?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
	<?php
	if($related_videos)
	foreach($related_videos as $video):
	?>
    <item>
        <title><?=$video['title']?></title>
        <link><?=videoLink($video);?></link>
        <media:thumbnail url="<?=get_thumb($video)?>" height="90" width="120" time="<?=setTime($video['duration'])?>"/>
    </item>
    <?php
	endforeach;
	?>
</channel>
</rss>