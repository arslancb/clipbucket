<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Feature / UnFeature Video
if(isset($_GET['make_feature'])){
	$video = mysql_clean($_GET['make_feature']);
	$cbvid->action('feature',$video);
}
if(isset($_GET['make_unfeature'])){
	$video = mysql_clean($_GET['make_unfeature']);
	$cbvid->action('unfeature',$video);
}

//Using Multple Action
if(isset($_POST['make_featured_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$cbvid->action('feature',$_POST['check_video'][$id]);
	}
	e("Selected videos have been set as featured",m);
}
if(isset($_POST['make_unfeatured_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$cbvid->action('unfeature',$_POST['check_video'][$id]);
	}
	e("Selected videos have been removed from featured list",m);
}



//Add To Editor's Pick
if(isset($_GET['editor_pick'])){
	$video = mysql_clean($_GET['editor_pick']);
	if($myquery->VideoExists($video)){
	$msg[] = $myquery->AddToEditorPick($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}

//Activate / Deactivate

if(isset($_GET['activate'])){
	$video = mysql_clean($_GET['activate']);
	$cbvid->action('activate',$video);
}
if(isset($_GET['deactivate'])){
	$video = mysql_clean($_GET['deactivate']);
	$cbvid->action('deactivate',$video);
}

//Using Multple Action
if(isset($_POST['activate_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$cbvid->action('activate',$_POST['check_video'][$id]);
	}
	e("Selected Videos Have Been Activated");
}
if(isset($_POST['deactivate_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$cbvid->action('deactivate',$_POST['check_video'][$id]);
	}
	e("Selected Videos Have Been Dectivated");
}

	
//Delete Video
if(isset($_GET['delete_video'])){
	$video = mysql_clean($_GET['delete_video']);
	$cbvideo->delete_video($video);
}

//Deleting Multiple Videos
if(isset($_POST['delete_selected']))
{
	for($id=0;$id<=RESULTS;$id++)
	{
		$cbvideo->delete_video($_POST['check_video'][$id]);
	}
	$eh->flush();
	e(lang("vdo_multi_del_erro"),m);
}
	
	//Jump To The page
	if(isset($_POST['display_page'])){
		redirect_to($_POST['page_number']);
	}
	
	//Using Form
	$cond = '';
	$cond_array = array();
	if(isset($_GET['search']))
	{
		//Valid Search Fields
		$valid_search_fields = array
		('videoid'	=> array('LIKE','%{VAR}%','AND'),
		 'title'	=> array('LIKE','%{VAR}%','AND'),
		 'videokey'	=> array('LIKE','%{VAR}%','AND'),
		 'tags'		=> array('LIKE','%{VAR}%','AND'),
		 );
		
		foreach($valid_search_fields as $field => $field_props)
		{
			if(!empty($_GET[$field]))
			{
				if(empty($field_props[0]))
					$field_props[0] = '=';
				if(empty($field_props[1]))
					$field_props[1] = '{VAR}';
				if(empty($field_props[2]))
					$field_props[2] = 'OR';
					
				$query_val = $field_props[1];
				$query_val = preg_replace('/{VAR}/',mysql_clean($_GET[$field]),$query_val);
				$cond_array[] = $field_props[2]." $field ".$field_props[0]." '".$query_val."' ";
			}
		}
		
		//Creating Condition
		$count = 0;
		$cond = " videoid<>'0' ";
		foreach($cond_array as $qcond)
		{
			$cond .= $qcond;
		}
	}
	
	//Getting Video List
	$page = mysql_clean($_GET['page']);
	$get_limit = create_query_limit($page,RESULTS);
	$videos = $db->select("video",'*',$cond,$get_limit,"date_added DESC");
	Assign('videos', $videos);	

	//Collecting Data for Pagination
	$total_rows = $db->count('video','*',$cond);
	$records = $total_rows/RESULTS;
	$total_pages = round($records+0.49,0);
	
	//Pagination
	$pages->paginate($total_pages,$page);

template_files('video_manager.html');
display_it();

?>