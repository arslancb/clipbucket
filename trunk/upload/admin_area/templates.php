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
    $dir = mysql_clean( $_GET['change'] );
      if ( is_template_hidden( $dir ) ) {
          show_the_template( $dir );
      }
}

if ( $_GET['hide'] ) {
    $tpl_dir = mysql_clean( $_GET['hide'] );
    hide_the_template( $tpl_dir );
}

if ( $_GET['show'] ) {
    $tpl_dir = mysql_clean( $_GET['show'] );
    show_the_template( $tpl_dir );
}

if ( $_POST['do-action'] ) {
    $do_action = mysql_clean( $_POST['do-action'] );
    switch( $do_action ) {
        case "upload-theme":
        default: 
        {
            assign( "uploading_theme", true );
            $theme_file = $_FILES['theme-file'];
            $messages = upload_new_theme( $theme_file );
        }
        break;
    }
    
    assign( 'messages', $messages );
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