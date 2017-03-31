<?php
	
	/**
	* File: Common Ajax
	* Description: ClipBucket default ajax file has gone too big. Time to make things a little cleaner
	* @since: 4th April, 2016, ClipBucket 2.8.1 
	* @author: Saqib Razzaq
	* @modified: 8th April, 2016
	*/

	require '../includes/config.inc.php';
	if (isset($_POST['mode'])) {
		$mode = $_POST['mode'];
		global $db;
		switch ($mode) {
			case 'emailExists':
				$email = $_POST['email'];
			    $check = $db->select(tbl('users'),"email"," email='$email'");
			    if (!$check) {
			    	echo "NO";
			    }
				break;

			case 'userExists':
				$username = $_POST['username'];
			    $check = $db->select(tbl('users'),"username"," username='$username'");
			    if (!$check) {
			    	echo "NO";
			    }
				break;
			case 'get_video':{
				$response = array();
				try{
					$videoid = $_POST['videoid'];
				    $videoDetails = $cbvid->get_video($videoid);
				    if ( $videoDetails && video_playable($videoDetails) ){
				    	assign('video',$videoDetails);
				    	$related_videos = get_videos(array('title'=>$videoDetails['title'],'tags'=>$videoDetails['tags'],'exclude'=>$videoDetails['videoid'],'show_related'=>'yes','limit'=>12,'order'=>'date_added DESC'));
						if(!$related_videos){
							$related_videos  = get_videos(array('exclude'=>$videoid,'limit'=>12,'order'=>'date_added DESC'));
						}
						foreach ($related_videos as $video){
							$video['imageSrc'] = get_thumb($video,1,FALSE,FALSE,TRUE,FALSE,'168x105');
							$video['url'] = video_link($video);
							$related_videos_temp[] = $video;
						}
						$related_videos = $related_videos_temp;
						assign('related_videos',$related_videos);
						$data = Fetch("blocks/editor_pick/video_block.html");
				    	$response['video'] = $data;

				    	$response['video_link'] = video_link($videoDetails);
				    	$response['video_details'] = $videoDetails;
				    	$response['success'] = true;
				    	$response['message'] = "success";
				    }else{
				    	if(msg()){
							$msg = msg_list();
						}
						if(error()){
							$msg = error_list();
						}
						if (!$msg){
							$msg = "Oops ! Something went worng in Playing this video!";
						}else{
							$msg = $msg[0];
						}
				    	$response['failure'] = true;
						$response['message'] = $msg;
				    }
				}catch(Exception $e){
					$response['failure'] = true;
					$response['message'] = $e->getMessage();
				}
				echo json_encode($response);
				
			}
			break;
			
			default:
				# code...
				break;
		}
	}
?>