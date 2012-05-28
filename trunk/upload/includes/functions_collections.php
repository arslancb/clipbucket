<?php

/**
 * Function used to validate category
 * INPUT $cat array
 */
function validate_collection_category( $array = NULL ) {
    global $cbcollection;
    return $cbcollection->validate_collection_category( $array );
}

/**
 * function used to get photos
 */
function get_collections( $param ) {
    global $cbcollection;
    return $cbcollection->get_collections( $param );
}

/**
 * Function used to get collection name from id
 * Smarty Function
 */
function get_collection_field( $cid, $field = 'collection_name' ) {
    global $cbcollection;
    return $cbcollection->get_collection_field( $cid, $field );
}

/**
 * Function used to delete photos if
 * whole collection is being deleted
 */
function delete_collection_photos( $details ) {
    global $cbcollection, $cbphoto;
    $type = $details['type'];

    if ( $type == 'photos' ) {
        $ps = $cbphoto->get_photos( array("collection" => $details['collection_id']) );
        if ( !empty( $ps ) ) {
            foreach ( $ps as $p ) {
                $cbphoto->make_photo_orphan( $details, $p['photo_id'] );
            }
            unset( $ps ); // Empty $ps. Avoiding the duplication prob
        }
    }
}

/*
 * Since 3.0, we have removed videos from collection, it is moved to playlists.
 * This function will confirm that no matter user will get photos
 */
function confirm_collection_type ( $type ) {
    global $cbcollection;
    if (empty($type) || $type != $cbcollection->types ) {
        if ( VERSION < '3.0' ) {
            // Get Deprecated Types;
            $dep_types = $cbcollection->deprecated_types;
            $message = 'Collections feature now only support photos';
            if ( array_key_exists( $type , $dep_types ) ) {
                $message .= '. '.$cbcollection->deprecated_types[$type].' support has been dropped since 2.6';
                $dep_type = $cbcollection->deprecated_types[$type].' ';
            }
            
            if ( userid() && has_access('admin_access',true) ){
                $message .= '. Please upgrade your Clipbucket to <a href="http://clip-bucket.com" target="_blank">latest version</a>';
            } else {
                $message .= '. Please contact Site Administrator about this.';
            }
            e(lang($message));
            cb_show_page();
        }
        return $cbcollection->types;
    }
    return $cbcollection->types;
}

function get_avatar_collections( $cond=null, $limit=null, $order=null ) {
    global $db, $cbcollection;
    
    if ( !is_null($cond) ) {
        $cond = ' AND '.$cond;
    }
    
    $results = $db->select( tbl($cbcollection->section_tbl), '*',  " is_avatar_collection = 'yes' $cond", $limit, $order);
    if ( $results ) {
        return $results;
    } else {
        return false;
    }
}

function get_collection_item ( $cid, $oid ) {
	global $db, $cbcollection;
	$result	= $db->select( tbl( $cbcollection->items ),'*', " collection_id = '".$cid."' AND object_id = '".$oid."' " );
	if ( $result ) {
		return $result[0];	
	} else {
		return false;	
	}
}
?>