<?php

require_once('../includes/common.php');

/**____________________________________
 * install_disciplines
 * ____________________________________
 *Creating Table for disciplines if not exists 
 */
function install_disciplines() {	
	$uploaddir = BASEDIR."/files/thumbs/disciplines";
	if (is_dir($uploaddir))
		rmdir($uploaddir);
	$folder = mkdir($uploaddir,0777);
	if ($folder || $found){
		//$handle = fopen(PLUG_DIR."/disciplines/default.png", "r");
		if(copy(PLUG_DIR."/disciplines/default.png", $uploaddir."/default.png")){
			global $db;
			$db->Execute(
			// WARNING ! Use `` instead of '' for fields - SMARTY restriction
			"CREATE TABLE IF NOT EXISTS ".tbl('disciplines')." (
			`id` int(225) NOT NULL AUTO_INCREMENT,
			`name` varchar(128) NOT NULL,
			`discipline_order` bigint(5) NOT NULL DEFAULT '1',
			`description` varchar(2048) NOT NULL DEFAULT 'Default discipline',
			`is_default` BOOLEAN NOT NULL DEFAULT '0',
			`in_menu` BOOLEAN NOT NULL DEFAULT '1',
			`color` varchar(50) NOT NULL DEFAULT '#999999',
			`thumb` BOOLEAN DEFAULT '1',
			`thumb_url` varchar(255) NOT NULL DEFAULT 'default.png',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			);
			// Add a default value
			$db->Execute("INSERT INTO  ".tbl('disciplines')." (name, is_default) VALUES ('Default',1)");
			// Add a field in video table
			$db->Execute("ALTER TABLE ".tbl('video')." ADD `discipline` varchar(255) NOT NULL DEFAULT '1'");
		} else {
			die(lang("unable_to_copy_default_image"));
		}
	} else {
		die(lang("unable_to_create_folder"));
	}
}


/**____________________________________
 * import_discipline_langage_pack
 * ____________________________________
 *Import language data from an xml file called  "discipline_lang_XX.xml" where "XX" is
 *the language iso code. The file must be placed in then "lang" subfolder of the plugin.
 *
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function import_discipline_langage_pack($lang){
	global $db,$lang_obj;
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/discipline_lang_'.$lang.'.xml';
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
		}
	}
}

//This will first check if plugin is installed or not, if not this function will install the plugin details
install_disciplines();
import_discipline_langage_pack('en');
import_discipline_langage_pack('fr');

?>