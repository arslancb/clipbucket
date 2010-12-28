<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

$id = mysql_clean($_GET['photo']);


if(isset($_POST['update']))
{
	$cbphoto->update_photo();		
}

//Performing Actions
if($_GET['mode']!='')
{
	$cbphoto->photo_actions($_GET['mode'],$id);
}

$p	= $cbphoto->get_photo($id);
$p['user'] = $p['userid'];

assign('data',$p);

$requiredFields = $cbphoto->load_required_forms($p);
$otherFields = $cbphoto->load_other_forms($p);
assign('requiredFields',$requiredFields);
assign('otherFields',$otherFields);

subtitle("Edit Photo");
template_files('edit_photo.html');
display_it();
?>