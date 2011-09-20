<?php
/**
***************************************************************************************************
 * @Software    ClipBucket
 * @Authoer     ArslanHassan
 * @copyright	Copyright (c) 2007-2009 {@link http://www.clip-bucket.com}
 * @license		http://www.clip-bucket.com
 * @version		Lite
 * @since 		2007-10-15
 * @License		CBLA
 **************************************************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User License First and Agree its
 Terms of use at http://www.opensource.org/licenses/attribution.php
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************

 check_user
 check_email
 DeleteFlv
 DeleteOriginal
 DeleteThumbs
 DeleteVideoFiles
 UpdateVideo
 GetCategory
 RateVideo
 AddComment
 AddToFavourite
 FlagAsInappropriate
 DeleteFlag
 
 **/

/**
 * Function used to return db table name with prefix
 * @param : table name
 * @return : prefix_table_name;
 */
 

function tbl($tbl)
{
	global $DBNAME;
	$prefix = TABLE_PREFIX;
	$tbls = explode(",",$tbl);
	$new_tbls = "";
	foreach($tbls as $ntbl)
	{
		if(!empty($new_tbls))
			$new_tbls .= ",";
		$new_tbls .= $DBNAME.".".$prefix.$ntbl;
	}

	return $new_tbls;
}


class myquery {

	function Set_Website_Details($name,$value)
	{
		//mysql_query("UPDATE config SET value = '".$value."' WHERE name ='".$name."'");
		global $db,$Cbucket;
		
		$this->config_exists($name,true);
		
		$db->update(tbl("config"),array('value'),array($value)," name = '".$name."'");
		//echo $db->db_query."<br/><br/>";
		$Cbucket->configs = $Cbucket->get_configs();
	}
	
	function Get_Website_Details()
	{
		
		$query = mysql_query("SELECT * FROM ".tbl("config"));
		while($row = mysql_fetch_array($query))
		{
			$name = $row['name'];
			$data[$name] = $row['value'];
		}
		return $data;
	}
	
	/**
	 * Function used to check weather config exists or not
	 * it also used to create a config if it does not exist
	 */
	function config_exists($name)
	{
		global $db,$Cbucket;
		
		$configs = $Cbucket->configs;
		
		if (array_key_exists($name, $configs)) 
			return true;
		else
			$db->insert(tbl("config"),array('name'),array($name));
		
		/*$count = $db->count(tbl("config"),"configid"," name='$name' ");
		if(!$count)
			$db->insert(tbl("config"),array('name'),array($name));*/
		
		return true;
	}

	
	
	//Function Used to Check Weather Video Exists or not
	function VideoExists($videoid){global $cbvid;return $cbvid->exists($videoid);}
	function video_exists($videoid){return $this->VideoExists($videoid);}
	function CheckVideoExists($videokey){return $this->VideoExists($videokey);}
	
	//Function used to Delete Video
	
	function DeleteVideo($videoid){
		global $cbvid;
		return $cbvid->delete_video($videoid);
	}

	//Video Actions - All Moved to video.class.php	
	function MakeFeaturedVideo($videoid){global $cbvid;return $cbvid->action('feature',$videoid);}
	function MakeUnFeaturedVideo($videoid){global $cbvid;return $cbvid->action('unfeature',$videoid);}
	function ActivateVideo($videoid){global $cbvid;return $cbvid->action('activate',$videoid);}
	function DeActivateVideo($videoid){global $cbvid;return $cbvid->action('deactivate',$videoid);}
	
	
	/**
	 * Function used to get video details
	 * from video table
	 * @param INPUT vid or videokey
	 */
	function get_video_details($vid){global $cbvid;return $cbvid->get_video($vid);}	
	function GetVideoDetails($video){return $this->get_video_details($video);}
	function GetVideDetails($video){return $this->get_video_details($video);}

	
	//Function Used To Update Videos Views
	function UpdateVideoViews($vkey){increment_views($vkey,'video');}
	
	/**
	 * Function used to check weather username exists not
	 */
	function check_user($username){
		global $userquery;
		return $userquery->username_exists($username);
	}
	
