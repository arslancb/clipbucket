<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'ep_ajax');

require_once '../../includes/admin_config.php';
$mode = $_POST['mode'];	

if(!empty($mode))
{
	switch($mode)
	{
		case 'upload_special_thumb':
		default: 
		{	
			
			$video_id = $_POST['vid'];
			$video_details = get_video_details($video_id);
			$files_dir = $video_details['file_directory'];

			$size = $_POST['size'];
			$size = explode('x',$size);

			$width  = $size[0];
			$height = $size[1];

			define('dir',$files_dir);

			$file = $_FILES['special_thumb'];
			$file_name = $video_details['file_name'];
			$file_num = $Upload->get_available_file_num($file_name);
			$ext = getExt($file['name']);
			
			
			if($imgObj->ValidateImage($file['tmp_name'],$ext))
			{
				if($files_dir!=NULL){
					$file_path = THUMBS_DIR.'/'.$files_dir.'/'.$file_name.'-'.$file_num.'.'.$ext;	
				}
				else{
					$file_path = THUMBS_DIR.'/'.$file_name.'-'.$file_num.'.'.$ext;
				}
			}

			move_uploaded_file($file['tmp_name'],$file_path);
			$imgObj->CreateThumb($file_path,$file_path,$width,$ext,$height,false);

			echo json_encode(array('msg'=>'Your Thumb has been Uploaded. Please Go to  custom thumbs manager to set it as a default <a href="'.BASEURL.'/admin_area/upload_thumbs.php?video=969" target="self" > Custom Thumb Manger </a>'));
		}
		break;
	}
}
else
{
    echo json_encode(array("no_index"=>"You Are Lost! Please Send the correct mode"));
}