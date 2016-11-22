<?php
require_once IMPORTCSV_DIR.'/importCSV_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("importCSV"));
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Tool Box');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('importcsv_manager'));
}

// run $_POST actions
if(isset($_POST['importMappingModel'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->importMappingModel(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}
else if(isset($_POST['deleteMappingModel'])){
	$importCSVobject->deleteMappingModel();
	e(lang('data_successfully_deleted'),'m');
}
else if(isset($_POST['importJoinModel'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->importJoinModel(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}
else if(isset($_POST['deleteJoinModel'])){
	global $db;
	$query='DELETE  FROM '.tbl("importCSV_join").' WHERE 1';
	$db->Execute($query);
	e(lang('data_successfully_deleted'),'m');
}
else if(isset($_POST['importMappingData'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->importMappingData($_POST['tablename'],IMPORTCSV_DOWNLOAD_DIR."/".$hashname, $_POST['separator']);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}
else if(isset($_POST['importJoinData'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->importJoinData($_POST['tablename'],IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}
else if(isset($_POST['generateVideoFileNames'])){
	global $importCSVobject;
	$importCSVobject->generateVideoFileNames();
}
	



//Set HTML title
subtitle(lang("importcsv_manager"));

template_files('manage_importCSV.html',IMPORTCSV_ADMIN_DIR);
?>