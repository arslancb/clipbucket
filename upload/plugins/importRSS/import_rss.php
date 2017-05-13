<?php
/*
Plugin Name: Import RSS
Description: Get and insert video from other Clipbucket system in order to register video here
Author: Adrien Ponchelet
Author Website: https://www.u-picardie.fr
ClipBucket Version: 2.8.2
Version: 1.0
*/

	// Define Plugin's uri constants
	define("SITE_MODE",'/admin_area');
	
	define('IMPORT_RSS',basename(dirname(__FILE__)));			// *** Chemin du plugin


define('IMPORT_RSS_DIR',PLUG_DIR.'/'.IMPORT_RSS);
define('IMPORT_RSS_URL',PLUG_URL.'/'.IMPORT_RSS);

define('IMPORT_RSS_ADMIN_DIR',IMPORT_RSS_DIR.'/admin');

define('IMPORT_RSS_ADMIN_URL',IMPORT_RSS_URL.'/admin');

define("IMPORT_RSS_EDITPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".IMPORT_RSS."/admin&file=edit_import_rss.php");
assign("rss_edit",IMPORT_RSS_EDITPAGE_URL);


define("IMPORT_RSS_VIEWPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".IMPORT_RSS."/admin&file=view_import_rss.php");
assign("rss_view",IMPORT_RSS_VIEWPAGE_URL);



	/**
	 *	Update Import RSS config entries
	 *		@var array $val array of post value
	 *		@var array $fld array of database fields
	 */
	function updateImportRssConfig($val = '', $fld = ''){
	
		global $db;

		
		/* **
		 *
		 * DB Fields : id, url_rss, last_crawl, crawl_frequence, nb_new_vid_from_last_crawl
		 * 
		 */
		if ($fld == ''){
			$fld = array('id', 'url_rss', 'last_crawl', 'crawl_frequence', 'nb_new_vid_from_last_crawl', 'default_cat', 'default_quality');
		}

		if ($val[0] == ''){
			// Insert value
			$db->insert(tbl('import_rss_config'), $fld, $val);
		}
		else{
			// Update value
			$db->update(tbl('import_rss_config'), $fld, $val, "id='$val[0]'");
		}
	}
	
	/**
	 *	Get the configuration information
	 *
	 *	@return array The key and value of config
	 */
	function getImportRssConfig(){
		global $db;

		$config = $db->_select('SELECT * FROM '.tbl("import_rss_config"));

		return $config;
	}

	
	
	
	/**
	 *	Get the list of video information
	 *
	 *	@return array The key and value of config
	 */
	function getImportRssVideo($id){
		global $db;
		
		if ($id != 0){
		$cond = ' WHERE id_rss_config = '.$id;
		}

		$videoqueued = $db->_select('SELECT * FROM '.tbl("import_rss_video_queued").$cond.';');

		return $videoqueued;
	}
	
	

	
	
	function getCbCategories(){
		global $db;

		$videocategories = $db->_select('SELECT * FROM '.tbl("video_categories"));

		return $videocategories;
	}

	
	
	
	/**
	 * Remove link from the database. 
	 * if the link is associated to a video, then nothing is done, just an error message appears.
	 *
	 * @param int $id
	 * 		the id of the link to be deleted 
	 */
	function deleteImportRssConfig($id){
		global $db;
		
		$test2=$db->execute("DELETE FROM ".tbl("import_rss_config")." WHERE id='$id'");
		
		if (!$test2){
			e(lang("cant_del_linked_link_msg")." id=".$id,"e");
		}
		else{
			e(lang("link_del_msg")." id=".$id,"m");
		}
	}

	/**
	 * Function used to get link details using it's id 
	 *
	 * @param int $id 
	 *		Link's id
	 * @return array|bool 
	 * 		a dictionary containing each fields for a link, false if no link found
	 */
	function getRssDetails($id=NULL){
		global $db;
		$fields = tbl_fields(array('import_rss_config' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('import_rss_config');
		$query .= " WHERE import_rss_config.id = '$id'";
		$result = select($query);
		Assign('rss_details', $result);
		if ($result) {
			$details = $result[0];
			return $details;
		}
		return false;
	}

	
	
	/**
	 * Function used to get link details using it's id 
	 *
	 * @param int $id 
	 *		Link's id
	 * @return array|bool 
	 * 		a dictionary containing each fields for a link, false if no link found
	 */
	function getRssVideoDetails($id=NULL){
		global $db;
		$fields = tbl_fields(array('import_rss_video_queued' => array('*'), 'import_rss_config' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('import_rss_video_queued');
		$query .= " LEFT JOIN import_rss_config ON import_rss_video_queued.id_rss_config = import_rss_config.id WHERE import_rss_video_queued.id = '$id'";
		
		$result = select($query);
		Assign('rss_details', $result);
		if ($result) {
			$details = $result[0];
			return $details;
		}
		return false;
	}	
	
	
	
	
	
	
	
	

	/**
	 * Count of queud video from rss for menu badge
	 */
	$nbqueued = $db->_select('SELECT COUNT(id) AS rssvid FROM '.tbl("import_rss_video_queued"));
	$rss_badge = ' <span class="badge" id="totrssvid">'.$nbqueued[0]['rssvid'].'</span>';


	/**
	 *	Add entries for the plugin in the administration pages
	 */
//	if ($cbplugin->is_installed('common_library.php') && $userquery->permission[getStoredPluginName("authcas")]=='yes')
	add_admin_menu('Videos','ImportRSS'.$rss_badge,'edit_import_rss.php',IMPORT_RSS.'/admin');

?>