<?php

/*
Plugin Name: Server Thumb for ClipBucket
Description: Add function in clipbucket to get server thumb :)
Author: Mohammad Shoaib
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/

if(!function_exists('server_thumb'))
{
	function server_thumb($vdetails, $array)
	{
		
        $w=100;
		$h=100;
		if( $array['num']=='big' || $array['size']=='big' )
    {
      $w = 320;
      $h = 250;
    }
    else if($array['num']=='medium' || $array['size']=='medium')
    {
    	$w = 160;
      $h = 90;	
    }
    else if($array['num']=='small' || $array['size']=='small')
    {
    	$w = 120;
      $h = 60;	
    }
    
    $tim_postfix = '&type=photos&h='.$h.'&w='.$w.'&zc=1';
   
		global $baseurl;
		$timthumb_path = $baseurl.'/plugins/cb_server_thumb/timthumb.php?src=';

		#get all possible thumbs of video
    $thumbDir = (isset($vdetails['file_directory']) && $vdetails['file_directory']) ? $vdetails['file_directory'] : "";
    if(!isset($vdetails['file_directory'])){
        $justDate = explode(" ", $vdetails['date_added']);
        $thumbDir = implode("/", explode("-", array_shift($justDate)));
    }
    if(substr($thumbDir, (strlen($thumbDir) - 1)) !== "/"){
        $thumbDir .= "/";
    }

    //$justDate = explode(" ", $vdetails['date_added']);
    //$dateAdded = implode("/", explode("-", array_shift($justDate)));
    
    $file_dir ="";
    if(isset($vdetails['file_name']) && $thumbDir)
    {
       $file_dir =  "/" . $thumbDir;
    }
    $vid_thumbs = glob(THUMBS_DIR."/" .$file_dir.$vdetails['file_name']."*");

    #replace Dir with URL
    if(is_array($vid_thumbs))
        foreach($vid_thumbs as $thumb)
        {
            if(file_exists($thumb) && filesize($thumb)>0)
            {
                $thumb_parts = explode('/',$thumb);
                $thumb_file = $thumb_parts[count($thumb_parts)-1];

                if(!is_big($thumb_file) || $array['return_big'])
                {
                    if($array['return_full_path'])
                        $thumbs[] = $timthumb_path.$thumb_file.'&directory=thumbs/'.$thumbDir.$tim_postfix;
                    else
                        $thumbs[] = $timthumb_path.$thumb_file.'&directory=thumbs/'.$tim_postfix;
                }
            }elseif(file_exists($thumb))
                unlink($thumb);
        }

    if(count($thumbs)==0)
    {
        $get_default_thumb = explode('/', default_thumb());
        $d_thumb = end($get_default_thumb);
        
        if($array['count'])
            return count($thumbs);
        if($array['multi'])
            return $dthumb[0] = $timthumb_path.$d_thumb.'&directory=thumbs/'.$tim_postfix;
        return $timthumb_path.$d_thumb.'&directory=thumbs/'.$tim_postfix;
    }
    else
    {
        if($array['multi'])
            return $thumbs;
        if($array['count'])
            return count($thumbs);

        //Now checking for thumb
        if($array['num']=='default')
        {
            $num = $vdetails['default_thumb'];
        }
        if($array['num']=='big' || $array['size']=='big')
        {

            $num = 'big-'.$vdetails['default_thumb'];
            if(!file_exists(THUMBS_DIR.'/'.$vdetails['file_name'].'-'.$num.'.jpg'))
                $num = 'big';
        }

        $default_thumb = array_find($vdetails['file_name'].'-'.$num, $thumbs);

        if(!empty($default_thumb))
            return $default_thumb;
        return $thumbs[0];
    }
		
		
	}
}

global $Cbucket;
$Cbucket->custom_get_thumb_funcs[] = 'server_thumb';


if(!function_exists('get_server_img'))
{
    function get_server_img($params)
    {
        $w=100;
        $h=100;
       
        global $baseurl;
        $timthumb_path = $baseurl.'/plugins/cb_server_thumb/timthumb.php?src=';

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

        $path = PHOTOS_DIR;
        $directory = get_photo_date_folder( $photo );
        $with_path = $params['with_path'] = ( $params['with_path'] === false ) ? false : true;
        $with_original = $params[ 'with_orig' ];

        $size = ( !in_array( $size, $default ) or !$size ) ? 't' : $size;

        if( $size=='l')
        {
          $w = 320;
          $h = 250;
        }
        else if($size=='m')
        {
          $w = 160;
          $h = 90;  
        }
        else if($size=='t')
        {
          $w = 120;
          $h = 60;  
        }

        $tim_postfix = '&type=photos&h='.$h.'&w='.$w.'&zc=1';

        if( $directory ) {
            $directory .= '/';
        }

        $path .= '/'.$directory;
        $filename = $photo[ 'filename' ].'%s.'.$photo[ 'ext' ];

        $files = glob( $path.sprintf( $filename, '*' ) );
        
        global $cbphoto;
        if ( !empty( $files ) ) {
            
            foreach( $files as $file ) {

                $thumb_name = end( explode( "/", $file ) );
                $thumb_type = $cbphoto->get_image_type( $thumb_name );

                if( $with_original ) {
                    //$thumbs[] = ( ( $with_path ) ? PHOTOS_URL.'/' : '' ) . $directory . $thumb_name;
                    $thumbs[] = $timthumb_path.$thumb_name.'&directory=photos/'.$directory.$tim_postfix;
                } else if( !empty( $thumb_type ) ) {
                    //$thumbs[] = ( ( $with_path ) ? PHOTOS_URL.'/' : '' ) . $directory . $thumb_name;
                    $thumbs[] = $timthumb_path.$thumb_name.'&directory=photos/'.$directory.$tim_postfix;
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
}
$Cbucket->custom_get_photo_funcs[] = 'get_server_img';

?>