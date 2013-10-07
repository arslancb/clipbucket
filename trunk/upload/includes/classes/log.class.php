<?php

/**
 * This class is used to keep details of each action done on a website using Clipbucket
 * this will manage log of each possible action 
 * @ Author : Arslan Hassan
 * @ since : June 14, 2009
 * @ ClipBucket : v2.x
 * @ License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * logging types
 * - login
 * - signup
 * - upload_video
 * - add_group
 * - add_friend
 * - video_comment
 * - profile_comment
 * - profile_update
 * - add_playlist
 * - add_topic
 * - subscribe
 * - add_favorite
 */


class CBLogs
{
	
	
	/**
	 * Function used to insert log
	 * @param VARCHAR $type, type of action
	 * @param ARRAY $details_array , action details array
	 */
	function insert($type,$details_array)
	{
		global $db,$userquery;
		$a = $details_array;
		$ip = $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$userid = $a['userid'];
		$username = $a['username'];
		$useremail = $a['useremail'];
		$userlevel = $a['userlevel'];
		
		$action_obj_id = $a['action_obj_id'];
		$action_done_id = $a['action_done_id'];
		
		$userid = $userid ? $userid : $userquery->udetails['userid'];
		$username = $username ? $username : $userquery->udetails['username'];
		$useremail = $useremail ? $useremail : $userquery->udetails['email'];
		$userlevel = $userlevel ? $userlevel : $userquery->udetails['level'];
		
		$success = $a['success'];
		$details = $a['details'];
		 
		$db->insert(tbl('action_log'),
		array
		(
		'action_type',
		'action_username',
		'action_userid',
		'action_useremail',
		'action_ip',
		'date_added',
		'action_success',
		'action_details',
		'action_userlevel',
		'action_obj_id',
		'action_done_id',
		),
		array
		(
		$type,
		$username,
		$userid ,
		$useremail,
		$ip,
		NOW(),
		$success,
		$details,
		$userlevel,
		$action_obj_id,
		$action_done_id
		)
		);
					  
	 }
	 
}

?>