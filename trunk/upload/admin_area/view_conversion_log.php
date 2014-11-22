<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Video Conversion');
}
if(!defined('SUB_PAGE')){
    if($_GET['active'] == 'no')
        define('SUB_PAGE', 'Logs');
    else
        define('SUB_PAGE', 'Logs');
}


$file_name = mysql_clean($_GET['file_name']);
$file_details = $myquery->file_details($file_name);
// $file_details['file_name'] = getname($file_details['File']);
// $file_details['file_ext'] = getext($file_details['File']);
if($file_details)
{
	$fileDetailsArray = explode("\n", $file_details);
	$file_details = implode("<br>", $fileDetailsArray);

	$video_details = '[]';
	$start = 0;
	foreach($fileDetailsArray as $key => $value)
	{
		if($start) $start++;

		if($value=='videoDetails')
		{
			$start = 1;
		}

		if($start==3)
		{
			$video_details = $value;
		}
	}
	$videoDetails = json_decode($video_details);
	$videoDetails->file_name = $file_name;

	assign('data',$file_details);
	assign('videoDetails',$videoDetails);
}


subtitle("Conversion Log");
template_files('view_conversion_log.html');
display_it();
?>