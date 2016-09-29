<?php
require_once('../includes/common.php');

/**____________________________________
 * install_documents
 * ____________________________________
 *Creating Table for documents if not exists 
 */
function install_documents() {
	$uploaddir = BASEDIR."/files/documents";
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
	$db->insert(tbl("config"),array("name","value"),array("document_max_filesize","25000000"));	
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("documents").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`title` varchar(1024) NOT NULL ,
	  		`filename` varchar(1024) NOT NULL ,
	  		`mimetype` varchar(256) NOT NULL ,
			`storedfilename` varchar(128) NOT NULL ,
			`size` int(11) NOT NULL ,
	  		`creationdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
	);
}


/**____________________________________
 * install_video_documents
 * ____________________________________
 *Creating a join Table for video and documents if not exists 
 */
function install_video_documents() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("video_documents").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`video_id` bigint(20) NOT NULL,
			`document_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
}

/**____________________________________
 * import_documents_langage_pack
 * ____________________________________
 *Import language data from an xml file called  "documents_lang_XX.xml" where "XX" is 
 *the language iso code. The file must be placed in then "lang" subfolder of the plugin.
 *
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function import_documents_langage_pack($lang){
	global $db,$lang_obj;
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	$file_name = $folder.'/documents_lang_'.$lang.'.xml';
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

install_documents();
install_video_documents();
import_documents_langage_pack('fr');
import_documents_langage_pack('en');
?>