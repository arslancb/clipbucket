<?php
/**
 * Created by PhpStorm.
 * User: Fawaz
 * Date: 11/15/13
 * Time: 2:59 PM
 */


function get_playlist ( $list_id, $user = null ) {
    global $cbvid;
    return $cbvid->action->get_playlist( $list_id, $user );
}

function is_playlist_viewable( $list_id ) {

    return true;

}

function get_playlist_items( $list_id, $limit = -1, $order = "playlist_items.playlist_item_id DESC" ) {
    global $cbvid;
    return $cbvid->get_playlist_items( $list_id, $order, $limit );
}

function playlist_runtime ( $playlist ) {

    $runtime = (int)0;

    if ( is_array( $playlist ) ) {
        $runtime = $playlist[ 'runtime' ];
    } else if ( is_numeric( $playlist ) ) {
        $runtime = $playlist;
    }

    $string = '';

    if ( $runtime >= 3600 ) {
        $hours = intval( intval( $runtime ) / 3600 );
        if ( $hours > 0 ) {
            $hours = str_pad( $hours, 2, "0", STR_PAD_LEFT );
            $string .= $hours.' '.( ( $hours == 1 ) ? lang( 'hour' ) : lang( 'hours' ) );
        }
    }

    $minutes = intval( ( $runtime / 60 ) % 60 );

    if ( $minutes > 0 ) {
        $minutes = str_pad( $minutes, 2, "0", STR_PAD_LEFT );
        $string .= $minutes.' '.( ( $minutes == 1 ) ? lang( 'minute' ) : lang( 'minutes' ) );
    }

    $seconds = intval( $runtime % 60 );
    $string .= $seconds.' '.( ( $seconds == 1 ) ? lang( 'second' ) : lang( 'seconds' ) );

    return $string;
}

function get_playlist_cover ( $playlist ) {
    $cover = $playlist[ 'cover' ];
    $playlist_dir = PLAYLIST_COVERS_DIR;

    if ( empty( $cover ) ) {
        return false;
    }

    if ( file_exists( $playlist_dir.'/'.$cover ) ) {
        return PLAYLIST_COVERS_URL.'/'.$cover;
    }

    return false;
}

function get_playlist_thumb ( $playlist ) {

    $first_item = $playlist[ 'first_item' ];

    if ( isset( $first_item ) ) {

        if ( !is_array( $first_item ) ) {
            $first_item = json_decode( $first_item, true );
        }

        $thumb = get_thumb( $first_item, 'big' );

        if ( strpos( 'processing', $thumb ) !== false ) {
            return $thumb;
        }
    }

    $thumb = get_playlist_cover( $playlist );

    return ( $thumb ? $thumb : get_playlist_default_thumb() );
}

function get_playlist_default_thumb() {
    return false;
}