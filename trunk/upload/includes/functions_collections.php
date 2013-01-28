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
    if ( empty($type) ) {
        $type = 'photos';
    }
    
    if ($type != $cbcollection->types ) {
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

/**
 * This will get only avatar collections
 * 
 * @global OBJECT $db
 * @global OBJECT $cbcollection
 * @param STRING $cond
 * @param INT $limit
 * @param STRING $order
 * @return MIX
 */
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

/**
 * This will be select object from items table
 * 
 * @global OBJECT $db
 * @global OBJECT $cbcollection
 * @param INT $cid
 * @param INT $oid
 * @return MIX
 */
function get_collection_item ( $cid, $oid ) {
	global $db, $cbcollection;
	$result	= $db->select( tbl( $cbcollection->items ),'*', " collection_id = '".$cid."' AND object_id = '".$oid."' " );
	if ( $result ) {
		return $result[0];	
	} else {
		return false;	
	}
}

/**
 * Gets photo manager orders
 * 
 * @return ARRAY
 */
function get_collection_manager_orders() {
    return object_manager_orders('collection');
}

/**
 * Adds photo manager order
 * 
 * @param STRING $title Title of order
 * @param STRING $order mySQL order
 * @param STRING $id Optional
 * @return MIX
 */
function add_collection_manager_order( $title, $order, $id =  false ) {
    return add_object_manager_order( $title, $order, 'collection', $id );
}

/**
 * Displays photo manager order
 * 
 * @param STRING $display
 * @return MIX
 */
function display_collection_manger_orders( $display='unselected' ) {
    return display_manager_orders('collection',$display);
}

/**
 * Displays current photo manager order
 * 
 * @return STRING
 */
function current_collection_order () {
    return current_object_order('collection');
}

function collection_links( $collection, $type = 'vc' ) {
    global $cbcollection;

    return $cbcollection->collection_links( $collection, $type );
}

function get_collection_thumb ( $cid, $size = null ) {
     global $cbcollection;
     
     if ( is_null( $cid ) ) {
         $collection = $cbcollection->get_collection( $cid );
         $cid = $collection['collection_id'];
     } else {
         $collection = $cid;
         $cid = $collection['collection_id'];
     }
     
     $cover_photo = $collection['cover_photo'];

     if ( $cover_photo ) {
         $cover_photo = json_decode( $cover_photo, true );
         if ( !$cover_photo['photo_id'] ) {           
             if ( $cover_photo != 0 ) {
                 $item = $cbcollection->get_collection_items_with_details( $cid, null, 1, " AND ".tbl('photos.photo_id')." = '".$cover_photo."' " );
                 $item = $item ? $item[0] : '';
             } else {
                 $item = $cbcollection->get_collection_items_with_details( $cid, null, 1 );
                 $item = $item ? $item[0] : '';
             }
         } else {
             $item = $cover_photo;
         }
     } else {
        $item = $cbcollection->get_collection_items_with_details( $cid, null, 1 );
        $item = $item ? $item[0] : '';
     }

     $type = $item['type'];
     switch( $type ) {
         case 'v' : {
             $thumb = $cbcollection->get_default_thumb( $size );
         }
         break;
         
        case 'p':
        default: {
             $thumb = get_image_url( ( $item['photo_id'] ? $item : $item['object_id'] ), $size );
         }
         break;
     }
     
     if ( $thumb ) {
         return $thumb;
     } else {
         $exts = array();
        foreach($exts as $ext)
        {
            if($size=="small") {
                $s = "-small";
            }
            
            if(file_exists(COLLECT_THUMBS_DIR."/".$cid.$s.".".$ext)) {
                return COLLECT_THUMBS_URL."/".$cid.$s.".".$ext;	
            }
        }
     }
     
     return $cbcollection->get_default_thumb($size);
}

function get_collection_fields( $extra = null ) {
    
    // Following fileds are required to view
    // photo file
    $fields = array(
        'collection_id', 'collection_name', 'active',
        'broadcast', 'cover_photo', 'total_objects',
        'is_avatar_collection'
    );
    
    if( !is_null( $extra) && is_array( $extra ) ) {
        $fields = array_merge( $fields, $extra );
    }
    
    return $fields;
}

?>