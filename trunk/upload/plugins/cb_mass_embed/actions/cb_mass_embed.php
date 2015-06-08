<?php

/**
 * This file is used to 
 * Mass Embed Videos
 * Author : Arslan Hassan
 * Since : 11 Jan 2010
 */
 
set_time_limit(3000);
require'../../../includes/admin_config.php';

//require '../cb_mass_embed.class.php';

$userquery->admin_login_check();

$cb_mass_embed = new cb_mass_embed();

//Updating video title and descript..
if(isset($_POST['id']) && isset($_POST['value']))
{
	$id = post('id');
	$input = explode('-',$id);
	$type = $input[0];
	$id = $input[1];
	
	if($type=='title')
	{
		$db->update(tbl("video"),array("title"),array(post('value'))," videoid='$id'");
	}elseif($type=='desc')
	{
		$db->update(tbl("video"),array("description"),array(post('value'))," videoid='$id'");
	}elseif($type=='tags')
	{
		$db->update(tbl("video"),array("tags"),array(post('value'))," videoid='$id'");
	}
	echo htmlspecialchars(post('value'));
	exit();
}

if(isset($_POST['update_cat']))
{
	$_POST['category'] = $_POST['cat'];
	$vid = post('vid');
	$form_array = array
	(
		 'cat'		=> array('title'=> lang('vdo_cat'),
		 'type'=> 'checkbox',
		 'name'=> 'category[]',
		 'id'=> 'category',
		 'value'=> array('category',$category),
		 'hint_1'=>  sprintf(lang('vdo_cat_msg'),ALLOWED_VDO_CATS),
		 'db_field'=>'category',
		 'required'=>'yes',
		 'validate_function'=>'validate_vid_category',
		 'invalid_err'=>lang('vdo_cat_err3'),
		 'display_function' => 'convert_to_categories'),
	);
	
	validate_cb_form($form_array,$_POST);
	
	if(!error())
	{
		foreach($_POST['cat'] as $v)
		{
			$new_val .= "#".$v."# ";
		}
		$val = $new_val;
		$db->update(tbl("video"),array("category"),array($val)," videoid='$vid' ");
	}else
		echo "Please select category";	
	exit();
}

///// check LICENSE ///////////////
$check_result=cb_mass_embed_license(CB_MASS_EMBED_LICENSE);		
if($check_result['status']!='Active'){
	//$eh->e('Unknown Mass Embedder license - Please Submit Ticket in <a href="http://client.clip-bucket.com/">client area</a> about the issue ');
	$cb_mass_embed->license_status = false;
}
else{
	$cb_mass_embed->license_status = true;	
}
$cb_mass_embed->license_status = true;	
////  check LICENSE  END///////

if(!$cb_mass_embed->license_status)
{
	
	$eh->e("Unable to process mass embedding, please contact <a href='http://client.clip-bucket.com'>ClipBucket Support</a>");
	if(error_list)
	{
		$errs = error_list();
		foreach($errs as $err)
			echo "<span style='color:#ed0000'>$err</span><br>";
	}
	exit();
}

if(isset($_POST['category_sync']))
{
	
	if($cb_mass_embed->sync_categories())
	{
		$msg['status'] = $cb_mass_embed->category_synced(true);
		$msg['msg']	= '<span style="color:#63d200">Categories have been synced</span>';
	}else
	{
		$err = error();
		$msg['status'] = $cb_mass_embed->category_synced(true);
		$msg['msg']	= '<span style="color:#ed0000">'.$err[0].'</span>';
	}
	echo json_encode($msg);
	exit();
}

$Smarty->assign_by_ref('cb_mass_embed',$cb_mass_embed);


$embedType = 'keywords';
if($_POST['embed_type']=='links')
	$embedType = 'links';
if($_POST['embed_type']=='yt_user')
	$embedType = 'yt_user';
if($_POST['embed_type']=='manual')
	$embedType = 'manual';

