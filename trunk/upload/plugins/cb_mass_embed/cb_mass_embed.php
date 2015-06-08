<?php
/*
	Plugin Name: ClipBucket Mass Embedder
	Description: This plugin will populate your website with videos grabbed from Youtube, Metacafe, Dailymotion and other famous video sharing websites.
	Author: Arslan Hassan
	Author Website: http://clip-bucket.com/
	ClipBucket Version: 2.5.1
	Version: 2.5
	Website: http://clip-bucket.com/
	Plugin Type: global
*/


//including blogger api



if(!function_exists('cb_mass_embed'))
{
	include("cb_mass_embed.class.php");
	define("cb_mass_embed_install","installed");
	define('CB_MASS_EMBED', 'cb_mass_embed');
	assign("cb_mass_embed","installed");
	assign('mass_embed_dir',PLUG_DIR.'/cb_mass_embed');
	assign("mass_embed_url",PLUG_URL.'/cb_mass_embed');
	
	if(BACK_END)
	{
		function cb_mass_embed()
		{
			/* */
		}

		$cb_mass_embed = new cb_mass_embed();
		// LICENSE 
		define('CB_MASS_EMBED_LICENSE',$cb_mass_embed->configs['license_key']);
		//LICENSE checking 
		check_cb_embed_license(CB_MASS_EMBED_LICENSE);
		//add_admin_menu('Videos','Mass Embed Videos','cb_mass_embed.php');
	
		$Cbucket->add_admin_header(dirname(__FILE__).'/headers/admin_header.html','cb_mass_embed');
		//Adding Admin Menu
		add_admin_menu("Mass Embedder","Mass Embed Videos",'cb_mass_embed.php','cb_mass_embed/admin');
		add_admin_menu("Mass Embedder","Mass Embed Configuration",'cb_mass_configuration.php','cb_mass_embed/admin');
		add_admin_menu("Mass Embedder","Pending Videos",'pending_videos.php','cb_mass_embed/admin');
		add_admin_menu("Mass Embedder","Mass Embed Table",'mass_embed_table.php','cb_mass_embed/admin');
	}
	
	/**
	 * Function used to remove track from database so 
	 * User can embed video later
	 */
	function remove_mass_embed_track($vid)
	{
		global $db;
		$unique_code = $vid['unique_embed_code'];
		$db->Execute("DELETE FROM ".tbl('mass_embed')." WHERE	mass_embed_unique_id='$unique_code'");
	}
	
	/**
	 * Function used to create category form for video
	 */
	function create_vid_cat_form($param)
	{
		#pr($param,true);
		$name = $param['name'];
		$name = $name ? $name : 'category';
		
		$video = $param['video'];
		$cats = $video['category'];
		preg_match_all('/#([0-9]+)#/',$cats,$m);
		$cat_array = $m[1];
		foreach($cat_array as $new_cat)
		{
			if($new_cat)
				$new_arr[$new_cat] = $new_cat;
		}
		
		
		$form =  array(
						'title'		=> lang('vdo_cat'),
						'type'		=> 'checkbox',
						'name'		=> $name.'[]',
						'id'		=> 'category',
						'value'		=> array('category',array($new_arr)));
		//pr($form,true);
		if($param['assign'])
			assign($param['assign'],$form);
		else
			return $form;

	}
	
	
	/**
	 * Function used to get video via Uniquey Embed Code
	 */
	function getVidFromUC($code)
	{
		global $db;
		$result = $db->select(tbl("video"),"*",
		"unique_embed_code = '$code' ");
		
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * get category list for video
	 */
	function getEmbedCategoryList()
	{
		return getCategoryList(array('type'=>'video'));
	}
	
	$Smarty->register_function('create_vid_cat_form','create_vid_cat_form');	
	//Registering Delete video function
	register_action_remove_video('remove_mass_embed_track');

	$Cbucket->add_admin_header(PLUG_DIR.'/cb_mass_embed/admin/header.html','cb_mass_embed');
}
/**
 * Check licensekey and show message for client 
 *@param string licensekey , string localkey  
 */
function cb_mass_embed_license($licensekey,$localkey="")
{
	
	$whmcsurl = "http://client.clip-bucket.com/";
	$prefix = "CBME";
	$licensing_secret_key = "CBME"; # Set to unique value of chars
	$checkdate = date("Ymd"); # Current dateW
	$usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
	$localkeydays = 15; # How long the local key is valid for in between remote checks
	$allowcheckfaildays = 5; # How many days to allow after local key expiry
	$localkeyvalid = false;
	
	$prefix_len = strlen($prefix);
	
	if(substr($licensekey,0,$prefix_len)!=$prefix)
	{
		return array('status'=>'Unknown license');
	}
	if ($localkey) {
		$localkey = str_replace("\n",'',$localkey); # Remove the line breaks
		$localdata = substr($localkey,0,strlen($localkey)-32); # Extract License Data
		$md5hash = substr($localkey,strlen($localkey)-32); # Extract MD5 Hash
		if ($md5hash==md5($localdata.$licensing_secret_key)) {
			$localdata = strrev($localdata); # Reverse the string
			$md5hash = substr($localdata,0,32); # Extract MD5 Hash
			$localdata = substr($localdata,32); # Extract License Data
			$localdata = base64_decode($localdata);
			$localkeyresults = unserialize($localdata);
			$originalcheckdate = $localkeyresults["checkdate"];
			if ($md5hash==md5($originalcheckdate.$licensing_secret_key)) {
				$localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-$localkeydays,date("Y")));
				if ($originalcheckdate>$localexpiry) {
					$localkeyvalid = true;
					$results = $localkeyresults;
					$validdomains = explode(",",$results["validdomain"]);
					if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {
						$localkeyvalid = false;
						$localkeyresults["status"] = "Invalid";
						$results = array();
					}
					$validips = explode(",",$results["validip"]);
					if (!in_array($usersip, $validips)) {
						$localkeyvalid = false;
						$localkeyresults["status"] = "Invalid";
						$results = array();
					}
					if ($results["validdirectory"]!=dirname(__FILE__)) {
						$localkeyvalid = false;
						$localkeyresults["status"] = "Invalid";
						$results = array();
					}
				}
			}
		}
	}
	if (!$localkeyvalid) {

		$postfields["licensekey"] = $licensekey;
		$postfields["domain"] = $_SERVER['SERVER_NAME'];
		$postfields["ip"] = $usersip;
		$postfields["dir"] = dirname(__FILE__);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $whmcsurl."modules/servers/licensing/verify.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		if (!$data) {
			$localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-($localkeydays+$allowcheckfaildays),date("Y")));
			if ($originalcheckdate>$localexpiry) {
				$results = $localkeyresults;
			} else {
				$results["status"] = "Remote Check Failed";
				return $results;
			}
		} else {
			preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
			$results = array();
			foreach ($matches[1] AS $k=>$v) {
				$results[$v] = $matches[2][$k];
			}
		}
		if ($results["status"]=="Active") {
			$results["checkdate"] = $checkdate;
			$data_encoded = serialize($results);
			$data_encoded = base64_encode($data_encoded);
			$data_encoded = md5($checkdate.$licensing_secret_key).$data_encoded;
			$data_encoded = strrev($data_encoded);
			$data_encoded = $data_encoded.md5($data_encoded.$licensing_secret_key);
			$data_encoded = wordwrap($data_encoded,80,"\n",true);
			$results["localkey"] = $data_encoded;
		}
		$results["remotecheck"] = true;
	}
	
	return $results;
}

/**
 * Check licensekey 
 *@param string licensekey , string localkey  
 */
function check_cb_embed_license($license,$localkey)
{
	$results = cb_mass_embed_license($license,$localkey);
	
	$error_setting_link = '<a href="'.BASEURL.'/admin_area/plugin.php?folder='.CB_MASS_EMBED.'/admin&file=cb_mass_configuration.php">Click Here to edit Mass Embedder Settings</a>';
	
	if(!$results)
	{
		if(BACK_END)
		e("Error while loading Mass Embedder license - $error_setting_link","w");
	}elseif ($results["status"]=="Invalid")
	{
		if(BACK_END)
		e("Your Mass Embedder License is Invalid - $error_setting_link","w");
	}elseif ($results["status"]=="Expired")
	{
		if(BACK_END)
		e("Your Mass Embedder License is Expired - $error_setting_link","w");
	}elseif($results["status"]=="Suspended")
	{
		if(BACK_END)
		e("Your Mass Embedder is suspended - $error_setting_link","w");
	}elseif($results['status']!='Active')
	{
		if(BACK_END)
		e("Error occured while checking license , status : ".$results['status']." - $error_setting_link","w");
	}
	return $results;
}

?>