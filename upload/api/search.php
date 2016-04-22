<?php

/**
 * Api Put method to Search
 * on ClipBucket website
 */
include('../includes/config.inc.php');
include('global.php');

$request = $_REQUEST;
$type = $request['type'];
$page = mysql_clean($request['page']);
$limit = 20;


if(!$type)
$type = 'video';

$search = cbsearch::init_search($type);


$search->limit = create_query_limit($page, $limit);
$search->key = mysql_clean($request['query']);

$results = $search->search();
print_r($results);
die;


if ($results)
{
    $the_results = array();
    switch ($type)
    {
        case "video":
        default:
            {
                if($results)
                foreach ($results as $video)
                {

                    $hq_file = get_hq_video_file($video);
                    $video['title'] = title($video['title']);
                    $video['description'] = mob_description($video['description']);

                    $video['thumbs'] = array('default' => get_thumb($video), 'big' => getSmartyThumb(array(
                            'vdetails' => $video, 'size' => 'big'
                        )));
                    $video['videos'] = array('mobile' => get_mob_video(array('video' => $video)));
                    $video['url'] = $video['video_link'] = $video['videoLink'] = videoLink($video);

                    if (has_hq($video))
                        $video['videos']['hd'] = $hq_file;
                    
                    foreach ($blacklist_fields as $field)
                        unset($video[$field]);
                    
                    $the_results[] = $video;
                }
            }
            break;

        case "users":
        {
            
            if($results)
            {
                foreach ($results as $user)
                {
                    $user['avatar'] = $user['user_photo'] = $userquery->avatar($user);
                    
                    foreach ($blacklist_fields as $field)
                        unset($user[$field]);
                    
                    $the_results[] = $user;
                }
            }
            //echo $db->db_query;
        }
        break;
    }
    
    echo json_encode($the_results);
}else
    echo json_encode(array('err' => 'Nothing found!'));
?>