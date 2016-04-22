<?php

/**
 * Clipbucet Global Resizer
 * ----------------------------------------------------------------------------
 * All image resizing will be done through this class. It can resize, crop,
 * flip both ways, rotate image, sharpen image and apply filters(only )
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @version 1.0
 * ----------------------------------------------------------------------------
 * Added : new $cropping CASE 10 on 19th September, 2012
 * ----------------------------------------------------------------------------
 *      Example code for using $cropping CASE 10
 * 
 *      <code>
 *          include 'resizer.class.php';
 *          $resizer = new CB_Resizer( 'image.jpg' );
 *          //Setting cropping method
 *          $resizer->cropping = 10;
 *          // resizing
 *          $resizer->_resize( 720, 0 );
 *      </code>
 * 
 *      You might have noticed that only width is provided and not height. Normally,
 *      for cropping to work we need both width and height, so this will resize the image
 *      not crop it. What changes are made when setting $cropping to 10. Difference is, 
 *      now this will check which side is bigger of source and set 720 to that side. If height 
 *      is bigger than width, image height will be set to 720 and width is calculated, this also
 *      goes the other way. 
 */

class CB_Resizer {

    var $source; // filepath to source file
    var $target; // complete filepath where file will be saved
    var $quality; // image quality, for jpeg | default: 90
    var $png_quality; // image quality, for png | default: 9
    var $cropping; // cropping method | default: 5
    var $preserve_aspect;
    var $exact_dimensions;
    var $bgcolor = "#FFFFFF";
    var $watermark_placement;
    var $watermark_padding = 10;
    var $font = 'freeroad.ttf';
    var $font_size = 48;
    protected $fonts_dir = 'fonts/';

    function __construct( $filepath = '' ) {
		
        // Increasing memory limit for this proccess
        // JPG usually takes alot of memory
		ini_set('memory_limit', '256M');
		
        $this->quality = 90;

        $this->png_quality = 9;

        $this->cropping = 5;

        $this->preserve_aspect = $this->auto_resource =  true;

        $this->exact_dimensions = false;

        $this->source = $filepath;

        $this->target = '';
        
        $this->number_of_colors = 25;

        $this->_setup_filters();
    }

