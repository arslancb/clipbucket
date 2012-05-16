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
		} else if( isset( $_POST['set_avatar']) ) {
			/* Run make avatar code */
			include_once 'includes/classes/resizer.class.php';
			$p = $cbphoto->get_image_file( $photo , 'o', false, null, false );
			$source = PHOTOS_DIR.'/'.$p; $pd = json_decode( $photo['photo_details'] , true);
			$r = new CB_Resizer( $source );
			
			$x = mysql_clean( $_POST['start_x'] ); $x2 = mysql_clean( $_POST['end_x'] );
			$y = mysql_clean( $_POST['start_y'] ); $y2 = mysql_clean( $_POST['end_y'] );

			if ( 
				 ( !is_numeric($x) || !is_numeric($x2) || !is_numeric($y) || !is_numeric($y2) )
				) {
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
				//redirect_to( $cbphoto->photo_links($photo, 'view_photo') );
				
				/* go to user profile */
				redirect_to( $userquery->profile_link(userid()) );
			}
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