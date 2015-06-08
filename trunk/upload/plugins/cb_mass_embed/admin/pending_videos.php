<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/


define("THIS_PAGE","cb_mass_embed");

//$page = mysql_clean($_GET['page']);
//$get_limit = create_query_limit($page,RESULTS);

//$cond=" mass_embed_status ='pending' ";


//$vids = $db->select(tbl("video"),"*",$cond,NULL," limit ".$get_limit);

//$count_video = $db->select(tbl("video"),"*",$cond,NULL,"");

//$total_rows  = count($count_video);
//$total_pages = count_pages($total_rows,RESULTS);
//$pages->paginate($total_pages,$page);

//$vids = $db->select(tbl("video"),"*",$cond," limit ".$get_limit);
//$query = "SELECT * FROM " . tbl("video") . " WHERE mass_embed_status ='pending' ";
//$vids = $db->Execute($query);
$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending' ");
$cb_mass_embed = new cb_mass_embed();
$Smarty->assign_by_ref('cb_mass_embed',$cb_mass_embed);


	
assign("videos",$vids);
assign('pending','yes');
assign('EMBED_PLUG_DIR',PLUG_DIR.'/cb_mass_embed/blocks');

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
	



	$vids = $db->select(tbl("video"),"*"," mass_embed_status ='pending' ");
	assign("videos",$vids);

}

template_files("videos.html",PLUG_DIR."/cb_mass_embed/blocks/");

//template_files("test.html",PLUG_DIR."/cb_mass_embed/admin/");
?>