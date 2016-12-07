<?php
require_once VIDEO_GROUPING_DIR.'/video_grouping_class.php';
/** Check if user has admin acces */
$userquery->admin_login_check();
/** Check if user has admin acces to this plugin */
if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName("videogrouping"));
$pages->page_redir();


/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', lang('video_addon'));
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', lang('manage_video_grouping'));
}


/** Edit the current groupingType */
if(isset($_GET['editGroupingType'])){
	if (error()){
		$details=$_POST;
		$details['id']=$details['editGroupingType'];
	}
	else {
		$id = $_GET['editGroupingType'];
		$details = $videoGrouping->getGroupingType($id);
	}
	if ($details){
		assign('groupingtype',$details);
	}
	assign('showedit',true);
}
/** Delete the current groupingType */
elseif (isset($_GET['deleteGroupingType'])) {
	$id = mysql_clean($_GET['deleteGroupingType']);
	$videoGrouping->deleteGroupingType($id);
	assign('showedit',false);
}
/** Delete the current grouping */
elseif (isset($_GET['deleteGrouping'])) {
	$id = mysql_clean($_GET['deleteGrouping']);
	$videoGrouping->deleteGrouping($id);
	assign('showedit2',false);
}
/** Modify the in_menu attribute for the current grouping */
elseif (isset($_GET['setInMenu'])) {
	$id = mysql_clean($_GET['id']);
	$value = $_GET['setInMenu']==1;
	$videoGrouping->setInMenu($id,$value);
	assign('showedit2',false);
}
/** Edit the current grouping */
elseif (isset($_GET['editGrouping'])) {
	$id = mysql_clean($_GET['editGrouping']);
	assign('currentGroup',$videoGrouping->getGrouping($id));
	assign('showedit2',true);
}




	


/** Add a new grouping type into the database */
if(isset($_POST['addGroupingType'])){
	if($videoGrouping->addGroupingType($_POST))	{
		e(lang("new_grouping_type_added"),"m");
		$_POST = '';
		assign('showedit',false);
	}
}
/** Update an existing grouping type */
elseif(isset($_POST['updateGroupingType'])){
	if($videoGrouping->updateGroupingType($_POST))	{
		e(lang("grouping_type_updated"),"m");
		$_POST = '';
		assign('showedit',false);
		assign('groupingtype',false);
	}
}
/** Update an existing grouping type */
elseif(isset($_POST['deleteSelectedGroupingTypes'])){
	$cnt=count($_POST['checkGroupingType']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$videoGrouping->deleteGroupingType($_POST['checkGroupingType'][$id]);
	}
	else
		e(lang("no_grouping_type_selected"),"w");
}
/** Add a new video Grouping */
elseif(isset($_POST['addGrouping'])){
	if($videoGrouping->addGrouping($_POST,$_FILES['groupingThumb']))	{
		e(lang("new_grouping_added"),"m");
		$_POST = '';
		assign('showedit2',false);
	}
}
/** set all video grouping order */
elseif(isset($_POST['updateOrder'])){
	$array=array();
	foreach ($_POST as $key=>$value){
		$key=mysql_clean($key);
		$value=mysql_clean($value);
		if (strpos($key, "order_")===0)
			$array[str_replace('order_', '', $key)]=$value;
	}
	if($videoGrouping->reorder($array))	{
		e(lang("grouping_reorder_done"),"m");
		$_POST = '';
		assign('showedit2',false);
	}
}
/** Delete multiple video groupings */
else if(isset($_POST['deleteSelected'])){
	$cnt=count($_POST['checkGrouping']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$videoGrouping->deleteGrouping($_POST['checkGrouping'][$id]);
	}
	else
		e(lang("no_grouping_selected"),"w");
}
/** Update grouping entry */
elseif(isset($_POST['updateGrouping'])){
	if($videoGrouping->updateGrouping($_POST,$_FILES['groupingThumb']))	{
		e(lang("grouping_updated"),"m");
		$_POST = '';
		assign('showedit2',false);
	}
}
/** Run after a post action called 'filter' (used to filter list of external documents) */
elseif(isset($_POST['filter'])){
	$filtercond=" g.name like '%".$_POST['name']."%'";
	assign('searchname',$_POST['name']);
	assign('showfilter',true);
	assign('showedit2',false);
}



/** Prepare page */
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
/** Getting grouping List */
$result_array['limit'] = $get_limit;
if ($filtercond) $result_array['cond']=$filtercond;
//pr($result_array,true);
$grp = $videoGrouping->getGroupings($result_array);

/** Collecting Data for Pagination */
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $videoGrouping->getGroupings($mcount);
$total_pages = count_pages($total_rows,RESULTS);
/** Pagination */
$pages->paginate($total_pages,$page);


//Set variables to use in the template
//$grp=$videoGrouping->getAllGroupings();
assign('groupingTypeList', $videoGrouping->getAllGroupingTypes());
assign('vdogrp',$grp);
assign('total',count($grp));
assign('msg',@$msg);



//Set HTML title
subtitle(lang("manage_video_grouping"));

template_files('manage_video_grouping.html',VIDEO_GROUPING_ADMIN_DIR);
$Cbucket->add_admin_header(VIDEO_GROUPING_ADMIN_DIR.'/header.html');
?>