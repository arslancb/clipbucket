<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
//$userquery->login_check('photo_moderation');

if ( isset($_GET['delete_tag']) ) {
    $id = mysql_clean( $_GET['delete_tag'] );
    if ( $cbphoto->remove_photo_tag( $id ) ) {
        e(lang('Photo tag has been deleted successfully'),'m');
    }
}

$action = mysql_clean( $_POST['action'] );

switch( $action ) {
    case 'delete_selected': {
        $tags = $_POST['check_tag'];
        $total_tags = count( $tags );
        foreach( $tags as $tag_id ) {
            $tag_id = mysql_clean($tag_id);
            $cbphoto->remove_photo_tag( $tag_id );
        }
        $eh->flush_msg();
        e( lang('Selected photo tags has been deleted successfully'), 'm' );
    } break;
}

$array = array();
$array['join_photos'] = true;

if( isset($_GET['search']) ) {
    $array['tag'] = $_GET['tag'];
    $array['order'] = $_GET['order'];
    $array['tagger'] = $_GET['tagger'];
    $array['tagged'] = $_GET['tagged'];
    $array['orderby'] = $_GET['orderby'];
    $array['user_tagged_only'] = $_GET['only_user'];
}

$array['order'] = $array['order'] ? $array['order'] : 'date_added';
$array['orderby'] = $array['orderby'] ? $array['orderby'] : 'desc';
$array['order'] = tbl('photo_tags.'.$array['order'].' '.$array['orderby']);

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array['limit'] = $get_limit;

$tags = $cbphoto->get_tags( $array );
//echo $db->db_query;
assign('tags',$tags);

$array['count_only'] = true;
$total_rows = $cbphoto->get_tags( $array );
$total_pages = count_pages($total_rows,RESULTS);
$pages->paginate($total_pages,$page);

subtitle("Photo Tags Manager");
template_files('tags_manager.html');
display_it();
?>
