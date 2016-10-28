<?php
/*
 Plugin Name: Common Library
 Description: This plugin contains functions used in many plugins 
 Author: Franck Rouze
 Author Website: 
 ClipBucket Version: 2.8.1
 Version: 1.0
 Website:
 */

// Define Plugin's uri constants
define("SITE_MODE",'/admin_area');
define('COMMON_LIBRARY_BASE',basename(dirname(__FILE__)));
define('COMMON_LIBRARY_DIR',PLUG_DIR.'/'.COMMON_LIBRARY_BASE);
define('COMMON_LIBRARY_URL',PLUG_URL.'/'.COMMON_LIBRARY_BASE);


/**
 * Import language data from an xml file called  "lang_XX.xml" where "XX" is the language iso code (ie: lang_en.fr).
 * Regenerate the language pack. 
 *
 * @param string $folder
 * 		the folder where to find the locales file. 	
 * @param string $lang 
 *		iso code of the pack to import (ie: 'en')
 *@see removeLangagePack()
 */
function importLangagePack($folder, $lang){
	global $db,$lang_obj;
	
	//$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/lang_'.$lang.'.xml';
	
	/** Reading Content */
	$content = file_get_contents($file_name);
	if(!$content) {
		e(lang("err_reading_file_content")." : ".$file_name);
	}
	else {
		/** Converting data from xml to array */
		$data = xml2array($content,1,'tag',false);
		$data = $data['clipbucket_language'];
		$phrases = $data['phrases'];
		if(count($phrases)<1) {
			e(lang("no_phrases_found"));
		}
		else if(!$lang_obj->lang_exists($data['iso_code'])) {
			e(lang("language_does_not_exist")." : ".$lang);
		}
		else
		{
			$sql = '';
			foreach($phrases as $code => $phrase) {
				if(!empty($sql))
					$sql .=",\n";
					$sql .= "('".$data['iso_code']."','$code','".htmlspecialchars($phrase,ENT_QUOTES, "UTF-8")."')";
			}
			$sql .= ";";
			$query = "INSERT INTO ".tbl("phrases")." (lang_iso,varname,text) VALUES \n";
			$query .= $sql;
			$db->execute($query);
			e(lang("lang_added")." : ".$lang,"m");
			/** Generate CB language pack */
			if($lang_obj->createPack($lang)){
				e(lang("lang__pack_updated"),"m");
			}
		}
	}
}


/**
 * Remove language data of the xml file called  "lang_XX.xml" where "XX" is the language iso code (ie: lang_en.fr) 
 * from the phrases table. Regenerate the language pack.
 *
 * @param string $folder
 * 		the folder where to find the locales file.
 * @param string $lang
 *		iso code of the pack to import (ie: 'en')
 *@see importLangagePack()
 */
function removeLangagePack($folder, $lang){
	global $db,$lang_obj;
	//$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/lang_'.$lang.'.xml';
	//Reading Content
	$content = file_get_contents($file_name);
	if(!$content) {
		e(lang("err_reading_file_content")." : ".$file_name);
	}
	else {
		//Converting data from xml to array
		$data = xml2array($content,1,'tag',false);
		$data = $data['clipbucket_language'];
		$phrases = $data['phrases'];
		$iso=$data['iso_code'];
		if(count($phrases)<1) {
			e(lang("no_phrases_found"));
		}
		else if(!$lang_obj->lang_exists($data['iso_code'])) {
			e(lang("language_does_not_exist")." : ".$lang);
		}
		else
		{
			$sql = '';
			foreach($phrases as $code => $phrase) {
				$query = "DELETE FROM ".tbl("phrases")." WHERE lang_iso='".$iso."' AND varname='".$code."'";
				$db->execute($query);
			}
			e(lang("lang_deleted")." : ".$lang,"m");
			/** Generate CB language pack */
			if($lang_obj->createPack($lang)){
				e(lang("lang_pack_updated"),"m");
			}
		}
	}
}

/**
 * Preparing management of plugins administration permissions
 *
 * Add fields and values in the database to allow the administrator setting on or off the administration
 * part for the specified plugin
 * 
 * @param string $pluginName
 * 		The plugin name. This name will be stored in the database as a new field in the "user_levels_permissions" table 
 * 		and as an entry in the "user_permissions" table so use an appropriate name (ie : my_plugin). 
 * 		For secure reasons the given name will be prefixed by this function. 
 * @param string $pluginTitle
 * 		This string will bi displayed in the "User levels" admin page as the title of the plugin 
 * 		for wich you want to set permissions
 * @param string $pluginDescription
 * 		This string is used to give a tooltip to administrator. It will also be displaied in the "User levels" admin page.
 * @param "yes"|"no" $allowAcces
 * 		If "yes" users will have acces to the plugin administration otherwinse not. 
 * 		Be carefull even the administrator will not acces to the plugin by default. 
 * 		It's default value is set to "no"
 * @see uninstallPluginAdminPermissions()
 */
function installPluginAdminPermissions($pluginName, $pluginTitle, $pluginDescription='Allow acces to this plugin in the admin panel',$allowAcces="no"){
	$pluginName = getStoredPluginName($pluginName);
	global $db;
	/** Add a field into user_level_permission table to be able to set the admnistration level for each user level */
	$db->Execute('ALTER TABLE '.tbl("user_levels_permissions"). " ADD `".$pluginName."` ENUM('yes','no') NOT NULL DEFAULT '".$allowAcces."'");

	/** Insert a new entry into the user_permission table to specify what is this adminstration level */
	$flds=['permission_type', 'permission_name', 'permission_code', 'permission_desc', 'permission_default'];
	$vls=['3', $pluginTitle, $pluginName, $pluginDescription, $allowAcces];
	$db->insert(tbl('user_permissions'), $flds, $vls);
}


/**
 * remove administration permissions access for the specified plugin 
 *
 * Cleanup the database by removing the appropriate field in the "user_levels_permissions" table 
 * and an entry in the "user_permissions" table.
 * @param string $pluginName
 * 		The plugin name. This name correspond to the $plugin_name given in the installPluginAdminPermissions function.
 * 		The corresponding field in the "user_levels_permissions" table will be droped and 
 * 		the entry in the "user_permissions" table deleted. 
 * 		For secure reasons the given name will be prefixed by this function. 
 * @see installPluginAdminPermissions()  
 */
function uninstallPluginAdminPermissions($pluginName){
	$pluginName = getStoredPluginName($pluginName);
	global $db;
	/** Remove the added field into user_level_permission table  that s used tu manage permissions for each user level */
	$db->Execute('ALTER TABLE '.tbl("user_levels_permissions"). " DROP `".$pluginName."` ");

	/** Remove the entry into the user_permission table that deal with this adminstration level */
	$db->Execute ("DELETE FROM ".tbl('user_permissions')." WHERE `permission_code` = '".$pluginName."'");
}


/**
 * Return the plugin name has it is stored in the persission tables 
 *
 * @param string $pluginName
 * 		The plugin name. This name correspond to the $plugin_name given in the installPluginAdminPermissions function.
 * 		For secure reasons the given name will be prefixed by this function.
 */
function getStoredPluginName($pluginName){
	/**	Add a fixed prefix to the $plugin_name to prevent wrong deletion when uninstalling */
	return  "plgadm_".$pluginName;
}

?>