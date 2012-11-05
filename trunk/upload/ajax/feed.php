<?php

include("../includes/config.inc.php");

$mode = $_POST['mode'];

switch ($mode)
{
    case "like_feed":
    {
        $liked      = mysql_clean($_POST['liked']);
        $feed_id    = mysql_clean($_POST['feed_id']);
        
        
        $results = $cbfeeds->like_feed(array('feed_id'=>$feed_id,'liked'=>$liked));
        $total_likes = $results['total_likes'];
        
        $likes = $results['likes'];
        $results['phrase'] = get_likes_phrase($likes,$total_likes,$liked);
        
        echo json_encode($results);
        
    }
    break;

    case "add_feed_comment":
    {
        $id = mysql_clean($_POST['feed_id']);
        $comment = $_POST['comment'];
        
        if($comment=='undefined')
                $comment = '';
        $reply_to = $_POST['reply_to'];

        $cid = $cbfeeds->add_comment($comment,$id,$reply_to);
        
        if(error())
        {
            exit(json_encode(array('err'=>error())));
        }
            
        $comment = $myquery->get_comment($cid);
        assign('comment',$comment);

        $template = get_template('single_feed_comment');

        $array = array(
            'msg' => msg(),
            'comment' => $template,
            'success' => 'ok',
            'cid' => $cid
        );
        
        echo json_encode($array);
        
        break;
    }
    
    default:
        exit(json_encode(array('err' => array(lang('Invalid request')))));
}
?>
