<?php

function get_comments($array = NULL)
{
    $configs = array(
        'order' => 'date_added DESC',
        'get_children' => true
    );

    $configs = array_merge($configs, $array);

    $valid_configs = array(
        'type_id', 'limit', 'type', 'get_children',
        'only_parents', 'parent_id', 'order',
        'get_children'
    );

    $the_configs = array();
    foreach ($valid_configs as $config)
    {
        $the_configs[$config] = $configs[$config];
    }

    extract($the_configs);

    $type_id = mysql_clean($type_id);
    $type = mysql_clean($type);
    $limit = mysql_clean($limit);
    $order = mysql_clean($order);
    $parent_id = mysql_clean($parent_id);
    $userid = mysql_clean($userid);

    //List of user fields we need to show with the comment
    $userfields = array('username', 'email', 'userid',
        'avatar', 'avatar_url');

    //Applying filters...
    $userfields = apply_filters($userfields, 'comment_user_fields');

    $ufields = '';
    foreach ($userfields as $userfield)
    {
        $ufields .= ',';
        $ufields .= tbl('users.' . $userfield);
    }

    $query = "SELECT " . tbl('comments.*') . $ufields . " FROM " . tbl('comments');
    $query .= " LEFT JOIN " . tbl('users') . " ON " . tbl('comments.userid');
    $query .= " = " . tbl('users.userid');

    start_where();

    if ($type)
        add_where("type='" . $type . "'");

    if ($type_id)
        add_where("type_id='" . $type_id . "'");


    if ($parent_id && !$only_parents)
        add_where("parent_id='$parent_id'");

    if ($userid)
        add_where("userid='$userid' ");

    if ($only_parents)
        add_where("parent_id='0'");

    if (get_where())
        $query .= " WHERE " . get_where();

    end_where();


    if ($order)
        $query .= " ORDER BY  " . $order;

    if ($limit)
        $query .= " LIMIT " . $limit;

    $comments = db_select($query);

    $the_comments = array();
    if ($comments)
    {
        foreach ($comments as $comment)
        {
            if ($comment['get_children'] && $comment['has_children'])
            {
                $child_array = array(
                    'parent_id' => $comment['comment_id'],
                    'type' => $array['type'],
                    'type_id' => $array['type_id']
                );

                $children = get_comments($child_array);

                if ($children)
                    $comment['children'] = $children;
            }
            $the_comments[] = $comment;
        }
    }
    return $the_comments;
}


/**
 * This file contains Smarty modifiers that will be applied in templates
 * You just need to define a function that can be used ClipBucket
 * and then you have to assign that function accordingly
 * For more information please visit http://docs.clip-bucket.com/
 * Author : Arslan Hassan
 * ClipBucket v 2.0
 */

/**
 * Function used to modify comment, if there is any plugin installed
 * @param : comment
 */
function comment($comment_arr)
{
    global $Cbucket;
    
    if(is_array($comment_arr))
    {
        $the_comment = $comment_arr['comment'];
        $attributes = json_decode($comment_arr['comment_attributes'], true);
        
        $comment = apply_mentions($the_comment, $attributes);
    }else
        $comment = $comment_arr;
    
    $comment = nl2br($comment);
    //Getting List of comment functions
    $func_list = $Cbucket->getFunctionList('comment');
    //Applying Function
    if (count($func_list) > 0)
    {
        foreach ($func_list as $func)
        {
            $comment = $func($comment);
        }
    }
    return $comment;
}

?>
