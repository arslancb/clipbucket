<?php

/**
 * @Author Arslan Hassan
 * @Since v3.0 - 2012
 * 
 * New Api for ClipBucket to let other application access data
 */
include('../includes/config.inc.php');
include('global.php');

$request = $_REQUEST;
$mode = $request['mode'];

$page = mysql_clean($request['page']);

$max_video_limit = 20;
$videos_limit = 20;


$api_keys = $Cbucket->api_keys;
if ($api_keys)
{
    if (!in_array($request['api_key'], $api_keys))
    {
        exit(json_encode(array('err' => 'App authentication error')));
    }
}

switch ($mode)
{
    case "getVideos":
    case "get_videos":
    default:
        {
            $blacklist_fields = array(
                'password', 'video_password', 'avcode', 'session'
            );

            $get_limit = create_query_limit($page, $videos_limit);

            $request['limit'] = $get_limit;

            if (VERSION < 3)
                $request['user'] = $request['userid'];

            //$request['order'] = tbl('video.'.$request['order']);

            $vids = $request['video_id'];

            if ($vids)
            {
                $vids = explode(',', $vids);

                $request['videoids'] = $vids;
            }

            $videos = $cbvid->get_videos($request);
            header('Content-Type: text/html; charset=utf-8');

            $new_videos = array();
            if ($videos)
            {
                foreach ($videos as $video)
                {

                    $video['title'] = utf8_encode($video['title']);
                    $video['description'] = utf8_encode($video['description']);
                    $video['thumbs'] = array('default' => get_thumb($video), 'big' => get_thumb($video, 'big'));

                    if (function_exists('get_mob_video'))
                    {
                        $video['videos'] = array('mobile' => get_mob_video(array('video' => $video)));
                        if (has_hq($video))
                        {
                            $video['videos']['hq'] = get_hq_video_file($video);
                        }
                    }
                    $video['url'] = $video['video_link'] = $video['videoLink'] = videoLink($video);
                    $video['avatar'] = $video['user_photo'] = $video['displayPic'] = $userquery->avatar($video);

                    /*
                      if (!$video['fullname'])
                      {
                      $video['userDetail'] = $userquery->get_user_details($video['userid']);
                      }
                     */

                    foreach ($blacklist_fields as $field)
                        unset($video[$field]);

                    $new_videos[] = $video;
                }
            }
            //echo $db->db_query;
            echo json_encode($new_videos);
        }
        break;

    case "getComments":
        {
            $params = array();
            $limit = config('comments_per_page');
            $page = $request['page'];

            if (!$page || !is_numeric($page) || $page < 1)
                $page = 1;

            if (!$limit || !is_numeric($limit) || $limit < 1)
                $limit = 20;


            $params['type'] = mysql_clean($request['type']);
            $params['type_id'] = mysql_clean($request['type_id']);
            $params['last_update'] = mysql_clean($request['last_update']);
            $params['limit'] = create_query_limit($page, $limit);

            $comments = $myquery->getComments($params);

            $blacklist_fields = array(
                'password', 'video_password', 'avcode', 'session'
            );

            $the_comments = array();

            if ($comments)
                foreach ($comments as $comment)
                {
                    foreach ($blacklist_fields as $field)
                    {
                        unset($comment[$field]);
                    }
                    $the_comments[] = $comment;
                }

            echo json_encode($the_comments);
        }
        break;

    case "getCategory":
    case "getCategories":
        {
            $type = $request['type'];
            switch ($type)
            {
                case "v":
                case "video":
                case "videos":
                default:
                    {
                        $categories = $cbvid->getCbCategories(arraY('indexes_only' => true));
                    }
                    break;

                case "u":
                case "user":
                case "users":
                    {
                        $categories = $userquery->getCbCategories(arraY('indexes_only' => true));
                    }

                    break;

                case "g":
                case "group":
                case "groups":
                    {
                        $categories = $cbgroup->getCbCategories(arraY('indexes_only' => true));
                    }
                    
                case "p":
                case "photo":
                case "photos":
                    {
                        $categories = $cbcollection->getCbCategories(arraY('indexes_only' => true));
                    }
            }
            echo json_encode($categories);
        }
        break;

    case 'getFields':
    case 'get_fields':
        {
            $groups = $Upload->load_video_fields(null);

            $new_groups = array();
            foreach ($groups as $group)
            {
                $new_fields = array();

                foreach ($group['fields'] as $field)
                {
                    // foreach($fields as $field)
                    if ($field)
                        $new_fields[] = $field;
                }

                $group['fields'] = $new_fields;
                $new_groups[] = $group;
            }

            //pr($new_groups,true);
            echo json_encode($new_groups);
        }
        break;
    case "get_playlists":
    case "getPlaylists":
        {
            $uid = mysql_clean($request['userid']);

            $playlists = $cbvid->action->get_playlists($uid);

            if (VERSION < 3)
            {
                $new_playlists = array();
                foreach ($playlists as $playlist)
                {
                    $playlist['total_items'] = $cbvid->action->count_playlist_items($playlist['playlist_id']);
                    $new_playlists[] = $playlist;
                }

                $playlists = $new_playlists;
            }

            if ($playlists)
                echo json_encode($playlists);
            else
                echo json_encode(array('err' => 'No playlist was found'));
        }

        break;
    case "get_playlist_items":
    case "getPlaylistItems":
        {
            $pid = mysql_clean($request['playlist_id']);
            $items = $cbvid->get_playlist_items($pid);

            $blacklist_fields = array(
                'password', 'video_password', 'avcode', 'session'
            );

            if ($items)
            {
                $new_videos = array();

                foreach ($items as $video)
                {
                    if (!$video['email'])
                    {
                        $udetails = $userquery->get_user_details($video['userid']);
                    }

                    $video = array_merge($video, $udetails);

                    $video['thumbs'] = array('default' => get_thumb($video));
                    $video['videos'] = array('mobile' => get_mob_video(array('video' => $video)));
                    $video['url'] = $video['video_link'] = $video['videoLink'] = videoLink($video);
                    $video['avatar'] = $video['user_photo'] = $video['displayPic'] = $userquery->avatar($video);

                    foreach ($blacklist_fields as $field)
                        unset($video[$field]);

                    $new_videos[] = $video;
                }
                echo json_encode($new_videos);
            }else
                echo json_encode(array('err' => 'No items in this playlist'));
        }
        break;

    case "getConfigs":
    case "get_configs":
    case "configs":
        {
            $upload_path = '';

            if (function_exists('get_file_uploader_path'))
                $upload_path = get_file_uploader_path();

            $array = array(
                'baseurl' => BASEURL,
                'title' => TITLE,
                'file_upload_url' => BASEURL . '/api/file_uploader.php',
                'session' => session_id()
            );

            echo json_encode($array);
        }

        break;

    case "videoFlagOptions":
    case "video_flag_options":
        {
            $type = $request['type'];
            $type = $type ? $type : 'v';

            $flags = get_flag_options($type);

            echo json_encode($flags);
        }


        break;
    case "getSubscribers":
        {
            $uid = $request['userid'];
            if (!$uid)
                $uid = userid();

            if (!$uid)
                exit(json_encode(array('err' => lang('Please login'))));

            $subscribers = $userquery->get_user_subscribers_detail($uid);

            if ($subscribers)
            {
                $the_subscribers = array();
                foreach ($subscribers as $subscriber)
                {
                    foreach ($blacklist_fields as $field)
                    {
                        unset($subscriber[$field]);
                    }
                    $the_subscribers = $subscriber;
                }

                $subscribers = $the_subscribers;
            }


            if ($subscribers)
                echo json_encode($subscribers);
            else
                echo json_encode(array('err' => lang('No Subscribers')));

            exit();
        }
        break;

    case "getSubscriptions":
        {
            $uid = $request['userid'];
            if (!$uid)
                $uid = userid();

            if (!$uid)
                exit(json_encode(array('err' => lang('Please login'))));

            $subscribers = $userquery->get_user_subscriptions($uid);

            if ($subscribers)
            {
                $the_subscribers = array();
                foreach ($subscribers as $subscriber)
                {
                    foreach ($blacklist_fields as $field)
                    {
                        unset($subscriber[$field]);
                    }
                    $the_subscribers = $subscriber;
                }

                $subscribers = $the_subscribers;
            }

            if ($subscribers)
                echo json_encode($subscribers);
            else
                echo json_encode(array('err' => lang('No Subscriptions')));

            exit();
        }
        break;


    case "get_favorite_videos":
    case "getFavoriteVideos":
        {
            $limit = 20;

            $get_limit = create_query_limit($page, $limit);
            $uid = $request['userid'];
            if (!$uid)
                $uid = userid();

            $params = array('userid' => $uid, 'limit' => $get_limit);
            $videos = $cbvid->action->get_favorites($params);
            $params['count_only'] = "yes";
            $total_rows = $cbvid->action->get_favorites($params);
            $total_pages = count_pages($total_rows, $get_limit);

            if ($total_rows > 0)
            {
                $new_videos = array();
                foreach ($videos as $video)
                {
                    if (!$video['email'])
                    {
                        $udetails = $userquery->get_user_details($video['userid']);
                    }

                    $video = array_merge($video, $udetails);

                    $video['thumbs'] = array('default' => get_thumb($video));
                    $video['videos'] = array('mobile' => get_mob_video(array('video' => $video)));
                    $video['url'] = $video['video_link'] = $video['videoLink'] = videoLink($video);
                    $video['avatar'] = $video['user_photo'] = $video['displayPic'] = $userquery->avatar($video);
                   
                    foreach ($blacklist_fields as $field)
                    unset($video[$field]);
                    
                    
                    $new_videos[] = $video;
                }
                echo json_encode($new_videos);
            }
            else
            {
                echo json_encode(array('err' => lang('No favorite videos were found')));
            }
        }
        break;

    case "get_users":
    case "get_channels":
    case "getChannels":
    case "getUsers":
        {
            $get_limit = create_query_limit($page, $videos_limit);

            $request['limit'] = $get_limit;

            $users = get_users($request);

            $new_users = array();
            if ($users)
            {
                foreach ($users as $user)
                {
                    $user['avatar'] = $user['user_photo'] = $userquery->avatar($user);
                    $new_users[] = $user;
                }
            }


            $user_api_fields = array(
                'username', 'first_name', 'last_name', 'fullname',
                'avatar', 'avatar_url',
                'userid', 'email',
                'total_videos',
                'total_photos', 'total_collections',
                'total_groups');


            $final_users = array();
            if ($new_users)
                foreach ($new_users as $user)
                {
                    $final_user = array();

                    foreach ($user_api_fields as $field)
                        $final_user[$field] = $user[$field];

                    $final_users[] = $final_user;
                }

            //echo $db->db_query;
            echo json_encode($final_users);
        }
        
        case "getPhotos":
        case "get_photos":
        {

            $get_limit = create_query_limit($page, $videos_limit);

            $request['limit'] = $get_limit;

            if (VERSION < 3)
                $request['user'] = $request['userid'];

            //$request['order'] = tbl('video.'.$request['order']);

            $photos = $cbphoto->get_photos($request);
            header('Content-Type: text/html; charset=utf-8');

            $new_photos = array();
            if ($photos)
            {
                foreach ($photos as $photo)
                {

                    $photo['photo_title'] = utf8_encode($photo['photo_title']);
                    $photo['photo_description'] = utf8_encode($photo['photo_description']);
                    $photo['photo_link'] = $cbphoto->photo_links($photo,'view_photo');
                    $photo['photo_thumb'] = get_image_file(array(
                        
                    ));
                            
                    //$photo['thumbs'] = array('default' => get_thumb($photo), 'big' => get_thumb($photo, 'big'));

                    $new_photos[] = $photo;
                }
            }
            //echo $db->db_query;
            echo json_encode($new_photos);
        }
        break;
}
?>