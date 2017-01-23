<?php

/**
 *************************************************************
 | Copyright (c) 2007-2017 Clip-Bucket.com. All rights reserved.
 | @ Author    : Arslan Hassan                              
 | @ Software  : ClipBucket
 | @ Modified : { January 10th, 2017 } { Saqib Razzaq } { Updated copyright date }
 *************************************************************
*/

    define("THIS_PAGE",'myaccount');
    define("PARENT_PAGE",'home');

    require 'includes/config.inc.php';
    global $db,$cbvid;
    $userquery->logincheck();

    assign('user',$userquery->get_user_details(userid()));
    $videos = $userquery->get_user_vids(userid(),false,false,true);
    assign('videos',$videos);

    $result_array['limit'] = $get_limit;
    $result_array['user'] = $udetails["userid"];
    $get_limit = create_query_limit($page,5);
    $videos = $cbvid->action->get_flagged_objects($get_limit);
    Assign('flagedVideos', $videos);

    $result_array['limit'] = $get_limit;
    $result_array['user'] = $udetails["userid"];
    $get_limit = create_query_limit($page,5);
    $photos = $cbphoto->action->get_flagged_objects($get_limit);
    assign('flagedPhotos', $photos);


    if(isset($_GET['delete_video'])) {
     $video = mysql_clean($_GET['delete_video']);
     $cbvideo->delete_video($video);
    }

    subtitle(lang("my_account"));
    template_files('myaccount.html');
    display_it();

?>