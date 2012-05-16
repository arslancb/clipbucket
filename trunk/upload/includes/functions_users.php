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
        $iMonth = date("m",$time);
        $iDay = date("d",$time);
        $iYear = date("Y",$time);

        $iTimeStamp = (mktime() - 86400) - mktime(0, 0, 0, $iMonth, $iDay, $iYear); 
        $iDays = $iTimeStamp / 86400;  
        $iYears = floor($iDays / 365 );  
        return $iYears; 
}



function update_user_voted($array,$userid=NULL)
{
        global $userquery;
        return $userquery->update_user_voted($array,$userid);	
}


 /**
 * Function used to check username is disallowed or not
 * @param USERNAME
 */
function check_disallowed_user($username)
{
        global $Cbucket;
        $disallowed_user = $Cbucket->configs['disallowed_usernames'];
        $censor_users = explode(',',$disallowed_user);
        if(in_array($username,$censor_users))
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
         array('name'=> lang('Videos'),  'link'=> cblink(array('name'=>'videos')))
    );
    
    $new_array = apply_filters($array,'user_menu_links');
    
    return $new_array;
    
}

function cb_upload_avatar ( $file, $uid = null) {
	global $db, $userquery;
	if ( is_null($uid) ) {
		$uid = userid();
	}
	if ( !$uid ) {
		return false;	
	}
	
	$user = $userquery->get_user_details($uid);
	if ( $user ) {
		$collection = $user['avatar_collection'];
	} else {
		return false;	
	}
}

/**
 * Create user avatar collection
 */
function cb_create_user_avatar_collection( $uid = null) {
	global $db, $userquery, $cbcollection;
	
	if ( is_null($uid) ) {
		$uid = userid();	
	}

	if ( !$uid ) {
		return;	
	}
	
	if( is_array($uid) ) {
		$user = $uid;	
	} else {
		$user = $userquery->get_user_details( $uid );	
	}
	
	if ( !empty($user) ) {
		if ( $user['avatar_collection'] == 0 ) {
			/* User has no avatar collection. Create one */
			$details = array(
				'collection_name' => 'User Avatars',
				'collection_description' => 'Collection of user avatars',
				'collection_tags' => ' ',
				//'category' => array('category', $cbcollection->get_avatar_collection_id() ),
				'category' => '#'.$cbcollection->get_avatar_collection_id().'#',
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
			$insert_id = $db->insert( tbl($cbcollection->section_tbl), array_keys($details), array_values($details) );
			if ( $insert_id ) {
				/* update user column avatar_collection */
				$db->update( tbl('users'), array('avatar_collection'), array($insert_id), " userid = '".$user['userid']."' " );
				return $insert_id;	
			}
		}
	}
}

?>
