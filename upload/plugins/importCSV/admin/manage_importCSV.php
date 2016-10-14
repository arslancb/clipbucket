<?php
require_once IMPORTCSV_DIR.'/importCSV_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Tool Box');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('importCSV_manager'));
}



if(isset($_POST['import_mapping_model'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->import_mapping_model(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}

if(isset($_POST['delete_mapping_model'])){
	$importCSVobject->delete_mapping_model();
	e(lang('data_successfully_deleted'),'m');
}

if(isset($_POST['import_join_model'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->import_join_model(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}
		
if(isset($_POST['delete_join_model'])){
	global $db;
	$query='DELETE  FROM '.tbl("importCSV_join").' WHERE 1';
	$db->Execute($query);
	e(lang('data_successfully_deleted'),'m');
}

if(isset($_POST['import_mapping_data'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->import_mapping_data($_POST['tablename'],IMPORTCSV_DOWNLOAD_DIR."/".$hashname, $_POST['separator']);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}

if(isset($_POST['import_join_data'])){
	global $importCSVobject;
	if ($_FILES['filename']['name']!="") {
		$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
		move_uploaded_file($_FILES['filename']['tmp_name'], IMPORTCSV_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		$importCSVobject->import_join_data($_POST['tablename'],IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		unlink(IMPORTCSV_DOWNLOAD_DIR."/".$hashname);
		e(lang('data_successfully_added'),"m");
	}
	else
		e(lang('no_file_selected'),"e");
}



//Set HTML title
subtitle(lang("importCSV_manager"));

template_files('manage_importCSV.html',IMPORTCSV_ADMIN_DIR);
?>