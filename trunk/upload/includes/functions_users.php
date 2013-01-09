<?php

/*
 * $Id$
 * 
 * @Since : 12/7/2011 - 2.7
 * 
 * All users related functions
 */

/**
 * Function used to get number of videos uploaded by user
 * @param INT userid
 * @param Conditions
 */
function get_user_vids($uid, $cond = NULL, $count_only = false)
{
    global $userquery;
    return $userquery->get_user_vids($uid, $cond, $count_only);
}

/**
 * Function used to return level name 
 * @param levelid
 */
function get_user_level($id)
{
    global $userquery;
    return $userquery->usr_levels[$id];
}

/**
 * function used to get vidos
 */
function get_users($param)
{
    global $userquery;
    return $userquery->get_users($param);
}

/**
 * Function used to check weather username already exists or not
 * @input USERNAME
 */
function user_exists($user)
{
    global $userquery;
    return $userquery->user_exists($user);
}

/**
 * Function used to validate username
 * @input USERNAME
 */
function username_check($username)
{
    global $Cbucket;
    $banned_words = $Cbucket->configs['disallowed_usernames'];
    $banned_words = explode(',', $banned_words);
    foreach ($banned_words as $word)
    {
        preg_match("/$word/Ui", $username, $match);
        if (!empty($match[0]))
            return false;
    }
    //Checking if its syntax is valid or not
    $multi = config('allow_unicode_usernames');

    //Checking Spaces
    if (!config('allow_username_spaces'))
        preg_match('/ /', $username, $matches);
    if (!is_valid_syntax('username', $username) && $multi != 'yes' || $matches)
        e(lang("class_invalid_user"));
    return true;
}

/**
 * FUnction used to get username from userid
 */
function get_username($uid)
{
    global $userquery;
    return $userquery->get_username($uid, 'username');
}

/**
 * Function used to get userid anywhere 
 * if there is no user_id it will return false
 */
function user_id()
{
    global $userquery;
    if ($userquery->userid != '' && $userquery->is_login)
        return $userquery->userid; else
        false;
}

//alias
function userid()
{
    return user_id();
}

/**
 * Function used to get username anywhere 
 * if there is no usern_name it will return false
 */
function user_name()
{
    global $userquery;
    if ($userquery->user_name)
        return $userquery->user_name;
    else
        return $userquery->get_logged_username();
}

function username()
{
    return user_name();
}

/**
 * Function used to check weather user access or not
 */
function has_access($access, $check_only = TRUE, $verify_logged_user = true)
{
    global $userquery;

    return $userquery->login_check($access, $check_only, $verify_logged_user);
}

/**
 * Function used to get user avatar
 * @param ARRAY $userdetail
 * @param SIZE $int
 */
function avatar($param)
{
    global $userquery;
    $udetails = $param['details'];
    $size = $param['size'];
    $uid = $param['uid'];
    return $userquery->avatar($udetails, $size, $uid);
}

/**
 * Function used to check weather email already exists or not
 * @input email
 */
function email_exists($user)
{
    global $userquery;
    return $userquery->duplicate_email($user);
}

/**
 * Function used to count age from date
 */
function get_age($input)
{
    $time = strtotime($input);
    $iMonth = date("m", $time);
    $iDay = date("d", $time);
    $iYear = date("Y", $time);

    $iTimeStamp = (mktime() - 86400) - mktime(0, 0, 0, $iMonth, $iDay, $iYear);
    $iDays = $iTimeStamp / 86400;
    $iYears = floor($iDays / 365);
    return $iYears;
}

function update_user_voted($array, $userid = NULL)
{
    global $userquery;
    return $userquery->update_user_voted($array, $userid);
}

/**
 * Function used to check username is disallowed or not
 * @param USERNAME
 */
function check_disallowed_user($username)
{
    global $Cbucket;
    $disallowed_user = $Cbucket->configs['disallowed_usernames'];
    $censor_users = explode(',', $disallowed_user);
    if (in_array($username, $censor_users))
        return false;
    else
        return true;
}

/**
 * get user menu links
 * 
 * these links are listed on user menu found on right top on default template
 * you can move the menu however.
 * 
 * @param NULL
 * @return Array of links
 */
function get_user_menu_links()
{
    $array = array(
        'videos' =>
        array('name' => lang('Videos'), 'link' => cblink(array('name' => 'videos')))
    );

    $new_array = apply_filters($array, 'user_menu_links');

    return $new_array;
}

