<?php
/**
 Plugin class
 Author:Arslan Hassan
 
 Wiget Areas, for multiple places, use "[place]placement[/place]" without quotes 
 -- For including some files in templates, following placements can be set
 --- in_menu
 --- in_category_list
 --- after_video
 --- before_video
 --- before_comments
 --- after_comments
 --- before_each_comment
 --- after_each_comment
 --- vw_after_featured_videos
 --- vw_before_feature_videos
 --- before_editors_pic
 --- after_editors_pic
 --- in_header_menu
 --- in_footer_menu
 --- after_styles_changer
 --- after_language_changer
 --- before_style_changer
 --- before_compose_box
 --- before_reply_compose_box
 --- after_reply_compose_box
 --- after_compose_box
 --- before_desc_compose_box
 --- after_desc_compose_box
 --- in_header
 
 
 function 
 -- get_plugin_details 
 reads the file and parse plugin details
 -- installPlugin
 get plugin details and install it
 
 */

class CBPlugin extends ClipBucket
{
	//var $admin_plug_menu = CBucket::AdminMenu;
	
	function CBPlugin()
	{
		
	}
	
	/**
	* get plugin list 
	*/
	function getPlugins()
	{
		#first we will read the plugin directory
		#Current Plugin Class will read files only, not sub directories
		$dir = PLUG_DIR;
		$dir_list = scandir($dir);
		foreach($dir_list as $item)
		{
			if($item=='..' || $item=='.' || substr($item,0,1)=='_'|| substr($item,0,1)=='.')
			{
				//Skip $item_list[] = $item;
			}else{
				//Now CHecking if its file, not a directory
				if(!is_dir(PLUG_DIR.'/'.$item))
				$item_list[] = $item;
			}
		}
		//Our Plugin List has plugin main files only, now star reading files
		foreach($item_list as $plugin_file)
		{
			$plugin_details = $this->getPluginDetails($plugin_file);
			if(!empty($plugin_details['name']))
			$plugins_array[]= $plugin_details;
		}
		
		return $plugins_array;
	}
	
	function getPluginList()
	{
		return $this->getPlugins();
	}
	
	
	/**
	 * Function used to get new plugins, that are not installed yet
	 */
	function getNewPlugins()
	{
		//first get list of all plugins
		$plugin_list = $this->getPluginList();
		
		//Now Checking if plugin is installed or not
		if(is_array($plugin_list))
		{
			foreach($plugin_list as $plugin)
			{
				if(!$this->is_installed($plugin['file']))
				$plug_array[] = $plugin;
			}
			return $plug_array;
		}
	}
	
	
	/**
	 * Function used to get new plugins, that are not installed yet
	 */
	function getInstalledPlugins()
	{
		//first get list of all plugins
		$plugin_list = $this->getPluginList();
		//Now Checking if plugin is installed or not
		foreach($plugin_list as $plugin)
		{
			if($this->is_installed($plugin['file']))
			{
			$plugin = array_merge($plugin,$this->getPlugin($plugin['file']));
			$plug_array[] = $plugin;
			}
		}
		return $plug_array;
	}
	
	/**
	* Function used to check weather plugin is instlled or not
	* @param : $plugin_code STRING
	*/
	function is_installed($file,$v=NULL)
	{
		global $db;
		
		if($v)
		$version_check = "AND plugin_version='$v'";
		$query = "SELECT plugin_file FROM plugins WHERE $version_check plugin_file='".$file."'";
		$details = $db->Execute($query);
		if($details->recordcount()>0)
		return true;
		else
		return false;
	}
	
	
	/**
	* get plugin details
	* @param : $file STRING
	*/
	function get_plugin_details($plug_file)
	{
		$file = PLUG_DIR.'/'.$plug_file;
		if(file_exists($file) && is_file($file))
		{
			// We don't need to write to the file, so just open for reading.
			$fp = fopen($file, 'r');
			// Pull only the first 8kiB of the file in.
			$plugin_data = fread( $fp, 8192 );
			// PHP will close file handle, but we are good citizens.
			fclose($fp);
			preg_match( '/Plugin Name:(.*)$/mi', $plugin_data, $name );
			preg_match( '/Website:(.*)$/mi', $plugin_data, $website );
			preg_match( '/Version:(.*)/mi', $plugin_data, $version );
			preg_match( '/Description:(.*)$/mi', $plugin_data, $description );
			preg_match( '/Author:(.*)$/mi', $plugin_data, $author );
			preg_match( '/Author Website:(.*)$/mi', $plugin_data, $author_page );
			preg_match( '/ClpBucket Version:(.*)$/mi', $plugin_data, $cbversion );
			preg_match( '/Plugint Type:(.*)$/mi', $plugin_data, $type );
			
			$details_array = array
			(
			 'name',
			 'website',
			 'version',
			 'description',
			 'author',
			 'cbversion',
			 'code',
			 'type',
			 
			 );
			foreach ($details_array as $detail)
			{
				$plugin_array[$detail]=${$detail}[1];
			}
			$plugin_array['file'] = $plug_file;
			$plugin_array['code'] = preg_replace('/\s/', '', $code[1]);
			
			return $plugin_array;
		}else{
			return false;
		}
	}
	function getPluginDetails($file)
	{
		return $this->get_plugin_details($file);
	}
	
	
	