$configs = $cb_mass_embed->configs;
//Gett List of pending videos
		
	//Setting Up Results
	$apis = $cb_mass_embed->get_installed_apis();
	
	if(isset($_POST['apis']))
		$apis = $_POST['apis'];
	
	if(is_array($apis))
	$apis = array_unique($apis);
	$total_apis = count($apis);
	
	if($embedType=='manual')
	{
		
		$api = $_POST['maual_api'];
		include_once(PLUG_DIR."/cb_mass_embed/apis/".$api.".api.php");
		//Just get results from you
		$manEmbed = new $api();
		
		//lets get the api url
		$manEmbed->keywords = $_POST['manual_keywords'];
		$page = $_POST['page'];
		if(!$page || !is_numeric($page) || $page<1)
			$page = 1;
		assign('page',$page);
		if($page<10)
		assign('next',$page+1);
		if($page>1)
		assign('pre',$page-1);
		
		$manEmbed->this_page = $page;
		
		$manEmbed->result_offset = ($_POST['manual_results'] * ($page-1))+1;
		
		$manEmbed->max_results = $manEmbed->results = $_POST['manual_results'];
		
		$manEmbed->ignore_data_exists = true;
		//Open api and parse results
		$results = $manEmbed->parse_get_results();
		
		//Setting categorization type
		switch($_POST['categorization'])
		{
			case "selected":
			{
				$manEmbed->categorization = 'selected';
				$manEmbed->set_category = $_POST['mass_category'];
			}
			break;
			case "manual":
			case "each":
			{
				$manEmbed->categorization = 'manual';
				$cat = $cbvid->get_default_category();
				$manEmbed->set_category = $cat['category_id'];
			}
			break;
			case "keywords":
			case "auto":
			default:
			{
				$manEmbed->categorization = 'auto';
			}
		}
				
				
		$newResutls = array();
		if($results)
		foreach($results as $result)
		{
			//Setting Categories
			$cat = $cbvid->get_default_category();
			//Matching Category
			if($manEmbed->categorization=='auto')
				$thecat = $manEmbed->match_cat($result['tags']);
			if($thecat)
				$result['category'] = $thecat;
			else	
				$result['category'][] = $cat['category_id'];
			if($manEmbed->categorization=='selected')
			{
				$result['category'] = array();
				$result['category'][] = $manEmbed->set_category;	
			}
			
			$cats = $result['category'];
			foreach($cats as $cat)
			$newCats .= '#'.$cat.'#';
			
			$result['category'] = $newCats;
			
			$newResutls[] = $result;
			
			
		}
		assign('videos',$newResutls);
		#Template(PLUG_DIR."/cb_mass_embed/blocks/temp_videos.html",false);
		Template(PLUG_DIR."/cb_mass_embed/blocks/temp_videos.html",false);
		
	}
	
	if($embedType=='keywords')
	{
		$results = $configs['results'];
		
		//Dividing Results
		if(isset($_POST['mass_results']) && is_numeric($_POST['mass_results']) && $_POST['mass_results']>0)
		{
			$results = $_POST['mass_results'];
		}
		
		if($_POST['result_type']!='each_site')
		{
			$results_per_api = round($results/$total_apis);
			$remain = $results - ($results_per_api*$total_apis );			
		}else
		{
			$results_per_api = $results ;
			$results = $results*$total_apis;
		}
		
		if($results_per_api<1)
				$results_per_api = 1;	

		$msg.="<div class='mass-massage'><h4>Requested Embedding for <strong>".$results."</strong> videos </h4>";



		//Calling Apis One By One
		if($cb_mass_embed->license_status)
		{
			$insert_vids = array();
			$loop = 1;
			$axist = 0;
			foreach($apis as $api)
			{
					
				include_once(PLUG_DIR."/cb_mass_embed/apis/$api.api.php");
			
				$embed = new $api();
				$embed->max_tries = $cb_mass_embed->config('max_tries');
				
				//Setting categorization type
				switch($_POST['categorization'])
				{
					case "selected":
					{
						$embed->categorization = 'selected';
						$embed->set_category = $_POST['mass_category'];
					}
					break;
					case "manual":
					case "each":
					{
						$embed->categorization = 'manual';
						$cat = $cbvid->get_default_category();
						$embed->set_category = $cat['category_id'];
					}
					break;
					case "keywords":
					case "auto":
					default:
					{
						$embed->categorization = 'auto';
					}
				}
				if($loop!=$total_apis)
					$embed->max_results = $embed->results = $results_per_api;
				else
				{
					$embed->max_results = $embed->results = $results_per_api+$remain;
				}
				
				//Setting Keywords
				echo $embed->keywords = $configs['keywords'];
				//Setting IMport Comments settings
				if(isset($_POST['import_comments']) && $_POST['import_comments']!='undefined')
					$embed->import_comments = true;
				else
					$embed->import_comments = false;	
				//Setting Import Stats Settings
				if(isset($_POST['import_stats']) && $_POST['import_stats']!='undefined')
					$embed->import_stats = true;
				else
					$embed->import_stats = false;
				if(isset($_POST['mass_keywords']))
				{
					$embed->keywords = $_POST['mass_keywords'];
				}
				
				/**
				 * ClipBucket Creating Channel Feature
				 */
				//checking weather user wants to create channel or not
				
				$uid = NULL;
				if($_POST['create_channel']=='yes')
				{
					$channel_name = SEO($embed->keywords);
					if($_POST['channel_type'] == 'keywords')
						$channel_name = SEO($embed->keywords);
					elseif(!empty($_POST['channel_name']))
					{
						$channel_name = SEO($_POST['channel_name']);
					}
					
					$udetails = $userquery->get_user_details($channel_name);
					
					if(!$udetails)
					{
						//Creating User
						$params['username'] = $channel_name;
						$params['email'] = $channel_name."_".RandomString(10)."@anonymous.com";
						$params['password'] = RandomString(10);
						$params['cpassword'] = $params['password'];
						
						$params['country'] = 'PK';
						$params['category'] = 1;
						$params['gender'] = 'male';
						$params['dob'] = '01-14-1989';
						$uid = $userquery->signup_user($params);
					}else
						$uid = $udetails['userid'];		
				}
			
				
				($embed->get_feed_url());
				
				#exit();
				$embed->parse_and_get_results();
				$embed->userid = $uid;
				$embed->download_and_insert_data();
				$msg.= "<div style='font-size:14px'><strong>".$embed->results."</strong> From <strong>$api</strong> - <strong>".$embed->_total_results." </strong> Results found in <strong>".$embed->tries."</strong> tries - <strong>".$embed->already_exist."</strong> already exists</div>";
				
				if(is_array($embed->insert_vids))
				$insert_vids = array_merge($insert_vids,$embed->insert_vids);
				
				$axist += $embed->already_exist;
				$total_results_found += $embed->_total_results;
				$loop++;
			
		}
			
			//Vids query
			if(is_array($insert_vids))
			{
				$vid_query = "";
				foreach($insert_vids as $vid)
				{
					if($vid)
					{
						if($vid_query!="")
							$vid_query .= " OR ";
						$vid_query .=" videoid='$vid' ";
					}
				}
				if($vid_query)
				$vid_query = " AND ( ".$vid_query." )";
				
				$except_vid_query = "";
				foreach($insert_vids as $vid)
				{
					if($vid)
					{
						if($except_vid_query!="")
							$except_vid_query .= " AND  ";
						$except_vid_query .=" videoid<>'$vid' ";
					}
				}
				if($except_vid_query)
					$except_vid_query = " AND ( ".$except_vid_query." )";
			}
			
			 #pr(error(),true);
			
			$msg.="<div ><strong>$total_results_found</strong> results found <strong>".$axist."</strong> video(s) already exist(s) in database</div>";
			$msg.="</div>";
			echo $msg;
			if($vid_query)
			$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending' $vid_query");
			assign("videos",$vids);
			assign("EMBED_PLUG_DIR",PLUG_DIR."/cb_mass_embed/blocks");
			Template(PLUG_DIR."/cb_mass_embed/blocks/videos.html",false);
			
			/*$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending' $except_vid_query");
			assign("videos",$vids);
			assign('pending','yes');
			Template(PLUG_DIR."/cb_mass_embed/blocks/videos.html",false);*/
			
		}else
		{
			$eh->e("Unable to process mass embedding, please contact <a href='http://client.clip-bucket.com'>ClipBucket Support</a>");
			if(error_list)
			{
				$errs = error_list();
				foreach($errs as $err)
					echo "<span style='color:#ed0000'>$err</span><br>";
			}
		}
	}
	
	//Now Using Mass Link Embedding
	if($embedType=='links')
	{
		/**
		 * ClipBucket Creating Channel Feature
		 */
		//checking weather user wants to create channel or not
		
		$uid = NULL;
		if($_POST['create_channel']=='yes' && $_POST['channel_name'])
		{

			$channel_name = SEO($_POST['channel_name']);
			
			$udetails = $userquery->get_user_details($channel_name);
			
			if(!$udetails)
			{
				//Creating User
				$params['username'] = $channel_name;
				$params['email'] = $channel_name."_".RandomString(10)."@anonymous.com";
				$params['password'] = RandomString(10);
				$params['cpassword'] = $params['password'];
				
				$params['country'] = 'PK';
				$params['category'] = 1;
				$params['gender'] = 'male';
				$params['dob'] = '01-14-1989';
				$uid = $userquery->signup_user($params);
			}else
				$uid = $udetails['userid'];		
		}
		
		$input = post('mass_links');
		
		//Setting IMport Comments settings
		if(isset($_POST['import_comments']) && $_POST['import_comments']!='undefined')
			$cb_mass_embed->import_comments = true;
		else
			$cb_mass_embed->import_comments = false;	
		//Setting Import Stats Settings
		if(isset($_POST['import_stats']) && $_POST['import_stats']!='undefined')
			$cb_mass_embed->import_stats = true;
		else
			$cb_mass_embed->import_stats = false;
		
		//Setting categorization type
		switch($_POST['categorization'])
		{
			case "selected":
			{
				$cb_mass_embed->categorization = 'selected';
				$cb_mass_embed->set_category = $_POST['mass_category'];
			}
			break;
			case "maual":
			case "each":
			{
				$cb_mass_embed->categorization = 'manual';
				$cat = $cbvid->get_default_category();
				$cb_mass_embed->set_category = $cat['category_id'];
			}
			break;
			case "keywords":
			case "auto":
			default:
			{
				$cb_mass_embed->categorization = 'auto';
			}
		}
		$cb_mass_embed->the_mass_links($input);
		
		$cb_mass_embed->userid = $uid;
		$cb_mass_embed->download_and_insert_data();
		
		$insert_vids = $cb_mass_embed->insert_vids;
		$msg .="<div class='mass-massage'>";
		$msg .="<h4><strong>".$cb_mass_embed->_total_results." videos embedded <br>".'</h4>';
		$msg .="<div style='font-size:14px'><strong>".$cb_mass_embed->already_exist." video(s) already exist(s)<br>".'</div>';
		$msg .="<div style='font-size:14px'><strong>".$cb_mass_embed->unknown_urls." unknown video(s) ".'</div>';
		$msg .="</div>";
		echo $msg;
		//Vids query
		if(is_array($insert_vids))
		{
			$vid_query = "";
			foreach($insert_vids as $vid)
			{
				if($vid)
				{
					if($vid_query!="")
						$vid_query .= " OR ";
					$vid_query .=" videoid='$vid' ";
				}
			}
			if($vid_query)
			$vid_query = " AND ( ".$vid_query." )";
			
			$except_vid_query = "";
			foreach($insert_vids as $vid)
			{
				if($vid)
				{
					if($except_vid_query!="")
						$except_vid_query .= " AND  ";
					$except_vid_query .=" videoid<>'$vid' ";
				}
			}
			if($except_vid_query)
			$except_vid_query = " AND ( ".$except_vid_query." )";
		}
		
		//Gett List of pending videos
		if($vid_query)
		$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending' $vid_query");
		#echo $db->db_query;
		assign("videos",$vids);
		assign("EMBED_PLUG_DIR",PLUG_DIR."/cb_mass_embed/blocks");
		Template(PLUG_DIR."/cb_mass_embed/blocks/videos.html",false);
		
		/*$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending'  $except_vid_query");
		
		assign("videos",$vids);
		assign('pending','yes');
		Template(PLUG_DIR."/cb_mass_embed/blocks/videos.html",false);*/
	}
	
	
	if($embedType=='yt_user')
	{
		
		$uid = NULL;
		if($_POST['create_channel']=='yes' && $_POST['channel_name'])
		{

			$channel_name = SEO($_POST['channel_name']);
			
			$udetails = $userquery->get_user_details($channel_name);
			
			if(!$udetails)
			{
				//Creating User
				$params['username'] = $channel_name;
				$params['email'] = $channel_name."_".RandomString(10)."@anonymous.com";
				$params['password'] = RandomString(10);
				$params['cpassword'] = $params['password'];
				
				$params['country'] = 'PK';
				$params['category'] = 1;
				$params['gender'] = 'male';
				$params['dob'] = '01-14-1989';
				$uid = $userquery->signup_user($params);
			}else
				$uid = $udetails['userid'];		
		}
		
		include_once(PLUG_DIR."/cb_mass_embed/apis/youtube.api.php");
			
		$embed = new youtube();
		if(isset($_POST['import_comments']) && $_POST['import_comments']!='undefined')
			$embed->import_comments = true;
		else
			$embed->import_comments = false;	
		//Setting Import Stats Settings
		if(isset($_POST['import_stats']) && $_POST['import_stats']!='undefined')
			$embed->import_stats = true;
		else
			$embed->import_stats = false;
		//Setting categorization type
		switch($_POST['categorization'])
		{
			case "selected":
			{
				$embed->categorization = 'selected';
				$embed->set_category = $_POST['mass_category'];
			}
			break;
			case "maual":
			case "each":
			{
				$embed->categorization = 'manual';
				$cat = $cbvid->get_default_category();
				$embed->set_category = $cat['category_id'];
			}
			break;
			case "keywords":
			case "auto":
			default:
			{
				$embed->categorization = 'auto';
			}
		}
		
		$embed->get_data_from_user($_POST['yt_user']);

		$embed->userid = $uid;
		$embed->download_and_insert_data();
		
		$insert_vids = $embed->insert_vids;
		$msg .="<div class='mass-massage'>";
		$msg .="<h4><strong>".$embed->_total_results." videos embedded <br></h4>";
		$msg .="<div style='0px;font-size:14px'><strong>".$embed->already_exist." video(s) already exist(s)</div>";
		$msg .="</div>";
		echo $msg;
		//Vids query
		if(is_array($insert_vids))
		{
			$vid_query = "";
			foreach($insert_vids as $vid)
			{
				if($vid)
				{
					if($vid_query!="")
						$vid_query .= " OR ";
					$vid_query .=" videoid='$vid' ";
				}
			}
			if($vid_query)
			$vid_query = " AND ( ".$vid_query." )";
			
			$except_vid_query = "";
			foreach($insert_vids as $vid)
			{
				if($vid)
				{
					if($except_vid_query!="")
						$except_vid_query .= " AND  ";
					$except_vid_query .=" videoid<>'$vid' ";
				}
			}
			if($except_vid_query)
			$except_vid_query = " AND ( ".$except_vid_query." )";
		}
		
		//Gett List of pending videos
		if($vid_query)
		$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending' $vid_query");
		
		assign("videos",$vids);
		assign("EMBED_PLUG_DIR",PLUG_DIR."/cb_mass_embed/blocks");
		Template(PLUG_DIR."/cb_mass_embed/blocks/videos.html",false);
		
		/*$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending'  $except_vid_query");
		
		assign("videos",$vids);
		assign('pending','yes');
		Template(PLUG_DIR."/cb_mass_embed/blocks/videos.html",false);*/
	}
?>