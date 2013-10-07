<?php
/**
 * Author : Arslan Hassan
 * Script : ClipBucket v2
 * License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 *
 * Class : Reindex
**/

class CBreindex 
{
	var $indexing = false; // Tells whether indexing is completed or not
	var $vtbl = 'video';
	var $utbl = 'users';
	var $gtbl = 'groups';

	/**
	 * Function is used to calculate
	 * the percentage of total figure
	 */	
	function percent($percent,$total)
	{
		$result = number_format($percent * $total / 100);
		return $result;
	}
	
	/**
	 * Function used to count
	 * indexes
	 */	
	function count_index($type,$params)
	{
		global $db;
		$arr = array();
		
		switch($type)
		{
			case "user":
			case "u":
			{
				
				
				if($params['video_count'])
				{
					$video_count = $db->count(tbl($this->vtbl),
											  tbl($this->vtbl).".videoid",
											  tbl($this->vtbl).".userid = ".$params['user']." AND 
											  ".tbl($this->vtbl).".active = 'yes' AND ".tbl($this->vtbl).".status = 'Successful'");
					//echo $db->db_query;										  
					$arr[] = $video_count;						  
				}
										  
				if($params['comment_added'])
				{
					$ctbl = tbl("comments");
					$comment_added = $db->count($ctbl,
												$ctbl.".comment_id",
												$ctbl.".userid = ".$params['user']."");	
					$arr[] = $comment_added;							
				}
				
				if($params['comment_received'])
				{
					$ctbl = tbl("comments");
					$comment_received = $db->count($ctbl,
												$ctbl.".comment_id",
												$ctbl.".type_id = ".$params['user']." AND
												".$ctbl.".type = 'c'");	
					$arr[] = $comment_received;							
				}
				
//				if($params['contacts'])
//				{
//					global $userquery;
//					$contacts = $userquery->get_contacts($params['user'],0,"yes",true);
//					$arr[] = $contacts;	
//				}
//				
				if($params['groups_count'])
				{
					global $cbgroup;
					$details = array("user"=>$params['user'],"active"=>"yes","count_only"=>true);
					$groups_count = $cbgroup->get_groups($details);
					$arr[] = $groups_count;	
				}
				
				// Counting user subscribers
				if($params['subscribers_count'])
				{
					$subtbl = tbl('subscriptions');
					$subscribers_count = $db->count($subtbl,
													$subtbl.".subscription_id",
													$subtbl.".subscribed_to = ".$params['user']."");
					$arr[] = $subscribers_count;	
				}


				// Counting user subscriptions
				if($params['subscriptions_count'])
				{
					$subtbl = tbl('subscriptions');
					$subscriptions_count = $db->count($subtbl,
													$subtbl.".subscription_id",
													$subtbl.".userid = ".$params['user']."");
					$arr[] = $subscriptions_count;	
				}

				if($params['collections_count'])
				{
					global $cbcollection;
					$details = array("user"=>$params['user'],"active"=>"yes","count_only"=>true);
					$collection_count = $cbcollection->get_collections($details);
					$arr[] = $collection_count;	
				}
				
				if($params['photos_count'])
				{
					global $cbphoto;
					$details = array("user"=>$params['user'],"active"=>"yes","count_only"=>true);
					$photos_count = $cbphoto->get_photos($details);
					$arr[] = $photos_count;	
				}
										
				return $arr;
				
			}
			break;
			
			case "videos":
			case "vid":
			case "v":
			{
				//$arr[] = $params['video_id'];
				if($params['video_comments'])
				{
					$ctbl = tbl("comments");
					$video_comments = $db->count($ctbl,
												 $ctbl.".comment_id",
												 $ctbl.".type_id = ".$params['video_id']." AND ".$ctbl.".type = 'v'");
					$arr[] = $video_comments;							 	
				}
				
				if($params['favs_count'])
				{
					$ftbl = tbl("favorites");
					$favs_count = $db->count($ftbl,
												 $ftbl.".favorite_id",
												 $ftbl.".id = ".$params['video_id']." AND ".$ftbl.".type = 'v'");
					$arr[] = $favs_count;							 	
				}
				
				if($params['playlist_count'])
				{
					$ptbl = tbl("playlist_items");
					$playlist_count = $db->count($ptbl,
												 $ptbl.".playlist_item_id",
												 $ptbl.".object_id = ".$params['video_id']." AND ".$ptbl.".playlist_item_type = 'v'");
					$arr[] = $playlist_count;						 	
				}
				
				return $arr;
			}
			break;
			
			case "group":
			case "gp":
			case "g":
			{
				//$arr[] = $params['group_id'];
				
				if($params['group_videos'])
				{
					$gvtbl = tbl('group_videos');
					$group_videos = $db->count($gvtbl,
											   $gvtbl.".group_video_id",
											   $gvtbl.".group_id = ".$params['group_id']." AND ".$gvtbl.".approved = 'yes'");
					$arr[] = $group_videos;
				}
				
				if($params['group_topics'])
				{
					$gttbl = tbl('group_topics');
					$group_topics = $db->count($gttbl,
											   $gttbl.".topic_id",
											   $gttbl.".group_id = ".$params['group_id']." AND ".$gttbl.".approved = 'yes'");
					$arr[] = $group_topics;						   
				}
				
				if($params['group_members'])
				{
					$gmtbl = tbl('group_members');
					$group_members = $db->count($gmtbl,
												$gmtbl.".group_mid",
												$gmtbl.".group_id = ".$params['group_id']." AND ".$gmtbl.".active = 'yes'");
												
					$arr[] = $group_members;							
				}
				
				return $arr;
			}
			break;
			
			case "photos":
			case "p":
			case "photo":
			{
				if($params['favorite_count'])
				{
					$fav_count = $db->count(tbl("favorites"),"favorite_id",tbl("favorites.id")." = ".$params['photo_id']." AND ".tbl("favorites.type")." = 'p' ");
					$arr[] = $fav_count;	
				}
				
				if($params['total_comments'])
				{
					$comment_count = $db->count(tbl("comments"),"comment_id",tbl("comments.type_id")." = ".$params['photo_id']." AND ".tbl("comments.type")." = 'p' ");
					$arr[] = $comment_count;	
				}
				
				return $arr;
			}
			break;
			
			case "collections":
			case "collection":
			case "cl":
			{
				if($params['favorite_count'])
				{
					$fav_count = $db->count(tbl("favorites"),"favorite_id",tbl("favorites.id")." = ".$params['collection_id']." AND ".tbl("favorites.type")." = 'cl' ");
					$arr[] = $fav_count;	
				}
				
				if($params['total_comments'])
				{
					$comment_count = $db->count(tbl("comments"),"comment_id",tbl("comments.type_id")." = ".$params['collection_id']." AND ".tbl("comments.type")." = 'cl' ");
					$arr[] = $comment_count;	
				}
				
				if($params['total_items'])
				{
					$item_count = $db->count(tbl("collection_items"),"ci_id",tbl("collection_items.collection_id")." = ".$params['collection_id']);
					$arr[] = $item_count;	
				}
				
				return $arr;
			}
			break;
		}
	}
	
	/**
	 * Function used to update
	 * indexes
	 */
	 function update_index($type,$params=NULL) {
		 global $db;
		 
		 switch($type)
		{
			case "user":
			case "u":
			{
				$db->update(tbl($this->utbl),$params['fields'],$params['values'], tbl($this->utbl).".userid = ".$params['user']."");								
				//echo $db->db_query."<br /><br />";				
			}
			break;
			
			case "videos":
			case "vid":
			case "v":
			{
				$db->update(tbl($this->vtbl),$params['fields'],$params['values'], tbl($this->vtbl).".videoid = ".$params['video_id']."");
				//echo $db->db_query."<br /><br />";	
			}
			break;
			
			case "group":
			case "gp":
			case "g":
			{
					$db->update(tbl($this->gtbl),$params['fields'],$params['values'], tbl($this->gtbl).".group_id = ".$params['group_id']."");
			}
			break;
			
			case "photos": case "photo":
			case "p": case "foto": case "piture":
			{
				$db->update(tbl("photos"),$params['fields'],$params['values'],tbl("photos.photo_id")." = ".$params['photo_id']);	
			}
			break;
			
			case "collection": case "collection":
			case "cl":
			{
				$db->update(tbl("collections"),$params['fields'],$params['values'],tbl("collections.collection_id")." = ".$params['collection_id']);
			}
			break;
		}
	 }
	 
	/**
	 * Function used to extract
	 * fields
	 */	
	 function extract_fields($type,$arr)
	 {
		 global $db;
		 $fields = array();
		 
		 switch($type)
		 {
			case "user":
			case "u":
			{
				if(is_array($arr))
				{
					if(array_key_exists('video_count',$arr))
						$fields[] = 'total_videos';
						
					if(array_key_exists('comment_added',$arr))
						$fields[] = 'total_comments';
						
					if(array_key_exists('comment_received',$arr))
						$fields[] = 'comments_count';
					
					if(array_key_exists('groups_count',$arr))
						$fields[] = 'total_groups';
						
					if(array_key_exists('subscribers_count',$arr))
						$fields[] = 'subscribers';
						
					if(array_key_exists('subscriptions_count',$arr))
						$fields[] = 'total_subscriptions';	
						
					if(array_key_exists('collections_count',$arr))
						$fields[] = 'total_collections';
						
					if(array_key_exists('photos_count',$arr))
						$fields[] = 'total_photos';									
					$result  = $fields;					
				} else {
					$result = $arr;	
				}
				
				return $result;
			}
			break;
			
			case "videos":
			case "vid":
			case "v":
			{
				if(is_array($arr))
				{
					if(array_key_exists('video_comments',$arr))
						$fields[] = "comments_count";
						
					if(array_key_exists('favs_count',$arr))
						$fields[] = "favourite_count";
						
					if(array_key_exists('playlist_count',$arr))
						$fields[] = "playlist_count";	
					
					$result  = $fields;			
				} else {
					$result = $arr;
				}
				
				return $result;
			}
			break;
			
			case "group":
			case "gp":
			case "g":
			{
				if(is_array($arr))
				{
					if(array_key_exists('group_videos',$arr))
						$fields[] = "total_videos";
						
					if(array_key_exists('group_topics',$arr))
						$fields[] = "total_topics";

					if(array_key_exists('group_members',$arr))
						$fields[] = "total_members";

					$result = $fields;
					
				} else {
					$result = $arr;
				}
				
				return $result;
			}
			
			case "photos": case "photo":
			case "p": case "piture":
			{
				if(is_array($arr))
				{
					if(array_key_exists("favorite_count",$arr))
						$fields[] = "total_favorites";
						
					if(array_key_exists("total_comments",$arr))
						$fields[] = "total_comments";	
						
					$result = $fields;	
				} else {
					$result = $arr;	
				}
				
				return $result;
			}
			break;
			
			case "collections": case "collection":
			case "cl":
			{
				if(is_array($arr))
				{
					if(array_key_exists("favorite_count",$arr))
						$fields[] = "total_favorites";
					
					if(array_key_exists("total_commnets",$arr))
						$fields[] = "total_comments";
						
					if(array_key_exists("total_items",$arr))
						$fields[] = "total_objects";
						
					$result = $fields;			
				} else {
					$result = $arr;	
				}
				
				return $result;
			}
			break;
		 }
	 }
}

?>