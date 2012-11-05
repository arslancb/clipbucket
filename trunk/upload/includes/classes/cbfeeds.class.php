<?php

/**
 * This file is used
 * to create user feeds
 * probably, identicaly to, F*CBOOK :D
 */
class cbfeeds
{

    /**
     * Function used to create a user feed
     * @param array
     * uid => userid
     * udetails => array of user details
     * limit => timelimit of feeds (1 day, 2 days and so)
     * 
     */
    function createFeed($array)
    {
        global $userquery;
        $uid = $array['uid'];

        if (!$uid)
            e(lang("please_provide_valid_userid"));
        elseif (!is_array($user))
        {
            $user = $userquery->get_user_details($uid);
        }
    }

    /**
     * Function used to add feed in user feed file
     * @param array
     * action => upload,joined,comment,created
     * object => video, photo, group
     * object_id => id of object
     * object_details => details of object
     * uid => user id
     * udetails => user details array
     * 
     * @deprecated v3.0
     */
    function addFeed($feed)
    {
        if (!isSectionEnabled('feeds'))
            return false;
        global $userquery;
        $uid = $feed['uid'];

        if (!$uid)
            return false;

        $ufeed = array();

        //Verifying feed action and object
        $action = $this->action($feed['action']);
        $object = $this->getObject($feed['object']);


        if (!$action || !$object)
            return false;

        //Setting user feed array
        $ufeed['action'] = $action;
        $ufeed['object'] = $object;
        $ufeed['object_id'] = $feed['object_id'];
        $ufeed['userid'] = $uid;
        $ufeed['time'] = time();

        //Unsetting feed array
        unset($feed);

        //Getting user feed file
        $feedFile = $this->getFeedFile($uid);

        //Convering feed using json
        $feed = json_encode($ufeed);
        //Creating unique md5 of feed
        $feedmd5 = md5($feed);
        $ufeed['md5'] = $feedmd5;
        //Recreating Feed
        $feed = json_encode($ufeed);

        if ($feedFile)
        {
            //Appending feed in a file	
            $file = fopen($feedFile, 'a+');
            fwrite($file, $feed);
            fclose($file);
        }

        //Tada <{^-^}>
    }

    /**
     * Adding feed in database...
     */
    function add_feed($array)
    {
        $valid_feed_items = array
            (
            'userid', 'user',
            'content_id', 'content', 'content_type',
            'object_id', 'object', 'object_type',
            'message', 'message_attributes',
            'icon', 'action', 'action_group_id',
            'is_activity'
        );

        $new_array = array();
        foreach ($valid_feed_items as $item)
        {
            $new_array[$item] = $array[$item];
        }

        unset($array);

        $array = $new_array;

        if (!isSectionEnabled('feeds'))
            return false;

        global $userquery;

        $uid = $array['userid'];

        if (!$uid)
            return false;



        //Verifying feed action and object
        $action = $this->action($array['action']);


        $object = $this->getObject($array['object_type']);

        
        if (!$action || !$object)
            return false;


        //Setting user feed array
        $feed = $array;

        //Setting content
        $feed['content'] = apply_filters($feed['content'], 'add_feed_content');
        $feed['content'] = json_encode($feed['content']);

        //Setting feed object
        $feed['object'] = apply_filters($feed['object'], 'add_feed_object');
        $feed['object'] = json_encode($feed['object']);

        //Feed message attributes
        $feed['message_attributes'] = apply_filters($feed['message_attributes'], 'add_feed_message_attributes');
        $feed['message_attributes'] = json_encode($feed['message_attributes']);

        //Feed User
        $user_fields = array(
            'username', 'email', 'userid', 'fullname',
            'total_videos', 'doj', 'dob'
        );

        $user_fields = apply_filters($user_fields, 'feed_user_fields');

        $user = array();
        foreach ($user_fields as $field)
            $user[$field] = $feed['user'][$field];

        $feed['user'] = apply_filters($user, 'add_feed_user');
        $feed['user'] = json_encode($user);

        $feed['date_added'] = now();
        $feed['last_updated'] = time();
        $feed['time'] = time();
        
        if(!$this->feed_exists($feed))
            db_insert(tbl('feeds'), $feed);

        return $feed;
    }

