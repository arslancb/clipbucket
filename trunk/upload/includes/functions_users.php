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

function upload_new_avatar( $array = null ) {
    global $cbphoto, $userquery, $photo;
    
    if ( is_null($array) ) {
        $array = $_POST;
    }
    /* include resizer class */
    include_once 'includes/classes/resizer.class.php';
    /* get original photo */
    $p = $cbphoto->get_image_file( $photo , 'o', false, null, false );
    /* define source and decode photo details */
    $source = PHOTOS_DIR.'/'.$p; $pd = json_decode( $photo['photo_details'] , true);
    /* set source */
    $r = new CB_Resizer( $source );
    /* coordinates */
    $x = mysql_clean( $_POST['start_x'] ); $x2 = mysql_clean( $_POST['end_x'] );
    $y = mysql_clean( $_POST['start_y'] ); $y2 = mysql_clean( $_POST['end_y'] );
    
    if ( ( !is_numeric($x) || !is_numeric($x2) || !is_numeric($y) || !is_numeric($y2) ) ) {
        e('Unable to crop. Coordinates were unrealiable.');	
    } else {
        /*
        * We will be using the original photo to crop the avatar.
        * First we'll covert the posted pixels to percentage
        * Second get pixels from original photo using percentage
        * Using newly computed pixels crop from original photo
        * Save it for temporary purpose, make it source and start making avatars
        * Delete tempblock when all avatars are created
        */
        
        $xx =  ( ( $x / $pd['l']['width'] ) * 100 ); // compute percentage
        $xx2 = ( ( $x2 / $pd['l']['width'] ) * 100 ); // compute percentage
        $newX = ( ( $xx * $pd['o']['width'] ) / 100 ); // compute pixels
        $newX2 =  ( ( $xx2 * $pd['o']['width'] ) / 100 ); // compute pixels

        $yy = ( ( $y / $pd['l']['height'] ) * 100 ); // compute percentage
        $yy2 = ( ( $y2 / $pd['l']['height'] ) * 100 ); // compute percentage
        $newY = ( ( $yy * $pd['o']['height'] ) / 100 ); // compute pixels
        $newY2 = ( ( $yy2 * $pd['o']['height'] ) / 100 ); // compute pixels
        
        /* We'll save temporary save the cropped photo. */
        $tempblock = $r->target = USER_THUMBS_DIR.'/'.userid()."-tempblock.".$photo['ext'];
        $r->_crop($newX, $newY, $newX2, $newY2 );
        $r->save();
        
        $exts = array('jpg','jpeg','png','gif');

        /* Delete previous avatar */
        foreach( $exts as $ext ) {
            if ( file_exists( USER_THUMBS_DIR.'/'.userid().'.'.$ext ) )	 {
            unlink(USER_THUMBS_DIR.'/'.userid().'.'.$ext)	;
            }

            if ( file_exists(USER_THUMBS_DIR.'/'.userid().'-small.'.$ext) ) {
            unlink( USER_THUMBS_DIR.'/'.userid().'-small.'.$ext );	
            }
        }
        
        /* Make $tempblock the source */
        $r->source = $r->target;
        
        /* Big Thumb */
        $r->target = USER_THUMBS_DIR.'/'.userid().'.'.$photo['ext'];
        $r->cropping = -1;
        $r->_resize( AVATAR_SIZE );
        $r->save();

        /* Small Thumb */
        $r->target = USER_THUMBS_DIR.'/'.userid().'-small.'.$photo['ext'];
        $r->cropping = 1;
        $r->_resize(AVATAR_SMALL_SIZE, AVATAR_SMALL_SIZE);
        $r->save();
        
        /* Remove $tempblock */
        if ( file_exists($tempblock) ) {
            unlink( $tempblock );	
        }
        
        /* go back to photo */
        redirect_to( $cbphoto->photo_links($photo, 'view_photo') );

        /* go to user profile */
        //redirect_to( $userquery->profile_link(userid()) );
    }
}

/**
 * Create user avatar collection
 */
function cb_create_user_avatar_collection( $uid = null) {
	return;
	
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
