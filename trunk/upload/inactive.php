<?php
/*
**********************************************************
| Copyright (c) 2007 Clip-Bucket.com. All rights reserved.
| @ Author : Fwhite; ArslanHassan                         
| @ Software : ClipBucket , © PHPBucket.com              
**********************************************************
*/
require 'includes/config.inc.php';
Assign('subtitle',$LANG['vdo_iac_msg']);
Template('header.html');
Template('message.html');
Template('inactive.html');
Template('footer.html');
?>