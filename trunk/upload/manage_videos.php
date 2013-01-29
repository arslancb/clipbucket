<?php

/*
 * *************************************************************
  | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
  | @ Author	   : ArslanHassan
  | @ Software  : ClipBucket , © PHPBucket.com
 * *************************************************************
 */

define("THIS_PAGE", 'manage_videos');
define("PARENT_PAGE", "videos");

require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user', $udetails);
assign('p', $userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page, VLISTPP);

assign('queryString', queryString(NULL, array('type',
            'makeProfileItem',
            'removeProfileItem',
            'vid_delete')));
switch ($mode) {
    case 'uploaded':
    default: {
            assign('mode', 'uploaded');

            //Deleting Video
            if (!empty($_GET['vid_delete'])) {
                $video = mysql_clean($_GET['vid_delete']);
                $cbvideo->delete_video($video);
            }

            //Deleting Videos
            if (isset($_POST['delete_videos'])) {
                for ($id = 0; $id <= VLISTPP; $id++) {
                    $cbvideo->delete_video($_POST['check_vid'][$id]);
                }
                $eh->flush();
                e(lang("vdo_multi_del_erro"), m);
            }

            //Setting Profile Video
            if (isset($_GET['makeProfileItem'])) {
                $item = mysql_clean($_GET['makeProfileItem']);
                $type = mysql_clean($_GET['type']);
                $userquery->setProfileItem($item, $type);
            }

            //Removing Profile Item
            if (isset($_GET['removeProfileItem'])) {
                $userquery->removeProfileItem();
            }

            //Getting Video List
            $vid_array = array('user' => $udetails['userid'], 'limit' => $get_limit);
            if (get('query') != '') {
                $vid_array['title'] = mysql_clean(get('query'));
                $vid_array['tags'] = mysql_clean(get('query'));
            }

            if (get('order') == 'oldest')
                $vid_array['order'] = ' date_added ASC ';
            else
                $vid_array['order'] = ' date_added DESC ';

            if(get('broadcast') && is_valid_broadcast(get('broadcast')))
            {
                $vid_array['broadcast'] = mysql_clean(get('broadcast'));
            }
            
            $videos = get_videos($vid_array);

            Assign('uservids', $videos);
            Assign('videos', $videos);

            //Collecting Data for Pagination
            $vid_array['count_only'] = true;
            $total_rows = get_videos($vid_array);
            assign('total_videos',$total_rows);
            $total_pages = count_pages($total_rows, VLISTPP);

            //Pagination
            $pages->paginate($total_pages, $page);

            subtitle(lang("vdo_manage_vdeos"));
        }
        break;
}

template_files('manage_videos.html');
display_it();
?>