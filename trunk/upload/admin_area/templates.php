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
//$pages->page_redir();

//Getting And Listing Files
if(!file_exists(BASEDIR.'/'.TEMPLATEFOLDER.'/'.@$_GET['temp']) || @$_GET['temp']==''){
$dir = SITETEMPLATEDIR.'/layout/';
$cur_dir = TEMPLATE;
}else{
$dir = BASEDIR.'/'.TEMPLATEFOLDER.'/'.$_GET['temp'].'/layout/';
$cur_dir = $_GET['temp'];
}
if(!($dp = opendir($dir))) die("Cannot open $dir.");
while($file = readdir($dp)){
$ext = GetExt($file);
if($ext == 'html' || $ext == 'HTML'){
$files[] = $file;
}
}
closedir($dp
);
sort($files);
Assign('files',$files);

//Writng File
if(isset($_POST['save'])){
	$file = $dir.$_POST['file'];
	$data = stripslashes($_POST['data']);
	$open_file = fopen($file, "w");
	fwrite($open_file, $data);
	$msg = $_POST['file']." Has Been Saved And Updated";
}

//Getting Data from File
if(isset($_POST['file'])){
$file 	= $dir.$_POST['file'];
$_file 	= $_POST['file'];
}else{
$file 	= $dir.$files[0];
$_file 	= $files[0];
}
$open_file = fopen($file, "r");
$data = htmlentities(file_get_contents($file));

//Getting Template List
$query = "SELECT * FROM template ORDER by template_name";
$TmpExe = $db->Execute($query);
$Temps = $TmpExe->getrows();
Assign('Temps',$Temps);

//Checking Which Template Currently Using
$query = mysql_query("SELECT template_name,template_dir FROM template WHERE template_dir='".mysql_clean($cur_dir)."'");
$TempArr = mysql_fetch_assoc($query);

Assign('CurTemplate',$TempArr['template_name']);
Assign('CurDir',$TempArr['template_dir']);
Assign('data',$data);
Assign('file',$_file);

Assign('msg',@$msg);
Template('header.html');
Template('leftmenu.html');
Template('templates.html');
Template('footer.html');
?>