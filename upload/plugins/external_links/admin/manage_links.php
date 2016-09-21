<?php
require_once LINK_DIR.'/link_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('external_links'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('link_manager'));
}


// Run after a post action called 'delete_link'
if (isset($_GET['delete_link'])) {
	$dellink = mysql_clean($_GET['delete_link']);
	$linkquery->delete_link($dellink);
}

// Run after a post action called 'delete_selected' (Deleting Multiple links)
if(isset($_POST['delete_selected'])){
	$cnt=count($_POST['check_link']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$linkquery->delete_link($_POST['check_link'][$id]);
	}
	else
		e(lang("no_link_selected"),"w");
}

// Run after a post action called 'filter' (used to filter list of external links)
if(isset($_POST['filter'])){
	$filtercond=" title like '%".$_POST['title']."%'";
	assign('title',$_POST['title']);
	assign('url',$_POST['url']);
	assign('showfilter',true);
}


//Getting link List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
//Getting link List
$result_array['limit'] = $get_limit;
if ($filtercond) $result_array['cond']=$filtercond;
//pr($result_array,true);
$links = $linkquery->get_links($result_array);
Assign('links', $links);

//Collecting Data for Pagination
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $linkquery->get_links($mcount);
$total_pages = count_pages($total_rows,RESULTS);
//Pagination
$pages->paginate($total_pages,$page);


//Set HTML title
subtitle(lang("link_manager"));

template_files('manage_links.html',LINK_ADMIN_DIR);
?>