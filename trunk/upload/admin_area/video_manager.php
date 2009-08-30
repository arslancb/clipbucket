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
	$page = $_GET['page'];
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