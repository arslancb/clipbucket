<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007 Clip-Bucket.com. All rights reserved.											|
 | @ Author : Murat Esgin (lavinya http://www.videoizlepaylas.com ) and ArslanHassan	|																		|
 | @ Software : Media Rss 2.0 for ClipBucket , © PHPBucket.com	 |
 ****************************************************************************************************
*/

require 'includes/config.inc.php';

//Assign 
Assign('WebsiteEmail',WEBSITE_EMAIL);

$show = $_GET['show'];
if($show!='featured' && $show!='most_viewed' && $show!='latest'){
$show  = 'most_viewed';
}
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";

//Getting Featured Videos
if($show == 'featured'){
$query = "SELECT * FROM video WHERE featured = 'yes' AND $query_param LIMIT 0,10";
}
//Getting Recently Added
if($show == 'latest'){
$query = "SELECT * FROM video WHERE $query_param ORDER BY date_added DESC LIMIT 0,10";
}
//Getting Most Viewed
if($show == 'most_viewed'){
$query = "SELECT * FROM video WHERE $query_param ORDER BY views DESC LIMIT 0,10";
}

//Getting Whats Hot
$list = array(
		'featured'		=> lang('rss_feed_featured_title'),
		'latest'		=> lang('rss_feed_latest_title'),
		'most_viewed'	=> lang('rss_feed_most_viewed_title'),
		'link01'		=> BASEURL.videos_link.'?order=fr',
		'link02'		=> BASEURL.videos_link.'?order=mr',
		'link03'		=> BASEURL.videos_link.'?order=mv'
		);
$text = $list[$show];
Assign('text',$text);

$link = @$_GET['link'];
$link = @$list[$link];
Assign('link',@$link);

$data 		= $db->Execute($query);
$videos		= $data->getrows();
$total		= $data->recordcount() + 0;
	
	for($id=0;$id<$total;$id++){
	$videos[$id]['thumb'] 		= GetThumb($videos[$id]['flv']);
	$videos[$id]['time']		= SetTime($videos[$id]['duration']);
	$videos[$id]['comments']	= $myquery->GetTotalVideoComments($videos[$id]['videoid']);
	$videos[$id]['show_rating'] = pullRating($videos[$id]['videoid'],false,false,false,@novote);
	$videos[$id]['url'] 		= VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
	$videos[$id]['category1'] 	= $myquery->GetCategory($videos[$id]['category01']);
	$videos[$id]['date_added'] 	= gmdate("D, d M Y H:i:s T", strtotime($videos[$id]['date_added']));
	}
	
Assign('videos',$videos);

Assign('show',$_GET['show']);
header ("Content-type: text/xml; charset=utf-8");
Template('rss.html');

?>