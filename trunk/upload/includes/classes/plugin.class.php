<?php
/**
 Plugin class
 Author:Arslan Hassan
 version : 1.1 - 2009 august 26
 
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
 
 
 You can additionally add admin area options
 such as if you want to add new option in admin panel
 you can add links at following places
 
 -- in_video_manage_links
 -- in_video_manage_buttons
 
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
				//$sub_dir_list = scandir(PLUG_DIR.'/'.$item);
			}else{
				//Now CHecking if its file, not a directory
				if(!is_dir(PLUG_DIR.'/'.$item))
				{
					$item_list[] = $item;
				}else{
					$sub_dir = $item;
					$sub_dir_list = scandir(PLUG_DIR.'/'.$item);
					foreach($sub_dir_list as $item)
					{
						if($item=='..' || $item=='.' || substr($item,0,1)=='_'|| substr($item,0,1)=='.')
						{
							//Skip $item_list[] = $item;
							//$sub_dir_list = scandir(PLUG_DIR.'/'.$item);
						}else{
							//Now CHecking if its file, not a directory
							if(!is_dir(PLUG_DIR.'/'.$sub_dir.'/'.$item))
							{
								$subitem_list[$sub_dir][] = $item;
							}
						}
					}
				}
			}
		}
		
		//Our Plugin List has plugin main files only, now star reading files
		foreach($item_list as $plugin_file)
		{
			$plugin_details = $this->getPluginDetails($plugin_file);
			if(!empty($plugin_details['name']))
			$plugins_array[]= $plugin_details;
		}
		
		//Now Reading Sub Dir Files
		foreach($subitem_list as $sub_dir => $sub_dir_list )
		{
			foreach($subitem_list[$sub_dir] as $plugin_file)
			{
				$plugin_details = $this->getPluginDetails($plugin_file,$sub_dir);
				$plugin_details['folder'] = $sub_dir;
				if(!empty($plugin_details['name']))
				$plugins_array[] = $plugin_details;
			}
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
		global $db;
		//first get list of all plugins
		$plugin_list = $this->getPluginList();
		
		if(FRONT_END)
			$active_query = " plugin_active='yes' ";
		else
			$active_query = NULL;
		$results = $db->select(tbl("plugins"),"*",$active_query);
		
		if(is_array($results))
		foreach($results as $result)
		{
			//Now Checking if plugin is installed or not
			$this_plugin = $this->get_plugin_details($result['plugin_file'],$result['plugin_folder']);
			if($this_plugin)
			{
				$result['file'] = $result['plugin_file'];
				$result['folder'] = $result['plugin_folder'];
				$plugin = array_merge($result,$this_plugin);
			//	pr($plugin);
				$plug_array[] = $plugin;
			}

		}
		
		
		/*
		 * OLDER VERSION
		 foreach($plugin_list as $plugin)
		{
			
			if($this->is_installed($plugin['file'],$plugin['version'],$plugin['folder']))
			{
				$plugin = array_merge($plugin,$this->getPlugin($plugin['file']));
				//pr($plugin);
				$plug_array[] = $plugin;
			}
		}*/

		return $plug_array;
	}
	
	/**
	* Function used to check weather plugin is instlled or not
	* @param : $plugin_code STRING
	*/
	function is_installed($file,$v=NULL,$folder=NULL)
	{
		global $db;
		
		//if($v)
		//$version_check = "AND plugin_version='$v'";
		if($folder)
			$folder_check = " AND plugin_folder ='$folder'";
		
		$query = "SELECT plugin_file FROM plugins WHERE plugin_file='".$file."' $version_check $folder_check";
		
		$details = $db->select(tbl("plugins"),"plugin_file","plugin_file='".$file."' $version_check $folder_check");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	
	/**
	* get plugin details
	* @param : $file STRING
	*/
	function get_plugin_details($plug_file,$sub_dir=NULL)
	{
		if($sub_dir!='')
			$sub_dir = $sub_dir.'/';
			
		$file = PLUG_DIR.'/'.$sub_dir.$plug_file;
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
			preg_match( '/Plugin Type:(.*)$/mi', $plugin_data, $type );
			
			$details_array = array
			(
			 'name',
			 'website',
			 'version',
			 'description',
			 'author',
			 'cbversion',
			 'code',
			 'author_page',
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
	function getPluginDetails($file,$sub_dir=NULL)
	{
		return $this->get_plugin_details($file,$sub_dir);
	}
	
	
	
	/**
	 * Function used to get plugin details from database
	 * @param : plugin_id_code STRING
	 */
	 function getPlugin($file,$folder=NULL)
	 {
		 if($folder)
		 	$folder_query = " AND plugin_folder = '$folder'";
			
		 $result = $db->select(tbl("plugins"),"*"," plugin_file ='".$file."' $folder_query" );
		 return $result[0];
	 }
	 
	 
	/**
	 * ClipBucket Internal Plugin Installer
	 * @param:plugin
	 */
	 function installPlugin($pluginFile,$folder=NULL)
	 {
		 global $db,$LANG,$Cbucket;
		 $plug_details = $this->get_plugin_details($pluginFile,$folder);	
		 if(!$plug_details)
		 	$msg = e(lang('plugin_no_file_err'));
		 if(empty($plug_details['name']))
		 	$msg = e(lang('plugin_file_detail_err'));
		 if($this->is_installed($pluginFile,$folder))
		 	$msg = e(lang('plugin_installed_err'));
		
		 if(empty($msg))
		 {	  
		 	$file_folder = $folder;
		 	if($folder!='')
				$folder  = $folder.'/';
			$plug_details = $this->getPluginDetails(PLUG_DIR.'/'.$folder.$pluginFile);
			
			if(file_exists(PLUG_DIR.'/'.$folder.'install_'.$pluginFile))
			require_once(PLUG_DIR.'/'.$folder.'install_'.$pluginFile);
			
			 dbInsert
			 (
			 tbl('plugins'),
			 array(
				   'plugin_file',
				   'plugin_license_type',
				   'plugin_license_key',
				   'plugin_license_code',
				   'plugin_active',
				   'plugin_folder'
				   ),
			 array(
				   $pluginFile,
				   $plugin_details_array['plugin_license_type'],
				   $plugin_details_array['plugin_license_key'],
				   $plugin_details_array['plugin_license_code'],
				   'yes',
				   $file_folder,
				   )
			 );
			 
			 //Checking For the installation SQL
			 $msg = e(lang('plugin_install_msg'),'m');
			 define('NEW_INSTALL',false);
			 return PLUG_DIR.'/'.$folder.$pluginFile;
		 }
		 return false;
	 }
	 
	 /**
	  * Function used to activate plugin
	  */
	 function pluginActive($plugin_file,$active='yes',$folder=NULL){
		global $db;
		
		if($folder)
		 	$folder_query = " AND plugin_folder = '$folder'";
			
		if($this->is_installed($plugin_file))
		{
			$db->Execute("UPDATE ".tbl("plugins")." SET plugin_active='".$active."' WHERE plugin_file='".$plugin_file."' $folder_query");
			$active_msg = $active=='yes' ? 'activated' : 'deactiveted';
			$msg = e(sprintf(lang("plugin_has_been_s"),$active_msg),'m');
		}else{
			$msg = e(lang('plugin_no_install_err'));
		}
		return $msg;
	 }
	 
	 /**
	  * Function used to activate plugin
	  */
	 function uninstallPlugin($file,$folder=NULL){
		global $db;
		if($this->is_installed($file))
		{
			if($folder)
		 		$folder_query = " AND plugin_folder = '$folder'";
				
			if($folder!='')
				$folder  = $folder.'/';

			$db->Execute("DELETE FROM ".tbl("plugins")." WHERE plugin_file='".$file."' $folder_query");
			if(file_exists(PLUG_DIR.'/'.$folder.'uninstall_'.$file))
			require_once(PLUG_DIR.'/'.$folder.'uninstall_'.$file);
			$msg = e(lang("plugin_uninstalled"),"m");
		}else{
			$msg = e(lang('plugin_no_install_err'));
		}
		return $msg;
	 }
	 
}

?>