<?php
/*
 * @Author  : Arslan Hassan
 * @License : CBLA
 * @Updated : March 12 2009
 * @Version : 1.8
*/

class groups
{
		//Function Used To Check Weather ENTERED URL exists or not
		function ExistsURL($url){
			$query = mysql_query("SELECT * FROM groups WHERE group_url = '".$url."'");
			if(mysql_num_rows($query)>0){
				return true;
			}else{
				return false;
			}
		}

		//Function Used TO CHeck Weather Entered Name already Exists or not
		function ExistsName($name){
			$query = mysql_query("SELECT * FROM groups WHERE group_name = '".$name."'");
			if(mysql_num_rows($query)>0){
				return true;
			}else{
				return false;
			}
		}

		//Function Used To Get Groups Details
		function GetDetails($url){
			$query = mysql_query("SELECT * FROM groups WHERE group_url='".$url."'");
			$data = mysql_fetch_array($query);
		return $data;
		}

		//Function Used To Get Groups Details
		function GetDetailsId($id){
			$query = mysql_query("SELECT * FROM groups WHERE group_id='".$id."'");
			$data = mysql_fetch_array($query);
		return $data;
		}


		//Check Group Owner
		function GetGroupOwner($group,$type=1){
			if($type == 1){
				$data = $this->GetDetailsId($group);
			}else{
				$data = $this->GetDetails($group);
			}
			return $data['username'];
		}

			//Function Used TO Join Group

			function JoinGroup($username,$group,$active='yes'){
			global $LANG;
			if($this->is_joined($username,$group)){
				$msg = e($LANG['grp_join_error']);
			}else{
				if(!mysql_query("INSERT INTO group_members(group_id,username,active)VALUES('".$group."','".$username."','".$active."')")) die(mysql_error());
				$total_users = $this->CountMembers($group);
				mysql_query("UPDATE groups SET total_members='".$total_users."' WHERE group_id='".$group."'");
			}
			return @$msg;
			}

