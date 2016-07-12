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
		//$ip = $_SERVER['REMOTE_ADDR'];
		$ipv = $this->get_local_ipv4();
		if($ipv['eth0']){
			$ip = $ipv['eth0'];
		}
		if($ipv['wlan0']){
			$ip = $ipv['wlan0'];
		}
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$userid = getArrayValue($a, 'userid');
		$username = $a['username'];
		$useremail = getArrayValue($a, 'useremail');
		$userlevel = getArrayValue($a, 'userlevel');
		
		$action_obj_id = getArrayValue($a, 'action_obj_id');
		$action_done_id = getArrayValue($a, 'action_done_id');
		
		$userid = $userid ? $userid : $userquery->udetails['userid'];
		$username = $username ? $username : $userquery->udetails['username'];
		$useremail = $useremail ? $useremail : $userquery->udetails['email'];
		$userlevel = $userlevel ? $userlevel : getArrayValue($userquery->udetails, 'level');
		
		$success = $a['success'];
		$details = getArrayValue($a, 'details');
		 
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

	 function get_local_ipv4() {
  		$out = split(PHP_EOL,shell_exec("/sbin/ifconfig"));
		$local_addrs = array();
		$ifname = 'unknown';
		foreach($out as $str) {
		   $matches = array();
		    if(preg_match('/^([a-z0-9]+)(:\d{1,2})?(\s)+Link/',$str,$matches)) {
		      $ifname = $matches[1];
		      if(strlen($matches[2])>0) {
		        $ifname .= $matches[2];
		      }
		    } elseif(preg_match('/inet addr:((?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3})\s/',$str,$matches)) {
		      $local_addrs[$ifname] = $matches[1];
		    }
		  }
		  return $local_addrs;
		}
	 
}

?>