function cb_upload_avatar($file, $uid = null)
{
    global $db, $userquery;
    if (is_null($uid))
    {
        $uid = userid();
    }
    if (!$uid)
    {
        return false;
    }

    $user = $userquery->get_user_details($uid);
    if ($user)
    {
        $collection = $user['avatar_collection'];
    }
    else
    {
        return false;
    }
}

function upload_new_avatar($file, $uid)
{
    global $userquery, $cbphoto, $cbcollection, $db;
    $size = $file['size'];
    $user = $userquery->get_user_details($uid);
    $avatar_dir = BASEDIR . '/images/avatars/';

    if ($user)
    {
        if ($file['size'] / 1024 > config('max_profile_pic_size'))
        {
            e(sprintf(lang('file_size_exceeds'), config('max_profile_pic_size')));
        }
        elseif (file_exists($file['tmp_name']))
        {
            $ext = getext($file['name']);
            $filename = cb_filename();
            $photopath = PHOTOS_DIR . '/' . createDataFolders(PHOTOS_DIR) . '/';
            if (validate_image_file($file['tmp_name'], $ext))
            {
                $cid = cb_create_user_avatar_collection($user);

                if (move_uploaded_file($file['tmp_name'], $photopath . $filename . '.' . $ext))
                {
                    $fields = array(
                        'photo_title' => 'Avatar',
                        'photo_description' => ' ',
                        'photo_tags' => ' ',
                        'filename' => $filename,
                        'userid' => $uid,
                        'ext' => $ext,
                        'is_avatar' => true,
                        'collection_id' => $cid
                    );

                    $photo_id = $cbphoto->insert_photo($fields);
                    $avatar = $uid . '_' . $filename . '.' . $ext;
                    $avatarpath = $avatar_dir . $avatar;

                    /* Resizing starts here */
                    $r = new CB_Resizer($photopath . $filename . '.' . $ext);
                    /* Big Thumb */
                    $r->target = USER_THUMBS_DIR . '/' . $avatar;
                    $r->_resize(AVATAR_SIZE, AVATAR_SIZE);
                    $r->save();

                    /* Small Thumb */
                    $r->target = USER_THUMBS_DIR . '/' . $uid . '_' . $filename . '-small.' . $ext;
                    $r->_resize(AVATAR_SMALL_SIZE, AVATAR_SMALL_SIZE);
                    $r->save();
                    /* Resizing ends here */

                    /* Update user avatar field */
                    $db->update(tbl('users'), array('avatar'), array($avatar), " userid = '" . $uid . "' ");
                    /* Update cover photo of collection */
                    $cbcollection->set_cover_photo($photo_id, $cid);

                    return $avatar;
                }
                else
                {
                    e(lang('Unable to upload file. Please try again'));
                    if (file_exists($file['tmp_name']))
                    {
                        unlink($file['tmp_name']);
                    }
                    return false;
                }
            }
            else
            {
                e(lang('Invalid File Type'));
                return false;
            }
        }
    }
    else
    {
        e(lang('user_doesnt_exist'));
        return false;
    }
}

