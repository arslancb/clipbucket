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

$limit = 20;
$page = $_GET['page'];
if($page<1 || !is_numeric )
	$page = 1;

if($page)
{
	$from = ($page-1)*$limit;
	$limit = "$from,$limit";
}
	
$mode = $_GET['mode'];
switch($mode)
{
	case 'recent':
	default:
	{
		 $videos = get_videos(array('limit'=>$limit,'order'=>'date_added DESC'));
		 $title  = "Recently Added Videos";
	}
	break;
	
	case 'views':
	{
		
		 $videos = get_videos(array('limit'=>$limit,'order'=>'views DESC'));
		 $title = "Most Viewed Videos";
	}
	break;
	
	case 'rating':
	{
		 $videos = get_videos(array('limit'=>$limit,'order'=>'rating DESC'));
		 $title = "Top Rated Videos";
	}
	break;
	
	case 'watching':
	{
		 $videos = get_videos(array('limit'=>$limit,'order'=>'last_viewed DESC'));
		 $title = "Videos Being Watched";
	}
	break;
	case 'user':
	{
		 $user = mysql_clean($_GET['username']);
		 //Get userid from username
		 $uid = $userquery->get_user_field_only($user,'userid');
		 $uid = $uid ? $uid : 'x';
		 $videos = get_videos(array('limit'=>$limit,'user'=>$uid,'order'=>'date_added DESC'));
		 //Count Total Videos of this user
		 $total_vids = get_videos(array('count_only'=>true,'user'=>$uid));
		 $title = "Videos uploaded by ".$user;
	}
	
	break;
}

subtitle($title);
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
	if($total_vids)
	{
	?>
    <total_videos><?=$total_vids?></total_videos>
    <?php
	}
	?>
    <?php
   
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