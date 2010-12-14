<?php
/* 
 ***********************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.		
 | @ Author 	: ArslanHassan												
 | @ Software 	: ClipBucket , © PHPBucket.com							
 *************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

$mode = $_GET['mode'];
$photo = mysql_clean($_GET['photo']);


switch($mode)
{
	case "single":
	{
		assign('mode',$mode);
		if($cbphoto->photo_exists($photo))
		{
			
					
			if($_GET['recreate'])
			{
				$cbphoto->generate_photos($photo);
				e("Photo has been re-created. Please remove cache if you dont see any change.","m");
			}
			
			$files = $cbphoto->get_image_file($photo,'t',TRUE);
			$p = $cbphoto->get_photo($photo);
			
			assign('files',$files);
			assign('p',$p);
		} else
			e("Photo does not exist");
	}
	break;
	
	case "mass":
	default:
	{
		assign('mode',$mode);
		$start_index = $_GET['start_index'] ? $_GET['start_index'] : 0;
		$loop_size = $_GET['loop_size'];
		$loop_size = $loop_size ? $loop_size : 2;
		assign('loop_size',$loop_size);
		$next_index = $start_index+$loop_size;
		assign('next_index',$next_index);
		if(isset($_GET['mass_recreation']))
		{
			$photos = $cbphoto->get_photos(array("limit"=>$start_index.",".$loop_size));
			$total  = $cbphoto->get_photos(array("count_only"=>true));
			$i = 0;
	
			assign('total',$total);
			assign('from',$start_index+1);
			$to = $start_index+$loop_size;
			if($to>$total)
			{
				$to = $total;
				e($total." photos image have been recreated.","m");
				assign("stop_loop","yes");
			}
			assign('to',$to);
			
			while($i < $total)
			{
				if($photos[$i]['photo_id'])
				{
					$cbphoto->generate_photos($photos[$i]['photo_id']);
					$msg[] = $photos[$i]['photo_id'].": Updating <strong><em>".$photos[$i]['photo_title']."</em></strong>"; 		
				}
				$i++;	
			}
			e($start_index+1 ." - ".$to."  photos image  have been recreated.","m");
			assign("index_msgs",$msg);
			assign("indexing","yes");
			assign('button','mass_recreation');			
		}
	}
	break;
	
	case "collection":
	{
		assign('mode',$mode);
		$cid = mysql_clean($_GET['cid']);
		if($cbphoto->collection->collection_exists($cid))
		{
			if(isset($_POST['recreating']))
			{
				$total = count($_POST['check_photo']);
				for($i=0;$i<$total;$i++)
				{
					$cbphoto->generate_photos($_POST['check_photo'][$i]);
				}
				$eh->flush();
				e($total." photo(s) have been re-created. Please remove browser cache if you don't see any change.","m");
			}
			$items = $cbphoto->collection->get_collection_items_with_details($cid);
			assign('items',$items);
		} else {
			e(lang("Collection does not exist"));	
		}
	}
	break;
}

subtitle("Recreate Photos");		
template_files('recreate_thumbs.html');
display_it();
?>