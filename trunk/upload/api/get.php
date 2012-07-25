<?php

/**
 * @Author Arslan Hassan
 * @Since v3.0 - 2012
 * 
 * New Api for ClipBucket to let other application access data
 */

include('../includes/config.inc.php');

$mode = $_POST['mode'];
$max_video_limit = 20;

switch($mode)
{
    case "getVideos":
    case "get_videos":
    default:
    {
        if($_POST['limit'] > $max_video_limit || !$_POST['limit'])
            $_POST['limit'] = $max_video_limit;
        
        $videos = $cbvid->get_videos($_POST);
        
        echo json_encode($videos);
    }
}

?>