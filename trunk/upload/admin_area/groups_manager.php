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
	$group = mysql_clean($_GET['make_feature']);
	if($groups->GroupExists($group)){
	$msg[] = $groups->MakeFeatured($group);
	}else{
	$msg[] = $LANG['grp_err'];
	}
}
if(isset($_GET['make_unfeature'])){
	$group = mysql_clean($_GET['make_unfeature']);
	if($groups->GroupExists($group)){
	$msg[] = $groups->MakeUnFeatured($group);
	}else{
	$msg[] = $LANG['grp_err'];
	}
}

//Using Multple Action
			if(isset($_POST['make_featured_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					 $groups->MakeFeatured($_POST['check_group'][$id]);
				}
			$msg = $LANG['grp_fr_msg'];
			}
			if(isset($_POST['make_unfeatured_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$groups->MakeUnFeatured($_POST['check_group'][$id]);
				}
			$msg = $LANG['grp_fr_msg1'];
			}

//Activate / Deactivate

if(isset($_GET['activate'])){
	$group = mysql_clean($_GET['activate']);
	if($groups->GroupExists($group)){
	$msg[] = $groups->Activate($group);
	}else{
	$msg[] = $LANG['grp_err'];
	}
}

if(isset($_GET['deactivate'])){
	$group = mysql_clean($_GET['deactivate']);
	if($groups->GroupExists($group)){
	$msg[] = $groups->DeActivate($group);
	}else{
	$msg[] = $LANG['grp_err'];
	}
}
//Using Multple Action
			if(isset($_POST['activate_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$groups->Activate($_POST['check_group'][$id]);
				}
			$msg = $LANG['grp_ac_msg'];
			}
			if(isset($_POST['deactivate_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$groups->DeActivate($_POST['check_group'][$id]);
				}
			$msg = $LANG['grp_dac_msg'];
			}
//Delete Group
if(isset($_GET['delete_group'])){
	$group = mysql_clean($_GET['delete_group']);
	if($groups->GroupExists($group)){
	$msg[] = $groups->DeleteGroup($group);
	}else{
	$msg[] = $LANG['grp_err'];
	}
}

//Deleting Multiple Videos
if(isset($_POST['delete_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					if($groups->GroupExists($_POST['check_group'][$id])){
						$groups->DeleteGroup($_POST['check_group'][$id]);
					}
				}
			$msg = $LANG['grp_del_msg'];
}


//Assigning Default Values
@$values_search= array(
	'search_uname' 		=> mysql_clean($_GET['username']),
	'search_tags'		=> mysql_clean($_GET['tags']),
	'search_category'	=> mysql_clean($_GET['category']),
	'search_title' 		=> mysql_clean($_GET['name']),
	'search_active'		=> mysql_clean($_GET['active']),
	'search_featured'	=> mysql_clean($_GET['featured']),
	'search_sort'		=> mysql_clean($_GET['sort']),
	'search_order'		=> mysql_clean($_GET['order'])
	);
	while(list($name,$value) = each($values_search)){
	DoTemplate::assign($name,$value);
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
	$order 	= "ORDER BY group_id ASC";
	$sql 	= "SELECT * from groups $order $query_limit";
	$sql_p	= "SELECT * from groups";
	
//Search
if(isset($_GET['search'])){
	$username	= mysql_clean($_GET['username']);
	$title		= mysql_clean($_GET['name']);
	$featured	= mysql_clean($_GET['featured']);
	$active		= mysql_clean($_GET['active']);
	$tags		= mysql_clean($_GET['tags']);
	$category	= mysql_clean($_GET['category']);
	$sort		= mysql_clean($_GET['sort']);
	$order		= mysql_clean($_GET['order']);	
	
	if($order == 'ASC'){
		if($sort == 'username'){	$orderby 	= 'ORDER BY username ASC';}
		if($sort == 'name'){		$orderby 	= 'ORDER BY group_name ASC';}
		if($sort == 'date_added'){	$orderby 	= 'ORDER BY date_added ASC';}
		if($sort == 'members'){		$orderby 	= 'ORDER BY total_members ASC';}
		if($sort == 'videos'){		$orderby 	= 'ORDER BY total_videos ASC';}
	}else{
		if($sort == 'username'){	$orderby 	= 'ORDER BY username DESC';}
		if($sort == 'name'){		$orderby 	= 'ORDER BY group_name DESC';}
		if($sort == 'date_added'){	$orderby 	= 'ORDER BY date_added DESC';}
		if($sort == 'members'){		$orderby 	= 'ORDER BY total_members DESC';}
		if($sort == 'videos'){		$orderby 	= 'ORDER BY total_videos DESC';}
	}
	
	if(!empty($category)){
	$category = "AND group_category = '".$category."'";
	}
	if(!empty($featured)){
		$query_featured = "AND featured = '".$featured."'";
		}
	if(!empty($active)){
		$query_active = "AND active = '".$active."'";
		}
	
	
	$sql	 = "SELECT * from groups ";
	$sql	.= "WHERE 
	username 	like '%$username%' AND 
	group_name 		like '%$title%'
	$query_featured
	$query_active AND
	group_tags 		like '%$tags%' 
	$category $orderby $query_limit
	";
	
	$sql_p = "SELECT * from groups WHERE 
	username 	like '%$username%' AND 
	group_name 		like '%$title%'
	$query_featured
	$query_active
	AND group_tags like '%$tags%'
	$category ";
}
		
	//Assing User Data Values
		$rs = $db->Execute($sql);
		$total = $rs->recordcount() + 0;
		$groups = $rs->getrows();
		
		for($id=0;$id<$total;$id++){
				//Setting Group Type
					switch($groups[$id]['group_type']){
						case 0;
						$groups[$id]['type'] = 'Public';
						break;
						case 1;
						$groups[$id]['type'] = 'Moderated';
						break;
						case 2;
						$groups[$id]['type'] = 'Private';
						break;
						default:
						$groups[$id]['type'] = 'Private';
					}
				
				//Setting Video Type
					switch($groups[$id]['video_type']){
						case 0;
						$groups[$id]['video_type'] = 'Public';
						break;
						case 1;
						$groups[$id]['video_type'] = 'Moderated';
						break;
						case 2;
						$groups[$id]['video_type'] = 'Private';
						break;
						default:
						$groups[$id]['video_type'] = 'Private';
					}
					
				//Setting Post Type
					switch($groups[$id]['post_type']){
						case 0;
						$groups[$id]['post_type'] = 'Public';
						break;
						case 1;
						$groups[$id]['post_type'] = 'Moderated';
						break;
						case 2;
						$groups[$id]['post_type'] = 'Private';
						break;
						default:
						$groups[$id]['post_type'] = 'Private';
					}
		$groups[$id]['members'] = $data['duration'];
		$groups[$id]['category'] = $myquery->GetCategory($groups[$id]['group_category'],'category_name');
		}
		
		Assign('total', $total + 0);
		Assign('groups', $groups);

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
	$pages = round($records+0.49,0);
	
	
Assign('pages',$pages+1);
Assign('cur_page',$page);
Assign('nextpage',$page+1);
Assign('prepage',$page-1);
Assign('total_pages',$page_id);

//Assigning Category List
	$sql = "SELECT * from category";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);
	
Assign('msg', @$msg);
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('groups_manager.html');
Template('footer.html');
?>