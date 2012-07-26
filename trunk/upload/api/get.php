<?php

/**
 * @Author Arslan Hassan
 * @Since v3.0 - 2012
 * 
 * New Api for ClipBucket to let other application access data
 */

include('../includes/config.inc.php');

$request = $_REQUEST;
$mode = $request['mode'];

$max_video_limit = 20;

switch($mode)
{
    case "getVideos":
    case "get_videos":
    default:
    {
        if($request['limit'] > $max_video_limit || !$request['limit'])
            $request['limit'] = $max_video_limit;
        
        $videos = $cbvid->get_videos($request);
        
        $new_videos = array();
        if($videos)
            foreach($videos as $video)
            {
                $video['thumbs'] = array('default'=>THUMBS_URL.'/default.jpg');
                $new_videos[] = $video;
            }
            
        
        echo json_encode($new_videos);
    }
}

?>