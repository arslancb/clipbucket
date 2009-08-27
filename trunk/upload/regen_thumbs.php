<?php

/**
#########################################################################################################
# Copyright (c) 2008 ClipBucket / PHPBucket.com. All Rights Reserved.
# [url]http://clip-bucket.com[/url]
# Function:         Regenerate Thumbnails
# Author:           fwhite
# Language:         PHP
# License:          CBLA @ [url]http://cbla.cbdev.org/[/url]
# Version:          1.7 SVN
# Created:          November 8, 2008
# Last Modified:    November 11, 2008 / 2:01 AM EST (fwhite)
# Notice:           Please maintain this section
#########################################################################################################
*/

require_once('includes/conversion.conf.php');

if(!empty($_REQUEST['returnto']))
{
$returnto = $_REQUEST['returnto'];
}
else
{
$returnto = videos_link;
}

$is_admin = $userquery->admin_check();
if ($is_admin == 1)
{
if(!empty($_REQUEST['flv']) && !empty($_REQUEST['duration']))
{
$flv = $_REQUEST['flv'];
$duration = $_REQUEST['duration'];

$myquery->DeleteThumbs($flv);

$ffmpeg = new ffmpeg();
$ffmpeg->AssignGeneratedThumbs($flv,$duration,1);
header( 'Location: '.$returnto.'' ) ;
}
}
else
{
header( 'Location: '.$returnto.'' ) ;
}

?>