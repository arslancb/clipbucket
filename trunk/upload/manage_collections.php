<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : FawazTahir, ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE",'manage_collections');
define("PARENT_PAGE","collections");

require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
$order = tbl("collection_items").".date_added DESC";

$mode = $_GET['mode'];
$cid = mysql_clean($_GET['cid']);

assign("mode",$mode);
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,COLLPP);

$orders = get_collection_manager_orders();

switch($mode)
{
	case "manage":
	default:
	{
        if(isset($_GET['delete_collection']))
        {
          $cid = clean($_GET['delete_collection']);
          $cbcollection->delete_collection($cid);	
        }

        if($_POST['delete_selected'])
        {
          $count = count($_POST['check_col']);
          for($i=0;$i<$count;$i++)
          {
            $cbcollection->delete_collection($_POST['check_col'][$i]);	
          }
          $eh->flush();
          e("selected_collects_del","m");
        }

        $order_id = $_GET['omo'] ? mysql_clean($_GET['omo']) : (int)0;

        if ( !$orders[$order_id] ) {
           $order = tbl('collections.date_added desc');
        } else {
           $order = $orders[$order_id]['order'];
        }


        $collectArray = array('user'=>userid(),"limit"=>$get_limit, 'order' => $order );
        if(get('query') != '')
        {
            $collectArray['name'] = mysql_clean(get('query'));
            $collectArray['tags']	= mysql_clean(get('query'));
        }

        $usr_collections = $cbcollection->get_collections($collectArray);

        assign('usr_collects',$usr_collections);
        assign('collections', $usr_collections );
        $collectArray['count_only'] = TRUE;
        $total_rows = $cbcollection->get_collections($collectArray);
        assign( 'total_collections',$total_rows );
        $total_pages = count_pages($total_rows,COLLPP);

        //Pagination
        $pages->paginate($total_pages,$page);
        subtitle(lang("manage_collections"));
	}
	break;
	
	case "add_new":
	{
		$reqFields = $cbcollection->load_required_fields();
		$otherFields = $cbcollection->load_other_fields();
		
		assign("fields",$reqFields);
		assign("other_fields",$otherFields);
		
		if(isset($_POST['add_collection']))
		{
			$cbcollection->create_collection($_POST);
			
			if(!error()) $_POST = '';
		}
		
		subtitle(lang("create_collection"));
	}
	break;
	
	case "edit":
	case "edit_collection":
	case "edit_collect":
	{
			
		if(isset($_POST['update_collection']))
		{
			$cbcollection->update_collection($_POST);	
		}
		
		$collection = $cbcollection->get_collection($cid);
		$reqFields = $cbcollection->load_required_fields($collection);
		$otherFields = $cbcollection->load_other_fields($collection);
		
		assign("fields",$reqFields);
		assign("other_fields",$otherFields);
		assign('c',$collection);
		assign( 'collection', $collection );
    
		subtitle(lang("edit_collection"));		
	}
	break;
	
	case "collection_items":
	case "items":
	case "manage_items":
	{
		$collection = $cbcollection->get_collection($cid);
            $type = clean($_GET['type']);
            $type = confirm_collection_type( $type );
            $orders = get_photo_manager_orders();
            $order_id = $_GET['omo'] ? mysql_clean($_GET['omo']) : (int)0;

            if ( !$orders[$order_id] ) {
               $order = tbl('photos.date_added desc');
            } else {
               $order = $orders[$order_id]['order'];
            }
            
            photo_manager_link_callbacks();
            
		assign('type',$type);
		switch($type)
		{
			case "videos":
			{
				if(isset($_POST['delete_selected']))
				{
					$count = count($_POST['check_item']);
					for($i=0;$i<$count;$i++)
					{
						$cbvideo->collection->remove_item($_POST['check_item'][$i],$cid);
					}
					$eh->flush();
					e(sprintf("selected_items_removed","videos"),"m");
				}
				$objs = $cbvideo->collection->get_collection_items_with_details($cid,$order);
			}
			break;
			
			case "photos":
			{
				if ( isset($_GET['cover_photo']) ) {
					$cover_photo = mysql_clean( $_GET['cover_photo'] );
					if ( $cover_photo != $collection['cover_photo'] ) {
						if ( $cbcollection->object_in_collection( $cover_photo, $collection['collection_id'] ) ) {
							if ( $cbcollection->set_cover_photo( $cover_photo, $collection['collection_id'] ) ) {
								e('new_cover_photo_set','m');
							} else {
								e('unable_new_cover_photo');
							}
						} else {
							e('item_not_exist');	
						}
					}
				}
				
				if(isset($_POST['delete_selected']))
				{
					$count = count($_POST['check_item']);
					for($i=0;$i<$count;$i++)
					{
						$cbphoto->collection->remove_item($_POST['check_item'][$i],$cid);
						$cbphoto->make_photo_orphan($cid,$_POST['check_item'][$i]);
					}
					$eh->flush();
					e(sprintf("selected_items_removed","photos"),"m");
				}
        
				$objs = $cbphoto->collection->get_collection_items_with_details($cid,$order);
			}
			break;
		}
				
		assign('c',$collection);
    assign('collection',$collection);
		assign('objs',$objs);
		
		subtitle(lang("manage_collection_items"));
	}
	break;
	
	case "favorite":
	case "favorites": case "fav":
	{
		if(isset($_GET['remove_fav_collection']))
		{
			$cid = mysql_clean($_GET['remove_fav_collection']);
			$cbcollection->action->remove_favorite($cid);	
		}
		
		if(isset($_POST['remove_selected_favs']))
		{
			$total = count($_POST['check_col']);
			for($i=0;$i<$total;$i++)
			{
				$cbcollection->action->remove_favorite($_POST['check_col'][$i]);	
			}
			$eh->flush();
			e(sprintf(lang("total_fav_collection_removed"),$total),"m");
		}
		
		if(get('query')!='')
		{
			$cond = " (collection.collection_name LIKE '%".mysql_clean(get('query'))."%' OR collection.collection_tags LIKE '%".mysql_clean(get('query'))."%' )";
		}
		
		$col_arr = array("user"=>userid(),"limit"=>$get_limit,"order"=>tbl('favorites.date_added DESC'),"cond"=>$cond);
		$collections = $cbcollection->action->get_favorites($col_arr);
		assign('collections',$collections);
		
		$col_arr['count_only'] = TRUE;
		$total_rows  = $cbcollection->action->get_favorites($col_arr);
            assign( 'total_collections',$total_rows );
		$total_pages = count_pages($total_rows,COLLPP);
		
		//Pagination
		$pages->paginate($total_pages,$page);
		subtitle(lang("manage_favorite_collections"));
	}
		
}

template_files('manage_collections.html');
display_it();
?>