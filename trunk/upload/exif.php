<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2012 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan, Fawaz Tahir											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

require 'includes/config.inc.php';

@$id = ( $_GET['id'] );
if ( empty($id) ) {
	e( lang('Photo does not exists') );
	cb_show_page();
} else {
	$photo = $cbphoto->get_photo( $id, true );
	if ( empty( $photo ) ) {
		e( lang('Photo does not exists') );
		cb_show_page();		
	} else {
		$exif = get_photo_meta_value( $photo['photo_id'], 'exif_data');
		if ( !$exif ) {
			e( lang(sprintf('<strong>%s</strong> does not have EXIF Data', $photo['photo_title'])) );
		} else {
			if ( $photo['userid'] != userid() && $photo['view_exif'] == 'no' ) {
				e( lang('Owner has decided to keep Exif Information hidden for this photo.') );
			} else {
				$exif = json_decode( $exif, true );

				$template_ready_data = ready_exif_data( $exif, $photo );
				
				assign( 'photo', $photo );
				assign('exif', $template_ready_data );
				if ( $photo['userid'] == userid() ) {
					assign( 'is_owner', true );	
				}
				subtitle( 'Exif | '.$photo['photo_title'] );
				template_files( STYLES_DIR.'/global/exif.html');				
			}
		}
	}
}

display_it();
?>