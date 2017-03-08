<?php
require_once DISCIPLINE_DIR.'/disciplines_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("discipline"));

$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('discipline'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('link_discipline'));
}

//get video object
$video = $cbvid->getVideo($_GET['video']);

// Run after a post action called 'validate' (link the selected discipline to the selected video)
if(isset($_POST['validate'])){
	$disciplinequery->setDiscipline($video['videoid'],$_POST['checked_discipline'][0]);
	$video['discipline'] = $_POST['checked_discipline'][0];
}
Assign('video',$video);

//Getting speaker List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];


$nbdiscipline=$disciplinequery->disciplineCount();
assign("nbdiscipline",$nbdiscipline);
$all_disciplines=$disciplinequery->getAllDisciplines();
assign("all_disciplines",$all_disciplines);



template_files('link_discipline.html',DISCIPLINE_ADMIN_DIR);
?>