	/**
	 * Function used to get plugin details from database
	 * @param : plugin_id_code STRING
	 */
	 function getPlugin($file)
	 {
		 $query = mysql_query("SELECT * FROM plugins WHERE plugin_file ='".$file."'");
		 return mysql_fetch_assoc($query);
	 }
	 
	 
	/**
	 * ClipBucket Internal Plugin Installer
	 * @param:plugin
	 */
	 function installPlugin($pluginFile)
	 {
		 global $db,$LANG,$Cbucket;
		 $plug_details = $this->get_plugin_details($pluginFile);	
		 if(!$plug_details)
		 	$msg = e($LANG['plugin_no_file_err']);
		 if(empty($plug_details['name']))
		 	$msg = e($LANG['plugin_file_detail_err']);
		 if($this->is_installed($pluginFile))
		 	$msg = e($LANG['plugin_installed_err']);
		
		 if(empty($msg))
		 {	  
		 
			$plug_details = $this->getPluginDetails(PLUG_DIR.'/'.$pluginFile);
			
			if(file_exists(PLUG_DIR.'/install_'.$pluginFile))
			require_once(PLUG_DIR.'/install_'.$pluginFile);
			
			 dbInsert
			 (
			 'plugins',
			 array(
				   'plugin_file',
				   'plugin_license_type',
				   'plugin_license_key',
				   'plugin_license_code',
				   'plugin_active',
				   ),
			 array(
				   $pluginFile,
				   $plugin_details_array['plugin_license_type'],
				   $plugin_details_array['plugin_license_key'],
				   $plugin_details_array['plugin_license_code'],
				   'yes',
				   )
			 );
			 
			 //Checking For the installation SQL
			 $msg = e($LANG['plugin_install_msg'],m);
			 define('NEW_INSTALL',false);
		 }
		 return $msg;
	 }
	 
	 /**
	  * Function used to activate plugin
	  */
	 function pluginActive($plugin_file,$active='yes'){
		global $LANG;
		if($this->is_installed($plugin_file))
		{
		mysql_query("UPDATE plugins SET plugin_active='".$active."' WHERE plugin_file='".$plugin_file."'");
		$active_msg = $active=='yes' ? 'activated' : 'deactiveted';
			$msg = e("Plugin has been $active_msg",m);
		}else{
			$msg = e($LANG['plugin_no_install_err']);
		}
		return $msg;
	 }
	 
	 /**
	  * Function used to activate plugin
	  */
	 function uninstallPlugin($file){
		global $LANG;
		if($this->is_installed($file))
		{
			mysql_query("DELETE FROM plugins WHERE plugin_file='".$file."' ");
			if(file_exists(PLUG_DIR.'/uninstall_'.$file))
			require_once(PLUG_DIR.'/uninstall_'.$file);
			$msg = e("Plugin has been Uninstalled",m);
		}else{
			$msg = e($LANG['plugin_no_install_err']);
		}
		return $msg;
	 }
	 
	 /**
	  * Function used to add ClipBucket Plugin Menu
	  */
	 function add_admin_menu($headers,$links)
	 {
		 //This Function used to createa admin menu
		 
	 }
	 
	 
}

?>