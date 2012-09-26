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
		
		if(VERSION<3)
			$request['user'] = $request['userid'];
        
		//$request['order'] = tbl('video.'.$request['order']);
		
        $vids = $request['video_id'];
        
        if($vids){
            $vids = explode(',',$vids);

            $request['videoids'] = $vids;
        }
        
        $videos = $cbvid->get_videos($request);
        header('Content-Type: text/html; charset=utf-8');

        $new_videos = array();
        if($videos)
		{
            foreach($videos as $video)
            {

				$video['title'] = utf8_encode($video['title']);
				$video['description'] = utf8_encode($video['description']);
                $video['thumbs'] = array('default'=>get_thumb($video));
                $video['videos'] = array('mobile' =>get_mob_video(array('video'=>$video)));
				$video['url'] = $video['video_link'] = $video['videoLink'] = videoLink($video);	
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
                if($field)
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
            $new_videos = array();
			
            foreach($items as $video)
            {
                $video['thumbs'] = array('default'=>get_thumb($video));
                $video['videos'] = array('mobile' => get_mob_video(array('video'=>$video)));
				$video['url'] = $video['video_link'] = $video['videoLink'] = videoLink($video);
                $new_videos[] = $video;
            }
            echo json_encode($new_videos);
        }else
            echo json_encode(array('err'=>'No items in this playlist'));
    }
    break;
    
    case "getConfigs":
    case "get_configs":
    case "configs":
    {
        $array = array(
            'baseurl' =>BASEURL,
            'title' => TITLE,
            'file_upload_url', BASEURL.'/actions/file_uploader.php'
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
}

?>