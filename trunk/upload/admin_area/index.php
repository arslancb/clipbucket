<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();

$pages->page_redir();
@$page = $pages->show_admin_page(clean($_GET['settings']));
if(!empty($page)){
$pages->redirect($page);
}

if(@$_GET['mode'] == 'force_update'){
$stats->__FORCEUPDATE__();
$msg = "Everything Has Been Updated";
}
$stats->Refresh();
//$stats->UpdateDate();

//Getting Website Statistics
Assign('stats',$stats->stats);
Assign('server',$stats->ServerDetails());
$logs = "No Logfile Found";
if(file_exists(BASEDIR.'/logs/logs.txt'))
{
$logs = nl2br(htmlentities(file_get_contents(BASEDIR.'/logs/logs.txt')));
}
Assign('con_log',$logs );

/*Template('header.html');
Template('leftmenu.html');
Template('index.html');
Template('footer.html');
*/

template_files('index.html');
display_it();
?>