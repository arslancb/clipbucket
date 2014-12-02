<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 9/3/13
 * Time: 11:38 AM
 * To change this template use File | Settings | File Templates.
 */


function get_photo_fields() {
    global $cb_columns;
    return $cb_columns->object( 'photos' )->get_columns();
}

/**
 * function used to get photos
 */
function get_photos($param)
{
    global $cbphoto;
    return $cbphoto->get_photos($param);
}

//Simple Width Fetcher
function getWidth($file)
{
    $sizes = getimagesize($file);
    if($sizes)
        return $sizes[0];
}

//Simple Height Fetcher
function getHeight($file)
{
    $sizes = getimagesize($file);
    if($sizes)
        return $sizes[1];
}

//Load Photo Upload Form
function loadPhotoUploadForm($params)
{
    global $cbphoto;
    return $cbphoto->loadUploadForm($params);
}
//Photo File Fetcher
function get_photo($params)
{
    return get_image_file( $params );
}

//Photo Upload BUtton
function upload_photo_button($params)
{
    global $cbphoto;
    return $cbphoto->upload_photo_button($params);
}

//Photo Embed Cides
function photo_embed_codes($params)
{
    global $cbphoto;
    return $cbphoto->photo_embed_codes($params);
}

//Create download button

function photo_download_button($params)
{
    global $cbphoto;
    return $cbphoto->download_button($params);
}

function add_photo_plupload_javascript_block() {
    if( THIS_PAGE == 'photo_upload' ) {
        return Fetch( JS_DIR.'/plupload/uploaders/photo.plupload.html', true );
    }
}

function plupload_photo_uploader() {
    $photoUploaderDetails = array
    (
        'uploadSwfPath' => JS_URL.'/plupload/Moxie.swf',
        'uploadScriptPath' => BASEURL.'/actions/photo_uploader.php?plupload=true',
    );


    assign('photoUploaderDetails',$photoUploaderDetails);
}


/**
 * Function is used to confirm the current photo has photo file saved in
 * structured folders. If file is found at structured folder, function
 * will the dates folder structure.
 *
 * @param INT|ARRAY $photo_id
 * @return bool|string $directory
 */
function get_photo_date_folder( $photo_id ) {
    global $cbphoto, $db;

    if ( is_array( $photo_id ) ) {
        $photo = $photo_id;
    } else {
        $photo = $cbphoto->get_photo( $photo_id );
    }

    if ( !$photo ) {
        return false;
    }

    /**
     * Check if file_directory index has value or not
     */
    if( $photo[ 'file_directory' ] ) {
        $directory = $photo[ 'file_directory' ];
    }

    if ( !$directory ) {
        /**
         * No value found. Extract time from filename
         */
        $random = substr( $photo['filename'], -6, 6 );
        $time = str_replace( $random, '', $photo['filename'] );
        $directory = date( ( defined( 'CB_FILES_SYSTEM_STRUCTURE' ) ? CB_FILES_SYSTEM_STRUCTURE : 'Y/m/d' ), $time );

        /**
         * Making sure file exists at path
         */
        $path = PHOTOS_DIR.'/'.$directory.'/'.$photo[ 'filename' ].'.'.$photo[ 'ext' ];
        $photo[ 'file_path' ] = $path;
        $photo = apply_filters( $photo, 'checking_photo_at_structured_path' );

        if( file_exists( $photo[ 'file_path' ] ) ) {
            /**
             * Photo exists, update file_directory index
             */
            $db->update( tbl( 'photos' ), array( 'file_directory' ), array( $directory ), " photo_id = '".$photo[ 'photo_id' ]."' " );
        } else {
            $directory = false;
        }
    }

    return $directory;
}

function get_photo_default_thumb( $size = null, $output = null ) {
    global $cbphoto;
    return $cbphoto->default_thumb( $size, $output );
}

