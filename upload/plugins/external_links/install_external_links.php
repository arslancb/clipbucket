<?php
require_once('../includes/common.php');

/**____________________________________
 * install_links
 * ____________________________________
 *Creating Table for links if not exists 
 */
function install_links() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("links").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`title` varchar(1024) NOT NULL ,
	  		`url` varchar(1024) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
}


/**____________________________________
 * install_video_links
 * ____________________________________
 *Creating a join Table for video and links if not exists 
 */
function install_video_links() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("video_links").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`video_id` bigint(20) NOT NULL,
			`link_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
}

/**____________________________________
 * import_links_langage_pack
 * ____________________________________
 *Import language data from an xml file called  "links_lang_XX.xml" where "XX" is 
 *the language iso code. The file must be placed in then "lang" subfolder of the plugin.
 *
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function import_links_langage_pack($lang){
	global $db,$lang_obj;
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/links_lang_'.$lang.'.xml';
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

install_links();
install_video_links();
import_links_langage_pack('fr');
import_links_langage_pack('en');
?>