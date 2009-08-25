<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Deleting Module

	$delete_module = mysql_clean(@$_GET['delete_module']);
	if(!empty($delete_module)){
		$msg = $Modules->DeleteModule($delete_module);
		}

//Activating And Deactivation module

	$active = mysql_clean(@$_GET['activate']);
	if(!empty($active)){
		$msg = $Modules->ActivateModule($active);
		}

	$deactive = mysql_clean(@$_GET['deactivate']);
	if(!empty($deactive)){
		$msg = $Modules->DeActivateModule($deactive);
		}
		
//Checking Files And Module Name
if(isset($_POST['add_module'])){
	$msg = $Modules->AddModule();
	}

//Getting And Listing Files
$dir = MODULEDIR;
if(!($dp = opendir($dir))) die("Cannot open $default_dir.");
while($file = readdir($dp)){
$ext = substr($file, strrpos($file,'.') -12);
if($ext == 'instructions.php' || $ext == 'instructions.php'){
$files[] = $file;
}
}
closedir($dp
);

//Listing Modules
		$sql = "SELECT * from modules";
		$rs = $db->Execute($sql);
		$total = $rs->recordcount() + 0;
		$modules = $rs->getrows();
		
		Assign('total', $total + 0);
		Assign('modules', $modules);	


Assign('files',$files);
Assign('msg',@$msg);
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('module_manager.html');
Template('footer.html');
?>