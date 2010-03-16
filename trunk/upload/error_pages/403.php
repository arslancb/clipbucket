<?php
/**
#########################################################################################################
# Copyright (c) 2008 ClipBucket / PHPBucket.com. All Rights Reserved.
# [url]http://clip-bucket.com[/url]
# Function:          HTTP Error Code 403
# Author:            fwhite
# Language:          PHP
# License:           Attribution Assurance License @ [url]http://www.opensource.org/licenses/attribution.php/[/url]
# Version:           1.6 SVN
# Created:           Friday, December 26, 2008 / 1:13 AM EST (fwhite)
# Last Modified:     Friday, December 26, 2008 / 1:13 AM EST (fwhite)
# Notice:            Please maintain this section
#########################################################################################################
*/

require '../includes/config.inc.php';
subtitle('403');
Template('header.html');
Template('message.html');
Template('403.html');
Template('footer.html');
?>