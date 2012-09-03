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

$page = mysql_clean($request['page']);

$max_video_limit = 20;
$videos_limit = 20;

switch($mode)
{
    case "getVideos":
    case "get_videos":
    default:
    {
        
        $get_limit = create_query_limit($page,$videos_limit);

        $request['limit'] = $get_limit;
        
        $vids = $request['video_id'];
        
        if($vids){
            $vids = explode(',',$vid);

            $request['videoids'] = $vids;
        }
        
        $videos = $cbvid->get_videos($request);
        
        $new_videos = array();
        if($videos)
            foreach($videos as $video)
            {
                $video['thumbs'] = array('default'=>BASEURL.'/api/thumb-sample.php');
                $video['videos'] = array('mobile' => VIDEOS_URL.'/12345.mp4');
                $new_videos[] = $video;
            }
            
        
        echo json_encode($new_videos);
    }
    break;
    
    case "getComments":
    {
        $params = array();
        $limit = config('comments_per_page');
        $page = $request['page'];
        $params['type'] = mysql_clean($request['type']);
        $params['type_id'] = mysql_clean($request['type_id']);
        $params['last_update'] = mysql_clean($request['last_update']);
        $params['limit'] = create_query_limit($page,$limit);	

        $comments = $myquery->getComments($params);
        
        echo json_encode($comments);
    }
    break;

    case "getCategory":
    case "getCategories":
    {
        $type = $request['type'];
        switch($type)
        {
            case "v":
            case "video":
            case "videos":
            default:
            {
                $categories = $cbvid->getCbCategories(arraY('indexes_only'=>true));     
            }
            break;
            
            case "u":
            case "user":
            case "users":
            {
                $categories = $userquery->getCbCategories(arraY('indexes_only'=>true)); 
            }
            
            break;
            
            case "g":
            case "group":
            case "groups":
            {
                $categories = $cbgroup->getCbCategories(arraY('indexes_only'=>true)); 
            }
        }
        echo json_encode($categories);
    }
    break;

    case 'getFields':
    case 'get_fields': {
        $groups = $Upload->load_video_fields(null);
        
        $new_groups = array();
        foreach($groups as $group)
        {
            $new_fields  = array();
            
            foreach($group['fields'] as $field)
            {
               // foreach($fields as $field)
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
        $playlists = $cbvid->action->get_playlists();
        
        if($playlists)
            echo json_encode($playlists);
        else
            echo json_encode(array('err'=>'No playlist was found'));
    }
    
    break;
    case "get_playlist_items":
    case "getPlaylistItems":
    {
        $pid = mysql_clean($request['playlist_id']);
        $items = $cbvid->get_playlist_items($pid);
        
        if($items){
            echo json_encode($items);
        }else
            echo json_encode(array('err'=>'No items in this playlist'));
    }
}

?>