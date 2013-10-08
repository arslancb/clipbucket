<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 9/3/13
 * Time: 11:38 AM
 * To change this template use File | Settings | File Templates.
 */


function get_photo_fields( $extra = null ) {
    global $cbphoto;
    return $cbphoto->get_fields( $extra );
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