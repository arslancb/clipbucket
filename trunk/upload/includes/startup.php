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
?>
