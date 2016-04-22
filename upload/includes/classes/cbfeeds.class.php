<?php

/**
 * This file is used
 * to create user feeds
 * probably, identicaly to, F*CBOOK :D
 */
 
class cbfeeds
{
	
	/**
	 * Function used to create a user feed
	 * @param array
	 * uid => userid
	 * udetails => array of user details
	 * limit => timelimit of feeds (1 day, 2 days and so)
	 */
	function createFeed($array)
	{
		global $userquery;
		$uid = $array['uid'];

		if(!$uid)
			e(lang("please_provide_valid_userid"));
		elseif(!is_array($user))
		{
			$user = $userquery->get_user_details($uid);
		}
	}
	
	
	/**
	 * Function used to add feed in user feed file
	 * @param array
	 * action => upload,joined,comment,created
	 * object => video, photo, group
	 * object_id => id of object
	 * object_details => details of object
	 * uid => user id
	 * udetails => user details array
	 */
	function addFeed($feed)
	{
		if(!isSectionEnabled('feeds'))
			return false;
		global $userquery;
		$uid = $feed['uid'];
		
		if(!$uid)
			return false;

		$ufeed = array();
		
		//Verifying feed action and object
		$action = $this->action($feed['action']);
		$object = $this->getObject($feed['object']);
		
		
		if(!$action || !$object)
			return false;

		//Setting user feed array
		$ufeed['action'] = $action;
		$ufeed['object'] = $object;
		$ufeed['object_id'] = $feed['object_id'];
		$ufeed['userid'] = $uid;
		$ufeed['time'] = time();
		
		//Unsetting feed array
		unset($feed);
		
		//Getting user feed file
		$feedFile = $this->getFeedFile($uid);
		
		//Convering feed using json
		$feed = json_encode($ufeed);
		//Creating unique md5 of feed
		$feedmd5 = md5($feed);
		$ufeed['md5'] = $feedmd5;
		//Recreating Feed
		$feed = json_encode($ufeed);
		
		//Appending feed in a file	
		$file = fopen($feedFile,'a+');
		fwrite($file,$feed);
		fclose($file);
		
		//Tada <{^-^}>
		
	}
	
	
	/**
	 * Function used to get action of feed
	 * it will verify weather actio is valid or not
	 */
	function action($action)
	{
		$objects = array
		('signup','upload_video','upload_photo','create_group',
		'join_group','add_friend','add_collection','add_playlist',
		'add_comment','add_favorite');
		
		if(!in_array($action,$objects))
			return false;
		else
			return $action;
	}
	
	
	/**
	 * Function used to get object of feed
	 * it will verify weather actio is valid or not
	 */
	function getObject($object)
	{
		$objects = array
		('signup','video','photo','group',
		'user','friend','collection');
		
		if(!in_array($object,$objects))
			return false;
		else
			return $object;
	}
	
	
	/**
	 * Function used to get feed file
	 */
	function getFeedFile($uid)
	{
		$time = time();
		$ufeedDir = USER_FEEDS_DIR.'/'.$uid;
		//checking user feed folder exists or not
		if(!file_exists($ufeedDir))
			mkdir($ufeedDir);
		$file = $ufeedDir.'/'.$time.'.feed';	
		return $file;
	}
	
