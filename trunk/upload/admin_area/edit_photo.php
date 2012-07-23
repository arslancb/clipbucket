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
            
            $tags = $cbphoto->get_photo_tags( $id );
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