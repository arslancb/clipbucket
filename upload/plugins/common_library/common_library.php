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

?>