	/**
	 * Function used to get user feed files
	 */
	function getUserFeedsFiles($uid=NULL)
	{
		if(!$uid)
			$uid = userid();
		
		$feeds = array();
		$ufeedDir = USER_FEEDS_DIR.'/'.$uid;
		if(file_exists($ufeedDir))
		{
			$time = time();
			$time = substr($timem,0,strlen($time)-3);
			
			$files = glob($ufeedDir.'/'.$time.'*.feed');
			rsort($files);
			foreach($files as $file)
			{
				$feed['content'] = file_get_contents($file);
				$feed['file'] = $file;			
				$feeds[] = $feed;
			}
			
			return $feeds;
		}
		return false;
	}
	
	
	/**
	 * Function used to get user feed
	 */
	function getUserFeeds($user)
	{
		global $cbphoto,$userquery,$cbvid,$cbgroup,$cbcollection;
		$allowed_feeds = USER_ACTIVITY_FEEDS_LIMIT;
		$uid = $user['userid'];
		$feeds = $this->getUserFeedsFiles($uid);
		
		if(!$feeds)
			return false;				
		$newFeeds = array();
		$coutn = 0;
		foreach($feeds as $feed)
		{
			$count++;
			
			if($count>$allowed_feeds)
				break;
			$feedArray = json_decode($feed['content'],true);
			if($feed && count($feedArray>0))
			{
				$remove_feed = false;
				$farr = $feedArray;
				
				$action = $farr['action'];
				$object = $farr['object'];
				$object_id = $farr['object_id'];
				$farr['user'] = $user;
				$farr['file']			= getName($feed['file']);
				$farr['datetime'] = nicetime($farr['time'],true);
				$userlink = '<a href="'.$userquery->profile_link($user).'">'.$user['username'].'</a>';
				//Creating Links
				switch($action)
				{
					case "upload_photo":
					{		
						$photo = $cbphoto->get_photo($object_id);	
						
						//If photo does not exists, simply remove the feed
						if(!$photo)
						{
							$this->deleteFeed($uid,$feed['file']);
							$remove_feed = true;
						}else
						{
							$objectArr['details'] 	= $photo;
							$objectArr['size']		= 't';
							$objectArr['output']	= 'non_html';
							$objectArr['alt'] 		= $photo['photo_title'];
							$farr['thumb'] 			= $cbphoto->getFileSmarty($objectArr);
							$farr['link'] 			= $cbphoto->photo_links($photo,'view_item');
							
							//Content Title
							$farr['title'] = $photo['photo_title'];
							$farr['action_title'] 
							= sprintf(lang('user_has_uploaded_new_photo'),$userlink);
							
							$farr['links'][] = array('link'=>($cbphoto->photo_links($photo,'view_item')),'text'=>lang('view_photo'));
							
							$farr['icon'] = 'images.png';
						}
						
						
					}
					break;
					
					case "upload_video":
					case "add_favorite":
					{
						$video = $cbvid->get_video($object_id);		
						//If photo does not exists, simply remove the feed
						if(!$video)
						{
							$this->deleteFeed($uid,$feed['file']);
							$remove_feed = true;
						}elseif(!video_playable($video))
						{
							$remove_feed = true;
						}else{
							
							//Content Title
							$farr['title'] = $video['title'];
							if($action=='upload_video')
								$farr['action_title'] = sprintf(lang('user_has_uploaded_new_video'),$userlink);
							if($action=='add_favorite')
								$farr['action_title'] = sprintf(lang('user_has_favorited_video'),$userlink);
							$farr['link'] 			= videoLink($video);
							$farr['object_content'] = $video['description'];
							$farr['thumb'] 			= get_thumb($video);
							
							$farr['links'][] = array('link'=>videoLink($video),'text'=>lang('watch_video'));
							
							$farr['icon'] = 'video.png';
							
							if($action=='add_favorite')
								$farr['icon'] = 'heart.png';
						}
					}
					break;
					
					
					case "create_group":
					case "join_group":
					{
						$group = $cbgroup->get_group($object_id);		
						//If photo does not exists, simply remove the feed
						if(!$group)
						{
							$this->deleteFeed($uid,$feed['file']);
							$remove_feed = true;
						}elseif(!$cbgroup->is_viewable($group))
						{
							$remove_feed = true;
						}else{
							
							//Content Title
							$farr['title'] = $group['group_name'];
							
							if($action=='create_group')
							$farr['action_title'] = sprintf(lang('user_has_created_new_group'),$userlink);
							if($action=='join_group')
							$farr['action_title'] = sprintf(lang('user_has_joined_group'),$userlink);
							
							$farr['link'] 			= group_link(array('details'=>$group));
							$farr['object_content'] = 
							$group['group_description']."<br>".
							lang('total_members')." : ".$group['total_members']."<br>".
							lang('total_videos')." : ".$group['total_videos']."<br>".
							lang('total_topics')." : ".$group['total_topics']."<br>";
							
							$farr['thumb'] 			= $cbgroup->get_group_thumb($group);
							$farr['icon'] = 'group.png';
							
							$joinlink = $cbgroup->group_opt_link($group,'join');
							if($joinlink)
							{
								if(SEO=="yes")
									$joinlink = group_link(array('details'=>$group)).'?join=yes"';
								else
									$joinlink = group_link(array('details'=>$group)).'&join=yes"';
								$farr['links'][] = array('link'=>$joinlink,'text'=>lang('join'));
							}
						}
					}
					break;
					
					case "signup":
					{
						$farr['action_title']  = sprintf(lang("user_joined_us"),$userlink,TITLE,$userlink);
						$farr['icon'] = 'user.png';
					}
					
					break;
					case "add_friend":
					{
						$friend = $userquery->get_user_details($object_id);
						
						if(!$friend)
						{
							$this->deleteFeed($uid,$feed['file']);
							$remove_feed = true;
						}else
						{
							
							$friendlink = '<a href="'.$userquery->profile_link($friend).'">'.$friend['username'].'</a>';
							$farr['action_title']  = sprintf(lang("user_is_now_friend_with_other")
							,$userlink,$friendlink);
							$farr['icon'] = 'user_add.png';
						}
					}
					break;
					
					
					case "add_collection":
					{		
						$collection = $cbcollection->get_collection($object_id);			
						if(!$collection)
						{
							$this->deleteFeed($uid,$feed['file']);
							$remove_feed = true;
						}else
						{
							$farr['action_title'] = sprintf(lang('user_has_created_new_collection'),$userlink);
							$farr['thumb'] = $cbcollection->get_thumb($collection,'small');
							$farr['title'] = $collection['collection_name'];
							$collection_link = $cbcollection->collection_links($collection,'view');
							$farr['link'] = $collection_link;
							
							$farr['object_content'] = 
							$collection['collection_description'].'<br>'.
							$collection['total_objects']." ".$collection['type'];
							$farr['icon'] = 'photos.png';
							
							$farr['links'][] = array('link'=>$collection_link,'text'=>lang('view_collection'));
						}
					}
					
				}
				
				if(!$remove_feed)
					$newFeeds[$feedArray['time']] = $farr;
			}
		}
		return $newFeeds;
	}
	
	
	/**
	 * Function used to delete feed
	 */
	function deleteFeed($uid,$feedid)
	{
		$ufeedDir = USER_FEEDS_DIR.'/'.$uid.'/'.getName($feedid).'.feed';
		if(file_exists($ufeedDir))
			unlink($ufeedDir);
	}
}