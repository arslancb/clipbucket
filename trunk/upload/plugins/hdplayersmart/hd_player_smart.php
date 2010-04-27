<?php
/*
	Plugin Name: HD Player Smart Plugin
	Description: This plugin will let you control HD Player more properly
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://clip-bucket.com/
*/


add_admin_menu('Templates And Players','HD Player Smart','hd_admin.php','hdplayersmart/admin');

class hdplayer_smart
{
	var $tbl = 'hd_smart';
	var $configs = array();
	var $plug_dir = 'hdplayersmart';
	var $skins = array();
	var $skins_path = '/hdplayersmart/skins'; //Relative to PLUGIN FOLDER
	
	//hd Smary Plugin Constructor
	function hdplayer_smart()
	{
		//Get hdplayer Configs
		$this->get_configs();
		//Get Available SKins
		$this->get_skins();
		//defining plugin
		define("hd_smart_install","installed");
		//Setting Skins Path
		//echo $this->skins_path = PLAYER_DIR.'/hdflvplayer/skin';
		
	}
	
	//Function used to get hdplyaer_smart configurations
	function get_configs()
	{
		global $db;
		$results = $db->select(tbl($this->tbl),"*");
		foreach($results as $result)
		{
			$this->configs[$result['hd_config_name']] = $result['hd_config_value'];
		}
		//Setting up plugin vars
		$vars = $this->configs['custom_variables'];
		$this->configs['custom_vars'] = json_decode($vars,true);
		
		return $this->configs;
	}
	
	//Function used to get list of all skins
	function get_skins()
	{
		$skins = array();
		$skin_dir = PLUG_DIR.$this->skins_path;
		$files = glob($skin_dir."/*.swf");
		if(is_array($files))
		foreach($files as $file)
		{
			$file_arr = explode("/",$file);
			$file_name = $file_arr[count($file_arr) -1];
			$skins[] = array('name'=>ucwords(preg_replace(array('/\.swf/','/\_/'),
					   array('',' '),$file_name)),'file'=>$file_name);
		}

 		return $this->skins = $skins;
	}
	
	//Function used to upload new skins in hd smarty skins directory
	// Make sure your skins directory is chmod to 777
	function upload_skin($file)
	{
		//Checking file name
		$file_name = $file['name'];
		$skins = PLUG_DIR.$this->get_skins();
		if(!in_array($file_name,$skins))
		{
			$skin_dir = $this->skins_path;
			
			if(getext($file_name)=='swf')
			{
				if(!move_uploaded_file($file['tmp_name'],$skin_dir.'/'.$file_name))
					e("Unable to upload new skin");
				else
					e("New skin hass been added","m");
			}else
				e("Please upload .swf files only");
		}else
			e("Skin with name '$file_name' already exists");
	}	
	
	/**
	 * Function used to convert input to custom variables
	 */
	function json_to_custom($input)
	{
		return json_decode(stripslashes($input),TRUE);
	}
	
	/**
	 * Function used to update configs
	 */
	function update_config($name,$value)
	{
		global $db;
		$db->update(tbl($this->tbl),array('hd_config_value'),array($value)," hd_config_name='$name' ");
	}
	
	/**
	 * Funtion used to add new value and return it in jason
	 */
	function custom_to_json($new_name,$new_value,$code,$carray)
	{
		$new_array = array();
		//Checkig if code is array or not
		if(!is_array($code))
		{
			$code = $this->json_to_custom($code);
		}
		//pr($code);
		if(is_array($code))
		foreach($code as $name => $value)
		{
			if($carray["cust_".$name] == 'yes')
				$new_array[$name] = $value;
		}
		
		if($new_name  && $new_value)
		{
			$new_array[$new_name] = $new_value;
		}
	//	pr($new_array);
		return json_encode($new_array);		
	}
	
	/**
	 * Function used to set hd player skn
	 */
	function skin($skin)
	{
		$skin_path = $this->skins_path.'/'.$skin;
		if(file_exists(PLUG_DIR.$skin_path))
			return PLUG_URL.$skin_path;
	}
	
}
$cb_hd_smart = new hdplayer_smart();
$Smarty->assign_by_ref('hd_smart',$cb_hd_smart);


?>