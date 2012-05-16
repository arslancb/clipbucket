<?php
/**
* @Author : Fawaz Tahir and Arslan Hassan
*/

include('../includes/config.inc.php');
$mode = mysql_clean($_POST['mode']);

switch ($mode) {
	case 'a': case 'add': case 'add_tag': case 'addTag': {
		/* ADDING NEW TAG
		* First check if user is logged-in or not
		 * Yes, then check if value entered is actually a user or not.
		 * If it is a user then check if this user is a confirmed friend or not
		 * Yes it is confirmed friend, add tag with user profile link and remove link
		 * No it not a confirmed friend, add tag with remove link
		 *
		 * No entered value is not a user, add new tag with remove link
		 *
		 * THINGS TO UPDATE
		 * - IF VALUE IS USER
		 * --- update user column in which photo id's, in json format, reside which shows on what photos this user was tagged: COLUMN|  
		 */
		if( !userid() ) {
			echo json_encode( array('error' => true, 'error_message' => lang('login_to_add_tag') ) );
		} else {
			$clean_post = array();
			foreach( $_POST as $key => $value ) {
				if ( $key != 'label' ) {
					$value = mysql_clean($value);
				}
				$clean_post[$key] = ($value);
			}
			$tag_id = $cbphoto->add_new_tag( $clean_post );
			if(!error()) {
				$tag = $cbphoto->get_tag_with_id( $tag_id , $clean_post['pid'] );
				$clean_post['success'] = true;
				$clean_post['id'] = $tag['ptag_id'];
				$clean_post['canDelete'] = true;
				if ( $tag['ptag_active'] == 'no' ) {
					$clean_post['pending'] = true;
				}
				if ( $tag['ptag_isuser'] == 1 && $tag['ptag_isfriend'] == 1 ) {
					$clean_post['link'] = $userquery->profile_link( $tag['ptag_userid'] ) ;
				}
				echo json_encode( $clean_post );
			} else {
				$msg = error_list();
				$msg = $msg[0];
				echo json_encode(array('error' => true, 'error_message' => $msg));
			}
			//echo json_encode( array('error' => true, 'error_message' => lang('unable_to_tag') ) );
		}
	}break;
	
	case 'r': case 'remove': case 'remove_tag': case 'removeTag':{
		$tag_id = mysql_clean($_POST['id']);
		$return = $cbphoto->remove_photo_tag($tag_id);
		if (!error()) {
			echo json_encode(array('success' => true));
		} else {
			$msg = error_list();
			$msg = $msg[0];
			echo json_encode(array('error' => true, 'error_message' => $msg));			
		}
	}break;

	default:
		echo json_encode( array('error' => true, 'error_message' => lang('unable_to_tag_due_to_issue') ) );
	break;
}
?>