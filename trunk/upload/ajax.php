<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'ajax');

$mode = $_POST['mode'];
require 'includes/config.inc.php';
	
if(!empty($mode))
{
	switch($mode)
	{
		case 'recent_viewed_vids':
		{
			if(!isSectionEnabled('videos') || !$userquery->perm_check('view_videos',false,true) )
			exit();
			
			$videos = get_videos(array('limit'=>config('recently_viewed_limit'),'order'=>'last_viewed DESC'));
			if($videos)
			foreach($videos as $video)
			{
				assign('video',$video);
				Template('blocks/video.html');
			}
		}
		break;
		
		case 'most_viewed':
		{
			if(!isSectionEnabled('videos') || !$userquery->perm_check('view_videos',false,true) )
			exit();
			$videos = get_videos(array('limit'=>config('videos_items_hme_page'),'order'=>'views DESC'));
			if($videos)
			foreach($videos as $video)
			{
				assign('video',$video);
				Template('blocks/video.html');
			}
		}
		break;
		
		case 'recently_added':
		{
			if(!isSectionEnabled('videos') || !$userquery->perm_check('view_videos',false,true) )
			exit();
			$videos = get_videos(array('limit'=>config('videos_items_hme_page'),'order'=>'date_added DESC'));
			if($videos)
			foreach($videos as $video)
			{
				assign('video',$video);
				Template('blocks/video.html');
			}
		}
		break;
		
		
		case 'rating':
		{
			switch($_POST['type'])
			{
				case "video":
				{
					$rating 	= $_POST['rating']*2;
					$id 		= $_POST['id'];
					$result 	= $cbvid->rate_video($id,$rating);
					$result['is_rating'] = true;
					$cbvid->show_video_rating($result);
				}
				break;
				
				case "photo":
				{
					$rating = $_POST['rating']*2;
					$id = $_POST['id'];
					$result = $cbphoto->rate_photo($id,$rating);
					$result['is_rating'] = true;
					$cbvid->show_video_rating($result);
				}
				break;
			}
		}
		break;
		
		
		case 'share_object':
		{
			
			$type = strtolower($_POST['type']);
			switch($type)
			{
				case 'v':
				case 'video':
				default:
				{
					$id = $_POST['id'];
					$vdo = $cbvid->get_video($id);
					$cbvid->set_share_email($vdo);
					$cbvid->action->share_content($vdo['videoid']);
					if(msg())
					{
						$msg = msg_list();
						$msg = '<div class="msg">'.$msg[0].'</div>';
					}
					if(error())
					{
						$msg = error_list();
						$msg = '<div class="error">'.$msg[0].'</div>';
					}
					
					echo $msg;
				}
				break;
				
				case "p":
				case "photo":
				{
					$id = $_POST['id'];
					$ph = $cbphoto->get_photo($id);
					$cbphoto->set_share_email($ph);
					$cbphoto->action->share_content($ph['photo_id']);
					if(msg())
					{
						$msg = msg_list();
						$msg = '<div class="msg">'.$msg[0].'</div>';
					}
					if(error())
					{
						$msg = error_list();
						$msg = '<div class="error">'.$msg[0].'</div>';
					}
					
					echo $msg;
				}
				break;
				
				case "cl":
				case "collection":
				{
					$id = $_POST['id'];
					$cl = $cbcollection->get_collection($id);
					$cbcollection->set_share_mail($cl);
					$cbcollection->action->share_content($cl['collection_id']);
					if(msg())
					{
						$msg = msg_list();
						$msg = '<div class="msg">'.$msg[0].'</div>';
					}
					if(error())
					{
						$msg = error_list();
						$msg = '<div class="error">'.$msg[0].'</div>';
					}
					
					echo $msg;
				}
				break;
			}
		}
		break;
		
		
		case 'add_to_fav':
		{
			$type = strtolower($_POST['type']);
			switch($type)
			{
				case 'v':
				case 'video':
				default:
				{
					$id = $_POST['id'];
					$cbvideo->action->add_to_fav($id);
					updateObjectStats('fav','video',$id); // Increment in total favs
					
					if(msg())
					{
						$msg = msg_list();
						$msg = '<div class="msg">'.$msg[0].'</div>';
					}
					if(error())
					{
						$msg = error_list();
						$msg = '<div class="error">'.$msg[0].'</div>';
					}
					
					echo $msg;
				}
				break;
				
				case 'p':
				case 'photo':
				{
					$id = $_POST['id'];
					$cbphoto->action->add_to_fav($id);
					updateObjectStats('fav','photo',$id); // Increment in total favs
					
					if(msg())
					{
						$msg = msg_list();
						$msg = '<div class="msg">'.$msg[0].'</div>';
					}
					if(error())
					{
						$msg = error_list();
						$msg = '<div class="error">'.$msg[0].'</div>';
					}
					
					echo $msg;
				}
				break;
				
				case "cl":
				case "collection":
				{
					$id = $_POST['id'];
					$cbcollection->action->add_to_fav($id);
					//updateObjectStats('fav','collection',$id); // Increment in total favs
					
					if(msg())
					{
						$msg = msg_list();
						$msg = '<div class="msg">'.$msg[0].'</div>';
					}
					if(error())
					{
						$msg = error_list();
						$msg = '<div class="error">'.$msg[0].'</div>';
					}
					
					echo $msg;
				}
				break;
			}
		}
		break;
		
		
		case 'flag_object':
		{
			$type = strtolower($_POST['type']);
			switch($type)
			{
				case 'v':
				case 'video':
				default:
				{
					$id = $_POST['id'];
					$cbvideo->action->report_it($id);
				}
				break;
				
				case 'g':
				case 'group':
				default:
				{
					$id = $_POST['id'];
					$cbgroup->action->report_it($id);
				}
				break;
				
				case 'u':
				case 'user':
				default:
				{
					$id = $_POST['id'];
					$userquery->action->report_it($id);
				}
				break;
				
				case 'p':
				case 'photo':
				{
					$id = $_POST['id'];
					$cbphoto->action->report_it($id);
				}
				break;
				
				case "cl":
				case "collection":
				{
					$id = $_POST['id'];
					$cbcollection->action->report_it($id);
				}
				break;
				
			}
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';
			}
			if(error())
			{
				$msg = error_list();
				$msg = '<div class="error">'.$msg[0].'</div>';
			}
			
			echo $msg;
		}
		break;
		
		
		case 'subscribe_user':
		{
			$subscribe_to = mysql_clean($_POST['subscribe_to']);
			$userquery->subscribe_user($subscribe_to);
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';
			}
			if(error())
			{
				$msg = error_list();
				$msg = '<div class="error">'.$msg[0].'</div>';
			}
			echo $msg;
		}
		break;
		
		case 'unsubscribe_user':
		{
			$subscribe_to = mysql_clean($_POST['subscribe_to']);
			$userquery->unsubscribe_user($subscribe_to);
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';
			}
			if(error())
			{
				$msg = error_list();
				$msg = '<div class="error">'.$msg[0].'</div>';
			}
			echo $msg;
		}
		break;
		
		
		case 'add_friend':
		{
			$friend = $_POST['uid'];
			$userid = userid();
			
			if($userid) {
				$userquery->add_contact($userid,$friend);
						
				if(msg())
				{
					$msg = msg_list();
					$msg = '<div class="msg">'.$msg[0].'</div>';
				}
				if(error())
				{
					$msg = error_list();
					$msg = '<div class="error">'.$msg[0].'</div>';
				}
				echo $msg;
			} else {
				echo '<div class="error">'.e(lang('you_not_logged_in')).'</div>';	
			}
		}
		break;
		
		case 'ban_user':
		{
			$user = $_POST['user'];
			$userquery->ban_user($user);
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';
			}
			if(error())
			{
				$msg = error_list();
				$msg = '<div class="error">'.$msg[0].'</div>';
			}
			echo $msg;
			
		}
		break;
		
		case 'rate_comment':
		{
			$thumb = $_POST['thumb'];
			$cid = mysql_clean($_POST['cid']);
			if($thumb!='down')
				$rate = 1;
			else
				$rate = -1;
				
			$rating = $myquery->rate_comment($rate,$cid);
			if(msg())
			{
				$msg = msg_list();
				$msg = $msg[0];
			}
			if(error())
			{
				$msg = error_list();
				$msg = $msg[0];
			}
			
			$ajax['msg'] = $msg;
			$ajax['rate'] = comment_rating($rating);
			
			echo json_encode($ajax);
		}
		break;
		
		case 'spam_comment':
		{
			$cid = mysql_clean($_POST['cid']);

				
			$rating = $myquery->spam_comment($cid);
			if(msg())
			{
				$msg = msg_list();
				$msg = $msg[0];
			}
			if(error())
			{
				$err = error_list();
				$err = $err[0];
			}
			
			$ajax['msg'] = $msg;
			$ajax['err'] = $err;
			
			echo json_encode($ajax);
		}
		break;
		
		case 'add_comment';
		{
			$type = $_POST['type'];
			switch($type)
			{
				case 'v':
				case 'video':
				default:
				{
					$id = mysql_clean($_POST['obj_id']);
					$comment = $_POST['comment'];
					if($comment=='undefined')
						$comment = '';
					$reply_to = $_POST['reply_to'];
					
					$cid = $cbvid->add_comment($comment,$id,$reply_to);
				}
				break;
				case 'u':
				case 'c':
				{
					
					$id = mysql_clean($_POST['obj_id']);
					$comment = $_POST['comment'];
					if($comment=='undefined')
						$comment = '';
					$reply_to = $_POST['reply_to'];
					
					$cid = $userquery->add_comment($comment,$id,$reply_to);
				}
				break;
				case 't':
				case 'topic':
				{
					
					$id = mysql_clean($_POST['obj_id']);
					$comment = $_POST['comment'];
					if($comment=='undefined')
						$comment = '';
					$reply_to = $_POST['reply_to'];
					
					$cid = $cbgroup->add_comment($comment,$id,$reply_to);
				}
				break;
				
				case 'cl':
				case 'collection':
				{
					$id = mysql_clean($_POST['obj_id']);
					$comment = $_POST['comment'];
					if($comment=='undefined')
						$comment = '';
					$reply_to = $_POST['reply_to'];
					
					$cid = $cbcollection->add_comment($comment,$id,$reply_to);	
				}
				break;
				
				case "p":
				case "photo":
				{
					$id = mysql_clean($_POST['obj_id']);
					$comment = $_POST['comment'];
					if($comment=='undefined')
						$comment = '';
					$reply_to = $_POST['reply_to'];
					$cid = $cbphoto->add_comment($comment,$id,$reply_to);	
				}
				break;
				
			}
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';;
			}
			if(error())
			{
				$err = error_list();
				$err = '<div class="error">'.$err[0].'</div>';;
			}
			
			$ajax['msg'] = $msg ? $msg : '';
			$ajax['err'] = $err;
			
			//Getting Comment
			if($cid)
			{
				$ajax['cid'] = $cid;
			}
			
			echo json_encode($ajax);
		
		}
		break;
		
		case 'get_comment';
		{
			$id = mysql_clean($_POST['cid']);
			$new_com  = $myquery->get_comment($id);
			assign('comment',$new_com);
			Template('blocks/comments/comment.html');
		}
		break;
		
		
		
		/**
		 * Function used to add item in playlist
		 */
		case 'add_playlist';
		{
			$vid = mysql_clean($_POST['vid']);
			$pid = mysql_clean($_POST['pid']);
			$cbvid->action->add_playlist_item($pid,$vid);
			updateObjectStats('plist','video',$vid);
			
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';;
			}
			if(error())
			{
				$err = error_list();
				$err = '<div class="error">'.$err[0].'</div>';;
			}
			
			$ajax['msg'] = $msg ? $msg : '';
			$ajax['err'] = $err ? $err : '';
			
			
			echo json_encode($ajax);
			
		}
		break;
		
		
		case 'add_new_playlist';
		{
			$vid = mysql_clean($_POST['vid']);
			
			$params = array('name'=>mysql_clean($_POST['plname']));
			$pid = $cbvid->action->create_playlist($params);
			
			if($pid)
			{
				$eh->flush();
				$cbvid->action->add_playlist_item($pid,$vid);
			}
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';;
			}
			if(error())
			{
				$err = error_list();
				$err = '<div class="error">'.$err[0].'</div>';;
			}
			
			$ajax['msg'] = $msg ? $msg : '';
			$ajax['err'] = $err ? $err : '';
			
			
			echo json_encode($ajax);
			
		}
		break;
		
		
		case 'quicklist':
		{
			
			$todo = $_POST['todo'];
			$id = mysql_clean($_POST['vid']);
			
			if($todo == 'add')
			{
				$return = $cbvid->add_to_quicklist($id);
			}else
				$return = $cbvid->remove_from_quicklist($id);
				
			echo $return;			
		}
		break;
		
		case 'getquicklistbox';
		{
			//$cookie = $_COOKIE[QUICK_LIST_SESS];
			//$vids = json_decode($cookie,true);
			if($cbvid->total_quicklist()>0)
				TEMPLATE('blocks/quicklist/block.html');		
		}
		break;
		
		case 'clear_quicklist':
		{
			$cbvid->clear_quicklist();
			return 'removed';
		}
		break;
		
		
		case 'delete_comment':
		{
			$type = $_POST['type'];
			switch($type)
			{
				case 'v':
				case 'video':
				default:
				{
					$cid = mysql_clean($_POST['cid']);
					$type_id = $myquery->delete_comment($cid);
					$cbvid->update_comments_count($type_id);
				}
				break;
				case 'u':
				case 'c':
				{
					$cid = mysql_clean($_POST['cid']);
					$type_id = $myquery->delete_comment($cid);
					$userquery->update_comments_count($type_id);
				}
				break;
				case 't':
				case 'topic':
				{
					$cid = mysql_clean($_POST['cid']);
					$type_id = $myquery->delete_comment($cid);
					$cbgroup->update_comments_count($type_id);
				}
				break;
				case 'cl':
				case 'collection':
				{
					$cid = mysql_clean($_POST['cid']);
					$type_id = $myquery->delete_comment($cid);
					$cbcollection->update_total_comments($type_id);	
				}
				
			}
			if(msg())
			{
				$msg = msg_list();
				$msg = $msg[0];
			}
			if(error())
			{
				$err = error_list();
				$err = $err[0];
			}
			
			$ajax['msg'] = $msg;
			$ajax['err'] = $err;
			
			echo json_encode($ajax);
		}
		break;
		
		case "add_new_item":
		{
			$type = $_POST['type'];
			
			switch($type)
			{
				case "videos":
				case "video":
				case "v":
				{
					$cid = $_POST['cid'];
					$id = $_POST['obj_id'];
					$cbvideo->collection->add_collection_item($id,$cid);	
				}
				break; 
				
				case "photos":
				case "photo":
				case "p":
				{
					$cid = $_POST['cid'];
					$id = $_POST['obj_id'];
					$cbphoto->collection->add_collection_item($id,$cid);	
				}
			}
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';	
			}
			
			if(error())
			{
				$err = error_list();
				$err = '<div class="error">'.$err[0].'</div>';	
			}
			
			$ajax['msg'] = $msg;
			$ajax['err'] = $err;
			
			echo json_encode($ajax);
		}
		break;
		
		
		case "remove_collection_item":
		{
			$type = $_POST['type'];
			
			switch($type)
			{
				case "videos":
				{
					$obj_id = $_POST['obj_id'];
					$cid = $_POST['cid'];
					$cbvideo->collection->remove_item($obj_id,$cid);
				}
				break;
				
				case "photos":
				{
					$obj_id = $_POST['obj_id'];
					$cid = $_POST['cid'];
					$cbphoto->collection->remove_item($obj_id,$cid);
					$cbphoto->make_photo_orphan($cid,$obj_id);	
				}
				break;
			}
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';	
			}
			
			if(error())
			{
				$err = error_list();
				$err = '<div class="error">'.$err[0].'</div>';	
			}
			
			$ajax['msg'] = $msg;
			$ajax['err'] = $err;
			
			echo json_encode($ajax);
		}
		break;
		
		case "get_item":
		{
			$item_id = $_POST['ci_id'];
			$cid = $_POST['cid'];
			$direc = mysql_clean($_POST['direction']);
			$t = $_POST['type'];
			
			switch($t)
			{
				case "videos":
				case "video":
				case "v":
				{
						$N_item = $cbvideo->collection->get_next_prev_item($item_id,$cid,$direc);
						//increment_views($N_item[0]['videoid'],'video');						
						$ajax['key'] = $N_item[0]['videokey'];
						$ajax['cid'] = $N_item[0]['collection_id'];
				}
				break;
				
				case "photos":
				case "photo":
				case "p":
				{
						$N_item = $cbphoto->collection->get_next_prev_item($item_id,$cid,$direc);
						increment_views($N_item[0]['photo_id'],'photo');
						$ajax['key'] = $N_item[0]['photo_key'];
						$ajax['cid'] = $N_item[0]['collection_id'];
						
				}
				break;
			}
			
			if($N_item)
			{
				assign('type',$t);
				assign('user',$userquery->get_user_details($N_item[0]['userid']));
				assign('object',$N_item[0]);
				$ajax['content'] = Fetch('view_item.html');
				echo json_encode($ajax);
			} else {
				return false;	
			}
		}
		break;
		
		case "load_more_items":
		case "more_items":
		case "moreItems":
		{
			$cid = $_POST['cid'];
			$page = $_POST['page'];				
			$newPage = $page+1;
			$type = $_POST['type'];
			$limit = create_query_limit($page,VLISTPP);
			$order = tbl("collection_items").".ci_id DESC";
			
			switch($type)
			{
				case "videos":
				case "video":
				case "v":
				{
					$items = $cbvideo->collection->get_collection_items_with_details($cid,$order,$limit);
				}
				break;
				
				case "photos":
				case "photo":
				case "p":
				{
					$items = $cbphoto->collection->get_collection_items_with_details($cid,$order,$limit);
				}
				break;
			}
			if($items)
			{
				assign('page_no',$newPage);
				assign('type',$type);
				assign('cid',$cid);
				$itemsArray['pagination'] = Fetch("blocks/new_pagination.html");
				
				foreach($items as $item)
				{
					assign('object',$item);
					assign('display_type','view_collection');
					assign('type',$type);
					$itemsArray['content'] .= Fetch("blocks/collection.html");	
				}
				
				echo json_encode($itemsArray);
			} else
				return false;
		}
		break;
		
		
		case "add_collection":
		{
			$name = mysql_clean($_POST['collection_name']);
			$desc = mysql_clean($_POST['collection_description']);
			$tags = mysql_clean(genTags($_POST['collection_tags']));
			$cat  = ($_POST['category']);
			$type = "photos";
			$CollectParams = 
			array("collection_name"=>$name,"collection_description"=>$desc,"collection_tags"=>$tags,"category"=>$cat,"type"=>$type,"allow_comments"=>"yes","broadcast"=>"public","public_upload"=>"yes");
			$insert_id = $cbcollection->create_collection($CollectParams);
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div class="msg">'.$msg[0].'</div>';	
			}
			
			if(error())
			{
				$err = error_list();
				$err = '<div class="error">'.$err[0].'</div>';	
			}
			
			$ajax['msg'] = $msg;
			$ajax['err'] = $err;
			$ajax['id'] = $insert_id;
			
			echo json_encode($ajax);
		}
		break;
		
		case "ajaxPhotos":
		{
			$cbphoto->insert_photo();
			
			if(msg())
			{
				$msg = msg_list();
				$msg = '<div id="photoUploadingMessages" class="ajaxMessages msg">'.$msg[0].'</div>';	
			}
			
			if(error())
			{
				$err = error_list();
				$err = '<div id="photoUploadingMessages" class="ajaxMessages err">'.$err[0].'</div>';	
			}
			
			$ajax['msg'] = $msg;
			$ajax['err'] = $err;
			
			echo json_encode($ajax);
		}
		break;
		
		case "viewPhotoRating":
		{
			$pid = mysql_clean($_POST['photoid']);
			$returnedArray = $cbphoto->photo_voters($pid);
			echo ($returnedArray);	
		}
		break;
		
		default:
		header('location:'.BASEURL);
				
	}
}else
	header('location:'.BASEURL);
	
	
?>