    /**
     * resizing method
     */    
    function _resize( $width = 0, $height = 0, $background = null, $resource = null ) {
        if ( $this->_check_resource( $resource ) ) {
            if ( $width == 0 || $height == 0 ) {
                $must_preserve_aspect = true;
            }

            if ( $this->preserve_aspect == true || $must_preserve_aspect == true ) {
                
                if ( ( $width > 0 && $height == 0 ) && $this->cropping == 10 ) {
                    if ( $this->source_width > $this->source_height ) {
                        $aspect_ratio = $this->source_width / ( $width ? $width : $height );
                    } else {
                        $aspect_ratio = $this->source_height / ( $height ? $height : $width );
                    }
                    
                    $target_width = round( $this->source_width / $aspect_ratio );
                    $target_height = round( $this->source_height / $aspect_ratio );
                } else if ( $width > 0 && $height == 0 ) {

                    $aspect_ratio = $this->source_height / $this->source_width;
                    $target_width = $width;
                    $target_height = round( $width * $aspect_ratio );
                } else if ( $width == 0 && $height > 0 ) {

                    $aspect_ratio = $this->source_width / $this->source_height;
                    $target_height = $height;
                    $target_width = round( $height * $aspect_ratio );
                } else if ( $width > 0 && $height > 0 && $this->exact_dimensions == true ) {

                    $x_aspect_ratio = $this->source_width / $width;
                    $y_aspect_ratio = $this->source_height / $height;

                    if ( round( $this->source_height / $x_aspect_ratio ) < $height ) {
                        $target_width = $width;
                        $target_height = round( $this->source_height / $x_aspect_ratio );
                    } else {
                        $target_height = $height;
                        $target_width = round( $this->source_width / $y_aspect_ratio );
                    }
                } else if ( $width > 0 && $height > 0 ) {
                    $x_aspect_ratio = $this->source_width / $width;
                    $y_aspect_ratio = $this->source_height / $height;

                    if ( $this->cropping != -1 ) {
                        $aspect_ratio = min( $x_aspect_ratio, $y_aspect_ratio );
                    } else {
                        /*
                         * If cropping is disabled and both width & height are
                         * provided, always use width to resize image.
                         */
                        $aspect_ratio = ( $x_aspect_ratio );
                    }

                    $target_width = round( $this->source_width / $aspect_ratio );
                    $target_height = round( $this->source_height / $aspect_ratio );
                } else {
                    $target_width = $this->source_width;
                    $target_height = $this->source_height;
                }
            } else {
                $target_width = ( $width > 0 ? $width : $this->source_width );
                $target_height = ( $height > 0 ? $height : $this->source_height );
            }

            $this->target_width = $target_width;
            $this->target_height = $target_height;

            if ( ($this->preserve_aspect == true || $must_preserve_aspect == true ) && $this->exact_dimensions != true ) {
                $canvas = $this->_create_canvas( $target_width, $target_height, -1 );
                imagecopyresampled( $canvas, $this->resource, 0, 0, 0, 0, $target_width, $target_height, $this->source_width, $this->source_height );
                if ( $this->cropping != -1 && $width > 0 && $height > 0 && $this->cropping < 10 ) {
                    switch ( $this->cropping ) {
                        // TOP LEFT
                        case 1: {
                                $start_x = 0;
                                $start_y = 0;
                                $end_x = $width;
                                $end_y = $height;
                            }break;
                        // TOP CENTER
                        case 2: {
                                $start_x = ( $target_width - $width ) / 2;
                                $start_y = 0;
                                $end_x = ( ( $target_width - $width ) / 2 ) + $width;
                                $end_y = $height;
                            }break;
                        // TOP RIGHT
                        case 3 : {
                                $start_x = $target_width - $width;
                                $start_y = 0;
                                $end_x = $target_width;
                                $end_y = $height;
                            }break;
                        // LEFT
                        case 4 : {
                                $start_x = 0;
                                $start_y = ( $target_height - $height ) / 2;
                                $end_x = $width;
                                $end_y = ( ( $target_height - $height ) / 2 ) + $height;
                            }break;
                        // CENTER
                        case 5 : default : {
                                $start_x = ( $target_width - $width ) / 2;
                                $start_y = ( $target_height - $height ) / 2;
                                $end_x = ( ( $target_width - $width ) / 2 ) + $width;
                                $end_y = ( ( $target_height - $height ) / 2 ) + $height;
                            }
                            break;
                        // RIGHT
                        case 6 : {
                                $start_x = $target_width - $width;
                                $start_y = ( $target_height - $height ) / 2;
                                $end_x = $target_width;
                                $end_y = ( ( $target_height - $height ) / 2 ) + $height;
                            }
                            break;
                        // BOTTOM LEFT
                        case 7 : {
                                $start_x = 0;
                                $start_y = $target_height - $height;
                                $end_x = $width;
                                $end_y = $target_height;
                            }
                            break;
                        // BOTTOM CENTER
                        case 8 : {
                                $start_x = ( $target_width - $width ) / 2;
                                $start_y = $target_height - $height;
                                $end_x = ( ( $target_width - $width ) / 2 ) + $width;
                                $end_y = $target_height;
                            }
                            break;
                        // BOTTOM RIGHT
                        case 9: {
                                $start_x = $target_width - $width;
                                $start_y = $target_height - $height;
                                $end_x = $target_width;
                                $end_y = $target_height;
                            }
                            break;
                    }

                    return $this->_crop( $start_x, $start_y, $end_x, $end_y, $canvas );
                }
            } else {
                $canvas = $this->_create_canvas( ( $width > 0 && $height > 0 ? $width : $target_width ), ( $width > 0 && $height > 0 ? $height : $target_height ), $background );
                imagecopyresampled(
                        $canvas, $this->resource, ( $width > 0 && $height > 0 ? ( $width - $target_width ) / 2 : 0 ), ( $width > 0 && $height > 0 ? ( $height - $target_height ) / 2 : 0 ), 0, 0, $target_width, $target_height, $this->source_width, $this->source_height );
            }

            return $this->final_image = $canvas;
        }

        return false;
    }

