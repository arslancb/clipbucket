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

    if ( is_array( $list_id ) ) {
        $playlist = $list_id;
    } else {
        $playlist = get_playlist( $list_id );
    }

    if ( isset( $playlist[ 'playlist_id' ] ) ) {

        if ( $playlist[ 'privacy' ] == 'private' and $playlist[ 'userid' ] != userid() ) {
            e( lang( 'User has made this playlist private.' ) );
            return false;
        }

        $data = cb_do_action( 'is_playlist_viewable', array( 'playlist' => $playlist ) );

        if ( $data ) {
            return $data;
        }

        return true;
    }

    return true;
}

function get_playlists( $args = array() ) {
    global $cbvid;
    return $cbvid->action->get_playlists( $args );
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

function get_playlist_cover ( $playlist, $return_default = false ) {
    $cover = $playlist[ 'cover' ];
    $playlist_dir = PLAYLIST_COVERS_DIR;

    if ( empty( $cover ) ) {
        return ( $return_default == true ) ? get_playlist_default_thumb() : false;
    }

    if ( file_exists( $playlist_dir.'/'.$cover ) ) {
        return PLAYLIST_COVERS_URL.'/'.$cover;
    }

    return ( $return_default == true ) ? get_playlist_default_thumb() : false;
}

function get_playlist_thumb ( $playlist ) {

    $first_item = $playlist[ 'first_item' ];

    if ( isset( $first_item ) ) {

        if ( !is_array( $first_item ) ) {
            $first_item = json_decode( $first_item, true );
        }

        $thumb = get_thumb( $first_item, 'big' );

        if ( strpos( $thumb, 'processing' ) === false ) {
            return $thumb;
        }
    }

    $thumb = get_playlist_cover( $playlist );

    return ( $thumb ? $thumb : get_playlist_default_thumb() );
}

function get_playlist_default_thumb() {
    $name = 'playlist_thumb.png';
    $template = TEMPLATEDIR;
    $images = IMAGES_URL;

    $url = $images.'/'.$name;

    if ( file_exists( $template.'/images/'.$name ) ) {
        $url = TEMPLATEURL.'/images/'.$name;
    }

    return $url;
}

function view_playlist( $playlist_id ) {

    $playlist_link = BASEURL;

    if ( is_array( $playlist_id ) and isset( $playlist_id[ 'playlist_id' ] ) ) {
        $playlist = $playlist_id;
    } else {
        $playlist = get_playlist( $playlist_id );
    }

    if ( empty( $playlist  ) ) {
        return BASEURL;
    }

    $is_seo = SEO;


    $data = cb_do_action( 'view_playlist_link', array( 'playlist' => $playlist, 'seo_enabled' => $is_seo ) );

    if ( $is_seo ) {
        $playlist_link .= '/list/'.$playlist[ 'playlist_id' ].'/'.SEO( $playlist[ 'playlist_name' ] );
    } else {
        $playlist_link .= '/view_playlist.php?list='.$playlist_id;
    }


    $data = cb_do_action( 'view_playlist_link', array(
        'playlist' => $playlist,
        'seo_enabled' => $is_seo,
        'playlist_link' => $playlist_link
    ) );

    if ( $data ) {
        return $data;
    }

    return $playlist_link;
}

function playlist_upload_cover ( $args ) {
    global $db;

    $filename = $args[ 'playlist_id' ];
    $extension = GetExt( $args[ 'name' ] );
    $folder = create_dated_folder( PLAYLIST_COVERS_DIR );
    $uploaded_file = PLAYLIST_COVERS_DIR.'/'.$folder.'/'.$filename.'.'.$extension;

    if ( !empty( $filename ) ) {

        if ( move_uploaded_file( $args[ 'tmp_name' ], $uploaded_file ) ) {

            $cover_name = $filename.'.'.$extension;

            $resizer = new CB_Resizer( $uploaded_file );
            $resizer->target = $uploaded_file;
            $resizer->resize( 1280, 800 );
            $resizer->save();


            $db->update( tbl( 'playlists' ), array( 'cover' ), array( $folder.'/'.$cover_name ), " playlist_id = '".$filename."' " );

            return true;
        }

    }

    return false;
}

function increment_playlist_played( $args = array() ) {
    global $db;

    if ( isset( $args[ 'playlist' ] ) ) {

        $cookie = 'playlist_played_'.$args[ 'playlist' ][ 'playlist_id' ];

        if ( !isset( $_COOKIE[ $cookie ] ) ) {

            $db->update( tbl( 'playlists' ), array( 'played' ), array( '|f|played+1' ), " playlist_id = '".$args[ 'playlist' ][ 'playlist_id' ]."' " );
            setcookie( $cookie, true, time()+3600 );

        }

    }

}

# BASEURL/show/SHOW-NAME/