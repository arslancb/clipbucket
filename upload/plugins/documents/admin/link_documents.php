<?php
require_once DOCUMENT_DIR.'/document_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("documents"));
$pages->page_redir();

/** Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('documents'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('link_document'));
}

/** get video object */
$video = $cbvid->getVideo($_GET['video']);
Assign('video',$video);

/**  Run after a post action called 'document_selected' (link and unlink multiple Document to the selected video) */
if(isset($_POST['document_selected'])){
	//remove unselected document from the first list
	$eh->flush();
	$cnt=count($_POST['checked_documents']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$documentquery->unlinkDocument($_POST['checked_documents'][$id],$video['videoid']);
	}
	//add selected document from the second list
	$cnt=count($_POST['check_documents']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$documentquery->linkDocument($_POST['check_documents'][$id],$video['videoid']);
	}
}

/** Run after a post action called 'filter' (used to filter list of documents) */
if(isset($_POST['filter'])){
	$filtercond=" title like '%".$_POST['title']."%' ";
	assign('documenttitle',$_POST['title']);
	assign('documenturl',$_POST['url']);
	assign('showfilter',true);
}


/** Prepare page */
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
/** Getting documents List */
$result_array['videoid'] = $video['videoid'];
$result_array['selected'] = 'yes';
$result_array['assign'] = 'linkedDocuments';

$linkedDocuments = $documentquery->getDocumentForVideo($result_array);
if ($filtercond) $result_array['cond']=$filtercond;
$result_array['limit'] = $get_limit;
$result_array['selected'] = 'no';
$result_array['assign'] = 'unlinkedDocuments';
$unlinkedDocuments = $documentquery->getDocumentForVideo($result_array);


/** Collecting Data for Pagination */
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $documentquery->getDocumentForVideo($mcount);
$total_pages = count_pages($total_rows,RESULTS);
/** Pagination */
$pages->paginate($total_pages,$page);

/** Set HTML title */
subtitle(lang('link_document'));

template_files('link_documents.html',DOCUMENT_ADMIN_DIR);
?>