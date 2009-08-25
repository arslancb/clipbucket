<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
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
	if($myquery->VideoExists($video)){
	$msg[] = $myquery->MakeFeaturedVideo($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}
if(isset($_GET['make_unfeature'])){
	$video = mysql_clean($_GET['make_unfeature']);
	if($myquery->VideoExists($video)){
	$msg[] = $myquery->MakeUnFeaturedVideo($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}

//Using Multple Action
			if(isset($_POST['make_featured_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$myquery->MakeFeaturedVideo($_POST['check_video'][$id]);
				}
			$msg = "Selected Videos Have Been Set As Featured";
			}
			if(isset($_POST['make_unfeatured_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$myquery->MakeUnFeaturedVideo($_POST['check_video'][$id]);
				}
			$msg = "Selected Videos Have Been Removed From The Featured Video List";
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
	if($myquery->VideoExists($video)){
	$msg[] = $myquery->ActivateVideo($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}
if(isset($_GET['deactivate'])){
	$video = mysql_clean($_GET['deactivate']);
	if($myquery->DeActivateVideo($video)){
	$msg[] = $myquery->DeActivateVideo($video);
	}else{
	$msg[] = $LANG['class_vdo_del_err'];
	}
}

		//Using Multple Action
			if(isset($_POST['activate_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$myquery->ActivateVideo($_POST['check_video'][$id]);
				}
			$msg = "Selected Videos Have Been Activated";
			}
			if(isset($_POST['deactivate_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$myquery->DeActivateVideo($_POST['check_video'][$id]);
				}
			$msg = "Selected Videos Have Been Dectivated";
			}


//Delete Video
if(isset($_GET['delete_video'])){
	$video = mysql_clean($_GET['delete_video']);
	if($myquery->VideoExists($video)){
		$msg[] = $myquery->DeleteVideo($video);
	}else{
		$msg[] = $LANG['class_vdo_del_err'];
	}
}

//Deleting Multiple Videos
if(isset($_POST['delete_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					if($myquery->VideoExists($_POST['check_video'][$id])){
						$msg[] = $myquery->DeleteVideo($_POST['check_video'][$id]);
					}
				}
			$msg = $LANG['vdo_del_selected'];
}
			
//Assigning Default Values
@$values_search= array(
	'search_uname' 		=> mysql_clean($_GET['username']),
	'search_flagged'	=> mysql_clean($_GET['flagged']),
	'search_tags'		=> mysql_clean($_GET['tags']),
	'search_category'	=> mysql_clean($_GET['category']),
	'search_title' 		=> mysql_clean($_GET['title']),
	'search_active'		=> mysql_clean($_GET['active']),
	'search_featured'	=> mysql_clean($_GET['featured']),
	'search_sort'		=> mysql_clean($_GET['sort']),
	'search_order'		=> mysql_clean($_GET['order'])
	);
	while(list($name,$value) = each($values_search)){
	DoTemplate::assign($name,$value);
	}
	

//Jump To The page
if(isset($_POST['display_page'])){
	redirect_to($_POST['page_number']);
}
	
//Pagination

	$limit  = RESULTS;	
	Assign('limit',$limit);
	$page   = clean(@$_GET['page']);
	Assign('limit',$limit);
	if(empty($page) || $page == 0){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;
	
	$query_limit  = "limit $from,$limit";
	$order 	= "ORDER BY date_added DESC";
	$sql 	= "SELECT * from video $order $query_limit";
	$sql_p	= "SELECT * from video";
	
//Search
if(isset($_GET['search'])){
	$username	= mysql_clean($_GET['username']);
	$title		= mysql_clean($_GET['title']);
	$flagged	= mysql_clean($_GET['flagged']);
	$featured	= mysql_clean($_GET['featured']);
	$active		= mysql_clean($_GET['active']);
	$featured	= mysql_clean($_GET['featured']);
	$tags		= mysql_clean($_GET['tags']);
	$category	= mysql_clean($_GET['category']);
	$sort		= mysql_clean($_GET['sort']);
	$order		= mysql_clean($_GET['order']);	
	
	if($order == 'ASC'){
		if($sort == 'username'){	$orderby 	= 'ORDER BY username ASC';}
		if($sort == 'title'){		$orderby 	= 'ORDER BY title ASC';}
		if($sort == 'date_added'){	$orderby 	= 'ORDER BY date_added ASC';}
		if($sort == 'views'){		$orderby 	= 'ORDER BY views ASC';}
		if($sort == 'rating'){		$orderby 	= 'ORDER BY rating ASC';}
	}else{
		if($sort == 'username'){	$orderby 	= 'ORDER BY username DESC';}
		if($sort == 'title'){		$orderby 	= 'ORDER BY title DESC';}
		if($sort == 'date_added'){	$orderby 	= 'ORDER BY date_added DESC';}
		if($sort == 'views'){		$orderby 	= 'ORDER BY views DESC';}
		if($sort == 'rating'){		$orderby 	= 'ORDER BY rating DESC';}
	}

	$category = "AND
	(
	category01 = '".$category."' OR
	category02 = '".$category."' OR
	category03 = '".$category."'
	)
	";
	
	if(!empty($flagged)){
		$query_flagged = "AND flagged = '".$flagged."'";
		}
	if(!empty($featured)){
		$query_featured = "AND featured = '".$featured."'";
		}
	if(!empty($active)){
		$query_active = "AND active = '".$active."'";
		}
	
	
	$sql	 = "SELECT * from video ";
	$sql	.= "WHERE 
	username 	like '%$username%' AND 
	title 		like '%$title%'
	$query_flagged
	$query_featured
	$query_active AND
	tags 		like '%$tags%' 
	$category $orderby $query_limit
	";
	
	$sql_p = "SELECT * from video WHERE 
	username 	like '%$username%' AND 
	title 		like '%$title%'
	$query_flagged
	$query_featured
	$query_active
	AND tags like '%$tags%'
	$category ";
}
		
	//Assing User Data Values
		$rs = $db->Execute($sql);
		$total = $rs->recordcount() + 0;
		$videos = $rs->getrows();
		
		for($id=0;$id<$total;$id++){
		$videos[$id]['thumb'] = substr($videos[$id]['flv'], 0, strrpos($videos[$id]['flv'], '.'));
        $videos[$id]['description'] = nl2br($videos[$id]['description']);
        $videos[$id]['category1'] = $myquery->GetCategory($videos[$id]['category01'],'category_name');
		$videos[$id]['category2'] = $myquery->GetCategory($videos[$id]['category02'],'category_name');
		$videos[$id]['category3'] = $myquery->GetCategory($videos[$id]['category03'],'category_name');
		}
		
		Assign('total', $total + 0);
		Assign('videos', $videos);

//Pagination #A Tough Job#
$view = clean(@$_GET['view']);
if($view == 'search'){
$link = '&amp;username=' .mysql_clean($_GET['username']). '&amp;title=' .mysql_clean($_GET['title']).'&amp;flagged=' .mysql_clean($_GET['flagged']).'&amp;featured=' .mysql_clean($_GET['featured']).'&amp;active='.mysql_clean($_GET['active']).'&amp;tags='.mysql_clean($_GET['tags']).'&amp;category01='.mysql_clean($_GET['category01']).'&amp;category02='.mysql_clean($_GET['category02']).'&amp;category03='.mysql_clean($_GET['category03']).'&amp;sort='.mysql_clean($_GET['sort']).'&amp;order='.mysql_clean($_GET['order']).'&amp;search='.mysql_clean($_GET['search']);
Assign('link',$link);
}	

	$query = mysql_query($sql_p);
	Assign('grand_total',mysql_num_rows($query));
	$total_rows = mysql_num_rows($query);
	$page_id=1;
	$id = 1;
	//$all_pages[0]['page'] = $page_id;
	$records = $total_rows/$limit;
	$total_pages = round($records+0.49,0);
if(empty($link)){
$link = "?view=Videos";
}

$pages->paginate($total_pages,$page,$link);


//Assigning Category List
	$sql = "SELECT * from category";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);
	
Assign('msg', @$msg);	
/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('video_manager.html');
Template('footer.html');*/

template_files('video_manager.html');
display_it();

?>