function get_image_file( $params ) {

    global $cbphoto, $Cbucket;
    //var_dump($params);
    $details = $params[ 'details' ];
    $output = $params[ 'output' ];
    $size = $params[ 'size' ];
    $default = array( 't', 'm', 'l', 'o' );
    $thumbs = array();
    if( !$details ) {
        //var_dump("get default 1");
        return get_photo_default_thumb( $size, $output );
    }

    if ( !is_array( $details ) ) {
        $photo = $cbphoto->get_photo( $details, true );
    } else {
        $photo = $details;
    }


    if ( empty( $photo[ 'photo_id' ] ) or empty( $photo[ 'photo_key' ] ) ) {
        //var_dump("get default 2");
        return get_photo_default_thumb( $size, $output );
    }

    if( empty( $photo[ 'filename' ] ) or empty( $photo[ 'ext' ] ) ) {
        //var_dump("get default 3");
        return get_photo_default_thumb( $size, $output );
    }

    $params[ 'photo' ] = $photo;

    if( count( $Cbucket->custom_get_photo_funcs ) > 0 ) {
        $functions = $Cbucket->custom_get_photo_funcs;
        foreach( $functions as $func ) {
            if( function_exists( $func ) ) {
                $func_data = $func( $params );
                if( $func_data ) {
                    return $func_data;
                }
            }
        }
    }

    $path = PHOTOS_DIR;
    $directory = get_photo_date_folder( $photo );
    $with_path = $params['with_path'] = ( $params['with_path'] === false ) ? false : true;
    $with_original = $params[ 'with_orig' ];

    $size = ( !in_array( $size, $default ) or !$size ) ? 't' : $size;

    if( $directory ) {
        $directory .= '/';
    }

    $path .= '/'.$directory;
    $filename = $photo[ 'filename' ].'%s.'.$photo[ 'ext' ];

    $files = glob( $path.sprintf( $filename, '*' ) );



    if ( !empty( $files ) ) {

        foreach( $files as $file ) {

            $splitted   = explode( "/", $file );
            $thumb_name = end( $splitted );
            $thumb_type = $cbphoto->get_image_type( $thumb_name );

            if( $with_original ) {
                $thumbs[] = ( ( $with_path ) ? PHOTOS_URL.'/' : '' ) . $directory . $thumb_name;
            } else if( !empty( $thumb_type ) ) {
                $thumbs[] = ( ( $with_path ) ? PHOTOS_URL.'/' : '' ) . $directory . $thumb_name;
            }
        }


        if ( empty( $output ) or $output == 'non_html' ) {

            if ( $params[ 'assign' ] and $params[ 'multi' ] ) {
                assign( $params[ 'assign' ], $thumbs );
            } else if( ( $params[ 'multi' ] ) ) {
                return $thumbs;
            } else {

                $search_name = sprintf( $filename, "_".$size );
                $return_thumb = array_find( $search_name, $thumbs );

                if( empty( $return_thumb ) ) {

                    return get_photo_default_thumb( $size, $output );
                } else {

                    if( $params[ 'assign' ] ) {
                        assign( $params[ 'assign' ], $return_thumb );
                    } else {
                        return $return_thumb;
                    }

                }
            }

        }

        if ( $output == 'html' ) {

            $search_name = sprintf( $filename, "_".$size );
            $src = array_find( $search_name, $thumbs );

            $src = ( empty( $src ) ) ? get_photo_default_thumb( $size ) : $src;
            $attrs = array( 'src' => $src );

            if( phpversion < '5.2.0' ) {
                global $json;
            }

            if ( $json ) {
                $image_details = $json->json_decode( $photo['photo_details'],true );
            } else {
                $image_details = json_decode( $photo[ 'photo_details' ], true );
            }

            if ( empty( $image_details ) or empty( $image_details[ $size ] ) ) {
                $dem = getimagesize( str_replace( PHOTOS_URL, PHOTOS_DIR, $src ) );
                $width = $dem[0];
                $height = $dem[1];
                /* UPDATEING IMAGE DETAILS */
                $cbphoto->update_image_details( $photo );
            } else {
                $width = $image_details[ $size ][ 'width' ];
                $height = $image_details[ $size ][ 'height' ];
            }

            if ( ( $params['width'] and is_numeric( $params['width'] ) ) and ( $params['height'] and is_numeric( $height  ) ) ) {
                $width = $params['width'];
                $height = $params['height'];
            } else if ( ( $params['width'] and is_numeric( $params['width'] ) ) ) {
                $height = round( $params['width'] / $width * $height );
                $width = $params['width'];
            } else if ( ( $params['height'] and is_numeric( $height  ) ) ) {
                $width = round( $params['height'] * $width / $height );
                $height = $params['height'];
            }

            //$attrs[ 'width' ] = $width;
            //$attrs[ 'height' ] = $height;
            $attrs[ 'id' ] = ( ( $params[ 'id' ] ) ? $params[ 'id' ].'_' : 'photo_' ).$photo[ 'photo_id' ];

            if( $params[ 'class' ] ) {
                $attrs[ 'class' ] = mysql_clean( $params[ 'class' ] );
            }

            if ( $params['align'] ) {
                $attrs['align'] = mysql_clean( $params['align'] );
            }

            $attrs[ 'title' ] = $photo[ 'photo_title' ];

            if ( isset( $params[ 'title' ] ) and $params[ 'title' ] == '' ) {
                unset( $attrs[ 'title' ] );
            }

            $attrs[ 'alt' ] = TITLE.' - '.$photo[ 'photo_title' ];

            $anchor_p = array( "place" => 'photo_thumb', "data" => $photo );
            $params['extra'] = ANCHOR( $anchor_p );

            if ( $params['style'] ) {
                $attrs['style'] = ( $params['style'] );
            }

            if ( $params['extra'] ) {
                $attrs['extra'] = ( $params['extra'] );
            }

            $image = cb_create_html_tag( 'img', true, $attrs );

            if ( $params[ 'assign' ] ) {
                assign( $params[ 'assign' ], $image );
            } else {
                return $image;
            }
        }
    } else {
        return get_photo_default_thumb( $size, $output );
    }
}

function get_photo_file( $photo_id, $size = 't', $multi = false, $assign = null, $with_path = true, $with_orig = false ) {

    $args = array(
        'details' => $photo_id,
        'size' => $size,
        'multi' => $multi,
        'assign' => $assign,
        'with_path' => $with_path,
        'with_orig' => $with_orig
    );

    return get_image_file( $args );
}