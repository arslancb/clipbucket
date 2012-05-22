<?php

/** 
 * All AJax requests which does not fall in other categories or files
 * are saved here
 * 
 * @author Arslan Hassan
 * @license AAL
 * @since 3.0 
 */



include("../includes/config.inc.php");


//Getting mode..
$mode = $_POST['mode'];

$mode = mysql_clean($mode);


switch($mode){
    
    //Rating function works with every object in similar manner therefore
    //Using the same code in different files we are using it here...
    case "rating":
    {
        
        $type = mysql_clean(post('type'));
        $id = mysql_clean(post('id'));
        $rating = mysql_clean(post('rating'));
        
        switch($type){
            case "video":
            {
                $result = $cbvid->rate_video($id,$rating);
                echo showRating($result,$type);
            }
            break;

            case "photo":
            {
                $rating = $_POST['rating']*2;
                $id = $_POST['id'];
                $result = $cbphoto->rate_photo($id,$rating);
                $result['is_rating'] = true;
                $cbvid->show_video_rating($result);

                $funcs = cb_get_functions('rate_photo');	
                if($funcs)
                foreach($funcs as $func)
                {
                        $func['func']($id);
                }
            }
            break;
            case "collection":
            {
                $rating = $_POST['rating']*2;
                $id = $_POST['id'];
                $result = $cbcollection->rate_collection($id,$rating);
                $result['is_rating'] = true;
                $cbvid->show_video_rating($result);

                $funcs = cb_get_functions('rate_collection');	
                if($funcs)
                foreach($funcs as $func)
                {
                        $func['func']($id);
                }
            }
            break;

            case "user":
            {
                $rating = $_POST['rating']*2;
                $id = $_POST['id'];
                $result = $userquery->rate_user($id,$rating);
                $result['is_rating'] = true;
                $cbvid->show_video_rating($result);

                $funcs = cb_get_functions('rate_user');	
                if($funcs)
                foreach($funcs as $func)
                {
                        $func['func']($id);
                }
            }
            break;
        }
    }
    break;

    default:
        exit(json_encode(array('err'=>lang('Invalid request'))));
}
?>