    function resize( $width = 0, $height = 0, $background = null, $resource = null ) {
        return $this->_resize( $width, $height, $background, $resource );
    }

    /**
     * create resource from provided source method
     */
    function _create_resource() {
        if ( !function_exists( 'gd_info' ) ) {
            echo 'no function';
            return false;
        } else if ( !is_file( $this->source ) ) {
            echo 'no source file';
            return false;
        } else if ( !is_readable( $this->source ) ) {
            echo 'no source readble';
            return false;
        } /*else if ( !is_writable( FOLDER ) ) {
            echo 'no target writeable';
            return false;
        }*/ else {
            list ( $this->source_width, $this->source_height, $this->source_type ) = getimagesize( $this->source );
            $this->target_extension = $this->get_extension( $this->target );

            switch ( $this->source_type ) {
                // GIF
                case 1: case IMAGETYPE_GIF: {
                        $resource = imagecreatefromgif( $this->source );
                        $this->gif_transparent_index = imagecolortransparent( $resource );
                        if ( $this->gif_transparent_index >= 0 ) {
                            $this->gif_transparent_color = imagecolorsforindex( $resource, $this->gif_transparent_index );
                        }
                    }
                    break;

                // JPG
                case 2: case IMAGETYPE_JPEG: {
                        $resource = imagecreatefromjpeg( $this->source );
                    }
                    break;

                //PNG
                case 3: case IMAGETYPE_PNG: {
                        $resource = imagecreatefrompng( $this->source );
                        imagealphablending( $resource, false );
                    }
                    break;
            }

            $this->resource = $resource;
            return true;
        }

        return false;
    }

    /**
     * Create a blank canvas with provided width and height
     */
    function _create_canvas( $width, $height, $background = null ) {
        $canvas = imagecreatetruecolor( ($width <= 0 ? (int) 1 : (int) $width ), ( $height <= 0 ? (int) 1 : (int) $height ) );
        if ( is_null( $background ) ) {
            $background = $this->bgcolor;
        }

        if ( $this->target_extension == 'png' && $background == -1 ) {
            imagealphablending( $canvas, false );
            $color = imagecolorallocatealpha( $canvas, 0, 0, 0, 127 );
            imagefill( $canvas, 0, 0, $color );
            imagesavealpha( $canvas, true );
        } else if ( $this->target_extension == 'gif' && $this->gif_transparent_index >= 0 && $background == -1 ) {
            $color = imagecolorallocate( $canvas, $this->gif_transparent_color['red'], $this->gif_transparent_color['green'], $this->gif_transparent_color['blue'] );
            imagefill( $canvas, 0, 0, $color );
            imagecolortransparent( $canvas, $color );
        } else {
            if ( $background == -1 ) {
                $background = $this->bgcolor;
            }
            $color = $this->_hex2rgb( $background );
            $color = imagecolorallocate( $canvas, $color['r'], $color['g'], $color['b'] );
            imagefill( $canvas, 0, 0, $color );
        }

        return $canvas;
    }

    /**
     * get extension of provided source or from global source
     */
    function get_extension( $source = null ) {
        if ( is_null( $source ) ) {
            $source = $this->source;
        }

        return end( explode( ".", $source ) );
    }

