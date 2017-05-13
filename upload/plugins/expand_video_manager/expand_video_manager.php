<?php
/*
Plugin Name: Expand Video Manager
Description: Add possibility to inject code on the Video manager (Edit Video section)
Author: Adrien Ponchelet
Author Website: https://www.u-picardie.fr
ClipBucket Version: 2.8.1 rc1
Version: 0.1
*/


	global $cbplugin;
	$tab_installed = $cbplugin->getInstalledPlugins();

	/**
	 *	Test if this plugin is activated
	 */
	$bool = false;
	
	foreach($tab_installed as $plug_installed){
		// TODO: Dynamically find name og the script
		if ($plug_installed['plugin_file'] == 'expand_video_manager.php'){
			if ($plug_installed['plugin_active'] == 'yes'){
			    $bool = true;
		
			}
		}
	}

	/**
	 *	If plugin activated, include the script
	 */
	if ($bool == true){
		/**
		*	Build an array of plugin to integrate
		*/
		function getExpandPage(){
			global $db, $cbplugin;
			// Get the list og plugin installed
			$tab_installed = $cbplugin->getInstalledPlugins();
			// Get the list of plugin to be integrated
			$results = $db->select(tbl("expand_video_manager"),"*");

			$tmp = array();

			if(is_array($results)){
			
				foreach($results as $result)
				{
					// Get the plugin folder by exploding the path
					$a = explode("/", $result["evm_plugin_url"]);
					// Search the item "plugins"
					$k = array_search('plugins', $a);
					// Get the item immediately after
					$plug_dir = $a[$k+1];
					
					foreach($tab_installed as $plug_installed){
					
						if ($plug_installed['folder'] == $plug_dir){
							if ($plug_installed['plugin_active'] == 'yes'){
								$id = $result["evm_id"];
								unset($result["evm_id"]);
								$tmp["evm-".$id] = $result;
							}
						}
					
					}
				}
			}
			
			return $tmp;
		}


		/**
		*	Begin
		*/
		$postData = '';
		$getData = '';

		/**
		*	Recupere les param POST et GET
		*/
		if (isset($_POST)){	$postData = $_POST; }
		if (isset($_GET)){	$getData = $_GET; }


		/**
		*	Passe le JSON aux JS
		*/
		$json = json_encode(array_merge( getExpandPage(), $postData, $getData ), true);
		Assign('expand_video_manager_json_content', $json);

	// 	function plop(){
	// 	  echo '<pre>';
	// 	  print_r();
	// 	  echo '</pre>';
	// 	}

	// 	register_anchor_function('plop', 'key_rep');

		/**
		*	Injecte le JS dans le HEADER
		*	Uniquement si on est dans la bonne page
		*/
		if (substr($_SERVER['SCRIPT_NAME'], -14, 14) == "edit_video.php"){
			$Cbucket->add_admin_header(PLUG_DIR . '/expand_video_manager/admin/header.html', 'global');
		}
		
		
		/**
		*	Add entries for the plugin in the administration pages
		*/
		if ($cbplugin->is_installed('common_library.php') && $userquery->permission[getStoredPluginName("expandVideoManager")]=='yes')
		add_admin_menu('Templates And Players','Expand Video Edit','evm_manager.php','expand_video_manager/admin');

	} // EndIf plugin is active
?>