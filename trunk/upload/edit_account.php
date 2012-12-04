<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE","edit_account");

require 'includes/config.inc.php';
$userquery->logincheck();

//Updating Profile
if(isset($_POST['update_profile']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->update_user($array);
}

//Updating Avatar
if(isset($_POST['update_avatar_bg']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->update_user_avatar_bg($array);
}

//Changing Email
if(isset($_POST['change_email']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->change_email($array);
}

//Changing User Password
if(isset($_POST['change_password']))
{
	$array = $_POST;
	$array['userid'] = userid();
	$userquery->change_password($array);
}

//Banning Users
if(isset($_POST['block_users']))
{
	$userquery->block_users($_POST['users']);
}

if ( mysql_clean($_GET['mode']) == 'make_avatar' ) {
	
	$pid = mysql_clean( $_GET['pid'] );
	$pid = $cbphoto->decode_key( $pid );
	$pid = substr( $pid, 12, 12 );
	$photo = $cbphoto->get_photo( $pid );

	if ( !$photo ) {
		e( lang('photo_not_exist') );
		cb_show_page( false );
	} else {
		assign( 'photo', $photo );
		if ( isset($_GET['set_avatar']) ) {
			/* Run set avatar code */
                $uid = userid();
                $db->update( tbl('users'), array('avatar'), array( $uid.'_'.$photo['filename'].'.'.$photo['ext'] ), " userid = '".$uid."' " );
                /* update cover photo of collection */
                $cbcollection->set_cover_photo( $photo['photo_id'], $photo['collection_id'] );
                // redirect back to photo
                redirect_to( $userquery->profile_link( $photo['userid'] ) );
		} else if( isset( $_POST['set_avatar']) ) {
                /* Run make avatar code */
                make_new_avatar();
		}
	}
	
}

$mode = $_GET['mode'];



switch($mode)
{
	case 'account':
	{
		assign('on','account');
		assign('mode','profile_settings');
	}
	break;
	case 'profile':
	{
		assign('on','profile');
		assign('mode','profile_settings');
	}
	break;
	
	case 'avatar_bg':
	{
		assign('mode','avatar_bg');
	}
	break;
	
	case 'make_avatar': {
		assign('mode', 'make_avatar');
	}
	break;
	
	case 'change_email':
	{
		assign('mode','change_email');
	}
	break;
	
	case 'change_password':
	{
		assign('mode','change_password');
	}
	break;
	
	case 'block_users':
	{
		assign('mode','block_users');
	}
	break;
	
	case 'subscriptions':
	{
		//Removing subscription
		if(isset($_GET['delete_subs']))
		{
			$sid = mysql_clean($_GET['delete_subs']);
			$userquery->unsubscribe_user($sid);
		}
		assign('mode','subs');
		assign('subs',$userquery->get_user_subscriptions(userid()));
	}
	break;
	
	default:
	{
		assign('on','account');
		assign('mode','profile_settings');
	}
}


$udetails = $userquery->get_user_details(userid());
$profile = $userquery->get_user_profile($udetails['userid']);
$user_profile = array_merge($udetails,$profile);
//pr($Cbucket->header_files);
assign('user',$udetails);
assign('p',$user_profile);


subtitle(lang("user_manage_my_account"));
template_files('edit_account.html');
display_it();
?>