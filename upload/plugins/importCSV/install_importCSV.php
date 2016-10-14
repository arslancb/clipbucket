<?php
require_once('../includes/common.php');

/**____________________________________
 * install_importCSV
 * ____________________________________
 *Creating Table for importCSV if not exists 
 */
function install_importCSV() {
	$uploaddir = BASEDIR."/files/importCSV";
	if (is_dir($uploaddir)){ 
		$files = glob($uploaddir.'/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
		rmdir($uploaddir);
	}
	$folder = mkdir($uploaddir,0775);
	global $db;
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("importCSV_mapping").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`import_table_name` varchar(128) NOT NULL ,
	  		`import_field_name` varchar(128) NOT NULL ,
	  		`static_value` varchar(2048) NOT NULL ,
			`cb_table_name` varchar(128) NOT NULL ,
	  		`cb_field_name` varchar(128) NOT NULL ,
			`cb_field_type` varchar(128) NULL,
			`search` varchar(1024) NULL,
			`replace` varchar(1024) NULL,
			`commentaire` varchar(128) NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
			);
	$db->Execute(
			'CREATE TABLE IF NOT EXISTS '.tbl("importCSV_join").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`import_table_name` varchar(128) NOT NULL ,
			`import_field1` varchar(128) NOT NULL ,
			`cb_table1_name` varchar(128) NOT NULL ,
			`cb_table1_field_search` varchar(128) NOT NULL ,
			`cb_table1_field` varchar(128) NOT NULL ,
			`import_field2` varchar(128) NOT NULL ,
			`cb_table2_name` varchar(128) NOT NULL ,
			`cb_table2_field_search` varchar(128) NOT NULL ,
			`cb_table2_field` varchar(128) NOT NULL ,
			`cb_tablejoin_name` varchar(128) NOT NULL ,
			`cb_tablejoin_field1` varchar(128) NOT NULL ,
			`cb_tablejoin_field2` varchar(128) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
			);
}



/**____________________________________
 * import_importCSV_langage_pack
 * ____________________________________
 *Import language data from an xml file called  "importCSV_lang_XX.xml" where "XX" is 
 *the language iso code. The file must be placed in then "lang" subfolder of the plugin.
 *
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function import_importCSV_langage_pack($lang){
	global $db,$lang_obj;
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/importCSV_lang_'.$lang.'.xml';
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

install_importCSV();
/*import_importCSV_langage_pack('fr');
import_importCSV_langage_pack('en');*/
?>