<?php
require_once('../includes/common.php');

/**____________________________________
 * uninstall_ul_speaker
 * ____________________________________
 *Remove speaker table from the database 
 */
function uninstall_ul_speaker()	{
		global $db;
		$db->Execute(
		'DROP TABLE  IF EXISTS '.tbl("speaker").''
		);
	}
	
/**____________________________________
 * uninstall_ul_speakerfunction
 * ____________________________________
 *Remove speakerfunction table from the database 
 */
	function uninstall_ul_speakerfunction() {
	global $db;
	$db->Execute(
	'DROP TABLE  IF EXISTS '.tbl("speakerfunction").''
	);
}

/**____________________________________
 * uninstall_ul_video_speaker
 * ____________________________________
 *Remove video_speaker table from the database 
 */
function uninstall_ul_video_speaker()
{
	global $db;
	$db->Execute(
	'DROP TABLE  IF EXISTS '.tbl("video_speaker").''
	);
}

/**____________________________________
 * remove_ul_speaker_langage_pack
 * ____________________________________
 *Remove the plugin language data from the "phrases" database table. Read 
 *inserted keys in the corresponding xml language pack 
 *e
 *input $lang : iso code of the pack to import (ie: 'en')
 */
function remove_ul_speaker_langage_pack($lang){
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

uninstall_ul_video_speaker();
uninstall_ul_speakerfunction();
uninstall_ul_speaker();
remove_ul_speaker_langage_pack('fr');
remove_ul_speaker_langage_pack('en');
?>