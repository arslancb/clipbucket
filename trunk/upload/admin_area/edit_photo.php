<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();
register_filter('photo_manager_links','cb_some_photo_plugin_links');
$id = mysql_clean($_GET['photo']);


if(isset($_POST['update']))
{
	$cbphoto->update_photo();		
}

//Performing Actions
if($_GET['mode']!='')
{
	$cbphoto->photo_actions($_GET['mode'],$id);
}
$p	= $cbphoto->get_photo($id, true);

if ( isset($_GET['view']) ) {
    $view = trim($_GET['view']);
    switch( $view ) {
        case "tags": case "ptags": {
            assign('view','tags');
            
            if ( isset($_GET['delete_tag']) ) {
                $tag_id = mysql_clean( $_GET['delete_tag'] );
                if ( $cbphoto->remove_photo_tag( $tag_id ) ) {
                    e(lang('Photo tag has been deleted successfully'),'m');
                }
            }
            
            $action = mysql_clean( $_POST['action'] );

            switch( $action ) {
                case 'delete_selected': {
                    $tags = $_POST['check_tag'];
                    $total_tags = count( $tags );
                    if ( $total_tags > 0 ) {
                        foreach( $tags as $tag_id ) {
                            $tag_id = mysql_clean($tag_id);
                            $cbphoto->remove_photo_tag( $tag_id );
                        }
                         $eh->flush_msg();
                        e( lang('Selected photo tags have been deleted successfully'), 'm' );
                    } else {
                        e( lang('Please select tags you want to delete') );
                    }
                } break;
            }            

            $array = array();
            $array['pid'] = $id;
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
            
            $tags = $cbphoto->get_photo_tags( $array );
            if ( $tags ) {
                assign('tags',$tags);
            }
            //pr( $tags, true );
            subtitle("Photo Tags");
        }
        break;
        
        case "exif": case "exif_data": {
            assign('view','exif');
            $exif = get_photo_meta_value( $id, 'exif_data');
            if ( $exif ) {
                $exif = json_decode( $exif, true );

                $template_ready_data = ready_exif_data( $exif, $p );
                
                assign( 'photo', $photo );
                assign('exif', $template_ready_data );
                
                subtitle( 'Exif Data' );
            }
        }
        break;
    }
}


$p['user'] = $p['userid'];

assign('data',$p);

$requiredFields = $cbphoto->load_required_forms($p);
$otherFields = $cbphoto->load_other_forms($p);
assign('requiredFields',$requiredFields);
assign('otherFields',$otherFields);

subtitle("Edit Photo");
template_files('edit_photo.html');
display_it();
?>