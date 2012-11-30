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
    if ($message && ($fid || $cid))
    {
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
            }

            return true;
        }
    }

    return false;
}

?>
