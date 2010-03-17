<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
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
	for($id=0;$id<=count($_POST['check_video']);$id++){
		$cbvid->action('feature',$_POST['check_video'][$id]);
	}
	$eh->flush();
	e("Selected videos have been set as featured","m");
}
if(isset($_POST['make_unfeatured_selected'])){
	for($id=0;$id<=count($_POST['check_video']);$id++){
		$cbvid->action('unfeature',$_POST['check_video'][$id]);
	}
	$eh->flush();
	e("Selected videos have been removed from featured list","m");
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
	for($id=0;$id<=count($_POST['check_video']);$id++){
		$cbvid->action('activate',$_POST['check_video'][$id]);
	}
	$eh->flush();
	e("Selected Videos Have Been Activated","m");
}
if(isset($_POST['deactivate_selected'])){
	for($id=0;$id<=count($_POST['check_video']);$id++){
		$cbvid->action('deactivate',$_POST['check_video'][$id]);
	}
	$eh->flush();
	e("Selected Videos Have Been Dectivated","m");
}

	
//Delete Video
if(isset($_GET['delete_video'])){
	$video = mysql_clean($_GET['delete_video']);
	$cbvideo->delete_video($video);
}

//Deleting Multiple Videos
if(isset($_POST['delete_selected']))
{
	for($id=0;$id<=count($_POST['check_video']);$id++)
	{
		$cbvideo->delete_video($_POST['check_video'][$id]);
	}
	$eh->flush();
	e(lang("vdo_multi_del_erro"),"m");
}


	//Calling Video Manager Functions
	call_functions($cbvid->video_manager_funcs);
		
	$page = mysql_clean($_GET['page']);
	$get_limit = create_query_limit($page,RESULTS);
	
	if(isset($_GET['search']))
	{
		
		$array = array
		(
		 'videoid' => $_GET['videoid'],
		 'videokey' => $_GET['videokey'],
		 'title'	=> $_GET['title'],
		 'tags'	=> $_GET['tags'],
		 'user' => $_GET['userid'],
		 'category' => $_GET['category'],
		 'featured' => $_GET['featured'],
		 'active'	=> $_GET['active'],
		 'status'	=> $_GET['status'],
		 );		
	}
	
	$result_array = $array;
	//Getting Video List
	$result_array['limit'] = $get_limit;
	if(!$array['order'])
		$result_array['order'] = " videoid DESC ";
	$videos = get_videos($result_array);
	
	Assign('videos', $videos);	

	//Collecting Data for Pagination
	$vcount = $array;
	$vcount['count_only'] = true;
	$total_rows  = get_videos($vcount);
	$total_pages = count_pages($total_rows,RESULTS);
	$pages->paginate($total_pages,$page);

	
	//Category Array
	if(is_array($_GET['category']))
		$cats_array = array($_GET['category']);		
	else
	{
		preg_match_all('/#([0-9]+)#/',$_GET['category'],$m);
		$cats_array = array($m[1]);
	}
	$cat_array =	array(lang('vdo_cat'),
					'type'=> 'checkbox',
					'name'=> 'category[]',
					'id'=> 'category',
					'value'=> array('category',$cats_array),
					'hint_1'=>  lang('vdo_cat_msg'),
					'display_function' => 'convert_to_categories');
	assign('cat_array',$cat_array);

subtitle("Video Manager");
template_files('video_manager.html');
display_it();

?>