    /**
     * Function used to get action of feed
     * it will verify weather actio is valid or not
     */
    function action($action)
    {


        $objects = array
            ('signup', 'upload_video', 'upload_photo', 'create_group',
            'join_group', 'add_friend', 'add_collection', 'add_playlist',
            'add_comment', 'add_favorite', 'share_video', 'post_message',
            'like_video', 'like_photo', 'like_post','feed');

        if (!in_array($action, $objects))
            return false;
        else
            return $action;
    }

    /**
     * Function used to get object of feed
     * it will verify weather actio is valid or not
     */
    function getObject($object)
    {
        $objects = array
            ('signup', 'video', 'photo', 'group',
            'user', 'friend', 'collection');

        if (!in_array($object, $objects))
            return false;
        else
            return $object;
    }

    /**
     * Function used to get feed file
     */
    function getFeedFile($uid)
    {
        $time = time();
        $ufeedDir = USER_FEEDS_DIR . '/' . $uid;
        //checking user feed folder exists or not
        if (!file_exists($ufeedDir))
            mkdir($ufeedDir);
        $file = $ufeedDir . '/' . $time . '.feed';
        return $file;
    }

    /**
     * Function used to get user feed files
     */
    function getUserFeedsFiles($uid = NULL)
    {
        if (!$uid)
            $uid = userid();

        $feeds = array();
        $ufeedDir = USER_FEEDS_DIR . '/' . $uid;
        if (file_exists($ufeedDir))
        {
            $time = time();
            $time = substr($timem, 0, strlen($time) - 3);

            $files = glob($ufeedDir . '/' . $time . '*.feed');
            rsort($files);
            foreach ($files as $file)
            {
                $feed['content'] = file_get_contents($file);
                $feed['file'] = $file;
                $feeds[] = $feed;
            }

            return $feeds;
        }
        return false;
    }

