<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require 'includes/config.inc.php';

define( 'THIS_PAGE', 'user_content' );
$pages->page_redir();

$u = $_GET['user'];
$u = $u ? $u : $_GET['userid'];
$u = $u ? $u : $_GET['username'];
$u = $u ? $u : $_GET['uid'];
$u = $u ? $u : $_GET['u'];

$udetails = $userquery->get_user_details( $u, false, true );

if ( $udetails ) {
    $usercontent->__set_current_user( $udetails );
    
    assign("u",$udetails);    
    subtitle( get_current_object_heading() );
} else {
    	e(lang("usr_exist_err"));
	$Cbucket->show_page = false;
}

if( $Cbucket->show_page ) {
    Template('user_content.html');
} else {
    display_it();
}
?>
