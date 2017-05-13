<?php
// require_once('../includes/common.php');


	/**
	* Install db tables of ImportRSS plugin
	*/
	function installImportRss() {
		global $db;

		$db->Execute(
			'CREATE TABLE '.tbl("import_rss_config").' (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`url_rss` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`last_crawl` datetime NOT NULL,
				`crawl_frequence` decimal(10,0) NOT NULL,
				`nb_new_vid_from_last_crawl` int(4) NOT NULL,
				`default_cat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`default_quality` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
		);

		$db->Execute(
			'CREATE TABLE '.tbl("import_rss_video_queued").' (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`url_cb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`description` text COLLATE utf8_unicode_ci NOT NULL,
				`category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`url_thumnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`filename` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
				`tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`date_uploaded` datetime NOT NULL,
				`id_rss_config` int(5) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;'
		);
	}


	/**
	* Install locales for this plugin
	*/
// 	global $cbplugin;
// 	if ($cbplugin->is_installed('common_library.php')){
// 		require_once PLUG_DIR.'/common_library/common_library.php';
// 		$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
// 		importLangagePack($folder,'en');
// 		importLangagePack($folder,'fr');
// 		installPluginAdminPermissions("authcas", "CAS Athentication administration", "Allow CAS Authentication management");
// 	}


 	installImportRss();
?>