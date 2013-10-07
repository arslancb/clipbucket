<?php
/**
 Name : Advertisment Class
 **************************************************************************************************
 Don Not Edit These Classes , It May cause your script not to run properly
 This source file is subject to the ClipBucket End-User License Agreement, available online at:
 http://www.opensource.org/licenses/attribution.php
 By using this software, you acknowledge having read this Agreement and agree to be bound thereby.
 **************************************************************************************************
 Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
**/

class AdsManager
{

	/**
	 * Function used to add new advertisment in ClipBucket
	 * @param : Array
	 */
	function AddAd($array=NULL)
	{
		global $LANG,$db;
		if(!$array)
			$array = $_POST;
			
		$name		= mysql_clean($array['name']);
		$code		= mysql_real_escape_string(htmlspecialchars($array['code']));
		$placement 	= mysql_clean($array['placement']);
		$category  	= $array['category'];
		$status		= $array['status'];
		
		if(empty($name))
		{
			$msg = e(lang('ad_name_error'));
		}else{
			$count = $db->count(tbl("ads_data"),"ad_id"," ad_name='$name'");
			
			if($count>0){
			e(lang('ad_exists_error2'));
			}else
			{
				$db->insert(tbl("ads_data"),array("ad_category","ad_name","ad_placement","ad_code","ad_status","date_added"),
											array($category,$name,$placement,"|no_mc|".$code,$status,now()));		
				$msg =  e(lang('ad_add_msg'),'m');
			}
			return $msg;
		}		
	}
	
	
	/**
	 * Function used to set advertisment status
	 * 1, to set as activate
	 * 0, to set as deactivate
	 */
	
	function ChangeAdStatus($status,$id)
	{
		global $db;
		
		if($status >1)
			$status = 1;
		if($status<0)
			$status = 0;
		
		if($this->ad_exists($id))
		{
			$db->update(tbl("ads_data"),array("ad_status"),array($status)," ad_id='".mysql_clean($id)."'");
			if($status == '0')
				$show_status = lang('ad_deactive');
			else
				$show_status = lang('ad_active');
			e(lang('ad_msg').$show_status,"m");
		}else
			e(lang("ad_exists_error1"));
	}
	
	/**
	 * Function used to edit advertisment
	 * @params Array
	 */
	function EditAd($array=NULL)
	{
		global $db;
		if(!$array)
			$array = $_POST;
			
		$placement 	= mysql_clean($array['placement']);
		$name	= mysql_clean($array['name']);
		$code	= mysql_real_escape_string(htmlspecialchars($array['code']));
		$category = mysql_clean(@$array['category']);
		$id = $array['ad_id'];
		
		if(!$this->ad_exists($id))
			e(lang("ad_exists_error1"));
		elseif(empty($name))
			e(lang('ad_name_error'));
		else
		{
			$db->update(tbl("ads_data"),array("ad_placement","ad_name","ad_category","ad_code","ad_status"),
						array($placement,$name,$category,"|no_mc|".$code,$array['status'],$id)," ad_id='$id' ");
			e(lang('ad_update_msg'),"m");
		}
	}
	
	/**
	 * Function used to delete advertisments
	 * @param Ad Id
	 */	
	function DeleteAd($id)
	{
		global $db;
		if(!$this->ad_exists($id))
			e(lang("ad_exists_error1"));
		else
		{
			$db->Execute("DELETE FROM ".tbl("ads_data")." WHERE ad_id='".$id."'");
			$msg = e(lang('ad_del_msg'),"m");
		}
	}
	
	/**
	 * Function used to remove advertismetn placement
	 */
	function RemovePlacement($placement)
	{
		global $db;
		if(!$this->get_placement($placement))
			e(lang("ad_placement_err4"));
		else
		{
			$db->execute("Delete from ".tbl("ads_data")." WHERE ad_placement='".$placement."'");
			$db->execute("Delete from ".tbl("ads_placements")." WHERE placement='".$placement."'");
			e(lang('ad_placment_delete_msg'),"m");
		}
	}
	
	
	/**
	 * Function used to add new palcement
	 * @param Array
	 * Array [0] => placement name
	 * Array [1] => placement code
	 */
	function AddPlacement($array)
	{
		global $db;
		if(empty($array[0])){
			$msg = e(lang('ad_placement_err2'));
		}elseif(empty($array[1])){
			$msg = e(lang('ad_placement_err3'));
		}
		if(empty($msg))
		{
			if($this->get_placement($array[1]))
				e(lang('ad_placement_err1'));
			else
			{
				$db->insert(tbl("ads_placements"),array("placement_name","placement"),array($array[0],$array[1]));
				e(lang('ad_placement_msg'),"m");
			}
		}
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
			$query = "SELECT ad_id,ad_code FROM ".tbl("ads_data")." 
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
			return stripslashes(htmlspecialchars_decode($code_array['ad_code']));
			
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
		$query = "UPDATE ".tbl("ads_data")." SET ad_impressions = ad_impressions+1 WHERE ad_id='".$ad_id."'";
		$db->Execute($query);
	}
	
	
	/** 
	 * Function usd to get all placemetns
	 */
	function get_placements()
	{
		global $db;
		
		$result = $db->select(tbl("ads_placements"));
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to get all advertisments
	 */
	function get_advertisements()
	{
		global $db;
		
		$result = $db->select(tbl("ads_data"));
		if($db->num_rows>0)
			return $result;
		else
			return false;

	}
	
	/**
	 * Function used to get placement
	 */
	function get_placement($place)
	{
		global $db;
		$result = $db->select(tbl("ads_placements"),"*"," placement='$place' OR placement_id='$place' ");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to create placement name
	 */
	function get_placement_name($place)
	{
		$details = $this->get_placement($place);
		if($details)
			return $details['placement_name'];
		else
			return false;
	}
	
	/**
	 * Function used to get advertismetn
	 */
	function get_ad_details($id)
	{
		global $db;
		$result = $db->select(tbl("ads_data"),"*"," 	ad_placement='$id' OR ad_id='$id'");
		if($db->num_rows>0)
		{
			$result = $result[0];
			$result['ad_code'] = stripslashes($result['ad_code']);
			return $result;
		}else
			return false;
	}
	
	/**
	 * Function used to check weather advertisment exists or not
	 */
	function ad_exists($id)
	{
		global $db;
		$count = $db->count(tbl("ads_data"),"ad_id"," ad_id='$id' ");
		if($count>0)
			return true;
		else
			return false;
	}
	
	
	/**
	 * Count ads in a placement
	 */
	function count_ads_in_placement($place)
	{
		global $db;
		return $db->count(tbl("ads_data"),"ad_id"," ad_placement='$place'");
	}
}
?>