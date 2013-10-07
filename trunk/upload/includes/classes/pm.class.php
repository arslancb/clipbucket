<?php


/**
 * This Class is used to
 * Send and recieve
 * private or personal messages
 * within the CLIPBUCKET system
 *
 * @Author : Arslan Hassan (=D)
 * @Software : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 *
 * Pleae check CBLA for more details
 * For code reference, please check docs.clip-bucket.com
 * This Code is property of PHPBucket - ClipBucket - Arslan Hassan
 *
 * NOTE : MAINTAIN THIS SECTION
 *
 *
 * Attachment Pattern : {v:videoidid}{p:pictureid}{g:groupid}{c:channelid}
 * For Multi Users : uid can be uid1|uid2|uid3|....
 */
 

define('CB_PM','ON');
define('CB_PM_MAX_INBOX',500); // 0 - OFF , U - Unlimited

	/**
	 * Function used to to attach video to pm
	 * @param array => 'attachment_video'
	 */
	function attach_video($array)
	{
		global $cbvid;
		if($cbvid->video_exists($array['attach_video']))
			return '{v:'.$array['attach_video'].'}';
	}
	
	/**
	 * Function used to pars video from attachemtn
	 */
	function parse_and_attach_video($att)
	{
		global $cbvid;
		preg_match('/{v:(.*)}/',$att,$matches);
		$vkey = $matches[1];
		if(!empty($vkey))
		{
			assign('video',$cbvid->get_video_details($vkey));
			assign('only_once',true);
			echo '<h3>Attached Video</h3>';
			template('blocks/video.html');
		}
	}
	
	/**
	 * Function used to add custom video attachment form field
	 */
	function video_attachment_form()
	{
		global $cbvid;
		$vid_array = array('user'=>userid(),'order'=>'date_added DESC','limit'=>15);
		$videos = $cbvid->get_videos($vid_array);
		$vids_array = array('' => lang("No Video"));
		if($videos)
		foreach($videos as $video)
		{
			$vids_array[$video['videokey']] = $video['title'];
		}
		$field = array(
					   'video_form' => array
					   ('title'=> 'Attach video',
						'type'=>'dropdown',
						'name'=> 'attach_video',
						'id'=> 'attach_video',
						'value'=> $vids_array,
						'checked'=>post('attach_video'),
						'anchor_before'=>'before_video_attach_box',
						)
					  );
		return $field;
	}
	
	
	

class cb_pm
{
	/**
	 * Private messages table
	 */
	var $tbl = 'messages';
	
	/**
	 * Allow multi users
	 */
	var $multi = true;
	
	
	/**
	 * Default Template
	 */
	var $email_template = 'pm_email_message';
	
	/**
	 * Send Email on pm
	 */
	var $send_email = true;
	
	/**
	 * Allow inline attachments
	 * these attachements are linked in the messages instead of attached like emails
	 */
	var $allow_attachments = true;
	
	//Attachment functionss
	var $pm_attachments = array('attach_video');
	var $pm_attachments_parse = array('parse_and_attach_video');
	
	var $pm_custom_field = array();
	
	
	/**
	 * Calling Constructor
	 */
	function init()
	{
		$array = video_attachment_form();
		$this->add_custom_field($array);
	}
	
	
	
