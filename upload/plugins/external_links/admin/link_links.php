<?php
require_once LINK_DIR.'/link_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("links"));
$pages->page_redir();

/** Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('external_links'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('link_external_link'));
}

/** get video object */
$video = $cbvid->getVideo($_GET['video']);
Assign('video',$video);

/** Run after a post action called 'link_selected' (link and unlink multiple external links to the selected video) */
if(isset($_POST['link_selected'])){
	//remove unselected link from the first list
	$eh->flush();
	$cnt=count($_POST['checked_links']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$linkquery->unlinkLink($_POST['checked_links'][$id],$video['videoid']);
	}
	//add selected link from the second list
	$cnt=count($_POST['check_links']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$linkquery->linkLink($_POST['check_links'][$id],$video['videoid']);
	}
}

/** Run after a post action called 'filter' (used to filter list of external links) */
if(isset($_POST['filter'])){
	$filtercond=" title like '%".$_POST['title']."%' ";
	assign('linktitle',$_POST['title']);
	assign('linkurl',$_POST['url']);
	assign('showfilter',true);
}


/** Prepare page */
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
/** Getting links List */
$result_array['videoid'] = $video['videoid'];
$result_array['selected'] = 'yes';
$result_array['assign'] = 'linkedLinks';
//pr($result_array,true);
$linkedLinks = $linkquery->getLinkForVideo($result_array);
if ($filtercond) $result_array['cond']=$filtercond;
$result_array['limit'] = $get_limit;
$result_array['selected'] = 'no';
$result_array['assign'] = 'unlinkedLinks';
$unlinkedLinks = $linkquery->getLinkForVideo($result_array);


/** Collecting Data for Pagination */
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $linkquery->getLinkForVideo($mcount);
$total_pages = count_pages($total_rows,RESULTS);
/** Pagination */
$pages->paginate($total_pages,$page);

/** Set HTML title */
subtitle(lang('external_links'));

template_files('link_links.html',LINK_ADMIN_DIR);
?>