<?php

/*
 * As the name suggest, this file make everything ready for website
 * to operate before it goes to give an output
 */

include(BASEDIR.'/modules/uploader/uploader.php');


/***
 * Adding custom thumb sizes
 */
add_thumb_size('120x60','default');
add_thumb_size('160x120');
add_thumb_size('300x250');
add_thumb_size('640x360');
add_thumb_size('original');



/**
 * Register metas
 */
$cbvid->register_meta('thumbs');

/**
 * Registering Photo Embed codes
 */
add_photo_embed_type(lang('HTML Code'), 'html-code', 'photo_html_code');
add_photo_embed_type(lang('Email or IM'), 'email-code', 'photo_email_code');
add_photo_embed_type(lang('BB Code'), 'bb-code', 'photo_bb_code');
add_photo_embed_type(lang('Linked BB Code'), 'bb-code-linked', 'photo_bb_code_linked');
add_photo_embed_type(lang('BB Code Alterantive'), 'bb-code-alternative', 'photo_bb_code_alt');
add_photo_embed_type(lang('Linked BB Code Alterantive'), 'bb-code-alternative-linked', 'photo_bb_code_alt_linked');

add_custom_photo_size( 'Small Square', '75x75', 75, 75, 5, false, true );
add_custom_photo_size( 'Medium Square', '100x100', 100, 100, 5, false );
add_custom_photo_size( 'Big Square', '150x150', 150, 150, 5 );
add_custom_photo_size( 'Thumb 240', '240', 240, 0, 10 );
add_custom_photo_size( 'Thumb 320', '320', 320, 0, 10 );
add_custom_photo_size( 'Medium 500', '500', 500, 0, 10, config('watermark_photo') );
add_custom_photo_size( 'Medium 640', '640', 640, 0, 10, config('watermark_photo') );
add_custom_photo_size( 'Large 800', '800', 800, 0, 10, config('watermark_photo') );
add_custom_photo_size( 'Large 1024', '1024', 1024, 0, 10, config('watermark_photo') );

function _edit_collection_link( $photo ) {
    global $cbphoto;
        
    if ( $cbphoto->collection->is_collection_owner( $photo['collection_id'] ) ) {
        return collection_links( $photo['collection_id'], 'edit_collection' );
    }
    
    return false;
}

/**
 * Add view exif data link
 * 
 * @global OBJECT $cbphoto
 * @param ARRAY $photo
 * @return MIX
 */
function _view_exif_data_link( $photo ) {
    global $cbphoto;
    if ( ( $photo['exif_data'] == 'yes' && $photo['view_exif'] == 'yes' ) || ( $photo['userid'] == userid() && $photo['exif_data'] == 'yes' )  ) {
        return $cbphoto->photo_links( $photo, 'exif_data' );
    }
    return false;
}

/**
 * Add link to set as avatar only if photo is avatar and photo is not current avatar
 * 
 * @global OBJECT $cbphoto
 * @global ARRAY $userquery
 * @param ARRAY $photo
 * @return string
 */
function _manager_set_avatar_link( $photo ) {
    global $cbphoto, $userquery;
    if ( $photo['is_avatar'] == 'yes' && userid().'_'.$photo['filename'].'.'.$photo['ext'] != $userquery->udetails['avatar'] ) {
        return $cbphoto->photo_links( $photo, 'make_avatar' );
    }
}

/**
 * Add set photo as cover for photos that are only not avatars
 * and photo is not current cover photo
 * 
 * @global OBJECT $cbphoto
 * @param ARRAY $photo
 * @return string
 */
function _set_cover_photo_link( $photo ) {
    global $cbphoto;

    if ( $photo['is_avatar'] == 'no' && userid() == $cbphoto->get_photo_owner( $photo['photo_id'] ) ) {
        return '?cover='.$photo['photo_id'].'&cid='.$photo['collection_id'];
    }
}

/**
 * Callback for _set_cover_photo_link
 * 
 * @global OBJECT $cbcollection
 * @return boolean
 */
function _set_cover_photo_callback() {
    global $cbcollection;
    $pid = mysql_clean( $_GET['cover'] );
    $cid = mysql_clean( $_GET['cid'] );
    
    if ( !$pid || !$cid ) {
        e( lang('Unable to update collection cover photo.') );
        return false;
    }
    
    if ( $cbcollection->set_cover_photo( $pid, $cid ) ) {
        e( lang('Collection cover photo has been updated.'), 'm' );
    } else {
        e( lang('Unable to update collection cover photo.') );
    }
}

add_photo_manager_link( lang('Exif data'), '_view_exif_data_link', false, true );
add_photo_manager_link( lang('Edit collection'), '_edit_collection_link', false, true );
add_photo_manager_link( lang('Set as avatar'), '_manager_set_avatar_link', false, true );
add_photo_manager_link( lang('Make collection cover'), '_set_cover_photo_link', '_set_cover_photo_callback', true );

/**
 * Adding orders for photo manager
 */
add_photo_manager_order( lang('Newest'), tbl('photos.date_added desc') );
add_photo_manager_order( lang('Oldest'), tbl('photos.date_added asc') );
add_photo_manager_order( lang('Most Viewed'), tbl('photos.views desc') );

/**
 * Adding orders for collection manager
 */
add_collection_manager_order( lang('Newest'), tbl('collections.date_added desc') );
add_collection_manager_order( lang('Oldest'), tbl('collections.date_added asc') );
add_collection_manager_order( lang('Most Viewed'), tbl('collections.views desc') );
add_collection_manager_order( lang('Most Photos'), tbl('collections.total_objects desc') );
add_collection_manager_order( lang('Last Updated'), tbl('collections.last_updated desc') );
?>
