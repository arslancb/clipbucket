<?php
require_once VIDEO_GROUPING_DIR.'/video_grouping_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("videogrouping"));
$pages->page_redir();

/** Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('grouping'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('link_grouping'));
}

/** get video object */
$video = $cbvid->getVideo($_GET['video']);
Assign('video',$video);

/** link and unlink groupings to the selected video) */
if(isset($_POST['groupingSelected'])){
	//remove unselected grouping from the first list
	$eh->flush();
	$cnt=count($_POST['checkedGrouping']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$videoGrouping->unlinkGrouping($_POST['checkedGrouping'][$id],$video['videoid']);
	}
	//add selected grouping from the second list
	$cnt=count($_POST['checkGrouping']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$videoGrouping->linkGrouping($_POST['checkGrouping'][$id],$video['videoid']);
	}
}
/** Run after a post action called 'filter' (used to filter list of external documents) */
elseif(isset($_POST['filter'])){
	$filtercond=" vdogrouping.name like '%".$_POST['name']."%'";
	assign('searchname',$_POST['name']);
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
$result_array['assign'] = 'linkedGrouping';

$linkedGrouping = $videoGrouping->getGroupingForVideo($result_array);
if ($filtercond) $result_array['cond']=$filtercond;
$result_array['limit'] = $get_limit;
$result_array['selected'] = 'no';
$result_array['assign'] = 'unlinkedGrouping';
$unlinkedGrouping = $videoGrouping->getGroupingForVideo($result_array);


/** Collecting Data for Pagination */
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $videoGrouping->getGroupingForVideo($mcount);
$total_pages = count_pages($total_rows,RESULTS);
/** Pagination */
$pages->paginate($total_pages,$page);

/** Set HTML title */
subtitle(lang('link_grouping'));

template_files('link_video_grouping.html',VIDEO_GROUPING_ADMIN_DIR);
?>