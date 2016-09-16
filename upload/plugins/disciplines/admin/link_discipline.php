<?php
require_once DISCIPLINE_DIR.'/disciplines_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
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

// Run after a post action called 'link_selected' (link and unlink multiple speaker to the selectedvideo)
if(isset($_POST['validate'])){
	$disciplinequery->set_discipline($video['videoid'],$_POST['checked_discipline'][0]);
	$video['discipline'] = $_POST['checked_discipline'][0];
}
Assign('video',$video);

//Getting speaker List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];


$nbdiscipline=$disciplinequery->discipline_count();
assign("nbdiscipline",$nbdiscipline);
$all_disciplines=$disciplinequery->get_all_disciplines();
assign("all_disciplines",$all_disciplines);



template_files('link_discipline.html',DISCIPLINE_ADMIN_DIR);
?>