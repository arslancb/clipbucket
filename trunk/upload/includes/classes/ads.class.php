<?php
/**
 Name : Advertisment Class
 **************************************************************************************************
 Don Not Edit These Classes , It May cause your script not to run properly
 This source file is subject to the ClipBucket End-User License Agreement, available online at:
 http://clip-bucket.com/cbla
 By using this software, you acknowledge having read this Agreement and agree to be bound thereby.
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
**/

class AdsManager
{

	function AddAd(){
		global $LANG;
	
		$name		= mysql_clean($_POST['name']);
		$code		= mysql_clean(htmlspecialchars($_POST['code']));
		$placement 	= mysql_clean($_POST['type']);
		$category  	= $_POST['category'];
		$status		= $_POST['status'];
		
		if(empty($name)){
			$msg = e($LANG['ad_name_error']);
		}
		if(empty($code)){
		//	$msg = e($LANG['ad_code_error']);
		}
		
	
		$query = mysql_query("SELECT * FROM ads_data WHERE ad_name ='".$name."'");
		if(mysql_num_rows($query)>0){
			$msg =  e($LANG['ad_exists_error2']);
		}
		if(empty($msg)){
		mysql_query("INSERT INTO ads_data (ad_category,ad_name,ad_placement,ad_code,ad_status)VALUES('".$category."','".$name."','".$placement."','".$code."','".$status."')");
		
		$msg =  e($LANG['ad_add_msg'],m);
		}
		return $msg;
	}
	
	//Function Used To Change Ad Status
	
	function ChangeAdStatus($status,$id){
		global $LANG;
		if($status >1 || $status <0 ){
		$status = 0;
		}
		$status;
		mysql_query("UPDATE ads_data SET ad_status = '".$status."' WHERE ad_id ='".$id."' ");
		if($status == '0'){
		$show_status = $LANG['ad_deactive'];
		}else{
		$show_status = $LANG['ad_active'];
		}
		$msg = e($LANG['ad_msg'].$show_status,m);
	return $msg;
	}
	
	//Function Used To Edit Advertisment
	
	function EditAd($id){
	global $LANG;
		$placement 	= mysql_clean($_POST['type']);
		$name	= mysql_clean($_POST['name']);
		//Replacing Single Quotes With Double Codes
		$code	= addslashes($_POST['code']);
		$category = mysql_clean(@$_POST['category']);	
				if(empty($name)){
					$msg = e($LANG['ad_name_error']);
				}
				if(empty($code)){
				//	$msg = e($LANG['ad_code_error']);
				}
				if(empty($msg)){
				mysql_query("UPDATE ads_data SET
				ad_placement = '".$placement."',
				ad_name = '".$name."',
				ad_category = '".$category."',
				ad_code	= '".$code."',
				ad_status = '".$_POST['status']."'
				Where ad_id = '".$id."'");
				$msg = e($LANG['ad_update_msg'],m);
				}
		return $msg;
	}
	
	//Function Used To delete AD
	
	function DeleteAd($id){
	global $LANG;
			$query = mysql_query("SELECT * FROM ads_data WHERE ad_id ='".$id."'");
			if(mysql_num_rows($query)!=1){
				$msg = e($LANG['ad_exists_error1']);
			}else{
			mysql_query("DELETE FROM ads_data WHERE ad_id='".$id."'");
				$msg = e($LANG['ad_del_msg'],m);
			}
		return $msg;
	}
	
	//Function Used To Remove Add Placement
	function RemovePlacement($placement){
		global $LANG;
		if(!mysql_query("Delete from ads_data WHERE ad_placement='".$placement."'"))die(mysql_error());
		if(!mysql_query("Delete from ads_placements WHERE placement='".$placement."'"))die(mysql_error());
		$msg = e($LANG['ad_placment_delete_msg'],m);
		return $msg;
	}
	
	
	//Function Used To Add Placement
	//FUNCTION ADDPLACEMENT
	//ARRAY : 	0=> Placement Name
	// 			1=> Placement Code
	function AddPlacement($array){
		global $LANG;
		if(empty($array[0])){
			$msg = e($LANG['ad_placement_err2']);
		}elseif(empty($array[1])){
			$msg = e($LANG['ad_placement_err3']);
		}
		if(empty($msg)){
			$query = mysql_query("SELECT * FROM ads_placements WHERE placement = '".$array[1]."'");
				if(mysql_num_rows($query) > 0){
					$msg = e($LANG['ad_placement_err1']);
				}else{
					if(!mysql_query("INSERT INTO ads_placements (placement_name,placement)VALUES('".$array[0]."','".$array[1]."')"))die(mysql_error());
					$msg  = e($LANG['ad_placement_msg'],m);
				}
		}
		return $msg;
	}
	
	/**
	* FUNCTION USED TO GET ADVERTISMENT FROM DATABSE WITH LOWEST IMPRESSION
	* @param : placement_code
	* @param : num_of_ads
	* return advertisment
	*/
	function getAd($placement_code,$limit=1)
	{
		global $db,$ads_array;
		if($limit==1)
		{
			//Creating Query, Not to select duplicate Ads
			foreach($ads_array  as $ad_id)
			{
				if(is_numeric($ad_id))
					$query_param .= " AND ad_id <> '".$ad_id."' ";
			}
			$limit_query = ' LIMIT 1';
			$order = ' ORDER BY ad_impressions ASC ';
			//Return Ad
			$query = "SELECT ad_id,ad_code FROM ads_data 
			WHERE ad_placement = '".$placement_code."'
			AND ad_status='1'
			";
			$code_array 	= $db->GetRow($query.$query_param.$order.$limit_query);
			
			//Checking If there is no code, then try to get duplicate ad
			if(empty($code_array['ad_id']))
			$code_array 	= $db->GetRow($query.$order.$limit_query);
			
			$ads_array[] 	= $code_array['ad_id'];
			
			//Incrementing Ad Impression
			$this->incrementImpression($code_array['ad_id']);
			return $code_array['ad_code'];
			
		}else{
			/*In that case, get all '$limit' 
			ads from database and do the same 
			as we did with the single ad*/
		}
	}
	
	
	/**
	* FUNCTION USED TO INCREASE AD IMPRESSIONGS
	* @param ad_id
	*/
	function incrementImpression($ad_id)
	{
		global $db;
		$query = "SELECT ad_impressions FROM ads_data WHERE ad_id='".$ad_id."'";
		$query_results = $db->GetRow($query);
		$ad_imp = $query_results['ad_impressions'] + 1;
		$query = "UPDATE ads_data SET ad_impressions = '".$ad_imp."' WHERE ad_id='".$ad_id."'";
		$db->Execute($query);
	}
	
}
?>