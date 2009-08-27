<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require 'includes/config.inc.php';
$show = $_GET['show'];
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";

//Getting Featured Videos
if($show == 'featured'){
$query = "SELECT * FROM video WHERE featured = 'yes' AND $query_param LIMIT 0,8";
}
//Getting Recently Added
if($show == 'latest'){
$query = "SELECT * FROM video WHERE $query_param ORDER BY date_added DESC LIMIT 0,8";
}
//Getting Most Viewed
if($show == 'most_viewed'){
$query = "SELECT * FROM video WHERE $query_param ORDER BY views DESC LIMIT 0,8";
}
//Getting Most Discussed
if($show == 'most_discussed'){
$query = "SELECT * FROM video WHERE comments_count > 0 AND $query_param ORDER BY comments_count DESC LIMIT 0,8";
}
//Getting Highest Rated
if($show == 'highest_rated'){
$query = "SELECT * FROM video WHERE rating > 0 AND $query_param ORDER BY rating DESC LIMIT 0,8";
}

//Getting Whats Hot
$list = array(
		'featured'		    => $LANG['featured'],
		'latest'		    => $LANG['recently_added'],
		'most_viewed'	    => $LANG['most_viewed'],
        'most_discussed'    => $LANG['most_discussed'],
        'highest_rated'     => $LANG['highest_rated'],
		'link01'		    => BASEURL.videos_link.'?order=fr',
		'link02'		    => BASEURL.videos_link.'?order=mr',
		'link03'		    => BASEURL.videos_link.'?order=mv',
        'link04'		    => BASEURL.videos_link.'?order=md',
        'link05'            => BASEURL.videos_link.'?order=hr',
		);
$text = $list[$show];
Assign('text',$text);

@$link = $_GET['link'];
@$link = $list[$link];
Assign('link',$link);

$data 		= $db->Execute($query);
if($data)
{
$videos		= $data->getrows();
$total		= $data->recordcount() + 0;
	
	for($id=0;$id<$total;$id++){
	$videos[$id]['thumb'] 		= GetThumb($videos[$id]['flv']);
    $videos[$id]['description'] = nl2br($videos[$id]['description']);
	$videos[$id]['duration']	= SetTime($videos[$id]['duration']);
	$videos[$id]['comments']	= $myquery->GetTotalVideoComments($videos[$id]['videoid']);
	$videos[$id]['show_rating'] = pullRating($videos[$id]['videoid'],false,false,false,@novote);
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
	}
	
Assign('videos',$videos);
}

Assign('show',$_GET['show']);
Template('tabs03.html');

?>