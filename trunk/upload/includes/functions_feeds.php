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
function feed_message($feed)
{
    $message = $feed['message'];
    $message = apply_filters($message, 'feed_message');
    $message = nl2br(stripcslashes($message));

    $attr = $feed['message_attributes'];
    if (!is_array($attr))
        $attr = json_decode($attr, true);

    //Replacing Names..
    $message = apply_mentions($message, $feed['message_attributes']);

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
                $phrase = sprintf(lang('You and %s other like this'), $total_likes - 1);
            elseif ($total_likes == 2)
            {

                foreach ($likes as $like)
                {
                    if ($like['userid'] != userid())
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

                $phrase = sprintf(lang('%s likes this post'), $username);
            }
            else
            {
                $phrase = sprintf(lang('%s people like this post'), $total_likes);
            }
        }
    }
    else
    {
        $phrase = lang('Be the first to like this post');
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
    if ($feed['comments'])
    {
        $comments = ($feed['comments']);


        $total = count($comments);
        $allowed = 50;
        $remain = $total - $allowed;
        if ($remain > 0)
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
function get_feed_share_block($type, $id = NULL, $object = NULL)
{
    global $cbfeeds;

    $content = $cbfeeds->get_content($type, $id, $object);

    assign('content', $content);

    $template = get_template('share_feed_block');

    return $template;
}

/**
 * functio used to get mentions from a message
 * 
 * @param STRING $message
 * @return ARRAY $mentions
 */
function get_mentions($message)
{
    preg_match_all('/@\[([A-Za-z _.\'\"]+):([A-Za-z_]+):([0-9]+)\]/i', $message, $parse_users);

    $total_users = count($parse_users[0]);

    $the_users = array();
    if ($total_users > 0)
    {
        for ($i = 0; $i <= $total_users; $i++)
        {
            if ($parse_users[1][$i])
                $the_users[] = array(
                    'name' => $parse_users[1][$i],
                    'type' => $parse_users[2][$i],
                    'id' => $parse_users[3][$i],
                    'syntax' => $parse_users[0][$i],
                );
        }
    }

    return $the_users;
}

/**
 * Apply mentions on a message to display Names of user or page
 * 
 * @param STRING $message
 * @param ARRAY $message_attr
 * @return STRING $parsed_message
 */
function apply_mentions($message, $attr)
{
    if ($attr && is_array($attr))
    {
        $mentions = get_mentions($message);

        foreach ($mentions as $mention)
        {
            if ($mention['type'] == 'user')
            {
                $user_details = $attr['user'][$mention['id']];
                $name = $user_details['fullname'];
                if (!$name)
                    $name = $user_details['username'];

                $user_details['name'] = $name;


                $syntax = $mention['syntax'];
                $html = get_mentioned_html($user_details);
                //Replacing name
                $message = str_replace($syntax, $html, $message);
            }
        }
    }

    return $message;
}

/**
 * function used to create an HTML clicable mention name
 * using INPUT details
 */
function get_mentioned_html($input)
{
    $link = $input['link'];

    $html = '<span class="cb_mentioned" >';
    $html .= '<a href="' . $link . '" >';
    $html .= $input['name'];
    $html .= '</a>';
    $html .= '<span>';

    return $html;
}

/**
 * get message attributes from message
 * 
 * @param STRING $message
 * @return ARRAY $message_attr
 */
function get_message_attributes($message)
{
    global $userquery;

    //Fetch user information
    $the_users = get_mentions($message);

    $mentions = array();

    if ($the_users)
    {
        foreach ($the_users as $the_user)
        {
            if ($the_user['type'] == 'user')
            {
                //Get user content...
                $user = get_content('user', $the_user['id']);
                if ($user)
                {
                    $mentions[$the_user['type']][$user['userid']] = $user;
                }
            }
        }
    }

    if ($mentions)
        return $mentions;
    else
        return false;
}

/**
 * Add user id in cb_user_mentions table so that in case
 * user updates profile we can update feeds too
 * 
 * @param STRING $messag
 * @param ARRAY $feed_id
 */
function add_users_mentioned($message, $fid = NULL, $cid = NULL)
{
    global $cbfeeds, $userquery;
    if ($message && ($fid || $cid))
    {
        if ($fid)
            $object = $cbfeeds->get_feed_object($fid);

        if ($cid && $fid)
            $object = get_comment_object($cid);

        $mentions = get_mentions($message);
        if ($mentions)
        {
            foreach ($mentions as $mention)
            {
                db_insert(tbl('user_mentions'), array(
                    'userid' => userid(),
                    'who_id' => $mention['id'],
                    'who_type' => $mention['type'],
                    'feed_id' => $fid,
                    'comment_id' => $cid,
                    'time' => time(),
                    'date_added' => now()
                ));

                if (!$cid && $fid)
                {
                    //Adding subscriptions..
                    $cbfeeds->addSubscription(array(
                        'id' => $fid,
                        'type' => 'feed_mention',
                        'userid' => $mention['id']
                    ));
                }

                $notification_action = 'feed_mention';
                if ($cid)
                    $notification_action = 'comment_mention';

                $cbfeeds->addNotification(
                        array(
                            'action' => $notification_action,
                            'feed_id' => $fid,
                            'comment_id' => $cid,
                            'object_id' => $object['object_id'],
                            'object_type' => $object['object_type'],
                            'object' => $object['object'],
                            'actor_id' => userid(),
                            'userid' => $mention['id'],
                            'actor' => get_content('user', $userquery->udetails)
                        )
                );
            }

            return true;
        }
    }

    return false;
}

/**
 * Create notification phrase
 */
function create_notification_phrase($notification)
{
    $phrases = array(
        'feed_mention'      => '{actor} {action} you in a post',
        'comment_mention'   => '{actor} {action} you in a comment',
        'commented_post'    => '{actor} has commented on your post',
        'liked_post'        => '{actor} has liked your post',
    );

    //Add filters and actions to extend phrases
    $action = $notification['action'];
    $actor = $notification['actor'];
    if (!is_array($actor))
        $actor = json_decode($actor, true);


    $actor_name = get_actor_name($actor);
    $action_done = get_real_action($action);

    $the_phrase = $phrases[$action];


    /**
     * {actor} {action} {object}
     */
    $phrase = str_replace(
            array('{actor}', 
            '{action}', 
            '{object}'), 
            array(actor_name_wrap($actor_name), $action_done, $object_name), $the_phrase);

    return $phrase;
}

function actor_name_wrap($name)
{
    return '<span class="actor_name">'.$name.'</span>';
}

function get_actor_name($actor)
{
    //Most of the time, actor is an a user
    //But it can be some OBJECT tooo
    //For now we will use USER as an actor
    //Later we will exten this function 
    //So that more functions can be used
    //to identify the actor name
    $name = '';

    if ($actor['full_name'])
        $name = $actor['full_name'];

    if (!$name)
        $name = $actor['username'];

    if (!$name)
        $name = $actor['title'];

    if (!$name)
        $name = lang('Someone');

    return $name;
}

function get_real_action($action)
{
    $human_actions = array(
        'feed_mention' => 'has mentioned',
        'comment_mention' => 'has mentioned'
    );

    $the_action = $human_actions[$action];
    return $the_action;
}

/**
 *  Get actor thumb, by default it works very good with user
 * but actor can system be itself so in that case
 * different thumb will be displayed.
 * @global type $userquery
 * @param type $actor
 */
function get_actor_thumb($actor)
{
    global $userquery;
    if ($actor['userid'] && $actor['username'])
    {
        return $userquery->avatar($actor);
    }
}

/**
 * Get notification link
 * 
 */
function get_notification_link($notification)
{
    $elements = $notification['elements'];
    $object_type = $elements['object_type'];
    $object = $elements['object'];
    
    
    if($object['on'])
    {
        $object = $object['on'];
        $object_type = $object['type'];
    }
    
    $content_link = get_content_link($object_type, $object);
    
    //Appending Feed ID...
    if ($notification['feed_id'])
        $content_link .= '#feed_id=' . $notification['feed_id'];

    //Appending Comment ID
    if ($elements['comment_id'])
        $content_link .= '|cid=' . $elements['comment_id'];
    
    return $content_link;
}

/**
 * Get notifications icon
 */
function get_notify_icon($notify)
{
    $actions = array(
        'added_video'       => 'icon-film',
        
        'liked_video'       => 'icon-thumbs-up',
        'shared_video'      => 'icon-share-alt',
        'commented_video'   => 'icon-comment',
        'favorited_video'   => 'icon-star',
        
        'added_photo'       => 'icon-picture',
        'liked_photo'       => 'icon-thumbs-up',
        'shared_photo'      => 'icon-share-alt',
        'commented_photo'   => 'icon-comment',
        'favorited_photo'   => 'icon-star',
        
        'added_group'       => 'icon-th-large',
        'invited_group'     => 'icon-th-large',
        'joined_group'      => 'icon-th-large',
        'favorited_group'   => 'icon-th-large',
        
        'added_friend'      => 'icon-user',
        'accepted_friend'   => 'icon-user',
        'commented_channel' => 'icon-user',
        'signup'            => 'icon-user',
        
        'added_status'      => 'icon-comment',
        'commented_status'  => 'icon-comment',
        'commented_post'    => 'icon-comment',
        'liked_status'      => 'icon-thumbs-up',
        
        'feed_mention'      => 'icon-comment',
        'comment_mention'   => 'icon-comment',
        
        'added_album'       => 'icon-camera',
        'added_playlist'    => 'icon-camera',
        
        'commented'         => 'icon-comment',
        'liked_post'        => 'icon-thumbs-up',
        'favorited'         => 'icon-camera',
        'feed'              => 'icon-star',
    );
    
    
    /** @todo Apply actions and filters */
    $action = $notify['action'];
    return $actions[$action];
}

?>
