<?php
/* 
****************************************************************************************************
| Copyright (c) 2008 Clip-Bucket.com. All rights reserved.                                 |
| @ Author : Murat Esgin (lavinya http://www.videoizlepaylas.com )    |         
| @ Software : Video Media Sitemap for ClipBucket , © PHPBucket.com    |
****************************************************************************************************
*/
ob_start();
session_start();
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";

require 'includes/config.inc.php';

//Assign 
Assign('WebsiteEmail',WEBSITE_EMAIL);

$show = $_GET['show'];
if($show!='latest'){
$show  = 'latest';
}
$query_param = "broadcast='public' AND active='yes' AND status='Successful'";

//Getting Recently Added
if($show == 'latest'){
$query = "SELECT * FROM video WHERE $query_param ORDER BY date_added DESC LIMIT 0,1000";
}

//Getting Whats Hot
$list = array(
      'latest'      => lang('latest_added_videos'),
      'link01'      => BASEURL.videos_link.'?order=fr',
      );
$text = $list[$show];
Assign('text',$text);

$link = $_GET['link'];
$link = $list[$link];
Assign('link',$link);

$data       = $db->Execute($query);
$videos      = $data->getrows();
$total      = $data->recordcount() + 0;
   
   for($id=0;$id<$total;$id++){
   $videos[$id]['thumb'] 	= GetThumb($videos[$id]['flv']);
   $videos[$id]['time']     = SetTime($videos[$id]['duration']);
   $videos[$id]['url']      = VideoLink($videos[$id]['videokey'],$videos[$id]['title']);
   }
   
Assign('videos',$videos);
Assign('show',$_GET['show']);

header ("Content-type: text/xml; charset=utf-8");
Template('sitemap.xml');
?>