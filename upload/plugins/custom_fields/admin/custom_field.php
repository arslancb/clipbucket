<?php
/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Custom Field');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'View Custom Field');
}
$test="test";
assign('test',$test);
template_files(PLUG_DIR.'/customfield/admin/custom_field.html',true);
?>