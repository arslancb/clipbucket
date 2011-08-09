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
			
			$videos = get_videos(array('limit'=>config('videos_items_hme_page'),'order'=>'last_viewed DESC'));
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
		
		case 'featured_videos':
		{
			if(!isSectionEnabled('videos') || !$userquery->perm_check('view_videos',false,true) )
			exit();
			$videos = get_videos(array('limit'=>config('videos_items_hme_page'),'featured'=>'yes','order'=>'featured_date DESC'));
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
					
					$funcs = cb_get_functions('rate_video');	
					if($funcs)
					foreach($funcs as $func)
					{
						$func['func']($id);
					}
				}
				break;
				
				case "photo":
				{
					$rating = $_POST['rating']*2;
					$id = $_POST['id'];
					$result = $cbphoto->rate_photo($id,$rating);
					$result['is_rating'] = true;
					$cbvid->show_video_rating($result);
					
					$funcs = cb_get_functions('rate_photo');	
					if($funcs)
					foreach($funcs as $func)
					{
						$func['func']($id);
					}
				}
				break;
				case "collection":
				{
					$rating = $_POST['rating']*2;
					$id = $_POST['id'];
					$result = $cbcollection->rate_collection($id,$rating);
					$result['is_rating'] = true;
					$cbvid->show_video_rating($result);
					
					$funcs = cb_get_functions('rate_collection');	
					if($funcs)
					foreach($funcs as $func)
					{
						$func['func']($id);
					}
				}
				break;
				
				case "user":
				{
					$rating = $_POST['rating']*2;
					$id = $_POST['id'];
					$result = $userquery->rate_user($id,$rating);
					$result['is_rating'] = true;
					$cbvid->show_video_rating($result);
					
					$funcs = cb_get_functions('rate_user');	
					if($funcs)
					foreach($funcs as $func)
					{
						$func['func']($id);
					}
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
					
					$funcs = cb_get_functions('favorite_video');	
					if($funcs)
					foreach($funcs as $func)
					{
						$func['func']($id);
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
					
					$funcs = cb_get_functions('favorite_photo');	
					if($funcs)
					foreach($funcs as $func)
					{
						$func['func']($id);
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
					
					$funcs = cb_get_functions('favorite_collection');	
					if($funcs)
					foreach($funcs as $func)
					{
						$func['func']($id);
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
					$reported = $cbvideo->action->report_it($id);
					/*if(!error()){
						/*  SENDING EMAIL TO ADMIN 
						$template =  $cbemail->get_template('flag_template');
						$video = $cbvideo->get_video_details($id);
						$emailVars = array(
							"{video_link}" => videoLink($video),
							"{video_title}" => $video['title'],
							"{flag_reason}" => flag_type(post('flag_type')),
							"{type}" => "video"
						);
						$admins = $userquery->get_level_users(1,false,'userid,email');
							$libs =  $userquery->get_level_users(17,false,'userid,email');
							if($libs)
								$users = array_merge($admins,$libs);
							else
								$users = $admins;	
						foreach ($users as $user)
						{
							if(isValidEmail($user['email']))
								$emailsArray[] = $user['email'];	
						}
						
						if(!is_array($var))
							$var = array();
						$vars = array_merge($emailVars,$var);
						$subj = $cbemail->replace($template['email_template_subject'],$vars);
						$msg = nl2br($cbemail->replace($template['email_template'],$vars));
												
						cbmail(array('to'=>$emailsArray,'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
					}*/
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
			
			//updating last update...
			$type = mysql_clean($_POST['type']);
			$typeid = mysql_clean($_POST['typeid']);
			update_last_commented($type,$typeid);
			
			
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
			
			$type = mysql_clean($_POST['type']);
			$typeid = mysql_clean($_POST['typeid']);
			
			if($_POST['type'] != 't')
			update_last_commented($type,$typeid);
			
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
			$id = mysql_clean($_POST['id']);
			$pid = mysql_clean($_POST['pid']);
			
			$type = post('objtype');
			
			if($type=='video')
			{
				$cbvid->action->add_playlist_item($pid,$id );
				updateObjectStats('plist','video',$id);
				
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
			
		}
		break;
		
		
		case 'add_new_playlist';
		{
			
			if(post('objtype')=='video')
			{
				$vid = mysql_clean($_POST['id']);
				
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
			$limit = create_query_limit($page,COLLIP);
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
				echo json_encode(array("error"=>TRUE));
		}
		break;
		
		
		case "add_collection":
		{
			$name = ($_POST['collection_name']);
			$desc = ($_POST['collection_description']);
			$tags = (genTags($_POST['collection_tags']));
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

		case "channelFeatured":
		{
			$contentType = $_POST['contentType'];
			if(!$contentType)
				echo json_encode(array("error"=>lang("content_type_empty")));
			else
			{
				switch($contentType)
				{
					case "videos": case "video":
					case "vid": case "v": case "vdo":
					{
						$video = $cbvideo->get_video_details(mysql_clean($_POST['objID']));
						if($video)
						{
							assign('object',$video);
							$content = Fetch('/blocks/view_channel/channel_item.html');
						}
					}
					break;
					
					case "photo": case "photos":
					case "foto": case "p":
					{
						$photo = $cbphoto->get_photo(mysql_clean($_POST['objID']));
						if($photo)
						{
							assign('object',$photo);
							$content = Fetch('/blocks/view_channel/channel_item.html');	
						}
					}
					break;
				}
				
				if($content)
				{
					echo json_encode(array("data"=>$content));	
				} else 
					echo json_encode(array("error"=>"Nothing Found"));
			}
		}
		break;
		
		case "channelObjects":
		{
			$contentType = strtolower(mysql_clean($_POST['content']));
			$u = $userquery->get_user_details($_POST['user']);
			$assign = $_POST['assign'];
			if(is_array($assign))
			{
				foreach($assign as $var=>$value)
					assign($var,$value);	
			}
			switch($contentType)
			{
				case "videos" :
				{
					$videos = get_videos(array("user"=>$u['userid'],"order"=>" date_added DESC","limit"=>config('videos_item_channel_page')));
					
					if($videos)
					{
						foreach($videos as $video)
						{
							assign('video',$video);
							assign('channelVideo',true);
							$content['html'] .= Fetch("/blocks/video.html");
						}
						$content['html'] .= '<div align="right" class="clearfix channelAjaxMoreLink videosMoreLink" style="clear:both; display:block;">';
						$content['html'] .= '<a href="'.cblink(array("name"=>"user_videos")).$u['username'].'">'.lang('more').'</a> | <a href="'.cblink(array("name"=>"user_favorites")).$u['username'].'">'.lang('Favorites').'</a>';
						$content['html'] .= '</div>';
					} else {
						$content['html'] = '<div align="center"><em>'.lang('user_have_no_vide').'</em></div>';	
					}
				}
				break;
				
				case "photos":
				{
					$photos = get_photos(array("user"=>$u['userid'],"order"=>" date_added DESC","limit"=>config('photo_channel_page')));
					if($photos)
					{
						foreach($photos as $photo)
						{
							assign('photo',$photo);
							assign('channelPhoto',true);
							$content['html'] .= Fetch("/blocks/photo.html");
						}
						$content['html'] .= '<div align="right" class="clearfix channelAjaxMoreLink photosMoreLink" style="clear:both; display:block;">';
						$content['html'] .= '<a href="'.cblink(array("name"=>"user_photos")).$u['username'].'">'.lang('more').'</a> | <a href="'.cblink(array("name"=>"user_fav_photos")).$u['username'].'">'.lang('Favorites').'</a>';
						$content['html'] .= '</div>';
					} else {
						$content['html'] = '<div align="center"><em>'.lang('User dont have any photos').'</em></div>';	
					}
				}
				break;
				
				case "groups":
				{
					$groups = get_groups(array("user"=>$u['userid'],"order"=>" date_added DESC","limit"=>config('photo_channel_page')));
					if($groups)
					{
						foreach($groups as $group)
						{
							assign('group',$group);
							assign('channelGroup',true);
							$content['html'] .= Fetch("/blocks/group.html");
						}
					} else {
						$content['html'] = '<div align="center"><em>'.lang('User dont have any groups.').'</em></div>';	
					}
				}
				break;
				
				case "collections":
				{
					$collections = get_collections(array("user"=>$u['userid'],"order"=>" date_added DESC","limit"=>config('collection_channel_page')));
					if($collections)
					{
						foreach($collections as $collection)
						{
							assign('collection',$collection);
							assign('channelCollection',true);
							$content['html'] .= Fetch("/blocks/collection.html");	
						}
						$content['html'] .= '<div align="right" class="clearfix channelAjaxMoreLink collectionsMoreLink" style="clear:both; display:block;">';
						$content['html'] .= '<a href="'.cblink(array("name"=>"user_collections")).$u['username'].'">'.lang('more').'</a> | <a href="'.cblink(array("name"=>"user_fav_collections")).$u['username'].'">'.lang('Favorites').'</a>';
						$content['html'] .= '</div>';						
					} else {
						$content['html'] = 	'<div align="center"><em>'.lang('User dont have any collections').'</em></div>';
					}
				}
				break;
				
				case "friends":
				{
					$friends = $userquery->get_contacts($u['userid'],'0','yes');
					if($friends)
					{
						foreach($friends as $friend)
						{
							assign('user',$friend);
							assign('channelUser','friends');
							$content['html'] .= Fetch("/blocks/user.html");
						}
					} else {
						$content['html'] = '<div align="center"><em>'.lang('User dont any friends yet').'</em></div>';	
					}
				}
				break;
				
				case "subscriptions":
				{
					$limit = config('users_items_subscriptions');
					$subscriptions = $userquery->get_user_subscriptions($u['userid'],$limit);
					if($subscriptions)
					{
						foreach($subscriptions as $subscription)
						{
							assign('user',$subscription);
							assign('channelUser','subscriptions');
							$content['html'] .= Fetch("/blocks/user.html");	
						}
						$content['html'] .= '<div align="right" class="clearfix channelAjaxMoreLink subscriptionsMoreLink" style="clear:both; display:block;">';
						$content['html'] .= '<a href="'.cblink(array("name"=>"user_subscriptions")).$u['username'].'">'.lang('more').'</a>';
						$content['html'] .= '</div>';
					} else {
						$content['html'] = '<div align="center"><em>'.sprintf(lang('user_no_subscriptions'),$u['username']).'</em></div>';	
					}
				}
				break;
				
				case "subscribers":
				{
					$limit = config('users_items_subscribers');
					$subscribers = $userquery->get_user_subscribers_detail($u['userid'],$limit);
					if($subscribers)
					{
						foreach($subscribers as $subscriber)
						{
							assign('user',$subscriber);
							assign('channelUser','subscribers');
							$content['html'] .= Fetch("/blocks/user.html");	
						}
						$content['html'] .= '<div align="right" class="clearfix channelAjaxMoreLink subscribersMoreLink" style="clear:both; display:block;">';
						$content['html'] .= '<a href="'.cblink(array("name"=>"user_subscribers")).$u['username'].'">'.lang('more').'</a>';
						$content['html'] .= '</div>';
					} else {
						$content['html'] = '<div align="center"><em>'.sprintf(lang('user_no_subscribers'),$u['username']).'</em></div>';	
					}
				}
				break;
				
				case "info":
				{
					
				}
				break;
			}
			
			if($content)
				echo json_encode($content);
				
		}
		break;
		
		case "viewCollectionRating":
		{
			$cid = mysql_clean($_POST['cid']);
			$returnedArray = $cbcollection->collection_voters($cid);
			echo ($returnedArray);	
		}
		break;
		
		case "loadAjaxPhotos":
		{
			$photosType = $_POST['photosType'];
			$cond = array("limit"=>config("photo_home_tabs"));
			switch($photosType)
			{
				case "last_viewed":
				default:
				{
					$cond['order'] = " last_viewed DESC";	
				}
				break;
				
				case "most_recent":
				{
					$cond['order'] = " date_added DESC";
				}
				break;
				
				case "featured":
				{
					$cond['featured'] = "yes";
				}
				break;
				
				case "most_favorited":
				{
					$cond['order'] = " total_favorites DESC";
				}
				break;
				
				case "most_commented":
				{
					$cond['order'] = " total_comments DESC";
				}
				break;
				
				case "highest_rated":
				{
					$cond['order'] = " rating DESC, rated_by DESC";
				}
				break;
				
				case "most_viewed":
				{
					$cond['order'] = " views DESC";
				}
				break;
				
				case "most_downloaded":
				{
					$cond['order'] = " downloaded DESC";
				}
				break;
			}
			
			$photos = get_photos($cond);
			if($photos)
			{
				foreach($photos as $photo)
				{
					assign("photo",$photo);
					$cond['photoBlocks'] .= Fetch("/blocks/photo.html");	
				}
				$cond['completed'] = "successfull";
			} else {
				$cond['failed'] = "successfully";	
			}
			
			echo json_encode($cond);
		}
		break;
		
		/**
		 * Getting comments along with template
		 */
		case "getComments":
		{
			$params = array();
			$limit = config('comments_per_page');
			$page = $_POST['page'];
			$params['type'] = mysql_clean($_POST['type']);
			$params['type_id'] = mysql_clean($_POST['type_id']);
			$params['last_update'] = mysql_clean($_POST['last_update']);
			$params['limit'] = create_query_limit($page,$limit);	
			
			$admin = "";
			if($_POST['admin']=='yes' && has_access('admin_access',true))
			{
				$params['cache'] ='no';
				$admin = "yes";
			}
			$comments = $myquery->getComments($params);
			//Adding Pagination
			$total_pages = count_pages($_POST['total_comments'],$limit);
			assign('object_type',mysql_clean($_POST['object_type']));		
			//Pagination
			$pages->paginate($total_pages,$page,NULL,NULL,'<a href="javascript:void(0)"
			onClick="getComments(\''.$params['type'].'\',\''.$params['type_id'].'\',\''.$params['last_update'].'\',
			\'#page#\',\''.$_POST['total_comments'].'\',\''.mysql_clean($_POST['object_type']).'\',\''.$admin.'\')">#page#</a>');
			
			assign('comments',$comments);
			assign('type',$params['type']);
			assign('type_id',$params['type_id']);
			assign('last_update',$params['last_update']);
			assign('total',$_POST['total_comments']);
			assign('total_pages',$total_pages);
			assign('comments_voting',$_POST['comments_voting']);
			
			if($_POST['admin']=='yes' && has_access('admin_access',true))
			Template(BASEDIR.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/cbv2/layout/blocks/comments.html',false);
			else
			Template('blocks/comments/comments.html');
			
			assign('commentPagination','yes');
			Template('blocks/pagination.html');
		}
		break;
		
		
		
		case "get_news":
		{
			$news = $Cbucket->get_cb_news();
			
			if($news)
			foreach($news as $n)
			{
					echo '<div class="item news">';
					echo '<div class="news_title">
					<span class="title"><a href="'.$n['link'].'">'.$n['title'].'</a></span>
					<span class="date">'.date("m-d-Y",strtotime($n['pubDate'])).'</span></div>
					<span class="clearfix"></span>';
					echo '<div>';
					echo $n['description'];
					echo '</div>';
					echo '</div>';
			}
			else
			echo '<div align="center"><em><strong>Unable to fetch news</strong></em></div>';
		}
		break;
		
		
		case "delete_feed":
		{
			$uid = mysql_clean($_POST['uid']);
			$file = mysql_clean($_POST['file']).'.feed';
			if($uid && $file)
			{
				if($uid==userid() || has_access("admin_access",true))
				{
					$cbfeeds->deleteFeed($uid,$file);
					$array['msg'] = lang("feed_has_been_deleted");
				}else
					$array['err'] = lang("you_cant_del_this_feed");
			}
			echo json_encode($array);
		}
		break;
		default:
		header('location:'.BASEURL);
		
				
	}
}else
	header('location:'.BASEURL);
	
?>