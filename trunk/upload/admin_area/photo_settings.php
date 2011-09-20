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
$userquery->login_check('video_moderation');
$pages->page_redir();

$mode = $_GET['mode'];
assign('mode',$mode);

switch($mode)
{
	case "photo_settings":
	default:
	{
		if($_POST['update'])
		{
			$rows = array(
			 'photo_ratio',
			 'photo_thumb_width',
			 'photo_thumb_height',
			 'photo_med_width',
			 'photo_med_height',
			 'photo_lar_width',
			 'photo_crop',
			 'photo_multi_upload',
			 'max_photo_size',
			 'own_photo_rating',
			 'photo_download',
			 'photo_comments',
			 'photo_rating');
			
			$numeric = array(
			 'photo_thumb_width',
			 'photo_thumb_height',
			 'photo_med_width',
			 'photo_med_height',
			 'photo_lar_width',
			 'photo_crop',
			 'max_photo_size',
			 'photo_multi_upload');
							 
			foreach($rows as $field)
			{
				$value = $_POST[$field];
				if(in_array($field,$numeric))
				{
					if($value < 0 || !is_numeric($value))
						$value = 1;	
				}
				$myquery->Set_Website_Details($field,$value);
			}
			e("Photo Settings Have Been Updated",'m');
		}
		
		
		subtitle("Photo Settings");
	}
	break;
	
	case "watermark_settings":
	{
		if($_POST['update_watermark'])
		{				
			$rows = array(
						  'watermark_photo',
						  'watermark_max_width',
						  'watermark_placement');
			$numeric = array(
							 'watermark_max_width'
							 );
							 			  
			foreach($rows as $field)
			{
				$value = $_POST[$field];
				if(in_array($filed,$numeric))
				{
					if($value < 0 || !is_numeric($value))
						$value = 1;	
				}
				$myquery->Set_Website_Details($field,$value);
			}
			if(!empty($_FILES['watermark_file']['tmp_name']))
				$cbphoto->update_watermark($_FILES['watermark_file']);
				
			e("Watermark Settings Have Been Updated",'m');
			
			subtitle("Watermark Settings");
		}
	}
	break;
}

$row = $myquery->Get_Website_Details();
assign('row',$row);

template_files('photo_settings.html');
display_it();

?>