<?php
$limit = defined('PHOTOS_RSS_LIMIT') ? PHOTOS_RSS_LIMIT : 20;
$page = $_GET['page'];
if($page<1 || !is_numeric ) {
	$page = 1;
}
$sqlLimit = create_query_limit( $page, $limit );
$mode = $_GET['mode'];
switch( $mode ) {
    case "recent": default : {
        $photos = get_photos( array('limit' => $sqlLimit, 'order' => 'date_added DESC') );
        $title = lang('Recently Added Photos');
    }
    break;

    case "featured": {
        $photos = get_photos( array('limit' => $sqlLimit, 'featured' => 'yes') );
        $title = lang('Featured Photos');
    }
    break;

    case "user": {
        $user = mysql_clean($_GET['username']);
        //Get userid from username
        $u = $userquery->get_user_details($user);
        $uid = $u['userid'] ? $u['userid'] : 'x';
        if ( $uid != 'x' ) {
            $photos = get_photos(array('limit'=>$sqlLimit,'user'=>$uid, 'order'=>'date_added DESC'));
            $total_photos = $u['total_photos'];
            $title = "Photos uploaded by ".$user;
        }
    }
    break;

    case "views": {
        $photos = get_photos( array('limit' => $sqlLimit, 'order' => 'views DESC') );
        $title = lang('Most Viewed Photos');
    }
    break;

    case "rating": {
        $photos = get_photos( array('limit' => $sqlLimit, 'order' => 'rating DESC, rated_by DESC') );
        $title = lang('Top Rated Photos');
    }
    break;

    case "watching": {
        $photos = get_photos( array('limit' => $sqlLimit, 'order' => 'last_viewed DESC') );
        $title = lang('Photos Being Viewed');
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
        if($total_photos)
        {
        ?>
            <total_photos><?=$total_photos?></total_photos>
        <?php
        }
        ?>
        
        <?php 
            if ( $photos ): 
                foreach ( $photos as $photo ):
                    $p=json_decode($photo['photo_details'],true);
        ?>
            <item>
                <author><?=$photo['username']?></author>
                <title><?=substr($photo['photo_title'],0,50)?></title>
                
                 <description>
                     <![CDATA[
                     <table width="750" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                             <td width="350" align="center" valign="top"><?=get_photo( array('size' => 'l', 'details' => $photo, 'output' => 'html', 'width' => '340') );?></td>
                             <td valign="top">
                                 <a href="<?=view_photo_link($photo)?>"><?=$photo['photo_title']?></a><br />
                                 <?=$photo['photo_description'] ? $photo['photo_description'] : lang('No description available') ?><br/><br/>
                                 Views: <?=$photo['views']?> &ndash; Collection: <a href="<?=view_photo_link($photo, 'view_collection')?>"><?=$photo['collection_name']?></a><br />
                                 Dimensions: <?=$p['o']['width'].'x'.$p['o']['height'];?> &ndash; Filesize: <?=$p['o']['size']['kilobytes'].' kb' ?>
                             </td>
                         </tr>
                     </table>
                     <hr size="1" noshade>
                     ]]>
                 </description>
                
                <pubDate><?=$photo['date_added']?></pubDate>
            </item>
        <?php
                endforeach;
            endif;
        ?>
    </channel>
</rss>
