<?php

/**
 * function used to make feed fully array based
 * 
 * @param ARRAY $feed
 * @return ARRAY $feed
 */
function feed($feed)
{

    if ($feed['user'] && !is_array($feed['user']))
        $feed['user'] = json_decode($feed['user'], true);


    if ($feed['content'] && !is_array($feed['content']))
        $feed['content'] = json_decode($feed['content'], true);


    if ($feed['object'] && !is_array($feed['object']))
        $feed['object'] = json_decode($feed['object'], true);

    if ($feed['comments'] && !is_array($feed['comments']))
        $feed['comments'] = json_decode($feed['comments'], true);

    if ($feed['likes'] && !is_array($feed['likes']))
        $feed['likes'] = json_decode($feed['likes'], true);

    if ($feed['message_attributes'] && !is_array($feed['message_attributes']))
        $feed['message_attributes'] = json_decode($feed['message_attributes'], true);

    $feed = apply_filters($feed, 'feed');

    return $feed;
}

/**
 * Feed message, used to apply filters
 * @param STRING $message
 * @return STRING $message
 */
function feed_message($message)
{
    $message = apply_filters($message, 'feed_message');
    $message = nl2br(stripcslashes($message));
    
    return $message;
}

/**
 * get likes phrase
 * 
 * @param ARRAY $likes
 * @return STRING $phrase
 */
function get_likes_phrase($likes, $total_likes, $liked)
{
    
    if ($total_likes > 0)
    {
        if ($liked == 'yes')
        {
            
                
            if ($total_likes > 2)
                $phrase = sprintf(lang('You and %s other like this'), $total_likes-1);
            elseif($total_likes == 2)
            {

                foreach ($likes as $like)
                {
                    if($like['userid']!=userid())
                        break;
                }
                $username = name($like);
                
                $phrase = sprintf(lang('You and %s like this'), $username);
            }else
                $phrase = lang('You like this');
        }else
        {
            if ($total_likes == 1)
            {
                //$likes = $results['likes'];
                foreach ($likes as $like)
                    break;
                $username = name($like);

                $phrase  = sprintf(lang('%s likes this post'), $username);
            }else
            {
                $phrase  = sprintf(lang('%s people like this post'), $total_likes);
            }
        }
    }
    else
    {
        $phrase  = lang('Be the first to like this post');
    }
    
    
    return $phrase;
}



/** 
 * get feed comments
 * @param ARRAY $feed
 * 
 * @return ARRAY $comments
 */
function feed_comments($feed)
{
    if($feed['comments'])
    {
        $comments = ($feed['comments']);
        
        
        $total = count($comments);
        $allowed = 50;
        $remain = $total-$allowed;
        if($remain>0)
        $comments = array_splice($comments, $remain, $allowed);

        return $comments;
    }
}



/**
 * Get sharing block for an object..
 * 
 * @param ARRAY $object details
 * @param STRING $type Objec type
 * @oaram INT $id of object
 * @return STRING $template of the sharing box
 */
function get_feed_share_block($type,$id=NULL,$object=NULL)
{
    global $cbfeeds;

    $content = $cbfeeds->get_content($type,$id,$object);
    
    assign('content',$content);
   
    $template = get_template('share_feed_block');
    
    return $template;
}
?>
