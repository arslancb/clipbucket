<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('admin_access');
$pages->page_redir();

//Adding
if(isset($_POST['add']))
{
	$adsObj->AddAd();
}

//Updating
if(isset($_POST['update']))
{
	$adsObj->EditAd($_POST['ad_id']);
}
//Deleting
if(isset($_GET['delete']))
{
	$adsObj->DeleteAd($_GET['delete']);
}



//Editing Ad
if(isset($_GET['ad_id']))
{
	$ad_id = mysql_clean($_GET['ad_id']);
	$ad_data = $adsObj->get_ad_details($ad_id);
	if(!$ad_data)
		e("Ad does not exist");
	else
		assign('ad_data',$ad_data);
}

subtitle("Advertisments Manager");
template_files('ads_manager.html');
display_it();

?>