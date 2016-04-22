<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , � PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
$userquery->perm_check('ad_manager_access',true);

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Advertisement');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Manage Placements');
}

//Removing Placement
if(isset($_GET['remove'])){
	$placement = mysql_clean($_GET['remove']);
	$msg =$ads_query->RemovePlacement($placement); 
}

//Adding Placement
if(isset($_POST['AddPlacement'])){
	$placement_name = mysql_clean($_POST['placement_name']);
	$placement_code = mysql_clean($_POST['placement_code']);
	$array = array($placement_name,$placement_code);
	$msg = $ads_query->AddPlacement($array);
}

//Getting List Of Placement
$sql = "SELECT * FROM ".tbl("ads_placements");
$ads_placements = db_select($sql);
$total_placements = $db->num_rows;
//Getting total Ads in each placement
for($id=0;$id<=$total_placements;$id++)
{
	$ads_placements[$id]['total_ads'] = $adsObj->count_ads_in_placement($ads_placements[$id]['placement']);
}
				
Assign('ads_placements',$ads_placements);
//pr($ads_placements,true);	
	
subtitle("Add Advertisment Placement");
template_files('ads_add_placements.html');
display_it();

?>