    /**
     * Convert HEX ( #FFF|#FFFFFF ) to rgb( red, green, blue )
     */
    function _hex2rgb( $color ) {
        if ( $color == -1 ) {
            $color = $this->bgcolor;
        }

        $color = ltrim( $color, '#' );
        if ( strlen( $color ) == 3 ) {
            $tmp_code = '';
            for ( $i = 0; $i < 3; $i++ ) {
                $tmp_code .= str_repeat( $color[$i], 2 );
            }
            $color = $tmp_code;
        }

        list ( $r, $g, $b ) = str_split( $color, 2 );
        $rgb = array('r' => hexdec( $r ), 'g' => hexdec( $g ), 'b' => hexdec( $b ));
        return $rgb;
    }

    /**
     * crop method
     */
    function _crop( $x_start, $y_start, $x_end, $y_end, $resource = null ) {

        if ( $this->_check_resource( $resource ) ) {
            // Difference of end and start point is area that needs to be cropped
            $canvas = $this->_create_canvas( $x_end - $x_start, $y_end - $y_start );
            imagecopyresampled(
                    $canvas, $this->resource, 0, 0, $x_start, $y_start, $x_end - $x_start, $y_end - $y_start, $x_end - $x_start, $y_end - $y_start );

            return $this->final_image = $canvas;
        }
    }

    /**
     * find sharpness method
     * function from Ryan Rud (http://adryrun.com)
     */
    function findSharp( $orig, $final ) { 
        $final = $final * (750.0 / $orig);
        $a = 52;
        $b = -0.27810650887573124;
        $c = .00047337278106508946;
        $result = $a + $b * $final + $c * $final * $final;

        return max( round( $result ), 0 );
    }

    /**
     * method used to display image in browser
     */
    function display() {
        $image = @$this->final_image;
        if ( !is_resource( $image ) ) {
            $image = $this->_check_resource();
        } 

        {
            switch ( $this->target_extension ? $this->target_extension : $this->get_extension($this->source) ) {
                case "gif": {
                        header( 'Content-Type: image/gif' );
                        imagegif( $image );
                        $this->_destroy();
                    }break;

                case "jpg": case "jpeg": {
                        header( 'Content-Type: image/jpeg' );
                        imagejpeg( $image, null, $this->quality );
                        $this->_destroy();
                    }break;

                case "png" : {
                        header( 'Content-Type: image/png' );
                        imagepng( $image, null, $this->png_quality );
                        $this->_destroy();
                    }break;
            }
        }
    }

    /**
     * method used to save image to provided $this->target
     */
    function save() {
        $image = @$this->final_image;
        if ( !is_resource( $image ) ) {
            $image = $this->_check_resource();
        }

        if ( is_resource( $image ) ) {

            if ( (IMAGETYPE_PNG == $this->source_type || IMAGETYPE_GIF == $this->source_type ) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ) {
                imagetruecolortopalette( $image, false, imagecolorstotal( $image ) );
            }

            switch ( $this->target_extension ) {

                case "gif": {
                        if ( !imagegif( $image, $this->target ) ) {
                            echo 'GIF Error';
                            return false;
                        } else {
                            $this->_destroy();
                        }
                    }
                    break;

                case "jpg": case "jpeg": {
                        if ( !imagejpeg( $image, $this->target, $this->quality ) ) {
                            echo 'JPG Error';
                            return false;
                        } else {
                            $this->_destroy();
                        }
                    }
                    break;

                case "png": {
                        if ( !imageistruecolor($image) && function_exists('imageistruecolor') ) {
                            imagetruecolortopalette( $image, false, imagecolorstotal($image) );
                        }

                        if ( !imagepng( $image, $this->target, $this->png_quality ) ) {
                            echo 'PNG Error';
                            return false;
                        } else {
                            $this->_destroy();
                        }
                    }
                    break;
            }
        } else {
            echo 'Error Occured';
            return false;
        }
    }
    
