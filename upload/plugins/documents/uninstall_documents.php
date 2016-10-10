<?php
require_once('../includes/common.php');

/**____________________________________
 * uninstall_documents
 * ____________________________________
 *Remove documents table from the database 
 */
function uninstall_documents()	{
	$uploaddir = BASEDIR."/files/documents";
	$files = glob($uploaddir.'/*'); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file))
			unlink($file); // delete file
	}
	rmdir($uploaddir);
	global $db;
	$db->Execute("DELETE FROM ".tbl("config")." WHERE name='document_max_filesize' ");
	$db->Execute(
		'DROP TABLE  IF EXISTS '.tbl("documents").''
		);
	}
	
/**____________________________________
 * uninstall_video_documents
 * ____________________________________
 *Remove video_documents table from the database 
 */
function uninstall_video_documents()
{
	global $db;
	$db->Execute(
	'DROP TABLE  IF EXISTS '.tbl("video_documents").''
	);
}

/**____________________________________
 * remove_documents_langage_pack
 * ____________________________________
 *Remove the plugin language data from the "phrases" database table. Read 
 *inserted keys in the corresponding xml language pack 
 *e
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function remove_documents_langage_pack($lang){
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
		}
	}
}

uninstall_video_documents();
uninstall_documents();
remove_documents_langage_pack('fr');
remove_documents_langage_pack('en');
?>