    /**
     * Function used to get user feed
     */
    function getUserFeeds($user)
    {
        global $cbphoto, $userquery, $cbvid, $cbgroup, $cbcollection;
        $allowed_feeds = USER_ACTIVITY_FEEDS_LIMIT;
        $uid = $user['userid'];
        $feeds = $this->getUserFeedsFiles($uid);

        if (!$feeds)
            return false;
        $newFeeds = array();
        $coutn = 0;
        foreach ($feeds as $feed)
        {
            $count++;

            if ($count > $allowed_feeds)
                break;
            $feedArray = json_decode($feed['content'], true);
            if ($feed && count($feedArray > 0))
            {
                $remove_feed = false;
                $farr = $feedArray;

                $action = $farr['action'];
                $object = $farr['object'];
                $object_id = $farr['object_id'];
                $farr['user'] = $user;
                $farr['file'] = getName($feed['file']);
                $farr['datetime'] = nicetime($farr['time'], true);
                $userlink = '<a href="' . $userquery->profile_link($user) . '">' . $user['username'] . '</a>';
                //Creating Links
                switch ($action)
                {
                    case "upload_photo":
                        {
                            $photo = $cbphoto->get_photo($object_id);

                            //If photo does not exists, simply remove the feed
                            if (!$photo)
                            {
                                $this->deleteFeed($uid, $feed['file']);
                                $remove_feed = true;
                            }
                            else
                            {
                                $objectArr['details'] = $photo;
                                $objectArr['size'] = 't';
                                $objectArr['output'] = 'non_html';
                                $objectArr['alt'] = $photo['photo_title'];
                                $farr['thumb'] = $cbphoto->getFileSmarty($objectArr);
                                $farr['link'] = $cbphoto->photo_links($photo, 'view_item');

                                //Content Title
                                $farr['title'] = $photo['photo_title'];
                                $farr['action_title']
                                        = sprintf(lang('user_has_uploaded_new_photo'), $userlink);

                                $farr['links'][] = array('link' => ($cbphoto->photo_links($photo, 'view_item')), 'text' => lang('view_photo'));

                                $farr['icon'] = 'images.png';
                            }
                        }
                        break;

                    case "upload_video":
                    case "add_favorite":
                        {
                            $video = $cbvid->get_video($object_id);
                            //If photo does not exists, simply remove the feed
                            if (!$video)
                            {
                                $this->deleteFeed($uid, $feed['file']);
                                $remove_feed = true;
                            }
                            elseif (!video_playable($video))
                            {
                                $remove_feed = true;
                            }
                            else
                            {

                                //Content Title
                                $farr['title'] = $video['title'];
                                if ($action == 'upload_video')
                                    $farr['action_title'] = sprintf(lang('user_has_uploaded_new_video'), $userlink);
                                if ($action == 'add_favorite')
                                    $farr['action_title'] = sprintf(lang('user_has_favorited_video'), $userlink);
                                $farr['link'] = videoLink($video);
                                $farr['object_content'] = $video['description'];
                                $farr['thumb'] = get_thumb($video);

                                $farr['links'][] = array('link' => videoLink($video), 'text' => lang('watch_video'));

                                $farr['icon'] = 'video.png';

                                if ($action == 'add_favorite')
                                    $farr['icon'] = 'heart.png';
                            }
                        }
                        break;


                    case "create_group":
                    case "join_group":
                        {
                            $group = $cbgroup->get_group($object_id);
                            //If photo does not exists, simply remove the feed
                            if (!$group)
                            {
                                $this->deleteFeed($uid, $feed['file']);
                                $remove_feed = true;
                            }
                            elseif (!$cbgroup->is_viewable($group))
                            {
                                $remove_feed = true;
                            }
                            else
                            {

                                //Content Title
                                $farr['title'] = $group['group_name'];

                                if ($action == 'create_group')
                                    $farr['action_title'] = sprintf(lang('user_has_created_new_group'), $userlink);
                                if ($action == 'join_group')
                                    $farr['action_title'] = sprintf(lang('user_has_joined_group'), $userlink);

                                $farr['link'] = group_link(array('details' => $group));
                                $farr['object_content'] =
                                        $group['group_description'] . "<br>" .
                                        lang('total_members') . " : " . $group['total_members'] . "<br>" .
                                        lang('total_videos') . " : " . $group['total_videos'] . "<br>" .
                                        lang('total_topics') . " : " . $group['total_topics'] . "<br>";

                                $farr['thumb'] = $cbgroup->get_group_thumb($group);
                                $farr['icon'] = 'group.png';

                                $joinlink = $cbgroup->group_opt_link($group, 'join');
                                if ($joinlink)
                                {
                                    if (SEO == "yes")
                                        $joinlink = group_link(array('details' => $group)) . '?join=yes"';
                                    else
                                        $joinlink = group_link(array('details' => $group)) . '&join=yes"';
                                    $farr['links'][] = array('link' => $joinlink, 'text' => lang('join'));
                                }
                            }
                        }
                        break;

                    case "signup":
                        {
                            $farr['action_title'] = sprintf(lang("user_joined_us"), $userlink, TITLE, $userlink);
                            $farr['icon'] = 'user.png';
                        }

                        break;
                    case "add_friend":
                        {
                            $friend = $userquery->get_user_details($object_id);

                            if (!$friend)
                            {
                                $this->deleteFeed($uid, $feed['file']);
                                $remove_feed = true;
                            }
                            else
                            {

                                $friendlink = '<a href="' . $userquery->profile_link($friend) . '">' . $friend['username'] . '</a>';
                                $farr['action_title'] = sprintf(lang("user_is_now_friend_with_other")
                                        , $userlink, $friendlink);
                                $farr['icon'] = 'user_add.png';
                            }
                        }
                        break;


                    case "add_collection":
                        {
                            $collection = $cbcollection->get_collection($object_id);
                            if (!$collection)
                            {
                                $this->deleteFeed($uid, $feed['file']);
                                $remove_feed = true;
                            }
                            else
                            {
                                $farr['action_title'] = sprintf(lang('user_has_created_new_collection'), $userlink);
                                $farr['thumb'] = $cbcollection->get_thumb($collection, 'small');
                                $farr['title'] = $collection['collection_name'];
                                $collection_link = $cbcollection->collection_links($collection, 'view');
                                $farr['link'] = $collection_link;

                                $farr['object_content'] =
                                        $collection['collection_description'] . '<br>' .
                                        $collection['total_objects'] . " " . $collection['type'];
                                $farr['icon'] = 'photos.png';

                                $farr['links'][] = array('link' => $collection_link, 'text' => lang('view_collection'));
                            }
                        }
                }

                if (!$remove_feed)
                    $newFeeds[$feedArray['time']] = $farr;
            }
        }
        return $newFeeds;
    }

    /**
     * Function used to delete feed
     */
    function deleteFeed($uid, $feedid)
    {
        $ufeedDir = USER_FEEDS_DIR . '/' . $uid . '/' . getName($feedid) . '.feed';
        if (file_exists($ufeedDir))
            unlink($ufeedDir);
    }

