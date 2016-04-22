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

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Videos');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Mass Upload Videos');
}


if(isset($_POST['mass_upload_video']))
{
	$files = $cbmass->get_video_files();
	$vtitle=$_POST['title'];
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
			$dosleep=0;
			//Moving file to temp dir and Inserting in conversion queue..
			$file_name = $cbmass->move_to_temp($file_arr,$file_key);
			$results=$Upload->add_conversion_queue($file_name);
			$str = "/".date("Y")."/".date("m")."/".date("d")."/";
			$str1 = date("Y")."/".date("m")."/".date("d");
			mkdir(BASEDIR.'/files/videos'.$str);
			$tbl=tbl("video");
			$fields['file_directory']=$str1;
			$fname=explode('.', $file_name);
			$cond='file_name='.'\''.$fname[0].'\'';
			$result=db_update($tbl, $fields, $cond);
			$result=exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $file_name $dosleep &> /dev/null &");
			if(file_exists(CON_DIR.'/'.$file_name))
			{
				unlink(CON_DIR.'/'.$file_name);
				foreach ($vtitle as &$title) 
				{
					$resul1=glob(BASEDIR.'/files/videos/'.$title.".*");
					unlink($resul1[0]);
				}
				
			}

		}
	}
}

if(count($error_lists)>0)
{
	foreach($error_lists as $e)
		e($e);
}

//Collecting Data for Pagination
$limit = config('comments_per_page');
$total_rows=count($cbmass->get_video_files());	
$total_pages = $total_rows/$limit;
$total_pages = round($total_pages+0.49,0);
$pages->paginate($total_pages,$page);

subtitle("Mass Uploader");

template_files("mass_uploader.html");
display_it();
?>