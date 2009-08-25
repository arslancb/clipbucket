<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
 This source file is subject to the ClipBucket End-User License Agreement, available online at:
 http://clip-bucket.com/cbla
 By using this software, you acknowledge having read this Agreement and agree to be bound thereby.
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
*/
 
class Stats {

//This Variable will be used to store all stats of the websites
var $stats;
		
		function Stats($refresh=false){
			$query = mysql_query("SELECT * FROM stats");
			while($data = mysql_fetch_array($query)){
				$details[$data['name']] = $data['value'];
			}
			$this->stats = $details;
			if($refresh=true){
			$this->Refresh();
			}
		}
		
		//FUNCTION USED TO REFRESH STATS DATA
		function Refresh(){
			$today = date("Y-m-d");
			$query = mysql_query("SELECT * FROM users WHERE doj like '%$today%'");
			$array['today_signups'] = mysql_num_rows($query);
			$query = mysql_query("SELECT * FROM users WHERE last_logged like '%$today%'");
			$array['todays_logins'] = mysql_num_rows($query);
			$query = mysql_query("SELECT * FROM video WHERE date_added like '%$today%'");
			$array['videos_added_today'] = mysql_num_rows($query);
			
			$month = date("Y-m");
			//if(date("Y-m",strtotime($this->stats['last_update'])) != $month ){
			$query = mysql_query("SELECT * FROM users WHERE last_logged like '%$month%'");
			$array['months_logins'] = mysql_num_rows($query);
			$query = mysql_query("SELECT * FROM video WHERE date_added like '%$month%'");
			$array['videos_added_this_month'] = mysql_num_rows($query);
			//}
			
			$query = mysql_query("SELECT videoid, count(*) AS flags FROM flagged_videos GROUP BY videoid ORDER BY flags DESC ");
			$array['total_flagged_videos'] = mysql_num_rows($query);
			
			mysql_query("UPDATE stats SET value='".$array['today_signups']."' WHERE name='today_signups'");
			mysql_query("UPDATE stats SET value='".$array['todays_logins']."' WHERE name='todays_logins'");
			mysql_query("UPDATE stats SET value='".$array['videos_added_today']."' WHERE name='videos_added_today'");
			mysql_query("UPDATE stats SET value='".$array['months_logins']."' WHERE name='months_logins'");
			mysql_query("UPDATE stats SET value='".$array['videos_added_this_month']."' WHERE name='videos_added_this_month'");
			mysql_query("UPDATE stats SET value='".$array['total_flagged_videos']."' WHERE name='total_flagged_videos'");
			mysql_query("UPDATE stats SET value=now() WHERE name='last_update'");
			$this->UpdateGroupRecord();
			$query = mysql_query("SELECT * FROM stats");
			while($data = mysql_fetch_array($query)){
				$details[$data['name']] = $data['value'];
			}
			
			if($query)
			mysql_free_result($query);
			$this->stats = $details;
		}
		
