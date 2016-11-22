<?php
/*
 Plugin Name: importCSV
 Description: Import data from CSV files into clipbucket tables
 Author: Franck Rouze
 Author Website: http://semm.univ-lille1.fr/
 ClipBucket Version: 2.8.1
 Version: 1.0
 Website:
 */
require_once 'importCSV_class.php';
if (!$cbplugin->is_installed('common_library.php'))
	e(sprintf(lang("plugin_not_installed : %s"),"Common Library"));
else
	require_once PLUG_DIR.'/common_library/common_library.php';

/** Define Plugin's uri constants */
define("SITE_MODE",'/admin_area');
define('IMPORTCSV_BASE',basename(dirname(__FILE__)));
define('IMPORTCSV_DIR',PLUG_DIR.'/'.IMPORTCSV_BASE);
define('IMPORTCSV_URL',PLUG_URL.'/'.IMPORTCSV_BASE);
define('IMPORTCSV_ADMIN_DIR',IMPORTCSV_DIR.'/admin');
define('IMPORTCSV_ADMIN_URL',IMPORTCSV_URL.'/admin');
define("IMPORTCSV_MANAGEPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".IMPORTCSV_BASE."/admin&file=manage_importCSV.php");
assign("importCSV_managepage",IMPORTCSV_MANAGEPAGE_URL);
define("IMPORTCSV_DOWNLOAD_DIR",BASEDIR."/files/importCSV");


/** Add entries for the plugin in the administration pages */
if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("importCSV")]=='yes')
	add_admin_menu('Tool Box',lang('importcsv_manager'),'manage_importCSV.php',IMPORTCSV_BASE.'/admin');
	
?>