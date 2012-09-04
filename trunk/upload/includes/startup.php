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
add_thumb_size('same', 'original');
?>
