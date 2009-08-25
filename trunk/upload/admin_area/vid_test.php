<?php
/**
#########################################################################################################
# Copyright (c) 2007-2008 ClipBucket / PHPBucket.com. All Rights Reserved.
# [url]http://clip-bucket.com[/url]
# Module:      Video Component Test
# Author:      fwhite
# Language:    PHP + xHTML + CSS
# License:     CBLA @ [url]http://cbla.cbdev.org/[/url]
# Version:     1.7 SVN
# Notice:      Please maintain this section
#########################################################################################################
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

Template('header.html');
Template('leftmenu.html');
Template('message.html');
test_exec( 'bash -version' );
test_exec( 'which ffmpeg' );
test_exec( 'which mencoder' );
test_exec( 'which mplayer' );
test_exec( 'which flvtool2' );
test_exec( 'which php' );

Template('footer.html');
?>