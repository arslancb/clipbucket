<?php
require_once('../includes/common.php');

/**____________________________________
 * install_ul_speaker
 * ____________________________________
 *Creating Table for video speakers if not exists 
 */
function install_ul_speaker() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("speaker").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`firstname` varchar(100) NOT NULL ,
	  		`lastname` varchar(100) NOT NULL ,
			`slug` varchar(100) NOT NULL ,
	  		`photo` varchar(200) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
}


/**____________________________________
 * install_ul_speakerfunction
 * ____________________________________
 *Creating Table for video speaker Role if not exists 
 */
function install_ul_speakerfunction() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("speakerfunction").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`description` longtext,
			`speaker_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
	$db->Execute(
		'ALTER TABLE '.tbl("speakerfunction").'
  			ADD CONSTRAINT `speakerfunc_speaker_id_fk_speaker_id` FOREIGN KEY (`speaker_id`) REFERENCES '.tbl("speaker").' (`id`);
		'
	);
}


/**____________________________________
 * install_ul_video_speaker
 * ____________________________________
 *Creating Table for video speaker Role if not exists 
 */
function install_ul_video_speaker() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("video_speaker").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`video_id` bigint(20) NOT NULL,
			`speakerfunction_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
	$db->Execute(
		'ALTER TABLE '.tbl("video_speaker").'
  			ADD UNIQUE KEY `video_id` (`video_id`,`speakerfunction_id`);
		'
	);
	$db->Execute(
		'ALTER TABLE '.tbl("video_speaker").'
			ADD CONSTRAINT `speakerfunction_id_fk_speakerfunction_id` FOREIGN KEY (`speakerfunction_id`) REFERENCES '.tbl("speakerfunction").' (`id`);
			'
	);
}

/**____________________________________
 * import_ul_speaker_langage_pack
 * ____________________________________
 *Import language data from an xml file called  "speaker_lang_XX.xml" where "XX" is 
 *the language iso code. The file must be placed in then "lang" subfolder of the plugin.
 *
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function import_ul_speaker_langage_pack($lang){
	global $db,$lang_obj;
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/speaker_lang_'.$lang.'.xml';
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

install_ul_speaker();
install_ul_speakerfunction();
install_ul_video_speaker();
import_ul_speaker_langage_pack('fr');
import_ul_speaker_langage_pack('en');
?>