	/**
	 * Function used to check weather email exists not
	 */
	function check_email($email){
		global $userquery;
		return $userquery->email_exists($email);
	}
	
	/**
	 * Function used to delete comments
	 * @param CID
	 */
	function delete_comment($cid,$type='v',$is_reply=FALSE,$forceDelete=false)
	{
		global $db,$userquery,$LANG;
		//first get comment details
		
		$cdetails = $this->get_comment($cid);	
		$uid = user_id();
		
		if(($uid == $cdetails['userid'] && $cdetails['userid']!='')
			|| $cdetails['type_owner_id'] == userid()										 
			|| has_access("admin_del_access",false)
			|| $is_reply==TRUE || $forceDelete)
		{
			$replies = $this->get_comments($cdetails['type_id'],$type,FALSE,$cid,TRUE);
			if(count($replies)>0 && is_array($replies))
			{
				foreach($replies as $reply)
				{
					$this->delete_comment($reply['comment_id'],$type,TRUE,$forceDelete);
				}
			}
			$db->Execute("DELETE FROM ".tbl("comments")." WHERE comment_id='$cid'");
			
			/*if($uid)
				$myquery->update_comments_by_user($uid);*/
			
			e(lang('usr_cmt_del_msg'),"m");
			return $cdetails['type_id'];
		}else{
			e(lang('no_comment_del_perm'));
			return false;
		}
		return false;
	}
	function DeleteComment($id,$videoid){return $this->delete_comment($videoid);}
	
	/**
	 * Function used to set comment as spam
	 */
	function spam_comment($cid)
	{
		global $db;
		$comment = $this->get_comment($cid);	
		$uid = user_id();
		if($comment)
		{
			$voters = $comment['spam_voters'];
		
			$niddle = "|";
			$niddle .= userid();
			$niddle .= "|";
			$flag = strstr($voters, $niddle);
			
			if(!$comment)
				e(lang('no_comment_exists'));
			elseif(!userid())
				e(lang('login_to_mark_as_spam'));
			elseif( userid()==$comment['userid'] || (!userid() && $_SERVER['REMOTE_ADDR'] == $comment['comment_ip']))
				e(lang('no_own_commen_spam'));
			elseif(!empty($flag))
				e(lang('already_spammed_comment'));
			else
			{
				if(empty($voters))
					$voters .= "|";
				
				$voters .= userid();
				$voters.= "|";
				
				$newscore = $comment['spam_votes']+1;
				$db->update(tbl('comments'),array('spam_votes','spam_voters'),array($newscore,$voters)," comment_id='$cid'");
				e(lang('spam_comment_ok'),"m");
				return $newscore;			
			}
		
		}
		e(lang('no_comment_exists'));
		return false;
	}

	/**
	 * Function used to set comment as spam
	 */
	 function remove_spam($cid) {
		global $db;
		$comment = $this->get_comment($cid);
		$vote = '0';
		$none = '';
		
		if($comment) {
			$votes = $comment['spam_votes'];
			if(!$votes) {
				e(lang('Comment is not a spam'));	
			} elseif(!userid()) {
				e(lang('login_to_mark_as_spam'));
			} else {
				$db->update(tbl('comments'),array('spam_votes','spam_voters'),array($vote,$none)," comment_id='$cid'"); 
				e(lang('Spam removed from comment.'),"m");
			}
		} else {
			e(lang('no_comment_exists'));	
		}
	 }
	

	/**
	 * Function used to delete all comments of particlar object
	 */
	function delete_comments($objid,$type='v',$forceDelete=false)
	{
		global $db,$userquery,$LANG;

		$uid = user_id();
		
		if($userquery->permission['admin_del_access'] == 'yes'  || $forceDelete)
		{
			$db->Execute("DELETE FROM ".tbl("comments")." WHERE type_id='$objid' AND type='$type' ");
			
			e(lang('usr_cmt_del_msg'),'m');
			return true;
		}else{
			e(lang('no_comment_del_perm'));
			return false;
		}
		return false;
	}
	
