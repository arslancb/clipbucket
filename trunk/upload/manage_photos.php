<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 **************************************************************
*/

define("THIS_PAGE",'manage_photos');

require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);

switch($mode)
{
	case "uploaded":
	default:
	{
		assign('mode','uploaded');
		if(isset($_GET['delete_photo']))
		{
			$id = mysql_clean($_GET['delete_photo']);
			$cbphoto->delete_photo($id);	
		}
		
		if(isset($_POST['delete_photos']))
		{
			$total = count($_POST['check_photo']);
			for($i=0;$i<$total;$i++)
			{
				$cbphoto->delete_photo($_POST['check_photo'][$i]);	
			}
			$eh->flush();
			e($total." photos has been deleted successfully","m");
		}
		
		$photo_arr = array("user"=>userid(),"limit"=>$get_limit, 'order'=>' date_added DESC');
		
		if(get('query') != '')
		{
			$photo_arr['title'] = mysql_clean(get('query'));
			$photo_arr['tags']	= mysql_clean(get('query'));
		}
		$photos = get_photos($photo_arr);
		assign('photos',$photos);
		
		//Collecting Data for Pagination
		$photo_arr['count_only'] = true;
		$total_rows  = get_photos($photo_arr);
		$total_pages = count_pages($total_rows,VLISTPP);
		
		//Pagination
		$pages->paginate($total_pages,$page);
		subtitle(lang("Manage Photos"));
	}
	break;
	
	case "favorite":
	{
		assign('mode','favorite');
		if($_GET['remove_fav_photo'])
		{
			$photo = mysql_clean($_GET['remove_fav_photo']);
			$cbphoto->action->remove_favorite($photo);
			updateObjectStats('fav','photo',$photo,'-');	
		}
		
		if($_POST['remove_fav_photos'])
		{
			$total = count($_POST['check_photo']);
			for($i=0;$i<$total;$i++)
			{
				$cbphoto->action->remove_favorite($_POST['check_photo'][$i]);
				updateObjectStats('fav','photo',$_POST['check_photo'][$i],'-');	
			}
			$eh->flush();
			e($total." photo(s) been removed from favorites","m");
		}
		
		if(get('query')!='')
		{
			$cond = " (photos.photo_title LIKE '%".mysql_clean(get('query'))."%' OR photos.photo_tags LIKE '%".mysql_clean(get('query'))."%' )";
		}
		
		$photo_arr = array('user'=>userid(),"limit"=>$get_limit,"cond"=>$cond);
		$photos = $cbphoto->action->get_favorites($photo_arr);
		assign('photos',$photos);
		
		$photo_arr['count_only'] = true;
		$total_rows  = $cbphoto->action->get_favorites($photo_arr);
		$total_pages = count_pages($total_rows,VLISTPP);
		
		//Pagination
		$pages->paginate($total_pages,$page);
		subtitle(lang("Manage Favorite Photos"));
	}
	break;
	
	case "orphan":
	{
		assign('mode','orphan');
		if(isset($_GET['delete_orphan_photo']))
		{
			$id = mysql_clean($_GET['delete_orphan_photo']);
			$cbphoto->delete_photo($id);		
		}
		
		if(isset($_POST['delete_orphan_photos']))
		{
			$total = count($_POST['check_photo']);
			for($i=0;$i<$total;$i++)
			{
				$cbphoto->delete_photo($_POST['check_photo'][$i],TRUE);
			}
			$eh->flush();
			e($total." photos has been deleted successfully.","m");
		}
		$photo_arr = array("user"=>userid(),"limit"=>$get_limit, 'order'=>' date_added DESC', "get_orphans"=>TRUE);
		$collection = $cbphoto->collection->get_collections(array("user"=>userid(),"type"=>"photos"));
		
		if(get('query') != '')
		{
			$photo_arr['title'] = mysql_clean(get('query'));
			$photo_arr['tags']	= mysql_clean(get('query'));
		}
		$photos = get_photos($photo_arr);		
		//echo $db->db_query;
		assign('photos',$photos);
		assign('c',$collection);
		
		$photo_arr['count_only'] = true;
		$total_rows  = get_photos($photo_arr);
		$total_pages = count_pages($total_rows,VLISTPP);
		
		//Pagination
		$pages->paginate($total_pages,$page);
		subtitle(lang("Manage Orphan Photos"));
	}
	break;
}
template_files('manage_photos.html');
display_it();
?>