	/**
	 * Sending PM
	 */
	function send_pm($array)
	{
		global $userquery,$db;
		$to = $this->check_users($array['to'],$array['from']);

		//checking from user
		if(!$userquery->user_exists($array['from']))
		{
			e(lang('unknown_sender'));
		//checking to user
		}elseif(!$to)
			return false;
		//Checking if subject is empty
		elseif(empty($array['subj']))
			e(lang('class_subj_err'));
		elseif(empty($array['content']))
			e(lang('please_enter_message'));
		else
		{
			$from = $this->get_the_user($array['from']);
			$attachments = $this->get_attachments($array);
			$type = $array['type'] ? $array['type'] : 'pm';
			$reply_to = $this->is_reply($array['reply_to'],$from);
			
			$fields = array('message_from','message_to','message_content',
										 'message_subject','date_added','message_attachments','message_box','reply_to');
			$values = array($from,$to,$array['content'],
											   $array['subj'],now(),$attachments);
			
			//PM INBOX FIELDS
			$fields_in = $fields;
			//PM INBOX
			$values_in = $values;
			$values_in[] = 'in';
			$values_in[] = $reply_to;
			
			$db->insert(tbl($this->tbl),$fields_in,$values_in);
			$array['msg_id'] = $db->insert_id();
			if($array['is_pm'])
			{
				//PM SENTBOX FIELDS
				$fields_out = $fields;
				$fields_out[] = 'message_status';
				
				//PM SENTBOX
				$values_out = $values;
				$values_out[] = 'out';
				$values_out[] = $reply_to;
				$values_out[] = 'read';
				
				$db->insert(tbl($this->tbl),$fields_out,$values_out);
			}
			
			//Sending Email
			$this->send_pm_email($array);
			e(lang("pm_sent_success"),"m");
		}		
	}
	
	
	/**
	 * Function used to check input users
	 * are valid or not
	 */
	function check_users($input,$sender)
	{
		global $userquery;
		
		if(empty($input)) {
			e(lang("unknown_reciever"));
		} else {
			//check if usernames are sperated by colon ';'
			$input = preg_replace('/;/',',',$input);
			//Now Exploding Input and converting it to and array
			$usernames = explode(',',$input);
			
			//Now Checkinf for valid usernames
			$valid_users = array();
			foreach($usernames as $username)
			{
				$user_id = $this->get_the_user($username);
				if($userquery->is_user_banned($username,userid())) {
					e(sprintf(lang("cant_pm_banned_user"),$username));
				} elseif($userquery->is_user_banned(username(),$username)){
					e(sprintf(lang("cant_pm_user_banned_you"),$username));
				}elseif(!$userquery->user_exists($username)) {
					e(lang("unknown_reciever"));
				} elseif($user_id == $sender) {
					e(lang("you_cant_send_pm_yourself"));				
				} else {
					$valid_users[] = $user_id;	
				}
			}
			
			$valid_users = array_unique($valid_users);
			
			if(count($valid_users)>0)
			{
				$vusers = '';
				foreach($valid_users as $vu)
				{
					$vusers .="#".$vu."#";
				}
				return $vusers;				
			}
			else
				return false;
		}
	}
	
	
	/**
	 * Function used to get user
	 */
	function get_the_user($user)
	{
		global $userquery;
		if(!is_numeric($user))
			return $userquery->get_user_field_only($user,'userid');
		else
			return $user;
	}
	
	
	/**
	 * Function used to make attachment valid
	 * and embed it in the message
	 */
	function get_attachments($array)
	{
		$funcs = $this->pm_attachments;
		$attachments = '';
		
		if(is_array($funcs))
		foreach($funcs as $func)
		{
			if(function_exists($func))
			{
				$attachments .= $func($array);
			}
		}	
		return $attachments;
	}
	
