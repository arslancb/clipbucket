<?php
require_once('../includes/common.php');
require_once PLUG_DIR.'/common_library/common_library.php';

/**
 * Install locales for this plugin
 */
$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
importLangagePack($folder,'en');
importLangagePack($folder,'fr');



?>