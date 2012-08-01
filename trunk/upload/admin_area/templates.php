<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
$userquery->perm_check('manage_template_access',true);

if ( $_GET['delete'] ) {
    $to_delete = mysql_clean($_GET['delete']); 
    if ( $cbtpl->delete_template( $to_delete ) ) {
        e(lang('Template deleted'),'m');
    }
}

if($_GET['change'])
{
	$myquery->set_template($_GET['change']);
}

if ( isset($_POST['delete_selected']) ) {
    $total = count($_POST['check_template']);
    if ( $total > 0 ) {
        for( $i=0; $i<$total; $i++ ) {
            $temp = $_POST['check_template'][$i];
            $cbtpl->delete_template( $temp );
        }
        $eh->flush();
        e( lang('Selected templates deleted'), 'm' );
    } else {
      e( lang('Select templates you want to delete') );  
    }
}

subtitle("Template Manager");
template_files('templates.html');
display_it();
?>