    /**
     * flipping the image
     */
    function _flip( $side, $resource = null ) {
        if ( $this->_check_resource( $resource ) ) {
            $canvas = $this->_create_canvas( $this->source_width, $this->source_height . -1 );

            switch ( $side ) {
                case "vertical": {
                        imagecopyresampled(
                                $canvas, $this->resource, 0, 0, 0, ( $this->source_height - 1 ), $this->source_width, $this->source_height, $this->source_width, -$this->source_height );
                    }break;
                case "horizontal": {
                        imagecopyresampled(
                                $canvas, $this->resource, 0, 0, ( $this->source_width - 1 ), 0, $this->source_width, $this->source_height, -$this->source_width, $this->source_height );
                    }break;
                case "both": {
                        imagecopyresampled(
                                $canvas, $this->resource, 0, 0, ( $this->source_width - 1 ), ( $this->source_height - 1 ), $this->source_width, $this->source_height, -$this->source_width, -$this->source_height );
                    }break;
                default: {
                        return false;
                    }break;
            }

            $this->final_image = $canvas;
            return $this->final_image;
        }
        return false;
    }

    /**
     * rotating the image
     */
    function _rotate( $angle, $background = null, $resource = null) {
        if ( is_null( $background ) ) {
            $background = $this->bgcolor;
        }

        if ( $this->_check_resource( $resource) ) {
            $angle = -$angle;

            if ( $this->source_type == IMAGETYPE_PNG && $background == -1 ) {
                // Because PNG8 fails to rotate, fill with default background color
                if ( !$this->final_image = imagerotate( $this->resource, $angle, -1 ) ) {
                    $color = $this->_hex2rgb( $this->bgcolor );
                    $background = imagecolorallocate( $this->resource, $color['r'], $color['g'], $color['b'] );
                    $this->final_image = imagerotate( $this->resource, $angle, $background );
                }
            } else if ( $this->source_type == IMAGETYPE_GIF && $this->gif_transparent_index >= 0 ) {
                $c = $this->_hex2rgb( $background );
                $background_color = imagecolorallocate( $this->resource, $c['r'], $c['g'], $c['g'] );
                $this->resource = imagerotate( $this->resource, $angle, $background_color );

                // Transpareney is messed-up, we will recreate photo with imagecopysampled to
                // preserve transparency
                $width = imagesx( $this->resource );
                $height = imagesy( $this->resource );

                $canvas = $this->_create_canvas( $width, $height, -1 );
                $this->final_image = imagecopyresampled( $canvas, $this->resource, 0, 0, 0, 0, $width, $height, $width, $height );
            } else {
                $c = $this->_hex2rgb( $background );
                $background_color = imagecolorallocate( $this->resource, $c['r'], $c['g'], $c['b'] );
                $this->final_image = imagerotate( $this->resource, $angle, $background_color );
            }

            return $this->final_image;
        }
    }

    /**
     * used to apply sharpness on image
     */
    function _sharpit( $resource = null ) {
        
        if ( !function_exists('imageconvolution') ) {
            return false;
        }
        
        if ( $this->_check_resource( $resource ) ) {
            $sharpness = $this->findSharp( $this->source_width, $this->target_width );
            $sharpenMatrix = array(
                array(-1, -1, -1),
                array(-1, $sharpness + 10, -2),
                array(-1, -1, -1)
            );
            $divisor = $sharpness;
            $offset = 0;
            // apply the matrix 
            imageconvolution( $this->resource, $sharpenMatrix, $divisor, $offset );
            $this->final_image = $this->resource;
            return $this->final_image;
        }
    }

    /**
     * Rotate left using _rotate method
     */
    function rotate_left( $background = null, $resource = null ) {
        return $this->_rotate( -90, $background, $resource );
    }

    /**
     * Rotate right using _rotate method
     */
    function rotate_right( $background = null, $resource = null ) {
        return $this->_rotate( 90, $background, $resource );
    }

    /**
     * Flip the image vertically and horizontally
     */
    function flip( $resource = null) {
        return $this->_flip( "both" , $resource);
    }

