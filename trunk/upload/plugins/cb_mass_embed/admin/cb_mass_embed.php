<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/


define("THIS_PAGE","cb_mass_embed");

$cb_mass_embed = new cb_mass_embed();
$Smarty->assign_by_ref('cb_mass_embed',$cb_mass_embed);

$cats = $cbvid->get_categories();
//pr($cb_mass_embed->get_cat_keywords());
if($cb_mass_embed->license_status){

	if(isset($_POST['update']))
	{

		//Setting Category Keywords
		$cat_keys = array();
		foreach($cats as $cat)
		{
			$keys = $_POST['cat_'.$cat['category_id']];
			$keys = preg_replace(array("/, /","/ ,/"),",",$keys);
			$keys = preg_replace("/\|/","",$keys);
			$cat_keys[] = $cat['category_id'].":".$keys;
		}
		$cat_keys = implode('|',$cat_keys);

		$cb_mass_embed->set_config('category_keywords',$cat_keys);
		
		//Setting Api
		$apis = $_POST['apis'];
		$apis = implode(',',$apis);
		$cb_mass_embed->set_config('apis',$apis);
		
		//Other Apis
		$configs = array(
		'results',
		'keywords',
		'sort_type',
		'time',
		'license_key',
		'import_stats',
		'import_comments');
		
		foreach($configs as $config)
		{
			$cb_mass_embed->set_config($config,$_POST[$config]);
		}
		
		$eh->flush();
		$cb_mass_embed = "";
		$cb_mass_embed = new cb_mass_embed();
		
		e("Settings have been updated","m");
	}

	//Approving videos
	if(isset($_POST['mass_embed']))
	{
		$wp_posts = array();
		

		//Getting list off pending videos
		$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending' "); 
		foreach($vids as $vid)
		{
			if($_POST['vid_'.$vid['videoid']]=='yes')
			{	
				$fields_array = array('mass_embed_status');
				$vals_array = array('approved');
				
				$new_cats = "";
				$cats = $_POST['cat'];
				if($cats)
				foreach($cats as $cat)
					$new_cats .= "#".$cat."#";
				
				if($new_cats)
				{
					$fields_array[] = 'category';
					$vals_array[] = $new_cats;
				}

				if($_POST['wp_post_'.$vid['videoid']]=='yes')
				{
					$wp_post = array
					(
						'title' => $vid['title'],
						'description' => description($vid['description']),
						'tags'=>$vid['tags'],
						'link' => video_link($vid),
						'embed_code' => $cbvid->embed_code($vid),
						'video_thumb' => get_thumb($vid),
						'duration' => $vid['duration'],
						'category' => $cb_mass_embed->cb_to_wp_cat($vid['category']),
					);
					
					$wp_posts[] = $wp_post;
					
				}
				
				$db->update(tbl("video"),$fields_array,$vals_array," videoid='".$vid['videoid']."'");
			}else
			{
				//echo $vid['videoid'];
				if($vid['videoid'])
				$cbvideo->delete_video($vid['videoid']);
			}
		}
		
		$eh->flush();
		if(count($wp_posts)>0)
		{
			$cb_mass_embed->post_to_wp($wp_posts);
		}	
		e("Videos have been approved","m");
		
	}

	if(isset($_POST['import_videos']))
	{	
		$total = $_POST['total'];
		$vids = array();
		for($i=1;$i<=$total;$i++)
		{
			if($_POST['vid_'.$i]=='yes')
			{
				$array = array(
				'title' => $_POST["title_$i"],
				'description' => $_POST["desc_$i"],
				'category' => $_POST["category_$i"],
				'tags' => $_POST["tags_$i"],
				'embed_code' => $_POST["embed_code_$i"],
				'duration' => $_POST["duration_$i"],
				'thumbs' => $_POST["thumbs_$i"],
				'website' => $_POST["website_$i"],
				'unique_id' => $_POST["unique_id_$i"],
				'url' => $_POST["url_$i"],
				'wp_post' => $_POST['wp_post_'.$i],
				);
				
				$vids[] = $array;
			}
		}
			
			
		//Creating User
		$uid = NULL;
		if($_POST['create_channel']=='yes')
		{
			$channel_name = SEO($cb_mass_embed->keywords);
			if($_POST['channel_type'] == 'keywords')
				$channel_name = SEO($_POST['manual_keywords']);
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
		
		$cb_mass_embed->userid = $uid;
		$cb_mass_embed->results_array = $vids;
		$cb_mass_embed->download_and_insert_data();
		
		$wp_posts = array();
		foreach($vids as $vid)
		{
			if($vid['wp_post']=='yes')
			{
				$video_details = getVidFromUC($vid['unique_id']);
				$wp_post = array
				(
					'title' => $vid['title'],
					'description' => description($vid['description']),
					'tags'=>$vid['tags'],
					'link' => video_link($video_details),
					'embed_code' => $cbvid->embed_code($vid),
					'video_thumb' => get_thumb($video_details),
					'duration' => $vid['duration'],
					'category' => $cb_mass_embed->cb_to_wp_cat($vid['category']),
				);
				
				$wp_posts[] = $wp_post;
			}
		}
		
		
		
		if(count($wp_posts)>0)
		{
			$cb_mass_embed->post_to_wp($wp_posts);
		}	
		
		if(!error() && $total>0)
			e("Videos have been imported successfully","m");
		elseif(!error() && $total==0)
			e("No video was imported");
	}

	if(is_installed("ebay_epn"))
	{
		assign("show_epn","yes");
	}
}
assign('categories',$cats);
//template_files('cb_mass_embed.html');
template_files('cb_mass_embed.html',PLUG_DIR.'/cb_mass_embed/admin');
#template_files('test.html',PLUG_DIR.'/cb_mass_embed/admin');
?>