function make_new_avatar($array = null)
{
    global $cbphoto, $userquery, $photo, $db, $cbcollection;

    if (is_null($array))
    {
        $array = $_POST;
    }
    /* include resizer class */
    include_once 'includes/classes/resizer.class.php';
    $date_dir = get_photo_date_folder($photo);
    /* get original photo */
    $p = get_original_photo($photo);
    /* define source and decode photo details */
    $source = PHOTOS_DIR . '/' . $date_dir . '/' . $p;
    $pd = json_decode($photo['photo_details'], true);
    /* coordinates */
    $x = mysql_clean($array['start_x']);
    $x2 = mysql_clean($array['end_x']);
    $y = mysql_clean($array['start_y']);
    $y2 = mysql_clean($array['end_y']);

    if ((!is_numeric($x) || !is_numeric($x2) || !is_numeric($y) || !is_numeric($y2)))
    {
        e('Unable to crop. Coordinates were unrealiable.');
    }
    else
    {
        /*
         * We will be using the original photo to crop the avatar.
         * First we'll covert the posted pixels to percentage
         * Second get pixels from original photo using percentage
         * Using newly computed pixels crop from original photo
         * Save it in avatar collection, make it source and start making avatars
         */

        $xx = ( ( $x / $pd['l']['width'] ) * 100 ); // compute percentage
        $xx2 = ( ( $x2 / $pd['l']['width'] ) * 100 ); // compute percentage
        $newX = ( ( $xx * $pd['o']['width'] ) / 100 ); // compute pixels
        $newX2 = ( ( $xx2 * $pd['o']['width'] ) / 100 ); // compute pixels

        $yy = ( ( $y / $pd['l']['height'] ) * 100 ); // compute percentage
        $yy2 = ( ( $y2 / $pd['l']['height'] ) * 100 ); // compute percentage
        $newY = ( ( $yy * $pd['o']['height'] ) / 100 ); // compute pixels
        $newY2 = ( ( $yy2 * $pd['o']['height'] ) / 100 ); // compute pixels

        /* set source */
        $r = new CB_Resizer($source);

        $filename = cb_filename();
        $photopath = PHOTOS_DIR . '/' . createDataFolders(PHOTOS_DIR);
        $avatar_dir = USER_THUMBS_DIR . '/';
        $uid = $userquery->udetails['userid'];
        $ext = $photo['ext'];
        $cid = cb_create_user_avatar_collection($userquery->udetails);

        /* Save the cropped as original source of photo. */
        $new_photo = $r->target = $photopath . '/' . $filename . "." . $photo['ext'];
        $r->_crop($newX, $newY, $newX2, $newY2);
        $r->save();

        $fields = array(
            'photo_title' => 'Avatar',
            'photo_description' => ' ',
            'photo_tags' => ' ',
            'filename' => $filename,
            'userid' => $uid,
            'ext' => $photo['ext'],
            'is_avatar' => true,
            'collection_id' => $cid
        );

        /* Inserting photo in user's avatar collection */
        $photo_id = $cbphoto->insert_photo($fields);
        $avatar = $uid . '_' . $filename . '.' . $ext;
        $avatarpath = $avatar_dir . $avatar;

        /* Make $new_photo the source */
        $r->source = $r->target;

        /* Big Thumb */
        $r->target = $avatarpath;
        $r->_resize(AVATAR_SIZE, AVATAR_SIZE);
        $r->save();

        /* Small Thumb */
        $r->target = USER_THUMBS_DIR . '/' . $uid . '_' . $filename . '-small.' . $photo['ext'];
        $r->_resize(AVATAR_SMALL_SIZE, AVATAR_SMALL_SIZE);
        $r->save();

        /* Update user avatar field */
        $db->update(tbl('users'), array('avatar'), array($avatar), " userid = '" . $uid . "' ");
        /* Update cover photo of collection */
        $cbcollection->set_cover_photo($photo_id, $cid);
        /* go back to photo */
        redirect_to($cbphoto->photo_links($photo, 'view_photo'));

        /* go to user profile */
        //redirect_to( $userquery->profile_link(userid()) );
    }
}

function cb_user_avatar_collection($uid = null)
{
    global $db, $userquery, $cbcollection;
    if (is_null($uid))
    {
        $uid = userid();
    }

    if (!$uid)
    {
        return false;
    }

    if (is_array($uid))
    {
        $user = $uid;
    }
    else
    {
        $user = $userquery->get_user_details($uid);
    }

    if ($user)
    {
        if ($user['avatar_collection'] != 0)
        {
            $collection = $cbcollection->get_collection($user['avatar_collection'], " AND type = 'photos' ");
            if ($collection)
            {
                return $collection;
            }
        }

        return false;
    }
    else
    {
        return false;
    }
}

/**
 * Create user avatar collection
 */
function cb_create_user_avatar_collection($uid = null)
{
    global $db, $userquery, $cbcollection;

    if (is_null($uid))
    {
        $uid = userid();
    }

    if (!$uid)
    {
        return;
    }

    if (is_array($uid))
    {
        $user = $uid;
    }
    else
    {
        $user = $userquery->get_user_details($uid);
    }

    if (!empty($user))
    {
        if ($user['avatar_collection'] == 0)
        {
            /* User has no avatar collection. Create one */
            $details = array(
                'collection_name' => ( $user['username'] ? $user['username'] : 'User') . ' Avatars',
                'collection_description' => 'Collection of user avatars',
                'collection_tags' => ' ',
                //'category' => array('category', $cbcollection->get_avatar_collection_id() ),
                'category' => '#' . $cbcollection->get_avatar_collection_id() . '#',
                'userid' => $user['userid'],
                'date_added' => NOW(),
                'is_avatar_collection' => 'yes',
                'type' => 'photos',
                'active' => 'yes',
                'public_upload' => 'no',
                'broadcast' => 'public',
                'allow_comments' => 'yes'
            );

            /* Insert collection */
            $insert_id = $db->insert(tbl($cbcollection->section_tbl), array_keys($details), array_values($details));
            if ($insert_id)
            {
                /* update user column avatar_collection */
                $db->update(tbl('users'), array('avatar_collection'), array($insert_id), " userid = '" . $user['userid'] . "' ");
                return $insert_id;
            }
        }
        else
        {
            return $user['avatar_collection'];
        }
    }
}