    /**
     * Flip the image vertically
     */
    function flip_vertical( $resource = null ) {
        return $this->_flip( "vertical" , $resource );
    }

/**
     * Flip the image horizontally
     */
    function flip_horizontal( $resource = null ) {
        return $this->_flip( "horizontal" , $resource );
    }

    /**
     * This method checks which resource to use
     * 1 - If resource provided to function, used that else call _create_resource()
     * 2 - If already has a resource and final_image, means we are working on some image
     *       apply all other methods to that resource
    * 3 - _create_resource() from Source provided
     */
    function _check_resource( $resource = null ) {
        if ( !is_null( $resource) && is_resource($resource) ) {
            $this->resource = $resource;
        } else if ( 
                $this->auto_resource == true && 
                isset($this->resource) && is_resource( $this->resource) &&
                isset( $this->final_image ) && is_resource( $this->final_image )
        ) {
            $this->resource = $this->final_image;
            $this->source_width = imagesx( $this->resource );
            $this->source_height = imagesy( $this->resource );
        } else {
            $resource = $this->_create_resource();
        }
        
        if ( is_resource( $this->resource ) ) {
            return $this->resource;
        } else {
            return false;
        }
    }
    
    /**
     * Destroy the resources
     */
    function _destroy() {

        if ( isset($this->resource) ) {
            is_resource( $this->resource ) ? imagedestroy($this->resource) : false;
        }
        
       if ( isset($this->final_image) ) {
           is_resource($this->final_image) ? imagedestroy($this->final_image) : false;
       }

        unset( $this->final_image );
        unset( $this->resource );
    }
    
    /**
     * Setup filters, if we have correct php version
     */
    function _setup_filters() {
        /* Making sure imagefilter function exists */
        $php5 = substr(phpversion(),0,1);
        if
        (
                ( $php5 == 5 || $php5 > 5 ) 
                && function_exists('imagefilter')
                && defined('IMG_FILTER_NEGATE')
        ) {
            $this->filters = array(
                /* array is filter name and number of args it accepts */
                1 => array(IMG_FILTER_NEGATE,0),
                2 => array(IMG_FILTER_GRAYSCALE,0),
                3 => array(IMG_FILTER_BRIGHTNESS,1), // level,+/-
                4 => array(IMG_FILTER_CONTRAST,1), // level,+/-
                5 => array(IMG_FILTER_COLORIZE,4), // r-g-b-a
                6 => array(IMG_FILTER_EDGEDETECT,0),
                7 => array(IMG_FILTER_EMBOSS,0),
                8 => array(IMG_FILTER_GAUSSIAN_BLUR,0),
                9 => array(IMG_FILTER_SELECTIVE_BLUR,0),
                10 => array(IMG_FILTER_MEAN_REMOVAL,0),
                11 => array(IMG_FILTER_SMOOTH,1)
            );
            
            $this->filters_alias = array(
                'negative' => 1,
                'grayscale' => 2,
                'brightness' => 3,
                'contrast' => 4,
                'colorize' => 5,
                'edgedetect' => 6,
                'emboss' => 7,
                'gaussian_blur' => 8,
                'selective_blur' => 9,
                'mean_removal' => 10,
                'smooth' => 11
            );
            if ( phpversion() == '5.3.0' ) {
                $this->filters[12] = array('IMG_FILTER_PIXELATE',2);
                $this->filters_alias['pixelate'] = 12;
            }
            
            return $this->filters;
        }
    }
    
