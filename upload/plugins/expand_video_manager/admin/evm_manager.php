<?php
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("expandVideoManager"));

// Assigning page and subpage
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Templates And Players');
}

if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Expand Video Manager');
}








$tmp = getExpandPage();



assign('listeplugin', $tmp);














// 	global $db;
// 
// 	$results = $db->select(tbl("expand_video_manager"),"*");
// 	
// 	$tmp = array();
// 
// 	if(is_array($results)){
// 		foreach($results as $result)
// 		{
// 			$id = $result["evm_id"];
// 			unset($result["evm_id"]);
// 			$tmp["evm-".$id] = $result;
// 		}
// 	}
// 	
// 	return $tmp;
// 		
// 		
// 		
// 
// 	/**
// 	 *	Test Tab 3 : testtab
// 	 */
// 	if (isset($_POST['plop'])) {
// //		$plop = $_POST['search'];
// 		assign('plop', 'plop');
// 	}

/**
 *	Before output, we assign all config value for the form
 */

// Output
template_files(PLUG_DIR.'/expand_video_manager/admin/evm_manager.html',true);
?>
