<?php

/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
/**
 **************************************************************************************************
 Mysql Queries are used to perform SQL Queries in DATABASE, Don not edit them this will may cause 
 script not to run properly
 This source file is subject to the ClipBucket End-User License Agreement, available online at:
 http://clip-bucket.com/cbla
 By using this software, you acknowledge having read this Agreement and agree to be bound thereby.
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/
 
 class MassUpload{
 
 				function DataEntry($title,$des,$flv,$tags){
				global $stats;
 				$username = $_SESSION['username'];
				if(empty($username)){
				$username = $_SESSION['username'];
				}
				$activation		= ACTIVATION;
				$title 			= mysql_clean($title);
				$description 	= mysql_clean($des);
				$tags 			= mysql_clean($tags);
				$broadcast 		= $_POST['broadcast'];
				$country		= $_POST['country'];
				$location		= mysql_clean($_POST['location']);
				$date			= $_POST['date'];
				$comments		= $_POST['comments'];
				$comment_voting	= $_POST['comment_voting'];
				$rating			= $_POST['rating'];
				$embedding		= $_POST['embedding'];
				$category01 	= $_POST['category01'];
				$category02 	= $_POST['category02'];
				$category03 	= $_POST['category03'];
				$vkey			= substr(md5(RandomString(10).$title.$tags),1,15);
				
				if($activation == 0){
					$active = 'yes';
					}else{
					$active = 'no';
				}	
				
								
				 mysql_query("INSERT INTO video(
				 title ,
				 videokey ,
				 flv,
				 username ,
				 category01 ,
				 category02 ,
				 category03 ,
				 description,
				 tags,
				 broadcast,
				 country,
				 location,
				 datecreated,
				 allow_comments,
				 comment_voting,
				 allow_rating,
				 allow_embedding,
				 date_added,
				 active,
				 last_viewed
				 
				 )VALUES(
				 
				'".$title."',
				'".$vkey."',
				'".$flv."',
				'".$username."',
				'".$category01."',
				'".$category02."',
				'".$category03."',
				'".$description."',
				'".$tags."',
				'".$broadcast."',
				'".$country."',
				'".$location."',
				'".$date."',
				'".$comments."',
				'".$comment_voting."',
				'".$rating."',
				'".$embedding."',
				now(),
				'".$active."',
				'0000-00-00 00:00:00'
				)");
				
					//Updating Users Number Of  Videos Added By User
					$videos_query 	= mysql_query("SELECT * FROM video WHERE username='".$username."'");
					$videoscount	= mysql_num_rows($videos_query);
					$updatequery = "UPDATE users SET total_videos='".$videoscount."' WHERE username = '".$username."'";
					$stats->UpdateVideoRecord(1);
				}
}
