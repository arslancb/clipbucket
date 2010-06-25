<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'ajax');

require 'includes/config.inc.php';


$mode = $_POST['mode'];

if(!empty($mode))
{
	switch($mode)
	{
		case 'recent_viewed_vids':
		{
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
		
		default:
		header('location:'.BASEURL);
		
		
	}
}else
	header('location:'.BASEURL);
	
	
?>