    /**
     * function create content
     * 
     * @param STRING type
     * @param STRING id
     * @param ARRAY content 
     */
    function get_content($type, $id, $content = NULL)
    {
        $the_content = null;

        global $cbvid, $cbphoto, $cbgroup;

        switch ($type)
        {
            case "v":
            case "video":
                {
                    $the_content = $cbvid->get_content($id, $content = NULL);
                }
        }

        $action_array = array('type' => $type, $id => $id, 'content' => $content);

        if (!$the_content)
            $the_content = call_actions('get_feed_content', $action_array);

        if ($the_content)
        {
            $the_content = apply_filters($the_content, 'get_feed_content');
        }else
            return false;

        return $the_content;
    }

    /**
     * Delete a feed
     * @param ARRAY conditons
     */
    function delete_feed($array)
    {
        $cond = array();

        $valid_array = array('feed_id', 'content_id', 'content_type',
            'object_id', 'object_type', 'userid');

        $valid_array = apply_filters($valid_array, 'delete_feed_array');

        $the_array = array();
        foreach ($valid_array as $arr)
            $the_array[$arr] = $array[$arr];

        extract($the_array);

        if ($feed_id)
            $cond[] = "feed_id='$feed_id'";
        if ($content_id)
            $cond[] = "content_id='$content_id'";
        if ($object_id)
            $cond[] = "object_id='$object_id'";
        if ($content_type)
            ;
        $cond[] = "content_type='$content_type'";
        if ($object_type)
            $cond[] = "object_type='$object_type'";
        if ($userid)
            $cond[] = "userid='$userid'";


        $cond_query = "";

        foreach ($cond as $c)
        {
            if ($cond_query)
                $cond_query .= " AND ";

            $cond_query .= " " . $c;
        }

        global $db;

        $db->Execute("DELETE from " . tbl('feeds') . " WHERE " . $cond_query);

        return true;
    }

    /**
     * Get feed likes
     * 
     */
    function get_likes($feed_id)
    {
        $result = db_select("SELECT likes,likes_count FROM " . tbl('feeds')
                . " WHERE feed_id='$feed_id' ");

        if ($result)
            return $result[0];
    }

    /**
     * function used to add an activity...
     */
    function like_feed($array)
    {
        global $userquery;
        
        if(!userid()) return false;
        
        $likes_data = $this->get_likes($array['feed_id']);
        $likes = json_decode($likes_data['likes'], true);
        $feed_id = $array['feed_id'];

        if ($array['liked'] == 'yes')
        {
            $likes_fields = array(
                'userid', 'username', 'fullname',
            );

            $user_fields = array();

            foreach ($likes_fields as $like_field)
                $user_fields[$like_field] = $userquery->udetails[$like_field];

            $likes[$userquery->udetails['userid']] = $user_fields;
        }
        else
        {
            unset($likes[$userquery->udetails['userid']]);
        }

        $total_likes = count($likes);
        
        db_update(tbl('feeds'), array(
        'likes_count' => $total_likes,
        'likes' => json_encode($likes)
        ), " feed_id='$feed_id' ");


        /*

          $like_array = array(
          'userid' => userid(),
          'type' => 'feed',
          'object_id' => $id,
          );

          ?if ($rating > 1)
          {
          $myquery->add_like($like_array);
          }
          else
          {
          $myquery->add_dislike($like_array);
          } */

        if (isSectionEnabled('feeds'))
        {

            $feed = $this->get_feed($feed_id);

            if ($array['liked'] == 'yes')
            {
                $object = $feed['object'];
                $object = json_decode($object, true);
                $type = $feed['object_type'];
                $id = $feed['object_id'];

                if ($feed['content'])
                {
                    $content = $feed['content'];

                    if ($content)
                        $content = json_decode($content, true);

                    $content['on'] = $object;
                    $content['on']['type'] = $type;
                    $content['on']['id'] = $id;

                    $object = $content;
                    $type = $feed['content_type'];
                    $id = $feed['content_id'];
                }

                $user = $userquery->udetails;
                $feed_array = array(
                    'userid' => userid(),
                    'message' => $feed['message'],
                    'user' => $user,
                    'object' => $object,
                    'object_id' => $id,
                    'object_type' => $type,
                    'content_id' => $feed['feed_id'],
                    'content_type' => $feed['action'],
                    'is_activity' => 'yes',
                    'action' => 'like_post',
                );
                

                $this->add_feed($feed_array);
            }
            else
            {
                $feed_array = array('feed_id' => $feed['feed_id'],
                'userid'=>userid(),
                'action'=>'like_post');
                $this->delete_feed($feed_array);
                
            }
        }
        
        $result = array(
            'total_likes'   => $total_likes,
            'likes_count'   => $total_likes,
            'likes'         => $likes,
            'liked'         => $array['liked'],
        );
        
        return $result;
    }

