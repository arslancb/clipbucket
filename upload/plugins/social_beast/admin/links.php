<?php
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'CB Subtitles');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Update Settings');
}

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('admin_access');
$pages->page_redir();

$links = getBeastLinks(true);
subtitle("Social Beast > Social Links");

template_files(SOCIAL_BEAST_ADMIN_DIR.'/links.html');
?>