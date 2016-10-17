<?php

/**
 * remove locales for this plugin
 */
$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
removeLangagePack($folder,'en');
removeLangagePack($folder,'fr');

?>