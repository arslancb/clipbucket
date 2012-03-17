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
function get_user_vids($uid,$cond=NULL,$count_only=false)
{
    global $userquery;
    return $userquery->get_user_vids($uid,$cond,$count_only);
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
        return $userquery->username_exists($user);
}

/**
 * Function used to validate username
 * @input USERNAME
 */
function username_check($username)
{
        global $Cbucket;
        $banned_words = $Cbucket->configs['disallowed_usernames'];
        $banned_words = explode(',',$banned_words);
        foreach($banned_words as $word)
        {
                preg_match("/$word/Ui",$username,$match);
                if(!empty($match[0]))
                        return false;
        }
        //Checking if its syntax is valid or not
        $multi = config('allow_unicode_usernames');

        //Checking Spaces
        if(!config('allow_username_spaces'))
        preg_match('/ /',$username,$matches);
        if(!is_valid_syntax('username',$username) && $multi!='yes' || $matches)
                e(lang("class_invalid_user"));
        return true;
}

/**
 * FUnction used to get username from userid
 */
function get_username($uid)
{
        global $userquery;
        return $userquery->get_username($uid,'username');
}

/**
 * Function used to get userid anywhere 
 * if there is no user_id it will return false
 */
function user_id()
{
        global $userquery;
        if($userquery->userid !='' && $userquery->is_login) return $userquery->userid; else false;
}
//alias
function userid(){return user_id();}

/**
 * Function used to get username anywhere 
 * if there is no usern_name it will return false
 */
function user_name()
{
        global $userquery;
        if($userquery->user_name)
                return $userquery->user_name;
        else
                return $userquery->get_logged_username();
}
function username(){return user_name();}

/**
 * Function used to check weather user access or not
 */
function has_access($access,$check_only=TRUE,$verify_logged_user=true)
{
        global $userquery;

        return $userquery->login_check($access,$check_only,$verify_logged_user);
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
        return $userquery->avatar($udetails,$size,$uid);
}
?>
