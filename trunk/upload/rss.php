<?php
/* 
 *****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 ******************************************************************
*/


define("THIS_PAGE",'rss');
require 'includes/config.inc.php';
header ("Content-type: text/xml; charset=utf-8");
echo '<?xml version=\'1.0\' encoding=\'UTF-8\'?>'."\n";
echo '<?xml-stylesheet type="text/css" href="http://localhost/clipbucket/2.x/2/upload/styles/cbv2new/theme/main.css" ?>'."\n";
subtitle('Rss Feed');
?>

<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title><?=cbtitle()?></title>
<link><?=BASEURL?></link>
    <image> 
        <url><?=website_logo()?></url>
        <link><?=BASEURL?></link>
        <title><?=cbtitle()?></title>
    </image>
    <description><?=$Cbucket->configs['description']?></description>
    
    <?php
    $videos = get_videos(array('limit'=>'20'));
    foreach($videos as $video)
    {
    ?>
    <item>
        <author><?=$video['username']?></author>
        <title><?=substr($video['title'],0,50)?></title>
        <link><?=video_link($video)?></link>
        <description>
        <![CDATA[   
        <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td width="130" height="90" align="center" valign="middle"><img src="<?=get_thumb($video)?>"  border="1"/></td>
        <td valign="top">
        <a href="<?=video_link($video)?>"><?=$video['title']?></a><br />
        <?=$video['description']?>
        </td>
        <td width="100" valign="top" align="right">
        Rating <?=$video['rating']?>/10<br />
        Views <?=$video['views']?><br />
        Duration <?=SetTime($video['duration'])?>


        </tr>
        </table>
         <hr size="1" noshade>
        ]]>           
        </description>
        <category><?=strip_tags(categories($video['category'],'video'))?></category>
        <guid isPermaLink="true"><?=video_link($video)?></guid>
        <pubDate><?=$video['date_added']?></pubDate>
        <media:player url="<?=video_link($video)?>" />
        <media:thumbnail url="<?=get_thumb($video)?>" width="120" height="90" />
        <media:title><?=substr($video['title'],0,50)?></media:title>
        <media:category label="Tags"><?=strip_tags(tags($video['tags'],'video'))?></media:category>
        <media:credit><?=$video['username']?></media:credit>
        <enclosure url="<?=video_link($video)?>" type="application/x-shockwave-flash" />

    </item>
    <?php
    }
    ?>

</channel>
</rss>

