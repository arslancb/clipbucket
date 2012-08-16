<?php

/**
 * @author : Arslan Hassan
 * @since : 3.0 , 8-13-2012
 */

if(!defined('IN_CLIPBUCKET')) 
    exit("Nothing to do here..");



/**
 * load plupload block..
 * @return type
 */
function load_plupload_block()
{
    $template = fetch(MODULES_DIR.'/uploader/pluploader.html');
    return $template;
}


/**
 * Registering our new-cute upload option
 */

register_upload_option(array(
    'title' => lang('Upload from computer'),
    'description' => lang('upload video files from your computer, supports variety of formats including mp4,mkv,avi and many other.'),
    'function' => 'load_plupload_block',
));

/**
 * Adding plupload JS files..
 */
$plupload_js_files = array(
    MODULES_URL.'/uploader/plupload/plupload.js',
    MODULES_URL.'/uploader/plupload/plupload.html5.js',
    MODULES_URL.'/uploader/plupload/plupload.flash.js',
   // MODULES_URL.'/uploader/plupload/jquery.plupload.queue/jquery.plupload.queue.js',
   // MODULES_URL.'/uploader/plupload/jquery.ui.plupload/jquery.ui.plupload.js'
);
add_js($plupload_js_files,'upload');

/**
 * Adding plupload CSS files
 */
$plupload_css_files = array(
    MODULES_URL.'/uploader/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css',
    MODULES_URL.'/uploader/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css'
);
//add_css($plupload_css_files,'upload');

?>