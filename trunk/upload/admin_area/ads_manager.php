<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Adding Advertisment

if(isset($_POST['add_ad'])){
	$msg = $ads_query->AddAd();
}

//Changing Status

if(isset($_GET['change_status'])){
$id 	= mysql_clean($_GET['change_status']);
$status	= mysql_clean($_GET['status']);
$msg 	= $ads_query->ChangeAdStatus($status,$id);
}

//Delet Advertisment
if(isset($_GET['delete_ad'])){
$id 	= mysql_clean($_GET['delete_ad']);
$msg 	= $ads_query->DeleteAd($id);
}

//Editing An Add/
if(isset($_GET['ad'])){
		$ad = clean($_GET['ad']);
		$query = mysql_query("SELECT * FROM ads_data WHERE ad_id='".$ad."'");
		if(mysql_num_rows($query)>0){
				if(isset($_POST['update_ad'])){
				$id 	= mysql_clean($_GET['ad']);
				$msg 	= $ads_query->EditAd($id);
				}					
			$sql = "SELECT * FROM ads_data WHERE ad_id='".$ad."'";
			$rs = $db->Execute($sql);
			$ad_data = $rs->getrows();;
			Assign('ad_data',$ad_data);
			Assign('edit_ad','show');			
		}else{
		$msg[] = $LANG['ad_exists_error1'];
		}
}

//Advertisment Array
$placements = array(
         "ad_160x600"	=> "Wide Skyscrapper 160 x 600",
         "ad_468x60"	=> "Banner 468 x 60",
         "ad_300x250"	=> "Medium Rectangle 300 x 250",
         "ad_728x90"	=> "Leader Board 728 x 90",
         "ad_200x200"	=> "Small Square 200 x 200",
         "ad_250x250"	=> "Square 250 x 250",
         "ad_120x600"	=> "Skyscrapper 120 x 600",
         "ad_336x280"	=> "Large Rectangle 336x280"
		 );
		 
Assign('placement',$placements);
		$sql = "SELECT * from ads_data";
		$rs = $db->Execute($sql);
		$total = $rs->recordcount();
		$ads = $rs->getrows();
		for($id=0;$id<$total;$id++){
		$category = $myquery->GetCategory($ads[$id]['ad_category']);
		$ads[$id]['ad_category'] = $category['category_name'];
		$ads[$id]['placement'] = $placements [$ads[$id]['ad_placement']];
		}
		
		Assign('total', $total);
		Assign('ads', $ads);	
	

//Getting List Of Placement
$sql = "SELECT * FROM ads_placements";
$ads_exec = $db->Execute($sql);
$ads_placements = $ads_exec->getrows();
Assign('ads_placements',$ads_placements);
Assign('msg',@$msg);
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('ads_manager.html');
Template('footer.html');
?>