		//Function Used to Validate Group Form
		function CreateGroup(){
		global $LANG;
			$name 			= mysql_clean($_POST['name']);
			$description	= mysql_clean($_POST['description']);
			$tags			= mysql_clean($_POST['tags']);
			$url			= mysql_clean($_POST['url']);
			@$category		= mysql_clean($_POST['category']);
			$group_type		= mysql_clean($_POST['group_type']);
			$video_type		= mysql_clean($_POST['video_type']);
			$post_type		= mysql_clean($_POST['post_type']);
			if(@GROUP_ACTIVATION == 'yes'){
			$active 		= 'no';
			}else{
			$active			= 'yes';
			}
			$username		= $_SESSION['username'];

			//Validation Starts Here

				// If Users Session Ends
				if(empty($_SESSION['username'])){
					redirect_to(BASEURL.signup_link);
				}

				//If Group Title isnt set
				if(empty($name)){
					$msg[] = e($LANG['grp_name_error']);
				}elseif($this->ExistsName($name)){
					$msg[] = e($LANG['grp_name_error1']);
				}
				//if description is not entered
				if(empty($description)){
					$msg[] = e($LANG['grp_des_error']);
				}
				//if tags is not entered
				if(empty($tags)){
					$msg[] = e($LANG['grp_tags_error']);
				}
				//if url is not entered or url already exists
				if(empty($url)){
					$msg[] = e($LANG['grp_url_error']);
				}elseif(!isValidText($url)){
					$msg[] = e($LANG['grp_url_error1']);
				}elseif($this->ExistsURL($url)){
					$msg[] = e($LANG['grp_url_error2']);
				}

				//Check If category is not selected
				if(empty($category)){
					$msg[] = e($LANG['grp_cat_error']);
				}

			//Data Entering
			
				if(empty($msg)){
					if(!mysql_query("INSERT INTO groups(group_name,group_description,group_tags,group_url,group_category,group_type,video_type,post_type,active,username,group_thumb)VALUES
								('".$name."','".$description."','".$tags."','".$url."','".$category."','".$group_type."','".$video_type."','".$post_type."','".$active."','".$username."','no_thumb.png')")) die(mysql_error());
				$ID = mysql_insert_id();	
				//Updating Users Number Of  Videos Added By User
				$groups_query 	= mysql_query("SELECT * FROM groups WHERE username='".$username."'");
				$groupscount	= mysql_num_rows($groups_query);
				$updatequery = mysql_query("UPDATE users SET total_groups='".$groupscount."' WHERE username = '".$username."'");
				//Auto Joining Group Owner
				$this->JoinGroup($username,$ID);
				
				redirect_to(BASEURL.view_group_link.$url);
				}
			return $msg;

		}


			//Function Used To add Topics ion the groups

			function AddTopic($topic,$group,$video=0,$approved='yes'){
			global $LANG;
				if(empty($topic)){
					if(empty($_SESSION['username'])){
					redirect_to(BASEURL.signup_link);
					}
					$msg = e($LANG['grp_tpc_error2']);
					}else{
					if(!mysql_query("INSERT into group_topics(topic_title,group_id,username,videokey,approved,last_reply)
					VALUES('".$topic."','".$group."','".$_SESSION['username']."','".$video."','".$approved."',now())")) die(mysql_error());
					$total_topics = $this->CountTopics($group);
					mysql_query("UPDATE groups SET total_topics='".$total_topics."' WHERE group_id='".$group."'");
					$msg[] = e($LANG['grp_tpc_msg'],m);
					if($approved=='no'){
					$msg[] = e($LANG['grp_tpc_error3']);
					}
					}
			return $msg;
			}

			//Function Used To Get Topic List

			function GetTopics($group){
				$sql = "Select * FROM group_topics WHERE group_id = ''".$group."' AND approved='yes'";
				$data = $db->Execute($sql);
				$details = $data->getrows;
				return $details;
			}

			//Function used to check Topic exists or not

			function is_topic($topic){
			$query = mysql_query("SELECT * FROM group_topics WHERE topic_id ='".$topic."'");
				if(mysql_num_rows($query) >0){
					return true;
				}else{
				return false;
				}
			}

			//Function Used to get Topic

			function GetTopic($topic){
				$query = mysql_query("SELECT * FROM group_topics WHERE topic_id ='".$topic."'");
				return mysql_fetch_array($query);
			}

			//Function Used To Add Comments

			function AddComment($comment,$topic,$reply_to=NULL){
			global $LANG;
				if(empty($comment)){
				$msg = e($LANG['grp_comment_error']);
				}else{
				if(!mysql_query("INSERT INTO group_posts(post,topic_id,username,reply_to)VALUES('".$comment."','".$topic."','".$_SESSION['username']."','".$reply_to."')")) die(mysql_error());
				if(!mysql_query("UPDATE group_topics  SET last_reply=now() WHERE topic_id='".$topic."'")) die(mysql_error());
				$msg = e($LANG['grp_comment_msg'],m);
				}
			return $msg;
			}

			//Function Used To Check User is invited or not to join this group

			function is_userinvite($user,$owner,$group){
				$query = mysql_query("SELECT * FROM group_invitations WHERE invited_user='".$user."' AND invited_by ='".$owner."' AND group_id='".$group."'");
				if(mysql_num_rows($query) > 0){
					$query = mysql_query("SELECT * FROM groups WHERE username = '".$owner."' AND group_id ='".$group."'");
						if(mysql_num_rows($query) > 0){
							return true;
						}else{
							return false;
						}
				}else{
					return false;
				}
			}

			//Function Used To CHeck User Is Joined or not

			function is_joined($username,$group){
				$query = mysql_query("SELECT * FROM group_members WHERE username = '".$username."' AND group_id ='".$group."'");
				if(mysql_num_rows($query)>0){
					return true;
				}else{
					return false;
				}
			}

			//Function Used To CHeck User Is Active or not

			function is_active($username,$group){
				$query = mysql_query("SELECT * FROM group_members WHERE username = '".$username."' AND group_id ='".$group."' AND active='yes'");
				if(mysql_num_rows($query)>0){
					return true;
				}else{
					return false;
				}
			}

			//Function Used To update group

			function UpdateGroup($redirect=1){
			global $LANG;
				$name_exist 	= $_POST['name_exist'];
				$url_exist 		= $_POST['url_exist'];
				$name 			= mysql_clean($_POST['name']);
				$description	= mysql_clean($_POST['description']);
				$tags			= mysql_clean($_POST['tags']);
				$url			= mysql_clean($_POST['url']);
				$category		= mysql_clean($_POST['category']);
				$group_type		= mysql_clean($_POST['group_type']);
				$video_type		= mysql_clean($_POST['video_type']);
				$post_type		= mysql_clean($_POST['post_type']);
				//If Group Title isnt set
				if(empty($name)){
					$msg[] = e($LANG['grp_name_error']);
				}elseif($this->ExistsName($name) && $name_exist !== $name ){
					$msg[] = e($LANG['grp_name_error1']);
				}
				//if description is not entered
				if(empty($description)){
					$msg[] = e($LANG['grp_des_error']);
				}
				//if tags is not entered
				if(empty($tags)){
					$msg[] = e($LANG['grp_tags_error']);
				}
				//if url is not entered or url already exists
				if(empty($url)){
					$msg[] = e($LANG['grp_url_error']);
				}elseif(!isValidText($url)){
					$msg[] = e($LANG['grp_url_error1']);
				}elseif($this->ExistsURL($url) && $url_exist !== $url){
					$msg[] = e($LANG['grp_url_error2']);
				}

				//Getting Thumb
				$file 		= $_FILES['thumb_upload']['name'];
				$thumb		= $_POST['thumb_exist'];
				$ext 		= substr($file, strrpos($file, '.') + 1);

				if(empty($msg)){

					if(!empty($file)){
					$image = new ResizeImage();
					if($image->ValidateImage($_FILES['thumb_upload']['tmp_name'],$ext)){
						if($thumb != 'no_thumb.png'){
						unlink(BASEDIR.'/images/groups_thumbs/'.$thumb);
						}
						$newname			= RandomString(10);
						$newthumb			= $newname.'.'.$ext;
						$new_thumb			= BASEDIR.'/images/groups_thumbs/'.$newthumb;
						copy($_FILES['thumb_upload']['tmp_name'],$new_thumb);
						$image->CreateThumb($new_thumb,$new_thumb,120,$ext);
						$thumb = $newthumb;
						}
					}

				if(!mysql_query("UPDATE groups SET
				group_name 			= '".$name."',
				group_tags 			= '".$tags."',
				group_description	= '".$description."',
				group_url			= '".$url."',
				group_category		= '".$category."',
				group_type			= '".$group_type."',
				video_type			= '".$video_type."',
				post_type			= '".$post_type."',
				group_thumb			= '".$thumb."'
				WHERE group_id = '".$_POST['group_id']."'"))  die(mysql_error());
				$msg = e($LANG['grp_update_msg'],m);
				if($redirect == 1){
				redirect_to(BASEURL.edit_group_link.$url.'&update=true');
				}
				}
			return $msg;
			}

		//Function Used To Count Number Of Member in a group
		function CountMembers($group){
			$query = mysql_query("SELECT * FROM group_members WHERE group_id='".$group."' AND active='yes'");
			return mysql_num_rows($query);
		}

		//Function Used to Count Number of Video In A Group
		function CountVideos($group){
			$query = mysql_query("SELECT * FROM group_videos WHERE group_id='".$group."' AND approved='yes'");
			return mysql_num_rows($query);
		}

		//Function Used To Count Nmber of topics
		function CountTopics($group){
			$query = mysql_query("SELECT * FROM group_topics WHERE group_id='".$group."' AND approved='yes'");
			return mysql_num_rows($query);
		}

		//Function Used TO check Number of posts in a group
		function CountGroupPosts($group){
			$query = mysql_query("SELECT * FROM group_topics WHERE group_id='".$group."' AND approved='yes'");
			$topics = 0;
				while($data = mysql_fetch_array($query)){
					$topic_query = mysql_query("SELECT * FROM group_posts WHERE topic_id ='".$data['topic_id']."'");
					$topics = $topics + mysql_num_rows($topic_query);
					}
			return $topics;
		}

		//Function Used To Get Number Of Replies in a post

		function CountReplies($topic){
		$query = mysql_query("SELECT * FROM group_posts WHERE topic_id ='".$topic."'");
		return  mysql_num_rows($query);
		}

		//Function Used TO check Video exist in the group or not

		function is_video($group,$video){
			$query = mysql_query("SELECT * FROM group_videos WHERE videokey = '".$video."' AND group_id = '".$group."'");
			if(mysql_num_rows($query)>0){
				return true;
			}else{
				return false;
			}
		}

		//Function Used To Add Videos To Groups

		function AddVideos($group,$approved='yes'){
		global $LANG;
			if(!empty($_SESSION['username'])){
			$msg = e($LANG['grp_vdo_msg1'],m);
				$query = mysql_query("SELECT * FROM video WHERE username = '".$_SESSION['username']."' ");
					while($data = mysql_fetch_array($query)){
						if($_POST[$data['videokey']] == 'yes'){
							if(!$this->is_video($group,$data['videokey'])){
								if(!mysql_query("INSERT INTO group_videos(videokey,group_id,username,approved)VALUES('".$data['videokey']."','".$group."','".$_SESSION['username']."','".$approved."') ")) die(mysql_error());
								$total_videos = $this->CountVideos($group);
								mysql_query("UPDATE groups SET total_videos='".$total_videos."' WHERE group_id='".$group."'");
								}
						}
					}
			}
		return $msg;
		}


		//Function Used To Delete Videos

		function RemoveVideos($group){
		global $LANG;
			$query = mysql_query("SELECT * FROM group_videos WHERE group_id = '".$group."'");
			while($data = mysql_fetch_array($query)){
				if($_POST[$data['videokey']] == 'yes'){
						mysql_query("DELETE FROM group_videos WHERE videokey = '".$data['videokey']."'");
						$total_videos = $this->CountVideos($group);
						mysql_query("UPDATE groups SET total_videos='".$total_videos."' WHERE group_id='".$group."'");
					}
				}
			$msg = e($LANG['grp_vdo_msg'],m);
		return $msg;
		}

		//Function Used To Approve Movies

		function ApproveVideos($group){
		global $LANG;
			$query = mysql_query("SELECT * FROM group_videos WHERE group_id = '".$group."'");
			while($data = mysql_fetch_array($query)){
				if($_POST[$data['videokey']] == 'yes'){
						mysql_query("UPDATE group_videos SET  approved='yes' WHERE videokey = '".$data['videokey']."'");
						$total_videos = $this->CountVideos($group);
						mysql_query("UPDATE groups SET total_videos='".$total_videos."' WHERE group_id='".$group."'");
					}
				}
			$msg = e($LANG['grp_vdo_msg2'],m);
		return $msg;
		}

		//Function Used To Delete Members

		function RemoveMembers($group){
		global $LANG;
			$query = mysql_query("SELECT * FROM group_members WHERE group_id = '".$group."'");
			while($data = mysql_fetch_array($query)){
				if($_POST[$data['username']] == 'yes'){
						mysql_query("DELETE FROM group_members WHERE username = '".$data['username']."'");
						$total_users = $this->CountMembers($group);
						mysql_query("UPDATE groups SET total_members='".$total_users."' WHERE group_id='".$group."'");
					}
				}
			$msg = e($LANG['grp_mem_msg'],m);
		return $msg;
		}

		//Function Used To Approve Members

		function ApproveMembers($group){
		global $LANG;
			$query = mysql_query("SELECT * FROM group_members WHERE group_id = '".$group."'");
			while($data = mysql_fetch_array($query)){
				if($_POST[$data['username']] == 'yes'){
						mysql_query("UPDATE group_members SET  active='yes' WHERE username = '".$data['username']."'");
						$total_users = $this->CountMembers($group);
						mysql_query("UPDATE
						 groups SET total_members='".$total_users."' WHERE group_id='".$group."'");
					}
				}
			$msg = e($LANG['grp_mem_msg1'],m);
		return $msg;
		}

		//Function Used To Send Invitations

		function SendInvitation($user,$group,$subj,$url){
		global $LANG;
				$video = "";
				//Sending Message to Website Users
				$query = mysql_query("SELECT * FROM contacts WHERE username='".$user."'");
				while($data=mysql_fetch_array($query)){
					if($_POST[$data['friend_username']] == 'yes'){
						//Send Message To $data['friend_username']
						$myquery 	= new myquery();
						$to 		= $data['friend_username'];
						$from 		= $_SESSION['username'];
						$message	= nl2br(mysql_clean($_POST['msg']))."<BR>Groupurl: <a href=".$url.">".$url."</a>";
						$redirect   = 0;
						$myquery->SendMessage($to,$from,$subj,$message,$video,$redirect);
						mysql_query("INSERT INTO group_invitations(group_id,invited_user,invited_by)VALUES('".$group."','".$to ."','".$from."')");
						}
				}

				//Sending Message Getting User From Boxes
				$users = $_POST['users'];
				$new_users = explode(',',$users);
				foreach($new_users as $user){
					$query = mysql_query("SELECT * FROM users WHERE username='".$user."' OR email='".$user."'");
						if(mysql_num_rows($query) > 0){
							$data = mysql_fetch_array($query);
							$myquery 	= new myquery();
							$to 		= $data['username'];
							$from 		= $_SESSION['username'];
							$message	= nl2br(mysql_clean($_POST['msg']))."<BR>Groupurl: <a href=".$url.">".$url."</a>";
							$redirect   = 0;
							$myquery->SendMessage($to,$from,$subj,$message,$video,$redirect);
							mysql_query("INSERT INTO group_invitations(group_id,invited_user,invited_by)VALUES('".$group."','".$to ."','".$from."')");
						}elseif(isValidEmail($user)){
							$to 		= $data['friend_username'];
							$from 		= $_SESSION['username'];
							$message	= nl2br(mysql_clean($_POST['msg']))."<BR>Groupurl: <a href=".$url.">".$url."</a>";;
							$redirect   = 0;
							send_email($from,$to,$subj,$message);
						}
				}
			$msg = e($LANG['grp_inv_msg'],m);
		return $msg;
		}


	 	//Function Used TO Delete Post
		function DeletePost($post){
		global $LANG;
			mysql_query("DELETE FROM group_posts WHERE post_id='".$post."'");
			$msg = e($LANG['grp_post_msg'],m);
			return $msg;
		}

		//Function Used To  Leave Group
		function LeaveGroup($user,$group){
		global $LANG;
			//CHecking if user is Group Owner or not
			$owner = $this->GetGroupOwner($group);
			if($owner == $user){
				return false;
			}else{
				mysql_query("DELETE FROM group_members WHERE group_id='".$group."' AND username='".$user."' ");
				$total_users = $this->CountMembers($group);
				mysql_query("UPDATE groups SET total_members='".$total_users."' WHERE group_id='".$group."'");
				return true;
			}
		}

		//Function Used To Delete Topic
		function DeleteTopic($topic){
		global $LANG;
			mysql_query("DELETE FROM group_topics WHERE topic_id='".$topic."'");
			mysql_query("DELETE FROM group_posts WHERE  topic_id='".$topic."'");
			$total_topics = $this->CountTopics($group);
			mysql_query("UPDATE groups SET total_topics='".$total_topics."' WHERE group_id='".$group."'");
			$msg = e($LANG['grp_tpc_msg'],m);
			return $msg;
		}


		//Function Used To Delete Group
		function DeleteGroup($group){
		global $LANG;
		$__groupsquery 	= mysql_query("SELECT * FROM groups WHERE group_id='".$group."'");
		$__Data = mysql_fetch_array($__groupsquery);
		mysql_query("DELETE FROM group_members WHERE group_id='".$group."'");
		mysql_query("DELETE FROM group_videos WHERE group_id='".$group."'");
		mysql_query("DELETE FROM group_invitations WHERE group_id='".$group."'");
		$query = mysql_query("SELECT * FROM group_topics WHERE group_id='".$group."'");
		while($data=mysql_fetch_array($query)){
			$this->DeleteTopic($data['topic_id']);
		}
		mysql_query("DELETE FROM groups WHERE group_id='".$group."'");
		//Updating Users Number Of  Videos Added By User
				$groups_query 	= mysql_query("SELECT * FROM groups WHERE username='".$__Data['username']."'");
				$groupscount	= mysql_num_rows($groups_query);
				$updatequery =  mysql_query("UPDATE users SET total_groups='".$groupscount."' WHERE username = '".@$username."'");
		$msg = e($LANG['grp_del_msg']);
		return $msg;
		}

		//Function Used To Approve Topics
		function ApproveTopic($topic){
			mysql_query("UPDATE group_topics SET approved='yes' WHERE topic_id='".$topic."'");
			$msg = e($LANG['grp_tpc_msg2'],m);
		return $msg;
		}

		//Functoin USed To Check weather group exist or not

		function GroupExists($group){
			$query = mysql_query("SELECT * FROM groups WHERE group_id = '".$group."'");
			if(mysql_num_rows($query)>0){
				return true;
			}else{
				return false;
			}
		}

		//Function Used To Feature Group

		function MakeFeatured($group){
		global $LANG;
			mysql_query("UPDATE groups SET featured='yes' WHERE group_id='".$group."'");
			return e($LANG['grp_fr_msg'],m);
		}

		//Function Used To UngFeatured Group

		function MakeUnFeatured($group){
		global $LANG;
			mysql_query("UPDATE groups SET featured='no' WHERE group_id='".$group."'");
			return e($LANG['grp_fr_msg2'],m);
		}

		//Function Used To Activate Group

		function Activate($group){
		global $LANG;
			mysql_query("UPDATE groups SET active='yes' WHERE group_id='".$group."'");
			return e($LANG['grp_av_msg'],m);
		}

		//Function Used To DeActivate Group

		function DeActivate($group){
		global $LANG;
			mysql_query("UPDATE groups SET active='no' WHERE group_id='".$group."'");
			return e($LANG['grp_inv_msg1'],m);
		}
		
		/**
		 * FUNCTION USED TO GET GROUP THUMB
		 * @param : group thumb
		 */
		 function getThumb($groupThumb)
		 {
			 //Path to thumbs directory
			 $dir = BASEDIR.'/images/groups_thumbs/';
			 $url = BASEURL.'/images/groups_thumbs/';
			 $thumb = $dir.$groupThumb;
			 $thumb_url = $url.$groupThumb;
			 $default = $url.'no_thumb.png';
			 //Checking if thumb exists or not
			 if(file_exists($thumb))
			 {
			 	return $thumb_url;
			 }else{
				return $default;
			 }
		 }
}
?>