<?php
require_once('../includes/common.php');



/**____________________________________
 * install_video_speaker
 * ____________________________________
 *Creating Table for video speaker Role if not exists 
 */
function install_auth_cas() {
	global $db;
	$db->Execute(
		'CREATE TABLE '.tbl("auth_cas_config").' ( 
			`id` INT(2) NOT NULL AUTO_INCREMENT , 
			`name` VARCHAR(30) NOT NULL , 
			`value` VARCHAR(255) NOT NULL , 
			PRIMARY KEY (`id`)
		) 
		ENGINE = InnoDB CHARSET=utf8;'
	);
}





/**____________________________________
 * import_speaker_langage_pack
 * ____________________________________
 *Import language data from an xml file called  "speaker_lang_XX.xml" where "XX" is 
 *the language iso code. The file must be placed in then "lang" subfolder of the plugin.
 *
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function import_auth_cas_langage_pack($lang){
	global $db,$lang_obj;
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/auth_cas_lang_'.$lang.'.xml';
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

install_auth_cas();
//	import_speaker_langage_pack('fr');
//	import_speaker_langage_pack('en');
?>
