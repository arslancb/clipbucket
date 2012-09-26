<?php

/*
 * As the name suggest, this file make everything ready for website
 * to operate before it goes to give an output
 */

include(BASEDIR.'/modules/uploader/uploader.php');


/***
 * Adding custom thumb sizes
 */
add_thumb_size('120x60','default');
add_thumb_size('160x120');
add_thumb_size('300x250');
add_thumb_size('640x360');
add_thumb_size('original');



/**
 * Register metas
 */
$cbvid->register_meta('thumbs');

/**
 * Registering Photo Embed codes
 */
add_photo_embed_type(lang('HTML Code'), 'html-code', 'photo_html_code');
add_photo_embed_type(lang('Email or IM'), 'email-code', 'photo_email_code');
add_photo_embed_type(lang('BB Code'), 'bb-code', 'photo_bb_code');
add_photo_embed_type(lang('Linked BB Code'), 'bb-code-linked', 'photo_bb_code_linked');
add_photo_embed_type(lang('BB Code Alterantive'), 'bb-code-alternative', 'photo_bb_code_alt');
add_photo_embed_type(lang('Linked BB Code Alterantive'), 'bb-code-alternative-linked', 'photo_bb_code_alt_linked');

add_custom_photo_size( 'Small Square', '75x75', 75, 75, (config('photo_crop') == 0 ? -1 : 5 ), false, true );
add_custom_photo_size( 'Medium Square', '100x100', 100, 100, (config('photo_crop') == 0 ? -1 : 5 ), false );
add_custom_photo_size( 'Big Square', '150x150', 150, 150, (config('photo_crop') == 0 ? -1 : 5 ) );
add_custom_photo_size( 'Thumb 240', '240', 240, 0, 10 );
add_custom_photo_size( 'Thumb 320', '320', 320, 0, 10 );
add_custom_photo_size( 'Medium 500', '500', 500, 0, 10, config('watermark_photo') );
add_custom_photo_size( 'Medium 640', '640', 640, 0, 10, config('watermark_photo') );
add_custom_photo_size( 'Large 800', '800', 800, 0, 10, config('watermark_photo') );
add_custom_photo_size( 'Large 1024', '1024', 1024, 0, 10, config('watermark_photo') );
?>
