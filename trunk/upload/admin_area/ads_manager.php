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
$pages->page_redir();
$userquery->perm_check('ad_manager_access',true);
//Adding
if(isset($_POST['add']))
{
	$adsObj->AddAd();
}

//Updating
if(isset($_POST['update']))
{
	$ad_id = mysql_clean($_GET['ad_id']);
	$array = $_POST;
	$array['ad_id'] = $ad_id;
	$adsObj->EditAd($array);
}
//Deleting
if(isset($_GET['delete']))
{
	$adsObj->DeleteAd($_GET['delete']);
}

//Chaing Ad Status
if(isset($_GET['activate']))
{
	$adid = mysql_clean($_GET['activate']);
	$adsObj->ChangeAdStatus(1,$adid);
}
if(isset($_GET['deactivate']))
{
	$adid = mysql_clean($_GET['deactivate']);
	$adsObj->ChangeAdStatus(0,$adid);
}

//Editing Ad
if(isset($_GET['ad_id']))
{
	$ad_id = mysql_clean($_GET['ad_id']);
	$ad_data = $adsObj->get_ad_details($ad_id);
	if(!$ad_data)
		e(lang("ad_exists_error1"));
	else
		assign('ad_data',$ad_data);
}

subtitle("Advertisments Manager");
template_files('ads_manager.html');
display_it();

?>