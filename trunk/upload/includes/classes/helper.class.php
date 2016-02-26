<?php

/**
 * File: Helper Class
 * @author : Saqib Razzaq
 * Description: Life saviour for ClipBucket Developers
 * Includes some handy helper functions to simplify things a little 
 * @since : 26th, Feburary, 2016 ClipBucket 2.8.1
 * @functions: Various
*/

	/**
	* 
	*/

	class clip_helper extends ClipBucket
	{
		function assign($vals) {
			if (is_array($vals)) {
				$total_vars = count($vals);
				foreach ($vals as $name => $value) {
					assign($name, $value);
				}
			}
		}

		function watch_video($show_page = true, $msg = false) {
			global $cbvid;
			if (!$show_page) {
				if ($msg) {
					e($msg, "e");
				}
				return false;
			}
			$vkey = @$_GET['v'];
			$vkey = mysql_clean($vkey);
			$vdo = $cbvid->get_video($vkey);
			$assign_arry['vdo'] = $vdo;
			if(video_playable($vdo)) {	
				//Checking for playlist
				$pid = $_GET['play_list'];
				if(!empty($pid)) {
					$plist = get_playlist( $pid );
					if ( $plist ) {
			            $assign_arry['playlist'] = $plist;
			        }
				}
				//Calling Functions When Video Is going to play
				call_watch_video_function($vdo);
				subtitle($vdo['title']);
			} else {
				$Cbucket->show_page = false;
			}

			//Return category id without '#'
			$v_cat = $vdo['category'];
			if($v_cat[2] =='#') {
			$video_cat = $v_cat[1];
			} else {
			$video_cat = $v_cat[1].$v_cat[2];}
			$vid_cat = str_replace('%#%','',$video_cat);
			#assign('vid_cat',$vid_cat);
			$assign_arry['vid_cat'] = $vid_cat;
			$title = $vdo['title'];
			$tags = $vdo['tags'];
			$videoid = $vdo['videoid'];
			$related_videos = get_videos(array('title'=>$title,'tags'=>$tags,
			'exclude'=>$videoid,'show_related'=>'yes','limit'=>12,'order'=>'date_added DESC'));
			if(!$related_videos){
				$related_videos  = get_videos(array('exclude'=>$videoid,'limit'=>12,'order'=>'date_added DESC'));
			}
			$playlist = $cbvid->action->get_playlist($pid,userid());
			$assign_arry['playlist'] = $playlist;
						//Getting Playlist Item
						$items = $cbvid->get_playlist_items( $pid, 'playlist_items.date_added DESC' );
						$assign_arry['items'] = $items;
			$assign_arry['videos'] = $related_videos;
			# assigning all variables
			$this->assign($assign_arry);
			template_files('watch_video.html');
		}

		function videos($show_page = true, $msg = false, $subtitle = false) {
			global $cbvid, $pages;
			if (!$show_page) {
				if ($msg) {
					e($msg, "e");
				}
				return false;
			}
			$sort = $_GET['sort'];
			$child_ids = "";
			$assign_arry = array();
			if($_GET['cat'] && $_GET['cat']!='all') {
				$childs = $cbvid->get_sub_categories(mysql_clean($_GET['cat']));
				$child_ids = array();
				if($childs) {
					foreach($childs as $child) {
						$child_ids[] = $child['category_id'];
						$subchilds = $childs = $cbvid->get_sub_categories($child['category_id']);
						if($subchilds) {
							foreach($subchilds as $subchild) {
								$child_ids[] = $subchild['category_id'];
							}	
						}
					}
				}
				$child_ids[] = mysql_clean($_GET['cat']);
			}
			$vid_cond = array('category'=>$child_ids,'date_span'=>mysql_clean($_GET['time']),'sub_cats');
			$vid_cond = $this->build_sort($sort, $vid_cond);
			//Getting Video List
			$page = mysql_clean($_GET['page']);
			$get_limit = create_query_limit($page,VLISTPP);
			$vlist = $vid_cond;
			$count_query = $vid_cond;
			$vlist['limit'] = $get_limit;
			$videos = get_videos($vlist);
			$assign_arry['videos'] = $videos;
			$vcount = $vid_cond;
			$counter = get_counter('video',$count_query);
			if(!$counter) {
				$vcount['count_only'] = true;
				$total_rows  = get_videos($vcount);
				$total_pages = count_pages($total_rows,VLISTPP);
				$counter = $total_rows;
				update_counter('video',$count_query,$counter);
			}
			$total_pages = count_pages($counter,VLISTPP);
			//Pagination
			$link==NULL;
			$extra_params=NULL;
			$tag='<li><a #params#>#page#</a><li>';
			$pages->paginate($total_pages,$page,$link,$extra_params,$tag);
			if (!$subtitle) {
				$subtitle = 'Videos';
			}
			subtitle(lang($subtitle));
			$this->assign($assign_arry);
			template_files('videos.html');
		}

		function photos($show_page = true, $msg = false, $subtitle = false) {
			global $cbphoto, $cbcollection, $pages;
			if (!$show_page) {
				if ($msg) {
					e($msg, "e");
				}
				return false;
			}
			$assign_arry = array();
			$sort = $_GET['sort'];
			$cond = array("category"=>mysql_clean($_GET['cat']),"date_span"=>$_GET['time'], "active"=>"yes");
			$table_name = "photos";
			$cond = $this->build_sort($sort, $cond);
			$page = mysql_clean($_GET['page']);
			$get_limit = create_query_limit($page,MAINPLIST);
			$clist = $cond;
			$clist['limit'] = $get_limit;
			$photos = get_photos($clist);
			$collections = $cbcollection->get_collections($clist);
			//Collecting Data for Pagination
			$ccount = $cond;
			$ccount['count_only'] = true;
			$total_rows = get_photos($ccount);
			$total_pages = count_pages($total_rows,MAINPLIST);
			//Pagination
			$link==NULL;
			$extra_params=NULL;
			$tag='<li><a #params#>#page#</a><li>';
			$pages->paginate($total_pages,$page,$link,$extra_params,$tag);
			if (!$subtitle) {
				$subtitle = 'Photos';
			}
			subtitle(lang($subtitle));
			//Displaying The Template
			$assign_arry['photos'] = $photos;
			$assign_arry['collections'] = $collections;
			$this->assign($assign_arry);
			template_files('photos.html');
		}

		function channels($show_page = true, $msg = false, $subtitle = false) {
			global $pages;
			if (!$show_page) {
				if ($msg) {
					e($msg, "e");
				}
				return false;
			}
			$assign_arry = array();
			$sort = $_GET['sort'];
			$u_cond = array('category'=>mysql_clean($_GET['cat']),'date_span'=>mysql_clean($_GET['time']));
			switch($sort) {
				case "most_recent":
				default:
					$u_cond['order'] = " doj DESC ";
				break;
				case "most_viewed":
					$u_cond['order'] = " profile_hits DESC ";
				break;
				case "featured":
					$u_cond['featured'] = "yes";
				break;
				case "top_rated":
					$u_cond['order'] = " rating DESC";
				break;
				case "most_commented":
					$u_cond['order'] = " total_comments DESC";
				break;
			}
			$page = mysql_clean($_GET['page']);
			$get_limit = create_query_limit($page,CLISTPP);
			$count_query = $ulist = $u_cond;
			$ulist['limit'] = $get_limit;
			$users = get_users($ulist);
			$counter = get_counter('channel',$count_query);

			if(!$counter) {
				//Collecting Data for Pagination
				$ucount = $u_cond;
				$ucount['count_only'] = true;
				$total_rows  = get_users($ucount);
				$counter = $total_rows;
				update_counter('channel',$count_query,$counter);
			}

			$total_pages = count_pages($counter,CLISTPP);
			//Pagination
			$link==NULL;
			$extra_params=NULL;
			$tag='<li><a #params#>#page#</a><li>';
			$pages->paginate($total_pages,$page,$link,$extra_params,$tag);
			if (!$subtitle) {
				$subtitle = 'Channels';
			}
			subtitle(lang($subtitle));
			Assign('users', $users);	
			template_files('channels.html');
		}

		function build_sort($sort, $vid_cond) {
			if (!empty($sort)) {
				switch($sort) {
					case "most_recent":
					default:
						$vid_cond['order'] = " date_added DESC ";
					break;
					case "most_viewed":
						$vid_cond['order'] = " views DESC ";
						$vid_cond['date_span_column'] = 'last_viewed';
					break;
					case "most_viewed":
						$vid_cond['order'] = " views DESC ";
					break;
					case "featured":
						$vid_cond['featured'] = "yes";
					break;
					case "top_rated":
						$vid_cond['order'] = " rating DESC, rated_by DESC";
					break;
					case "most_commented":
						$vid_cond['order'] = " comments_count DESC";
					break;
				}
				return $vid_cond;
			}
		}
	}

?>