    /**
     * Apply filter to image resource
     */
    function apply_filter ( $filters, $resource = null ) {
        if ( isset( $this->filters) ) {
            if ( $this->_check_resource( $resource ) ) {
                // explode filters
                $usr_filters = explode( ":", $filters );
                if ( $usr_filters ) {
                    foreach ( $usr_filters as $filter ) {
                        $fs = explode(",",$filter);
                        
                        if ( !is_numeric( $fs[0] ) ) {
                            if ( isset( $this->filters_alias[ $fs[0] ]) ) {
                                $fs[0] = $this->filters_alias[ $fs[0] ];
                            } else {
                                continue;
                            }
                        }
                        
                         if ( isset( $this->filters[$fs[0]] ) ) {
                             for( $i=1; $i <= 4; $i++ ) {
                                 if ( isset( $fs[$i] ) ) {
                                     $fs[$i] = (int)$fs[$i];
                                 } else {
                                     $fs[$i] = null;
                                 }
                             }
                             // make number of args the switch
                             
                             switch( $this->filters[$fs[0]][1] ) {
                                 case 0: {
                                    imagefilter( $this->resource, $this->filters[$fs[0]][0] );
                                 }break;
                             
                                 case 1: {
                                    imagefilter( $this->resource, $this->filters[$fs[0]][0], $fs[1] ); 
                                 }break;
                             
                                 case 2: {
                                     imagefilter( $this->resource, $this->filters[$fs[0]][0], $fs[1], $fs[2] );
                                 }break;
                             
                                 case 3: {
                                     imagefilter( $this->resource, $this->filters[$fs[0]][0], $fs[1], $fs[2], $fs[3] );
                                 }break;
                             
                                 case 4: {
                                     imagefilter( $this->resource, $this->filters[$fs[0]][0], $fs[1], $fs[2], $fs[3], $fs[4] );
                                 }break;
                             }
                         }
                    }
                    $this->final_image = $this->resource;
                    return $this->final_image;
                }
            }
        }
    }

