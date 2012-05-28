<?php

/** 
 * All ajax requests that are related photos or its object will be listed here
 *  
 * @author Arslan Hassan
 * @license AAL
 * @since 3.0 
 */

include('../includes/config.inc.php');

//Getting mode..
$mode = post('mode');
if(!$mode) {
    $mode = get('mode');
}

$mode = mysql_clean($mode);

switch($mode){
    
	case 'send_photo_pm': {
                $array = $_POST;
                $array['is_pm'] = true;
                $array['from'] = userid();
                
                $cbpm->send_pm( $array );
                
                if ( error() ) {
                    $errors = error_list();
                    $response = array( 'error' => $errors[0] );
                }
                
                if ( msg() ) {
                    $success = msg_list();
                    $response = array( 'success' => $success[0] );
                }
                                
                echo json_encode( $response );
	}
	break;
	
	case 'delete_photo': {
		$id = mysql_clean( $_POST['id'] );
		$photo = $cbphoto->get_photo( $id );
		$item = get_collection_item( $photo['collection_id'], $photo['photo_id'] );
		$redirect_to = $cbcollection->get_next_prev_item($item['ci_id'], $item['collection_id'], 'next');
		$response = array( 'success' => true, 'redirect_to' => $cbphoto->photo_links( $redirect_to[0], 'view_photo' ) );
		
		/* Delete photo */
		$cbphoto->delete_photo( $id );
		
		if ( error() ) {
			$response = array( 'error' => error() );	
		}
	
		echo json_encode( $response );
	}
	break;
	
    default: {
        exit(json_encode(array('err'=>lang('Invalid request'))));
	}
}
?>