function delete_photo_avatar($photo)
{
    if ($photo['is_avatar'] == 'yes')
    {
        $dir = USER_THUMBS_DIR . '/';
        $avatar = $photo['userid'] . '_' . $photo['filename'] . '.' . $photo['ext'];
        $avatar_small = $photo['userid'] . '_' . $photo['filename'] . '-small.' . $photo['ext'];
        if (file_exists($dir . $avatar))
        {
            unlink($dir . $avatar);
        }

        if (file_exists($dir . $avatar_small))
        {
            unlink($dir . $avatar_small);
        }
    }
}

/**
 * This function includes the file containing user custom background
 * if uploaded. This code should run after opening html <body> tag.
 * This will spread the background cross the window.
 * 
 * @global OBJECT $userquery
 * @param MIX $uid
 * @return custom_background.html Template
 */
function display_user_custom_background($uid = null)
{
    global $userquery;

    if (is_null($uid))
    {
        return false;
    }

    if (is_numeric($uid))
    {
        $user = $userquery->get_user_details($uid);
    }
    else
    {
        $user = $uid;
    }

    if (empty($user))
    {
        return false;
    }

    assign('u', $user);
    Template(STYLES_DIR . '/global/blocks/view_channel/custom_background.html', false);
}

/**
 * Function used to return a name of a user
 * 
 * @param ARRAY userdetails
 * @return STRING name
 */
function name($user, $field = NULL)
{
    if ($field)
    {
        if ($user[$field])
            $name = $user[$field];
    }else
    {

        if ($user['fullname'])
            $name = $user['fullname'];
        elseif ($user['first_name'])
            $name = $user['first_name'] . ' ' . $user['last_name'];
        elseif ($user['username'])
            $name = $user['username'];
    }

    if ($name)
        $name = apply_filters($name, 'user_name');

    return $name;
}

/**
 * Get user details
 * 
 * @param STRING | INT $username or $userid
 * @return ARRAY $userdetails
 */
function get_user_details($uid)
{
    global $userquery;

    return $userquery->get_user_details($uid);
}

/**
 * get user basic fields
 * 
 * @return ARRAY list of basic fields for users table
 */
function get_user_fields($extra_fields=NULL)
{
    $fields = array(
        'userid', 'username', 'email',
        'first_name', 'last_name', 'avatar',
        'avatar_url', 'sex', 'dob'
    );

    if ($extra_fields)
        $fields = array_merge($fields, $extra_fields);

    return $fields;
}

/* * *
 * get user basic details
 */

function get_basic_user_details($uid)
{
    $uid = mysql_clean($uid);
    $fields = get_user_fields();

    $fields = tbl_fields($fields, 'users');
    
    $query = " SELECT ".$fields." FROM ".tbl('users');
    $query .= " WHERE userid='".$uid."' ";
    $query .= " LIMIT 1";
    

    $results = db_select($query);
    
    if($results)
        return $results[0];
    
}

function add_profile_item_type_callback( $type, $callback ) {
    global $cb_profile_item_callbacks;
    
    if ( !$type or !$callback or !function_exists( $callback ) or isset( $cb_profile_item_callbacks[ $type ] ) ) {
        return false;
    }
    
    return $cb_profile_item_callbacks[ $type ] = $callback;
}

function display_profile_item( $uid = null ) {
    global $cb_profile_item_callbacks;
    
    if ( !$cb_profile_item_callbacks ) {
        return false;
    }
    
    if ( is_null( $uid ) ) {
        $uid = userid();
    }
    
    $query = "SELECT pro.profile_item FROM ".tbl('user_profile')." as pro ";
    $query .= " WHERE pro.userid = '".mysql_clean( $uid )."' ";
    
    $result = db_select( $query );
    
    if ( $result ) {
        $result = $result[0];
        
        $item = json_decode( $result['profile_item'], true );
        // Database item is here, time to check if callback exists
        if ( isset( $cb_profile_item_callbacks[ $item['type'] ] ) and function_exists( $cb_profile_item_callbacks[ $item['type'] ] ) ) {
            return $cb_profile_item_callbacks[ $item['type'] ]( $item['id'] );
            
        }
    } else {
        return false;
    }
}

?>