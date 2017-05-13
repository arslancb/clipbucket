<?php
require_once LINK_DIR.'/link_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("links"));
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('video_addon'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('external_links_manager'));
}


/** Run after a post action called 'deleteLink' */
if (isset($_GET['deleteLink'])) {
	$dellink = mysql_clean($_GET['deleteLink']);
	$linkquery->deleteLink($dellink);
}

/** Run after a post action called 'delete_selected' (Deleting Multiple links) */
if(isset($_POST['delete_selected'])){
	$cnt=count($_POST['check_link']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$linkquery->deleteLink($_POST['check_link'][$id]);
	}
	else
		e(lang("no_link_selected"),"w");
}

/** Run after a post action called 'filter' (used to filter list of external links) */
if(isset($_POST['filter'])){
	$filtercond=" title like '%".$_POST['title']."%'";
	assign('title',$_POST['title']);
	assign('url',$_POST['url']);
	assign('showfilter',true);
	assign('showadd',false);
	assign('showedit',false);
}

/** Run after a post action called 'addLink' (used to filter list of external links) */
if(isset($_POST['addLink'])){
	if($linkquery->addLink($_POST))	{
		e(lang("new_link_added"),"m");
		$_POST = '';
		assign('showfilter',false);
		assign('showadd',false);
		assign('showedit',false);
	}
}

/** Run after a post action called 'editLink' */
if (isset($_GET['editLink'])) {
	if (error()){
		$details=$_POST;
		$details['id']=$details['linkid'];
	}
	else {
		$id = $_GET['editLink'];
		$details = $linkquery->getLinkDetails($id);
	}

	if ($details){
		assign('link',$details);
	}
	assign('showedit',true);
	assign('showfilter',false);
	assign('showadd',false);
}

/** Run after a post action called 'updateLink' */
if(isset($_POST['updateLink'])){
	if ($linkquery->updateLink($_POST)) {
		e(lang("update_link"),"m");
		$_POST = '';
		assign('showfilter',false);
		assign('showadd',false);
		assign('showedit',false);
		assign('link',false);
	}
}



/** Prepare page */
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
/** Getting link List */
$result_array['limit'] = $get_limit;
if ($filtercond) $result_array['cond']=$filtercond;
//pr($result_array,true);
$links = $linkquery->getLinks($result_array);
Assign('links', $links);

/** Collecting Data for Pagination */
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $linkquery->getLinks($mcount);
$total_pages = count_pages($total_rows,RESULTS);
/** Pagination */
$pages->paginate($total_pages,$page);


/** Set HTML title */
subtitle(lang("external_links_manager"));

template_files('manage_links.html',LINK_ADMIN_DIR);
?>