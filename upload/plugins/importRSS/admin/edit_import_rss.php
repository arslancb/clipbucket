<?php
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
//if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("authcas"));

// Assigning page and subpage
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Videos');
}

if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Import RSS');
}

	
		
	/**
	 *	INSERT / UPDATE
	 */
	if ( (isset($_POST['import_rss_update'])) ){
	
		if ( (isset($_POST['url_rss'])) and (isset($_POST['frequence'])) ){
		
			//$flds = array('url_rss', 'frequence');
			//$flds = array('id', 'url_rss', 'last_crawl', 'crawl_frequence', 'nb_new_vid_from_last_crawl');
			$val = array('', $_POST['url_rss'], $_POST['rsslast_crawl'], $_POST['frequence'], $_POST['rssnb_new_vid_from_last_crawl'], $_POST['default_cat'], $_POST['default_quality']);
			
			if ($_POST['rssid']){
				$val[0] = $_POST['rssid'];
			}
			
			updateImportRssConfig($val);
			
			e("Post.<br />\n", "m");

		}
	}


	/**
	 *	DELETE MULTIPLE
	 */
	if(isset($_POST['delete_selected'])){
		$cnt=count($_POST['check']);
		if ($cnt>0){
			for($id=0;$id<$cnt;$id++)
				deleteImportRssConfig($_POST['check'][$id]);
		}
		else
			e(lang("no_link_selected"),"w");
	}


	/**
	 *	DELETE ONE
	 */
	if (isset($_GET['delete'])) {
		$del = mysql_clean($_GET['delete']);
		deleteImportRssConfig($del);
	}


	/**
	 *	SELECT
	 */
	if (isset($_GET['edit'])) {
		if (error()){
			$details=$_POST;
			$details['id']=$details['linkid'];
		}
		else {
			$id = $_GET['edit'];
			$details = getRssDetails($id);
		}

		if ($details){
			assign('rss_details',$details);
		}
		assign('showedit',true);
		assign('showfilter',false);
		assign('showadd',false);
	}



$rss = getImportRssConfig();
Assign('rss', $rss);

$videocategorie = getCbCategories();
Assign('videocategorie', $videocategorie);


// Output
template_files(PLUG_DIR.'/importRSS/admin/edit_import_rss.html',true);
?>
