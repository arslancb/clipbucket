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

$id = mysql_clean($_GET['collection']);
$type = mysql_clean($_GET['type']);
$type = confirm_collection_type( $type );
$data = $cbcollection->get_collection($id);

switch($type)
{
	case "photos":
	{
            $action = $_GET['action']; $pid = $_GET['id'];
            if ( $action && $pid ) {
                 if ( $cbcollection->object_in_collection($pid,$data['collection_id']) ) {
                    if ( $cbphoto->photo_exists( $pid ) ) {
                         switch( $action ) {
                            case 'delete': {
                                $cbphoto->delete_photo( $pid, true );
                                e( lang('Photo has been deleted successfully'), 'm' );
                            }break;

                            case 'orphan': {
                                $cbphoto->collection->remove_item($pid,$data['collection_id']);
                                $cbphoto->make_photo_orphan($data['collection_id'],$pid);
                                e( lang('Photo has been made orphan successfully'), 'm' );
                            }break;

                            case 'activate': {
                                $cbphoto->photo_actions('activate',$pid);
                                //e( lang('Photo has been activated'), 'm' );
                            }break;

                            case 'deactivate': {
                                $cbphoto->photo_actions('deactivate',$pid);
                                //e( lang('Photo has been deactivated'), 'm' );
                            }break;

                            default: {
                                e(lang('No or unsupported action provided'));
                            }break;
                        }
                    } else {
                        e( lang('Photo does not exist') );
                    }
                } else {
                    e( lang('Item does not exist in collection') );
                }
            }
                       
            /* Remove multiple */
		if(isset($_POST['remove_selected']))
		{
			$total = count($_POST['check_obj']);
			for($i=0;$i<$total;$i++)
			{
				$cbphoto->collection->remove_item($_POST['check_obj'][$i],$id);
                    if ( $_POST['multi_action'] == 'orphan' ) {
                        $cbphoto->make_photo_orphan($id,$_POST['check_obj'][$i]);
                        $msg = $total." photos have been made orphans successfully.";
                    }else if ( $_POST['multi_action'] == 'delete' ) {
                        $cbphoto->delete_photo( $_POST['check_obj'][$i], true );
                        $msg = $total." photos have been deleted successfully.";
                    } else {
                        $cbphoto->photo_actions( 'deactivate', $_POST['check_obj'][$i] );
                        $msg = $total." photos have been deactivated.";
                    }
			}
			$eh->flush();
			e($msg,"m");
		}
		
            /* Activate mulitple */
            if ( isset($_POST['activate_selected']) ) {
                $total = count($_POST['check_obj']);
                for($i=0;$i<$total;$i++) {
                    $cbphoto->photo_actions('activate',$_POST['check_obj'][$i]);
                }
                $eh->flush();
			e($total." photos have been activated.","m");
            } 
            
            /* Deactivate Multiple */
            if ( isset($_POST['deactivate_selected']) ) {
                $total = count($_POST['check_obj']);
                for( $i=0; $i<$total; $i++ ) {
                    $cbphoto->photo_actions('deactivate',$_POST['check_obj'][$i]);
                }
                $eh->flush();
                e(lang($total." photos have been deactivated."),'m');
            }
            /* Move multiple */
		if(isset($_POST['move_selected']))
		{
			$total = count($_POST['check_obj']);
			$new = mysql_clean($_POST['collection_id']);
                /**
                 * Checking if collection is authentic
                 */
                 if ( !$new ) {
                     e( lang('No collection selected') );
                 } else if( $new == $data['collection_id']) {
                     e( lang('You have selected the same collection') );
                 } else if( !$cbcollection->collection_exists( $new ) ) {
                     e( lang('Collection does not exist') );
                 } else {
                     $new_c = $cbcollection->get_collection( $new );
                     if ( !$cbcollection->is_collection_owner( $new_c, $data['userid']) && !has_access('admin_access',true) ) {
                         e( lang('You does not own this collection') );
                     } else if ( !$cbcollection->is_collection_owner( $new_c, $data['userid']) && has_access('admin_access',true) ) {
                         e( lang('You can not move photos across different user collections. ') );
                     } else {
                         for($i=0;$i<$total;$i++)
                        {
                          $cbphoto->collection->change_collection($new,$_POST['check_obj'][$i],$id);
                          $db->update(tbl('photos'),array('collection_id'),array($new)," collection_id = $id AND photo_id = ".$_POST['check_obj'][$i]."");
                        }
                        $eh->flush();
                        e($total." photo(s) have been moved to <strong><a href='manage_items.php?collection=".$new_c['collection_id']."&type=".$new_c['type']."'>".get_collection_field($new,'collection_name')."</a></strong>","m");
                     }
                 }				
		}
		
		$items = $cbphoto->collection->get_collection_items_with_details($id);
		$collection = $cbphoto->collection->get_collections(array("type"=>"photos","user"=>$data['userid']));
	}
	break;
	
	case "videos":
	{
		
		if(isset($_POST['remove_selected']))
		{
			$total = count($_POST['check_obj']);
			for($i=0;$i<$total;$i)
			{
				$cbvideo->collection->remove_item($_POST['check_obj'][$i],$id);
			}
		}
		
		if(isset($_POST['move_selected']))
		{
			$total = count($_POST['check_obj']);
			$new = mysql_clean($_POST['collection_id']);
			for($i=0;$i<$total;$i++)
			{
				$cbvideo->collection->change_collection($new,$_POST['check_obj'][$i],$id);
			}
			$eh->flush();
			e($total." video(s) have been moved to '<strong>".get_collection_field($new,'collection_name')."</strong>'","m");
				
		}
		
		$items = $cbvideo->collection->get_collection_items_with_details($id);
		$collection = $cbvideo->collection->get_collections(array("type"=>"videos","user"=>$data['userid']));
	}
}



assign('data',$data);
assign('obj',$items);
assign('type',$type);
assign('c',$collection);

subtitle("Manage Items");
template_files('manage_items.html');
display_it();
?>