	/***
	 * Function used to rate comment
	 ***/
	function rate_comment($rate,$cid)
	{
		global $db;
		$comment = $this->get_comment($cid);
		$voters = $comment['voters'];
		
		$niddle = "|";
		$niddle .= userid();
		$niddle .= "|";
		$flag = strstr($voters, $niddle);
		
		if(!$comment)
			e(lang('no_comment_exists'));
		elseif(!userid())
			e(lang('class_comment_err6'));
		elseif(userid()==$comment['userid'] || (!userid() && $_SERVER['REMOTE_ADDR'] == $comment['comment_ip']))
			e(lang('no_own_commen_rate'));
		elseif(!empty($flag))
			e(lang('class_comment_err7'));
		else
		{
			if(empty($voters))
				$voters .= "|";
			
			$voters .= userid();
			$voters.= "|";
			
			$newscore = $comment['vote']+$rate;
			$db->update(tbl('comments'),array('vote','voters'),array($newscore,$voters)," comment_id='$cid'");
						
			e(lang('thanks_rating_comment'),"m");
			return $newscore;			
		}
		
		return false;
	}
	
	
	//Function Used To Varify Syntax
	function isValidSyntax($syntax){
	global $LANG;
      $pattern = "^^[_a-z0-9-]+$";
      if (eregi($pattern, $syntax)){
         return true;
      	}else {
         return false;
      	}   
	}
	
	
	/**
	* FUNCTION USED TO GET VIDEOS FROM DATABASE
	* @param: array of query parameters array()
	* featured => '' (yes,no)
	* username => '' (TEXT)
	* title => '' (TEXT)
	* tags => '' (TEXT)
	* category => '' (INT)
	* limit => '' (OFFSET,LIMIT)
	* order=>'' (BY SORT) -- (date_added DESC)
	* extra_param=>'' ANYTHING FOR MYSQL QUERY
	* @param: boolean
	* @param: results type (results,query)
	*/
	
	function getVideoList($param=array(),$global_cond=true,$result='results')
	{
		global $db;
		
		$sql = "SELECT * FROM video";
		
		//Global Condition For Videos
		if($global_cond==true)
			$cond = "broadcast='public' AND active='yes' AND status='Successful'";
		//Checking Condition
		if(!empty($param['featured']))
		{
			$param['featured'] = 'yes' ? 'yes' : 'no';
			$cond .=" AND featured= '".$param['featured']."' ";
		}
		if(!empty($param['username']))
		{
			$username = mysql_clean($param['username']);
			$cond .=" AND featured= '".$username."' ";
		}
		if(!empty($param['category']))
		{
			$category = intval($param['category']);
			$cond .=" AND (category01= '".$category."' OR category02= '".$category."' OR category03= '".$category."') ";
		}
		if(!empty($param['tags']))
		{
			$tags = mysql_clean($param['tags']);
			$cond .=" AND tags LIKE '%".$tags."%' ";
		}
		if(!empty($param['title']))
		{
			$tags = mysql_clean($param['tags']);
			$cond .=" AND title LIKE '%".$param['title']."%' ";
		}
		
		//Adding Condition in Query
		if(!empty($cond))
			$sql .= " WHERE $cond ";
		
		//SORTING VIDEOS
		if(!empty($param['order']))
			$sort = 'ORDER BY '.$param['order'];
		
		//Adding Sorting In Query
			$sql .= $sort;
			
		//LIMITING VIDEO LIST
		if(empty($param['limit']))
			$limit = " LIMIT ". VLISTPP;
		elseif($param['limit']=='nolimit')
			$limit = '';
		else
			$limit = " LIMIT ".$param['limit'];
				
		$sql .= $limit;
		//Final Executing of Query and Returning Results
		if($result=='results')
			return $db->Execute($sql);
		else
			return $sql;
	}


