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
		$file_key = time().RandomString(5)
		//Inserting Video Data...
		$array = array
		(
		'title' => $_POST['title'][$i],
		'description' => $_POST['description'][$i],
		'tags' => $_POST['tags'][$i],
		'category' => $_POST['category'][$i],
		'file_name' => ,
		);
		$vid = $Upload->submit_upload($array);
		//Moving file to temp dir and Inserting in conversion queue..
		$file_name = $cbmass->move_to_temp($files,$file_key);
		$Upload->add_conversion_queue($file_name);
	}
}
		  

subtitle("Mass Uploader");
template_files("mass_uploader.html");
display_it();
?>