    /**
     * Extract color palette from image
     */
    function color_palette( $resource = null ) {
        if ( $this->_check_resource( $resource ) ) {

            if ( $this->source_width > 600 ) {
                // Source is greater than 600, resize it to 600
                $this->_resize( 600 );
                $width = $this->target_width;
                $height = $this->target_height;
            } else {
                $width = $this->source_width;
                $height = $this->source_height;
            }

            $colors = array();

            /* Thanks to NogDog: http://board.phpbuilder.com/board/showpost.php?p=10868783&postcount=2 */
            for( $x = 0; $x < $width; $x+=12 ) {
                for ($y=0; $y < $height; $y+=12 ) { 
                     $thisColor = imagecolorat($this->resource, $x, $y); 
                     $rgb = imagecolorsforindex($this->resource, $thisColor); 
                     $red = round( round(($rgb['red'] / 0x33)) * 0x33 ); 
                     $green = round( round(($rgb['green'] / 0x33)) * 0x33 ); 
                     $blue = round( round(($rgb['blue'] / 0x33)) * 0x33 );
                     $thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue);
                     if(array_key_exists($thisRGB, $colors)) 
                     { 
                        $colors[$thisRGB]++; 
                     } 
                     else 
                     { 
                        $colors[$thisRGB] = 1; 
                     } 
                }
            }

            arsort($colors);
            $this->_destroy(); // free the memory by destroying resource
            $total_colors = array_sum($colors);

            foreach( $colors as $color => $count ) {
                $ccolors[ $color ] = array(
                    'color' => '#'.$color,
                    'rgb' => implode( ",", $this->_hex2rgb('#'.$color) ),
                    'count' => $count,
                    'percent' => round( ( $count / $total_colors ) * 100, 3 )
                );
            }

            return $ccolors;
        }
    }

    function watermark( $watermark = null, $resource = null ) {
        if ( $this->_check_resource( $resource ) ) {
            if ( !is_null($watermark) && file_exists( $watermark ) && is_file( $watermark ) && strtolower(end(explode(".",$watermark))) == 'png' ) {
                $resource = $this->resource;

                $this->watermark = $wresource = imagecreatefrompng( $watermark );
                $this->watermark_width = $wrx = imagesx($wresource);
                $this->watermark_height = $wry = imagesy($wresource);
                
                list ( $destX, $destY ) = $this->watermark_placement();

                $cut = imagecreatetruecolor($wrx, $wry);

                imagecopy( $cut, $resource, 0, 0, $destX, $destY, $wrx, $wry );
                imagecopy( $cut, $wresource, 0, 0, 0, 0, $wrx, $wry );
                imagecopymerge( $resource, $cut, $destX, $destY, 0, 0, $wrx, $wry, 100 );
            } else {
                /* Above contained failed, we will now use a string watermark */
                //$this->string_watermark( $this->resource );
            }
            
            $this->final_image = $resource;
            return $this->final_image;
        }
    }

    /* Thanks to fodybrabec: http://www.php.net/manual/en/function.imagettfbbox.php#105593 */
    function calculate_font_box( $text ) {
        $font = $this->fonts_dir.$this->font;
        $rect = imageftbbox($this->font_size, 0, $font, $text );

        $minX = min(array($rect[0],$rect[2],$rect[4],$rect[6])); 
        $maxX = max(array($rect[0],$rect[2],$rect[4],$rect[6])); 
        $minY = min(array($rect[1],$rect[3],$rect[5],$rect[7])); 
        $maxY = max(array($rect[1],$rect[3],$rect[5],$rect[7])); 

        return array( 
         "left"   => abs($minX) - 1, 
         "top"    => abs($minY) - 1, 
         "width"  => $maxX - $minX, 
         "height" => $maxY - $minY, 
         "box"    => $rect 
        ); 
    }

    function string_watermark ( $resource = null ) {
        if ( $this->_check_resource ( $resource ) ) {
                $font_file = $this->fonts_dir.$this->font;
                $this->font_size = round( ( ( $this->source_width + $this->source_height ) / 96 ) * 0.75 +14 );
                $text = strtoupper( TITLE );
                $color = imagecolorallocate($this->resource, 235, 235, 235 );
                $shadow = imagecolorallocate($this->resource, 0, 0, 0 );
                $return = $this->calculate_font_box( $text );
                list ( $x, $y ) = $this->watermark_placement( $return );

                imagefttext( $this->resource, $this->font_size, 0, $x, $y - 1, $shadow, $font_file, $text );
                imagefttext( $this->resource, $this->font_size, 0, $x, $y, $color, $font_file, $text );
        }
    }

    function watermark_placement( $string = false ) {
        if ( !$this->watermark_placement ) {
            $this->watermark_placement = "left:top";
        }

        list( $x, $y ) = explode(":", $this->watermark_placement );

        if ( $string == false && !is_array( $string )) {
            switch ( $x ) {
                case "left":
                default: {
                    $x = $this->watermark_padding;
                }
                break;

                case "center": {
                    $x = ( $this->source_width - $this->watermark_width ) / 2;
                }
                break;

                case "right": {
                    $x = ( $this->source_width - $this->watermark_width ) - $watermark_padding;
                }
                break;
            }

            switch ( $y ) {
                case "top":
                default: {
                    $y = $this->watermark_padding;
                }
                break;

                case "center": {
                    $y = ( $this->source_height - $this->watermark_height ) / 2;
                }
                break;

                case "bottom" : {
                    $y = ( $this->source_height - $this->watermark_height ) - $this->watermark_padding;
                }
                break;
            }
        } else {
            switch( $x ) {
                case "left":
                default: {
                    $x = $string['left'] + $this->watermark_padding;
                }
                break;

                case "center": {
                    $x = ( $this->source_width - $string['width'] ) / 2;
                }
                break;

                case "right": {
                    $x = ( $this->source_width - $string['width'] ) - $this->watermark_padding;
                }
                break;

            }

            switch( $y ) {
                case "top":
                default: {
                    $y = $string['top'] + $this->watermark_padding;
                }
                break;

                case "center": {
                    $y = ( $this->source_height + $string['height'] ) / 2;
                }
                break;

                case "bottom": {
                    $y = ( $this->source_height - $string['height'] ) + $this->watermark_padding;
                }
                break;
            }
        }

        return array( round( $x ), round( $y ) );
    }
}

?>