		//FUNCTION USED TO UPDATE USER RECORD
		function UpdateUserRecord($type=1){
			$today = date("Y-m-d");
			$month = date("Y-m");
			switch($type){
			//TYPE 1 , INCREASE TOTAL USERS
			case 1;
			$update = $this->stats['total_users'] + 1;
			break;
			//TYPE 2 , Decrease TOTAL USERS
			case 2;
			$update = $this->stats['total_users'] - 1;
			break;
			//Type 3, Update Users Signup Today
			case 3;
			$query = mysql_query("SELECT * FROM users WHERE doj like '%$today%'");
			$array['today_logins'] = mysql_num_rows($query);
			mysql_query("UPDATE stats SET value='".$array['today_logins']."' WHERE name='today_signups'");
			break;
			//Type 4, Update Users Login Today
			case 4;
			$query = mysql_query("SELECT * FROM users WHERE last_logged like '%$today%'");
			$array['today_logins'] = mysql_num_rows($query);
			mysql_query("UPDATE stats SET value='".$array['today_logins']."' WHERE name='todays_logins'");
			break;
			//Type 5, Update Users Login This Month
			case 5;
			$query = mysql_query("SELECT * FROM users WHERE last_logged like '%$month%'");
			$array['months_logins'] = mysql_num_rows($query);
			mysql_query("UPDATE stats SET value='".$array['months_logins']."' WHERE name='months_logins'");
			break;
			//Type 6, Update Number of total user comments
			case 6;
			$update = $this->stats['total_user_comments'] + 1;
			if(!mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_user_comments'"))die(mysql_error());
			break;
			}
			
			if($type<=2){
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_users'");
			}
			
			if(@$query)
			mysql_free_result($query);
		}
		
		
		//FUNCTION USED TO UPDATE VIDEO RECORD
		function UpdateVideoRecord($type=1){
			switch($type){
			//Case1: Increase 1 Video
			case 1;
			$update = $this->stats['total_videos'] + 1;
			break;
			case 2;
			$update = $this->stats['total_videos'] - 1;
			break;
			case 3;
			$update = $this->stats['total_video_comments'] + 1;
			break;
			case 4;
			$update = $this->stats['total_video_comments'] - 1;
			break;
			case 5;
			$update = $this->stats['total_watched_videos'] + 1;
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_watched_videos'");
			break;
			case 6;
			$update = $this->stats['total_added_favorites'] + 1;
			break;
			case 7;
			$update = $this->stats['total_added_favorites'] - 1;
			break;
			case 8;
			$update = $this->stats['total_success_videos'] + 1;
			$update2 = $this->stats['total_videos_processing'] - 1;
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_success_videos'");
			mysql_query("UPDATE stats SET value = '".$update2."' WHERE name='total_videos_processing'");
			break;
			case 9;
			$update = $this->stats['total_videos_processing'] + 1;
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_videos_processing'");
			break;
			case 10;
			$update = $this->stats['total_failed_videos'] + 1;
			$update2 = $this->stats['total_videos_processing'] - 1;
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_failed_videos'");
			mysql_query("UPDATE stats SET value = '".$update2."' WHERE name='total_videos_processing'");
			break;
			case 11;
			$update = $this->stats['total_active_videos'] + 1;
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_active_videos'");
			break;
			case 12;
			$update = $this->stats['total_active_videos'] - 1;
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_active_videos'");
			break;
			}

			if($type<=2){
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_videos'");
			}
			if($type==4 || $type==3){
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_video_comments'");
			}
			if($type==6 || $type==7){
			mysql_query("UPDATE stats SET value = '".$update."' WHERE name='total_added_favorites'");
			}
			
			if(@$query)
			mysql_free_result($query);
			
		}
		
		//Update Group Record
		function UpdateGroupRecord(){
		$details = $this->GetGroupStats();
			mysql_query("UPDATE stats SET value='".$details['total'				]."' WHERE name='total_groups'");
			mysql_query("UPDATE stats SET value='".$details['total_topics'		]."' WHERE name='total_topics'");
			mysql_query("UPDATE stats SET value='".$details['total_posts'		]."' WHERE name='total_posts'");
			mysql_query("UPDATE stats SET value='".$details['group_invitations'	]."' WHERE name='group_invitations'");
			mysql_query("UPDATE stats SET value='".$details['groups_added_today']."' WHERE name='groups_added_today'");
			mysql_query("UPDATE stats SET value='".$details['groups_added_month']."' WHERE name='groups_added_month'");
			mysql_query("UPDATE stats SET value='".$details['group_members'		]."' WHERE name='group_members'");		
		}
		
		//FUNCTIONS USED TO GET USER STATSS
		function GetUsersStats(){
			//Query of total users
			$query = mysql_query("SELECT * FROM users");
				$array['total'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Query For Active Users Only
			$query = mysql_query("SELECT * FROM users WHERE usr_status='Ok'");
				$array['active'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Query For Todays Signups
			$today = date("Y-m-d");
			$query = mysql_query("SELECT * FROM users WHERE doj like '%$today%'");
				$array['today_singups'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Query for todays Logins
			$query = mysql_query("SELECT * FROM users WHERE last_logged like '%$today%'");
				$array['today_logins'] = mysql_num_rows($query);
			//Query for Active Users in This Month
			$month = date("Y-m");
			$query = mysql_query("SELECT * FROM users WHERE last_logged like '%$month%'");
				$array['months_logins'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Query For counting comments on user profiles
			$query = mysql_query("SELECT * FROM channel_comments");
				$array['total_comments'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
				
			
			return $array;
		}
	
		//FUNCTION USED TO GET VIDEO DETAILS
		function GetVideoStats(){
			$today = date("Y-m-d");
			$month = date("Y-m");
			//Query User To Get Total Videos
			$query = mysql_query("SELECT * FROM video");
				$array['total'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Query User To Get Total Active Videos
			$query = mysql_query("SELECT * FROM video WHERE active='yes'");
				$array['total_active'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Query User To Get FlaggedVideos
			
			$query = mysql_query("SELECT videoid, count(*) AS flags FROM flagged_videos GROUP BY videoid ORDER BY flags DESC ");
				$array['total_flags'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Videos Added Today
			$query = mysql_query("SELECT * FROM video WHERE date_added like '%$today%'");
				$array['added_today'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			
			$query = mysql_query("SELECT * FROM video WHERE date_added like '%$month%'");
			 	$array['added_this_month'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			
			//Total Videos Watched
			$query = mysql_query("SELECT views FROM video ");
			$views = 0;
			while($data = mysql_fetch_array($query)){
				@$views = $views + $data['views'];
			}
			 	$array['total_watched'] = $views;
			//Used To Count Comment Of Videos
			$query = mysql_query("SELECT * FROM video_comments");
				$array['total_comments'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Used To Count Number Videos in Favourites
			$query = mysql_query("SELECT * FROM video_favourites");
				$array['total_favorites'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Get Total Processed Videos
			$query = mysql_query("SELECT * FROM video WHERE status='Processing'");
				$array['total_videos_processing'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Get Total Successfull Videos
			$query = mysql_query("SELECT * FROM video WHERE status='Successful'");
				$array['total_success_videos'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			//Get Total Failed Videos
			$query = mysql_query("SELECT * FROM video WHERE status='Failed'");
				$array['total_failed_videos'] = mysql_num_rows($query);
			if($query)
			mysql_free_result($query);
			return $array;
		}
		
		
		//Function Used Get Group Details
		function GetGroupStats($type=false){
			$today = date("Y-m-d");
			$month = date("Y-m");
			if($type==FALSE){
			//Used To Get Total Number Of Groups
			$query = mysql_query("SELECT * FROM groups");
				$array['total'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
			//Used To Get Total Number Of Topics
			$query = mysql_query("SELECT * FROM group_topics");
				$array['total_topics'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
			//Used To Get Total Number Of Posts
			$query = mysql_query("SELECT * FROM group_posts");
				$array['total_posts'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
			//Used To Get Total Number Of Invitations
			$query = mysql_query("SELECT * FROM group_invitations");
				$array['group_invitations'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
			//Groups Added Today
			$query = mysql_query("SELECT * FROM groups WHERE date_added like '%$today%'");
				$array['groups_added_today'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
			//Groups Added This Month
			$query = mysql_query("SELECT * FROM groups WHERE date_added like '%$month%'");
				$array['groups_added_month'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
			//Total Members
			$query = mysql_query("SELECT * FROM group_members");
				$array['group_members'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
			}else{
				switch($type){
				case 1;
				//Used To Get Total Number Of Groups
				$query = mysql_query("SELECT * FROM groups");
					$array['total'] = mysql_num_rows($query);
					if($query)
			mysql_free_result($query);
				break;
				
				case 2;
				//Used To Get Total Number Of Topics
				$query = mysql_query("SELECT * FROM group_topics");
					$array['total_topics'] = mysql_num_rows($query);
					if($query)
			mysql_free_result($query);
				break;
				
				case 3;
				//Used To Get Total Number Of Posts
				$query = mysql_query("SELECT * FROM group_posts");
					$array['total_posts'] = mysql_num_rows($query);
					if($query)
			mysql_free_result($query);
				break;
				
				case 4;
				//Used To Get Total Number Of Invitations
				$query = mysql_query("SELECT * FROM group_invitations");
					$array['group_invitations'] = mysql_num_rows($query);
					if($query)
			mysql_free_result($query);
				break;
				
				case 5;
				//Groups Added Today
				$query = mysql_query("SELECT * FROM groups WHERE date_added like '%$today%'");
					$array['groups_added_today'] = mysql_num_rows($query);
					if($query)
			mysql_free_result($query);
				break;
				
				case 6;
				//Groups Added This Month
				$query = mysql_query("SELECT * FROM groups WHERE date_added like '%$month%'");
					$array['groups_added_today'] = mysql_num_rows($query);
					if($query)
			mysql_free_result($query);
				break;
				
				case 7;
				//Total Members
				$query = mysql_query("SELECT * FROM group_members");
				$array['group_members'] = mysql_num_rows($query);
				if($query)
			mysql_free_result($query);
				break;
				
				}
			}
				
				
			return $array;
		}
		
		
		//Use To Get Database Stats
		function ServerDetails(){
			global $db;
			$status['mysql_ver'] = mysql_get_server_info();
    		$result = mysql_query( "SHOW TABLE STATUS" );
  			$dbsize = 0;
   			while( $row = mysql_fetch_array( $result ) ) {  
			$dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];
    		}
			$status['db_size'] = formatfilesize($dbsize);
			$status['disk_space'] = formatfilesize(disk_total_space(BASEDIR));
			$status['space_free'] = formatfilesize(disk_free_space(BASEDIR));
			$status['space_used'] = formatfilesize(disk_total_space(BASEDIR)-disk_free_space(BASEDIR));
			$status['php_ver'] = PHP_VERSION;
			return  $status ;
		}
		
		//Function Force Update
		//FUNCTION USED TO COUNT THE DETAIL FROMT THE TABLES 
		//1 by 1
		function __FORCEUPDATE__(){
			//Update User Stats
			$user_details = $this->GetUsersStats();
			mysql_query("UPDATE stats SET value='".$user_details['total'			]."' WHERE name='total_users'");
			mysql_query("UPDATE stats SET value='".$user_details['active'			]."' WHERE name='total_active_users'");
			mysql_query("UPDATE stats SET value='".$user_details['today_singups'	]."' WHERE name='today_signups'");
			mysql_query("UPDATE stats SET value='".$user_details['today_logins'		]."' WHERE name='todays_logins'");
			mysql_query("UPDATE stats SET value='".$user_details['months_logins'	]."' WHERE name='months_logins'");
			mysql_query("UPDATE stats SET value='".$user_details['total_comments'	]."' WHERE name='total_user_comments'");
			
			//Update Video Stats
			$video_details = $this->GetVideoStats();
			mysql_query("UPDATE stats SET value='".$video_details['total'			]."' WHERE name='total_videos'");
			mysql_query("UPDATE stats SET value='".$video_details['total_active'	]."' WHERE name='total_active_videos'");
			mysql_query("UPDATE stats SET value='".$video_details['total_flags'		]."' WHERE name='total_flagged_videos'");
			mysql_query("UPDATE stats SET value='".$video_details['added_today'		]."' WHERE name='videos_added_today'");
			mysql_query("UPDATE stats SET value='".$video_details['added_this_month']."' WHERE name='videos_added_this_month'");
			mysql_query("UPDATE stats SET value='".$video_details['total_watched'	]."' WHERE name='total_watched_videos'");
			mysql_query("UPDATE stats SET value='".$video_details['total_comments'	]."' WHERE name='total_video_comments'");
			mysql_query("UPDATE stats SET value='".$video_details['total_favorites'	]."' WHERE name='total_added_favorites'");
			mysql_query("UPDATE stats SET value='".$video_details['total_videos_processing']."' WHERE name='total_videos_processing'");
			mysql_query("UPDATE stats SET value='".$video_details['total_success_videos']."' WHERE name='total_success_videos'");
			mysql_query("UPDATE stats SET value='".$video_details['total_failed_videos']."' WHERE name='total_failed_videos'");
		
		}
		
		function UpdateDate(){
		mysql_query("UPDATE stats SET value=now() WHERE name='date_updated'");
		}

}

?>