<?php

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
       global $cbphoto;
       return $cbphoto->getFileSmarty($params);
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

function load_photo_controls ( $args ) {
  global $cbphoto;
  if ( !empty($args['photo'])) {
    $controls = explode(',',$args['controls']);
    $controls = array_map('trim', $controls);
    foreach ($controls as $control) {
      $control_args = null;
      // Parameters for this controls
      $control_args = $args[$control];
      $method_to_call = 'load_'.$control;
      if ( method_exists($cbphoto, $method_to_call) ) {
        $cbphoto->$method_to_call( $args['photo'], $control_args); // Call the method
      }
    }
  }
}

function get_original_photo( $photo ) {
	global $cbphoto;
	if ( !is_array($photo) ) {
		$ph = $cbphoto->get_photo($photo);	
	} else {
		$ph = $photo;	
	}
	
	if ( is_array($ph) ) {
		$files = $cbphoto->get_image_file( $ph, 'o', true, null, false, true);
		$orig = $ph['filename'].'.'.$ph['ext'];
		$file = array_find( $orig, $files );
		return $file;			
	}
}

function insert_photo_colors( $photo ) {
	global $db, $cbphoto;	
	
	if ( !is_array($photo) ) {
		$ph = $cbphoto->get_photo( $photo );	
	} else {
		$ph = $photo;	
	}
		
	if ( is_array($ph) && isset($ph['photo_id']) ) {
		
		if ( $id =  $db->select( tbl('photosmeta'),'pmeta_id'," photo_id = '".$ph['photo_id']."' AND meta_name = 'colors' " ) ) {
			return $id;	
		}
		
		$dir = PHOTOS_DIR.'/';
		$file = get_original_photo( $ph );
		$path = $dir.$file;
		
		if ( file_exists($path) ) {
			$img = new CB_Resizer( $path );
			$colors = $img->color_palette();
			if ( $colors ) {
				$jcolors = json_encode( $colors );
				$insert_id = $db->insert( tbl('photosmeta'), array('photo_id','meta_name','meta_value'), array($ph['photo_id'],'colors','|no_mc|'.$jcolors) );	
				if ( $insert_id ) {
					return $insert_id;	
				}
			}
		}
	}
}

function insert_exif_data( $photo ) {
	global $db, $cbphoto;
	
	if ( !is_array($photo) ) {
		$ph = $cbphoto->get_photo( $photo );	
	} else {
		$ph = $photo;	
	}
	
	if ( is_array($ph) && isset($ph['photo_id']) ) {
		$dir = PHOTOS_DIR.'/';

		if ( strtolower($ph['ext']) != 'jpg' ) {
			/* return if photo is not jpg */
			return;	
		}
		
		$file = get_original_photo( $ph );
		$path = $dir.$file;
		if ( file_exists($path) ) {
			/* File exists. read the exif data. Thanks to CopperMine, Love you */
			$data = exif_read_data_raw( $path, 0);
			if ( isset($data['SubIFD']) ) {
				$exif_to_include = array('IFD0','SubIFD','IFD1','InteroperabilityIFD');
				foreach( $exif_to_include as $eti ) {
					if ( isset( $data[$eti]) )	 {
						$exif[$eti] = $data[$eti];	
					}
				}
				$jexif = json_encode($exif);
				/* add new meta of exif_data for current photo */
				$insert_id = $db->insert( tbl('photosmeta'), array('photo_id','meta_name','meta_value'), array($ph['photo_id'],'exif_data','|no_mc|'.$jexif) );
				if ( $insert_id ) {
					/* update photo has_exif to yes, so we know that this photo has exif data */
					$db->update( tbl($cbphoto->p_tbl), array('exif_data'), array('yes'), " photo_id = '".$ph['photo_id']."' " );
					
					return $insert_id;
				}
			}
		}
	}
}

function add_custom_photo_size( $code, $width = 0, $height = 0, $crop = 4, $watermark = false, $sharpit = false ) {
	global $cbphoto;
	$sizes = $cbphoto->thumb_dimensions;
	$code = strtolower( $code );
	
	if ( $code == 't' || $code == 'm' || $code == 'l' || $code == 'o' ) {
		return false;	
	}
	
	if ( !is_numeric( $width )  || !is_numeric( $height ) ) {
		return false;	
	}
	
	$sizes [ $code ] = array(
		'width' => abs( $width ),
		'height' => abs( $height ),
		'crop' => $crop,
		'watermark' => $watermark,
		'sharpit' => $sharpit
	);
	
	return  $cbphoto->thumb_dimensions = $sizes;
}
?>