	/**
	 * Function used to send subsribtion message
	 */
	function send_subscription($subscriber,$from,$video)
	{
		global $LANG;
		//First checking weather $subscriber exists or not
		$array = array('%subscriber%','%user%','%website_title%');
		$replace = array($subscriber,$from,TITLE);
		
		$to = $subscriber;
		$subj = str_replace($array,$replace,lang('user_subscribe_subject'));
		
		//Get Subscription Message Template
		$msg = get_subscription_template();
		$msg = str_replace($array,$replace,$msg);
		$this->SendMessage($to,$from,$subj,$msg,$video,0,0);
	}
	
	
	/**
	 * Function used to add comment
	 * This is more advance function , 
	 * in this function functions can be applied on comments
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL,$type='v',$obj_owner=NULL,$obj_link=NULL,$force_name_email=false)
	{
		global $userquery,$eh,$db,$Cbucket;
		//Checking maximum comments characters allowed
		if(defined("MAX_COMMENT_CHR"))
		{
			if(strlen($comment) > MAX_COMMENT_CHR)
				e(sprintf("'%d' characters allowed for comment",MAX_COMMENT_CHR));
		}
		if(!verify_captcha())
			e(lang('usr_ccode_err'));
		if(empty($comment))
			e(lang("pelase_enter_something_for_comment"));
		
		$params = array('comment'=>$comment,'obj_id'=>$obj_id,'reply_to'=>$reply_to,'type'=>$type);
		$this->validate_comment_functions($params);
		/*		
		if($type=='video' || $type=='v')
		{
			if(!$this->video_exists($obj_id))
				e(lang("class_vdo_del_err"));
			
			//Checking owner of video
			if(!USER_COMMENT_OWN)
			{
				if(userid()==$this->get_vid_owner($obj_id));
					e(lang("usr_cmt_err2"));
			}
		}
		*/
		
		if(!userid() && $Cbucket->configs['anonym_comments']!='yes')
			e(lang("you_not_logged_in"));
		
		if((!userid() && $Cbucket->configs['anonym_comments']=='yes') || $force_name_email)
		{
			//Checking for input name and email
			if(empty($_POST['name']))
				e(lang("please_enter_your_name"));
			if(empty($_POST['email']))
				e(lang("please_enter_your_email"));
			
			$name = mysql_clean($_POST['name']);
			$email = mysql_clean($_POST['email']);
		}
		
