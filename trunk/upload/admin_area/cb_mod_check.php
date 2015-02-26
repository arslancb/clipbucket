<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Tool Box');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Server Modules Info');
}


$ffmpegVersion = check_ffmpeg("ffmpeg");
assign("ffmpegVersion", $ffmpegVersion);

$phpVersion = check_php_cli("php");
assign("phpVersion", $phpVersion);

$MP4BoxVersion = check_mp4box("MP4Box");
assign("MP4BoxVersion", $MP4BoxVersion);


$imagick_version = Imagick::getVersion();
$imagick_version_string = $imagick_version['versionString'];

assign("imagick_version",$imagick_version_string);
subtitle("ClipBucket Server Module Checker");
template_files("cb_mod_check.html");
display_it();
?>