	/**
	 * function used to check weather message is reply or not
	 */
	function is_reply($id,$uid)
	{
		global $db;
		$results = $db->select(tbl($this->tbl),'message_to'," message_id = '$id' AND message_to LIKE '%#$uid#%'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to get message from inbox, set the template
	 * and display it
	 */
	function get_message($id)
	{
		global $db;
		$result = $db->select(tbl($this->tbl),'*'," message_id='$id'");
		$result = $result[0];
		if($db->num_rows>0)
		{
			return $result[0];
		}else{
			e(lang('no_pm_exist'));
			return false;
		}	
	}
	
	/**
	 * Function used to get user INBOX Message
	 * @param MESSAGE ID
	 * @param USER ID
	 */
	function get_inbox_message($mid,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		$result = $db->select(tbl($this->tbl.',users'),tbl($this->tbl.'.*,users.userid,users.username')," message_id='$mid' AND message_to LIKE '%#$uid#%' AND userid=".tbl($this->tbl).".message_from",NULL," date_added DESC ");
		
		if($db->num_rows>0)
		{
			return $result[0];
		}else{
			e(lang('no_pm_exist'));
			return false;
		}		
	}
	
	/**
	 * Function used to get user OUTBOX Message
	 * @param MESSAGE ID
	 * @param USER ID
	 */
	function get_outbox_message($mid,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid = userid();
		$result = $db->select(tbl($this->tbl.',users'),tbl($this->tbl.'.*,users.userid,users.username')," message_id='$mid' AND message_from='$uid' AND userid=".tbl($this->tbl.".message_from"));
		
		if($db->num_rows>0)
		{
			return $result[0];
		}else{
			e(lang('no_pm_exist'));
			return false;
		}		
	}
	
	
	/**
	 * Get Total PM
	 */
	function pm_count()	{
		global $db;
		return $db->count(tbl($this->tbl),'message_id');
	}
	
	/**
	 * Function used to get user inbox messages
	 */
	function get_user_messages($uid,$box='in',$count_only=false)
	{
		global $db;
		
		if(!$uid)
			$uid = userid();
		switch ($box)
		{
			
			case 'in':
			{
				if($count_only)
				{
					$result = $db->count(tbl($this->tbl),'message_id'," message_to LIKE '%#$uid#%' AND message_box ='in' AND message_type='pm' ");
				}else{
					$result = $db->select(tbl($this->tbl.',users'),tbl($this->tbl.'.*,users.username AS message_from_user '),
										  tbl($this->tbl).".message_to LIKE '%#$uid#%' AND ".tbl("users").".userid = ".tbl($this->tbl).".message_from 
										  AND ".tbl($this->tbl).".message_box ='in' AND message_type='pm'",NULL," date_added DESC");
				}
			}
			break;
			
			
			case 'out':
			{
				if($count_only)
				{
					$result = $db->count(tbl($this->tbl),'message_id'," message_from = '$uid' AND message_box ='out' ");
				}else{
					$result = $db->select(tbl($this->tbl.',users'),tbl($this->tbl.'.*,users.username AS message_from_user '),
										  tbl($this->tbl).".message_from = '$uid' AND ".tbl("users").".userid = ".tbl($this->tbl).".message_from 
										  AND ".tbl($this->tbl).".message_box ='out'",NULL," date_added DESC");
					//echo $db->db_query;
					//One More Query Need To be executed to get username of recievers
					$count = 0;
					
					$cond = "";
					if(is_array($result))
					foreach($result as $re)
					{
						
						$cond = '';
						preg_match_all("/#(.*)#/Ui",$re['message_to'],$receivers);
						//pr($receivers);
						foreach($receivers[1] as $to_user)
						{
	
							if(!empty($to_user))
							{
								if(!empty($cond))
									$cond .= " OR ";
								$cond .= " userid = '$to_user' ";
							}
						}
						
						$to_names = $db->select(tbl('users'),'username',$cond);
						$t_names = '';
						
						if(is_array($to_names))
						foreach($to_names as $tn)
						{
							$t_names[] = $tn[0];
						}
						if(is_array($t_names))
							$to_user_names = implode(', ',$t_names);
						else
							$to_user_names = $t_names;
						$result[$count]['to_usernames'] = $to_user_names;
						$count++;
					}
				}
			}
			break;
			
			case 'notification':
			{
				if($count_only)
				{
					$result = $db->count(tbl($this->tbl),'message_id'," message_to LIKE '%#$uid#%' AND message_box ='in' AND message_type='pm' ");
				}else{
					$result = $db->select(tbl($this->tbl.',users'),tbl($this->tbl.'.*,users.username AS message_from_user '),
										  tbl($this->tbl).".message_to LIKE '%#$uid#' AND ".tbl("users.userid")." = ".tbl($this->tbl).".message_from 
										  AND ".tbl($this->tbl).".message_box ='in' AND message_type='notification'",NULL," date_added DESC");
				}
			}
		}
		
		if($result)
			return $result;
		else
			return false;
			
	}
	
	function get_user_inbox_messages($uid,$count_only=false){ return $this->get_user_messages($uid,'in',$count_only); }
	function get_user_outbox_messages($uid,$count_only=false){ return $this->get_user_messages($uid,'out',$count_only); }
	function get_user_notification_messages($uid,$count_only=false){ return $this->get_user_messages($uid,'notification',$count_only); }
	
	/**
	 * Function used parse attachments
	 */
	function  parse_attachments($attachment)
	{
		$funcs = $this->pm_attachments_parse;
		if(is_array($funcs))
		foreach($funcs as $func)
		{
			if(function_exists($func))
			{
				$attachments .= $func($attachment);
			}
		}
	}
	
	
	/**
	 * Function used to create PM FORM
	 */
	function load_compose_form()
	{
		$to = post('to');
		$to = $to ? $to : get('to');
		
		$array = array
		(
		 'to'	=>array(
						'title'=> 'to',
						'type'=>'textfield',
						'name'=> 'to',
						'id'=> 'to',
						'value'=> $to,
						//'hint_2'=> "seperate usernames by comma ','",
						'required'=>'yes'
					),
		 'subj'	=>array(
						'title'=> 'Subject',
						'type'=>'textfield',
						'name'=> 'subj',
						'id'=> 'subj',
						'value'=> post('subj'),
						'required'=>'yes'
					),
		 'content'	=>array(
						'title'=> 'content',
						'type'=>'textarea',
						'name'=> 'content',
						'id'=> 'pm_content',
						'value'=> post('content'),
						'required'=>'yes',
						'anchor_before'=>'before_pm_compose_box',
					),
		 
		 
		 );
		
		return array_merge($array,$this->pm_custom_field);
	}
	
	
	/**
	 * Function used to add custom pm field
	 */
	function add_custom_field($array)
	{
		$this->pm_custom_field = array_merge($array,$this->pm_custom_field);
	}
	
	
	/**
	 * Function used to send PM EMAIL
	 */
	function send_pm_email($array)
	{
		global $cbemail,$userquery;
		$sender = $userquery->get_user_field_only($array['from'],'username');
		$content = clean($array['content']);
		$subject = clean($array['subj']);
		$msgid = $array['msg_id'];
		//Get To(Emails)
		$emails = $this->get_users_emails($array['to']);
		$vars =	array
		(
		'{sender}' => $sender,
		'{content}' => $content,
		'{subject}' => $subject,
		'{msg_id}'	=> $msgid
		);
		
		$tpl = $cbemail->get_template($this->email_template);
		$subj = $cbemail->replace($tpl['email_template_subject'],$vars);
		$msg = $cbemail->replace($tpl['email_template'],$vars);
		
		cbmail(array('to'=>$emails,'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg,'nl2br'=>true));
	}
	
	/**
	 * Function used to get emails of users from input
	 */
	 
	function get_users_emails($input)
	{
		global $userquery,$db;
		//check if usernames are sperated by colon ';'
		$input = preg_replace('/;/',',',$input);
		//Now Exploding Input and converting it to and array
		$usernames = explode(',',$input);
		$cond = '';
		foreach($usernames as $user)
		{
			if(!empty($user))
			{
				if(!empty($cond))
					$cond .= " OR ";
				$cond .= " username ='".$user."' ";
			}
		}
		
		$emails = array();
		$results = $db->select(tbl($userquery->dbtbl['users']),'email',$cond);
		foreach($results as $result)
		{
			$emails[] = $result[0];
		}
		
		return implode(',',$emails);
	}
	
	
	/**
	 * Function used to set private message status as read
	 */
	function set_message_status($mid,$status='read')
	{
		global $db;
		if($mid)
			$db->update(tbl($this->tbl),array('message_status'),array($status)," message_id='$mid'");
	}
	
	/**
	 * Function used to delete message from user messages box
	 */
	function delete_msg($mid,$uid,$box='in')
	{
		global $db;
		if($box=='in')
		{
			$inbox = $this->get_inbox_message($mid,$uid);
			if($inbox)
			{
				$inbox_user = $inbox['message_to'];
				$inbox_user = preg_replace("/#".$uid."#/Ui","",$inbox_user);
				if(empty($inbox_user))
					$db->delete(tbl($this->tbl),array("message_id"),array($mid));
				else
					$db->update(tbl($this->tbl),array("message_to"),array($inbox_user)," message_id='".$inbox['message_id']."'  ");
				e(lang('msg_delete_inbox'),'m');
			}
		}else{
			$outbox = $this->get_outbox_message($mid,$uid);
			if($outbox)
			{
				$db->delete(tbl($this->tbl),array("message_id"),array($mid));
				e(lang('msg_delete_outbox'),'m');
			}
		}
	}
	
	
	
	/**
	 * Function used to get new messages
	 */
	function get_new_messages($uid=NULL,$type='pm')
	{
		if(!$uid)
			$uid = userid();
		global $db;
		switch($type)
		{
			case 'pm':
			default:
			{
				$count = $db->count(tbl($this->tbl),"message_id"," message_to LIKE '%#$uid#%' AND message_box='in' AND message_type='pm' AND 	message_status='unread'");
				
			}
			break;
			
			case 'notification':
			default:
			{
				$count = $db->count(tbl($this->tbl),"message_id"," message_to LIKE '%#$uid#%' AND message_box='in' AND message_type='notification' AND message_status='unread'");
			}
			break;
		}
		
		if($count>0)
			return $count;
		else
			return "0";
	}
}

?>