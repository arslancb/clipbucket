<?php
//Function used to uninstall Plugin
$uploaddir = BASEDIR."/files/thumbs/disciplines";
if (!is_dir($uploaddir)) {
	die($uploaddir.lang("not_a_valid_folder"));
}

/**____________________________________
 * uninstall_disciplines
 * ____________________________________
 *Remove discplines table from the database 
 */
function uninstall_disciplines(){
	global $db;
	$uploaddir = BASEDIR."/files/thumbs/disciplines";
	// remove all thumb images
	$disc = $db->_select("SELECT thumb_url FROM ".tbl("disciplines")." ORDER BY discipline_order ASC");
	foreach($disc as $tmp){
		unlink($uploaddir."/".$tmp['thumb_url']);
	}
	unset($tmp);
	rmdir($uploaddir);
	// remove disciplines table
	$db->Execute("DROP TABLE ".tbl('disciplines'));
	// remove discipline field in video table
	$db->Execute("ALTER TABLE ".tbl('video')." DROP `discipline` ");
}

/**____________________________________
 * remove_discipline_langage_pack
 * ____________________________________
 *Remove the plugin language data from the "phrases" database table. Read
 *inserted keys in the corresponding xml language pack
 *e
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function remove_discipline_langage_pack($lang){
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


uninstall_disciplines();
remove_discipline_langage_pack('en');
remove_discipline_langage_pack('fr');
?>