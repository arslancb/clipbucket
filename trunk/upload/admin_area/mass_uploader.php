<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 **************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();


if(isset($_POST['mass_upload_video']))
{
	$files = $cbmass->get_video_files();
	
	$total = count($_POST['mass_up']);
	for($i=0;$i<$total;$i++)
	{	
		$file_key = time().RandomString(5);
		$file_arr = $files[$i];
		
		if($cbmass->is_mass_file($file_arr))
		{
			$code = $i+1;
			//Inserting Video Data...
			$array = array
			(
			'title' => $_POST['title'][$i],
			'description' => $_POST['description'][$i],
			'tags' => $_POST['tags'][$i],
			'category' => $_POST['category'.$code],
			'file_name' => $file_key,
			);
			$vid = $Upload->submit_upload($array);
		}else{
			e("\"".$file_arr['title']."\" is not available");
		}
		
		if(error())
		{
			$error_lists[] = "Unable to upload \"".$file_arr['title']."\"";
			$errors = error();
			foreach($errors as $e)
				$error_lists[] = $e;
			
			$eh->flush_error();
		}else{
			e("\"".$file_arr['title']."\" Has been uploaded successfully","m");
		}
		
		if($vid)
		{
			//Moving file to temp dir and Inserting in conversion queue..
			$file_name = $cbmass->move_to_temp($file_arr,$file_key);
			$Upload->add_conversion_queue($file_name);
		}
	}
}

if(count($error_lists)>0)
{
	foreach($error_lists as $e)
		e($e);
}

subtitle("Mass Uploader");
template_files("mass_uploader.html");
display_it();
?>