		if(empty($eh->error_list))
		{
			$db->insert(tbl("comments"),array
						 ('type,comment,type_id,userid,date_added,parent_id,anonym_name,anonym_email','comment_ip','type_owner_id'),
						 array
						 ($type,$comment,$obj_id,userid(),NOW(),$reply_to,$name,$email,$_SERVER['REMOTE_ADDR'],$obj_owner));
			
			$db->update(tbl("users"),array("total_comments"),array("|f|total_comments+1")," userid='".userid()."'");
			
			e(lang("grp_comment_msg"),"m");
			
			$cid = $db->insert_id();	
			$own_details = $userquery->get_user_field_only($obj_owner,'email');
			
			
			$username = username();
			$username = $username ? $username : post('name');	
			$useremail = $email;
			
			//Adding Comment Log
			$log_array = array
			(
			 'success'=>'yes',
			 'action_obj_id' => $cid,
			 'action_done_id' => $obj_id,
			 'details'=> "made a comment",
			 'username'=>$username,
			 'useremail'=>$useremail,
			 );
			insert_log($type.'_comment',$log_array);
			
			//sending email
			if(SEND_COMMENT_NOTIFICATION=='yes' && $own_details )
			{
				
				global $cbemail;
				
				$tpl = $cbemail->get_template('user_comment_email');
				
				$more_var = array
				('{username}'	=> $username,
				 '{obj_link}' => $obj_link.'#comment_'.$cid,
				 '{comment}' => $comment,
				 '{obj}'	=> get_obj_type($type)
				);
				if(!is_array($var))
					$var = array();
				$var = array_merge($more_var,$var);
				$subj = $cbemail->replace($tpl['email_template_subject'],$var);
				$msg = nl2br($cbemail->replace($tpl['email_template'],$var));
				
				//Now Finally Sending Email
				cbmail(array('to'=>$own_details,'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
			}
			
					
			return $cid;
		}
		
		return false;
	}
	

	
	/**
	 * Function used to  get file details from database
	 */
	function file_details($file_name)
	{
		global $db;
		return get_file_details($file_name);
		/*$results = $db->select("video_files","*"," src_name='$file_name'");
		if($db->num_rows==0)
			return false;
		else
		{
			return $results[0];
		}*/
	}
	
	
		 
	/**
	 * Function used to update video and set a thumb as default
	 * @param VID
	 * @param THUMB NUM
	 */
	function set_default_thumb($vid,$thumb)
	{
		global $cbvid;
		return $cbvid->set_default_thumb($vid,$thumb);
	}
	
	
	
	
	/**
	 * Function used to update video
	 */
	function update_video()
	{
		global $cbvid;
		return $cbvid->update_video();
	}
	
	
	
	/**
	 * Function used to get categorie details
	 */
	function get_category($id)
	{
		global $db;
		$results = $db->select(tbl("category"),"*"," categoryid='$id'");
		return $results[0];
	}
	
	
	/**
	 * Function used to get comment from its ID
	 * @param ID
	 */
	function get_comment($id)
	{
		global $db,$userquery;
		$result = $db->select(tbl("comments"),"*"," comment_id='$id'");
		if($db->num_rows>0)
		{
			$result = $result[0];
			if($result['userid'])
				$udetails = $userquery->get_user_details($result['userid']);
			if($udetails)
			$result = array_merge($result,$udetails);
			return $result ;
		}else{
			return false;
		}
	}
	
	/**
	 * Function used to get from database
	 * @param TYPE_ID
	 * @param TYPE
	 * @param COUNT_ONLY Boolean
	 * @param PARENT_ID 
	 * @param GET_REPLYIES_ONLY Boolean
	 */
	function get_comments($type_id='*',$type='v',$count_only=FALSE,$get_type='all',$parent_id=NULL,$useCache='yes')
	{
		
		$params = array(
		'type_id' 		=> $type_id,
		'type' 			=> $type,
		'count_only' 	=> $count_only,
		'get_type' 		=> $get_type,
		'parent_id' 	=> $parent_id,
		'cache'			=> $useCache
		);
		
		return $this->getComments($params);
	}
	
	/**
	 * Function used to get using ARRAY as paramter
	 */
	function getComments($params)
	{
		global $db,$userquery;
		$cond = '';
			
		if(!$params['cache'])
			$params['cache'] = 'yes';
				
		$p = $params;
		extract( $p, EXTR_SKIP );
		
		
		switch($type)
		{
			case "video":
			case "videos":
			case "v":
			case "vdo":
			{
				$type = 'v';
			}
			break;
			
			case "photo":
			case "p":
			case "photos":
			{
				$type='p';
			}
			break;
			
			case "topic":
			case "t":
			case "topics":
			{
				$type='t';
			}
			break;
			
			case "channel":
			case "c":
			case "channels":
			{
				$type='c';
			}
			break;
			
			case "cl":
			case "collect":
			case "collection":
			case "collections":
			{
				$type='cl';
			}
			break;
			
		}
		
		if(!$count_only && $params['cache']=='yes')
		{
			$file = $type.$type_id.str_replace(',','_',$limit).'-'.strtotime($last_update).'.tmp';
			
			$files = glob(COMM_CACHE_DIR.'/'.$type.$type_id.str_replace(',','_',$limit).'*');
			
			$theFile = getName($files[0]);
			$theFileDetails = explode('-',$theFile);
			$timeDiff = time() - $theFileDetails[1];
			
			if(file_exists(COMM_CACHE_DIR.'/'.$file) && $timeDiff < COMM_CACHE_TIME)
				return json_decode(file_get_contents(COMM_CACHE_DIR.'/'.$file),true);
		}
		
		if(!$order)
			$order = ' date_added DESC ';
		#Checking if user wants to get replies of comment 
		if($parent_id!=NULL && $get_reply_only)
		{
			$cond .= " AND parent_id='$parent_id'";
		}
		
		if($type_id!='*')
			$typeid_query = "AND type_id='$type_id' ";
		
		if(!$count_only)
		{
			/**
			 * we have to get comments in such way that
			 * comment replies comes along with their parents
			 * in order to achieve this, we have to create a complex logic
			 * lets see if we can get some tips from WP commenting system ;)
			 * HAIL Open Source
			 */
			 
			 $results = $db->select(tbl("comments"),'*'
			 ," type='$type' $typeid_query $cond",$limit,$order);
			 
			 if(!$results)
			 	return false;
			 $parent_cond = '';
			 
			 $parents_array = array();
			 if($results)
			 foreach($results as $result)
			 {
				 if($result['parent_id'] && !in_array($result['parent_id'],$parents_array))
				 {
					if($parent_cond)
						$parent_cond .= " OR ";
				 	$parent_cond .= " comment_id='".$result['parent_id']."' " ;
					
					$parents_array[] = $result['parent_id'];
				 }
			 }
			
			//Getting Parents
			 $parents = $db->select(tbl("comments"),'*'
			 ," type='$type' AND ($parent_cond) ",NULL,$order);
			 
			 if($parents)
			 	foreach($parents as $parent)
					$new_parents[$parent['comment_id']] = $parent;
			 
			 
			 //Inserting user data
			 $new_results = array();
			 foreach($results as $com)
			 {
				 $userid = $com['userid'];
				 
				 $uservar = 'user_'.$userid;
				 
				 if($userid && !$$uservar)
				 	$$uservar = $userquery->get_user_details($userid);
					
				 if($$uservar)
				 	$com = array_merge($com,$$uservar);
				
				$new_results[] = $com;
			 }
			 
			 $comment['comments'] = $new_results;
			 $comment['parents'] = $new_parents;
			 
			 //Deleting any other previuos comment file
			 $files = glob(COMM_CACHE_DIR.'/'.$type.$type_id.str_replace(',','_',$limit).'*');
			 
			 if($files)
				 foreach($files as $delFile)
			 		if(file_exists($delFile))
						unlink($delFile);
			 
			 //Caching comment file
			 if($file)
			 	file_put_contents(COMM_CACHE_DIR.'/'.$file,json_encode($comment));
			 return $comment;
			 
		}else
		{
			return $db->count(tbl("comments"),"*"," type='$type' $typeid_query $cond");
		}
	}
	
	
	/**
	 * Function used to get from database
	 * with nested replies
	 */	
	/*function get_comments($obj_id,$type='v',$parent_only=TRUE,$count_only=FALSE)
	{
		global $db;
		$cond = '';
		$user_flds = tbl("users.userid").",".tbl("users.username").",".tbl("users.email").",".tbl("users.avatar").",".tbl("users.avatar_url");
		if($obj_id != "all")
			$cond = " type_id = '$obj_id'";
		else
			$cond = " ";
			
		if(!empty($cond))
			$cond .= " AND ";
			
		if($parent_only && is_numeric($parent_only))
		{	
			$parent_id = $parent_only;			
			$cond .= " parent_id='$parent_id'";
		} else {
			$cond .= " parent_id = '0'";
		}
		
		if(!$count_only) 
		{
			$result = $db->select(tbl("comments,users"),tbl("comments").".*, $user_flds"," $cond AND type='$type' AND ".tbl("comments.userid")." = ".tbl("users.userid")."");
			//echo $db->db_query;
			$ayn_result = $db->select(tbl("comments,users"),tbl("comments").".*, $user_flds"," $cond AND type='$type' AND ".tbl("comments.userid")." = '0'");
			if($result && $ayn_result)
				$result = array_merge($result,$ayn_result);
			elseif(!$result && $ayn_result)
				$result = $ayn_result;
			
			$arr = array();
			foreach($result as $comment)
			{
					$arr[$comment['comment_id']] = $comment;
					$replies = $this->get_replies($comment['comment_id'],$type);
					if($replies)
					{
						$arr[$comment['comment_id']][] = $replies;	
					}
			}
			
			return $arr;
		}
	}*/
	
	function get_replies($p_id,$type='v')
	{
		global $db;
		$result = $db->select(tbl("comments,users"),"*"," type='$type' AND parent_id = '$p_id' AND ".tbl("comments.userid")." = ".tbl("users.userid")."");
		$ayn_result = $db->select(tbl("comments,users"),"*"," type='$type' AND parent_id = '$p_id' AND ".tbl("comments.userid")." = '0'");
		
		if($result && $ayn_result)
			$result = array_merge($result,$ayn_result);
		elseif(!$result && $ayn_result)
			$result = $ayn_result;
			
		return $result;	
	}
	
	/**
	 * Function used to get video owner
	 */
	function get_vid_owner($vid)
	{
		global $db;
		$results = $db->select(tbl("video"),"userid"," videoid='$vid'");
		return $results[0];
	}
	
	/**
	 * Function used to set website template
	 */
	function set_template($template)
	{
		global $myquery;
		if(is_dir(STYLES_DIR.'/'.$template) && $template)
		{
			$myquery->Set_Website_Details('template_dir',$template);
			e(lang("template_activated"),'m');
		}else
			e(lang("error_occured_changing_template"));
			
	}
	
	
	/**
	 * Function used to update comment
	 */
	function update_comment($cid,$text)
	{
		global $db;
		$db->Execute("UPDATE ".tbl("comments")." SET comment='$text' WHERE comment_id='$cid'");
		//$db->update(tbl("comments"),array("comment"),array($text)," comment_id = $cid");
	}
	
	
	/**
	 * Function used to validate comments
	 */
	function validate_comment_functions($params)
	{
		$type = $params['type'];
		$obj_id = $params['obj_id'];
		$comment = $params['comment'];
		$reply_to = $params['reply_to'];
		
		if($type=='video' || $type=='v')
		{
			if(!$this->video_exists($obj_id))
				e(lang("class_vdo_del_err"));
			
			//Checking owner of video
			//if(!USER_COMMENT_OWN)
			//{
			//	if(userid()==$this->get_vid_owner($obj_id));
			///		e(lang("usr_cmt_err2"));
			//}
		}
		
		$func_array = get_functions('validate_comment_functions');
		if(is_array($func_array))
		{
			foreach($func_array as $func)
			{
				if(function_exists($func))
				{
					return $func($params);
				}
			}
		}
		
	}
	
	
	/**
	 * Function used to insert note in data base for admin referance
	 */
	function insert_note($note)
	{
		global $db;
		$db->insert(tbl('admin_notes'),array('note,date_added,userid'),array($note,now(),userid()));
	}
	/**
	 * Function used to get notes
	 */
	function get_notes()
	{
		global $db;
		return $db->select(tbl('admin_notes'),'*'," userid='".userid()."'",NULL," date_added DESC ");
	}
	/**
	 * Function usde to delete note
	 */
	function delete_note($id)
	{
		global $db;
		$db->delete(tbl("admin_notes"),array("note_id"),array($id));
	}
	
	
	/**
	 * Function used to check weather object is commentable or not
	 */
	function is_commentable($obj,$type)
	{
		switch($type)
		{
			case "video":
			case "v":
			case "vdo":
			case "videos":
			case "vid":
			{
				if($obj['allow_comments'] == 'yes' && config('video_comments')==1)
					return true;
			}
			break;
			
			case "channel":
			case "user":
			case "users":
			case "u":
			case "c":
			{
				if($obj['allow_comments'] == 'Yes' && config('channel_comments')==1)
					return true;
			}
			break;
			
			case "collection":
			case "collect":
			case "cl":
			{
				if($obj['allow_comments'] == 'yes')
					return true;
			}
			break;
			
			case "photo":
			case "p":
			case "photos":
			{
				if($obj['allow_comments'] == 'yes' && config('photo_comments') == 1)
					return true;	
			}
		}
		return false;
	}
	
	/**
	 * Function used to get list of items in conversion queue
	 * @params $Cond, $limit,$order
	 */
	function get_conversion_queue($cond=NULL,$limit=NULL,$order='date_added DESC')
	{
		global $db;
		$result = $db->select(tbl("conversion_queue"),"*",$cond,$limit,$oder);
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * function used to remove queue
	 */
	function queue_action($action,$id)
	{
		global $db;
		switch($action)
		{
			case "delete":
			$db->execute("DELETE from ".tbl('conversion_queue')." WHERE cqueue_id ='$id' ");
			break;
			case "processed":
			$db->update(tbl('conversion_queue'),array('cqueue_conversion'),array('yes')," cqueue_id ='$id' ");
			break;
			case "pending":
			$db->update(tbl('conversion_queue'),array('cqueue_conversion'),array('no')," cqueue_id ='$id' ");
			break;
		}
	}
	
	

}
?>