    /**
     * get feed from feed_id
     * 
     * @param INT $feed
     * @reutn ARRAY $feed
     */
    function get_feed($fid)
    {
        $result = db_select("SELECT * FROM " . tbl('feeds') . " WHERE feed_id='$fid' ");

        global $db;

        if ($db->num_rows > 0)
            return $result[0];
        else
            return false;
    }
    
    /**
     * feed exists
     * 
     * @param ARRAY $feed_feeds
     * @return BOOLEAN
     */
    function feed_exists($feed_fields)
    {
        $valid_fields = array(
            'feed_id','userid','action','content_id','object_id','object_type',
            'content_type','is_activity','message'
        );
        
        $query_fields = array();
        
        foreach($valid_fields as $field)
        {
            if($feed_fields[$field])
            $query_fields[$field] = $feed_fields[$field];
        }
        
        $condition = "";
        
        foreach($query_fields as $field => $value)
        {
            
            if($condition)
                $condition .= ' AND ';
            $condition .= " ".$field."='$value' ";
        }
        
        global $db;
        $result = db_select("SELECT * FROM ".tbl('feeds')." WHERE ".$condition);
        
        if($db->num_rows>0)
            return $result;
        else
            return false;
        
    }
    
    /**
     * function used to chek if feed is liked or not by the logged in user
     * 
     * @param ARRA $feed Like
     * @return BOOLEAN
     */
    function is_liked($likes)
    {
        $userid = userid();
        
        if($likes[$userid] && userid())
            return true;
    }
    
    
    /**
     * Make a comment on a feed
     * 
     * @param INT | ARRAY $feed
     * @param STRING $comment
     */
    /**
     * Function used to add video comment
     */
    function add_comment($comment, $object, $reply_to = NULL, $force_name_email = false) {
        global $myquery, $db;
        
        
        if(is_array($object))
            $feed = $object;
        else
            $feed = $this->get_feed($object);
        
        $obj_id = $feed['feed_id'];
        
        if (!$feed)
            e(lang("Feed does not exist"));
        else {
            //Getting Owner Id
            $owner_id = $feed['userid'];
            $add_comment = $myquery->add_comment($comment, $obj_id, $reply_to, 'f', $owner_id,NULL, $force_name_email);
            
            if($add_comment):
            /*if ($add_comment) {
                //Updating Number of comments of video
                $this->update_comments_count($obj_id);
            }*/
            
            $comments = $feed['comments'];
            $comments = json_decode($comments,true);
            
            
            $userfields = array(
                'username','email',
                'fullname','first_name','last_name',
                'avatar','avatar_url'
            );
            
            $new_comment = array(
                'comment_id' => $add_comment,
                'userid'     => userid(),
                'comment'    => $comment,
                'vote'       => 0,
                'date_added' => now(),
            );
            
            global $userquery;
            foreach($userfields as $field)
                $new_comment[$field] = $userquery->udetails[$field];
            

            $comments[] = $new_comment;
            $total_comments = $feed['comments_count'] + 1;
            
            db_update(tbl('feeds'),array(
            'comments_count'    => $total_comments,
            'comments'          => json_encode($comments)
            )," feed_id='$obj_id' ");
            
            return $add_comment;
            
            endif;
        }
    }
    
    /**
     * Function used to update feed comments count
     */
    function update_comments_count($id) {
        global $db;
        $total_comments = $this->count_feed_comments($id);
        $db->update(tbl("feeds"), array("comments_count", "last_commented"), array($total_comments, now()), " feed_id='$id'");
    }
    
    /**
     * Function used to count feed video comments
     */
    function count_feed_comments($id) {
        global $db;
        $total_comments = $db->count(tbl('comments'), "comment_id", "type='f' AND type_id='$id'");
        return $total_comments;
    }
    
    
    /**
     * function to get feeds
     */
    function get_feeds($array)
    {
        $type = $array['type'];
        $id = $array['id'];

        $results = db_select("SELECT * FROM ".tbl('feeds')
        ." WHERE object_type='".$type."' AND object_id='$id' ");
        
        
        return $results;
    }
}


?>