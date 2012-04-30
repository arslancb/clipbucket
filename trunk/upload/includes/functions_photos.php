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
?>