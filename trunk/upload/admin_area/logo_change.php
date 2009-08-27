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

//Setting Website Logo
$tem_img_url = BASEURL.'/'.TEMPLATEFOLDER.'/'.$row['template_dir'].'/images';
$tem_img_dir = BASEDIR.'/'.TEMPLATEFOLDER.'/'.$row['template_dir'].'/images';

//On Submit CHange Logo
if(isset($_POST['change_logo'])){
	$msg = $myquery->ChangeLogo('logo.gif',$tem_img_dir,1);
}

$cur_logo = $tem_img_url.'/logo.gif?'.RandomString(3);

Assign('cur_logo',$cur_logo);



//Setting Player Logo
$logo_path_url = BASEURL.'/player';
$logo_path_dir = BASEDIR.'/player';
//On Submit CHange Logo
if(isset($_POST['change_player_logo'])){
	$msg = $myquery->ChangeLogo('logo.png',$logo_path_dir,2);
}

$cur_player_logo = $logo_path_url.'/logo.png?'.RandomString(3);

Assign('cur_player_logo',$cur_player_logo);


//On Submit CHange Logo
if(isset($_POST['change_eplayer_logo'])){
	$msg = $myquery->ChangeLogo('mini_logo.png',$logo_path_dir,2);
}

$cur_eplayer_logo = $logo_path_url.'/mini_logo.png?'.RandomString(3);

Assign('cur_eplayer_logo',$cur_eplayer_logo);

Assign('msg',@$msg);
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('logo_change.html');
Template('footer.html');
?>