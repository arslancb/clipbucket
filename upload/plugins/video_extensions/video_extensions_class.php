<?php

// Global Object $videoExtension is used in the plugin
$videoExtension = new VideoExtension();
$Smarty->assign_by_ref('videoExtension', $videoExtension);

/**
 * Contains all actions that can affect the  video_extensions plugin 
 */
class VideoExtension extends CBCategory{
	
	/**
	 * Constructor for VideoExtension's instances
	 */
	function VideoExtension($vid)	{
	}

	/**
	 * Create a minimal empty video entry into the database
	 *
	 */
	function addEmptyVideo(){
		global $db, $cbvid, $Upload;
		$title 	= lang("new_empty_video");
		$file_name	= "".time().RandomString(5);
		
		#filename <<<<<$tmp="".strtotime($v['date_added']);
		
		$file_directory = createDataFolders();
		$vidDetails = array	(
			'title' => $title,
			'description' => $title,
			'tags' => genTags(str_replace(' ',', ',$title)),
			'category' => array($cbvid->get_default_cid()),
			'file_name' => $file_name,
			'file_directory' => $file_directory,
			'userid' => userid(),
			'video_version' => '2.7',
			'allow_comments' => "no",
			'comment_voting' => "no",
			'allow_rating' => "no",
		);
		$vid = $Upload->submit_upload($vidDetails);
		// inserting into video views as well
		$query = "INSERT INTO " . tbl("video_views") . " (video_id, video_views, last_updated) VALUES({$vid}, 0, " . time() . ")";
		$db->Execute($query);
		
		return $vid;
	}
	
	/**
	 * Duplicate a video 
	 *
	 *	@param array $vid
	 *		The id of the video data to be duplicated
	 */
	function duplicateVideo($vid){
		global $db,$cbplugin;
		// Create an empty video data in the video table
		$newvid=$this->addEmptyVideo();
		// copy the data form origin video to destination video
		$arr=array("video_password","video_users","title","flv","description","tags","category",
				"category_parents","broadcast","location","country","allow_embedding","allow_comments","comment_voting",
				"allow_rating");
		$query = "UPDATE ".tbl("video")." dst,  ".tbl("video")." src SET ";
		foreach ($arr as $entry){
			$query.=" dst.".$entry."=src.".$entry.", ";
		}
		$query= substr($query,0,-2);
		$query.=" WHERE src.videoid=".$vid." AND dst.videoid=".$newvid.";";
		$db->Execute($query);

		// link the new video to the same discipline as the other
		if ($cbplugin->is_installed('discipline.php')) {
			$query = "UPDATE ".tbl("video")." dst,  ".tbl("video")." src SET dst.discipline=src.disciline";
			$db->Execute($query);
		}
		
		// link the new video to the same speakers as the other
		if ($cbplugin->is_installed('speaker.php')) {
			$query="INSERT INTO ".tbl("video_speaker")." (`video_id`,`speakerfunction_id`) SELECT ".$newvid.", speakerfunction_id FROM ".tbl("video_speaker")." WHERE video_id=".$vid.";";
			$db->Execute($query);
		}
		
		// link the new video to the same video grouping as the other
		if ($cbplugin->is_installed('video_grouping.php')) {
			$query="INSERT INTO ".tbl("video_grouping")." (`video_id`,`vdogrouping_id`) SELECT ".$newvid.", vdogrouping_id FROM ".tbl("video_grouping")." WHERE video_id=".$vid.";";
			$db->Execute($query);
		}

		// link the new video to the same external links as the other
		if ($cbplugin->is_installed('external_links.php')) {
			$query="INSERT INTO ".tbl("video_links")." (`video_id`,`link_id`) SELECT ".$newvid.", link_id FROM ".tbl("video_links")." WHERE video_id=".$vid.";";
			$db->Execute($query);
		}

		// link the new video to the same documents as the other
		if ($cbplugin->is_installed('documents.php')) {
			$query="INSERT INTO ".tbl("video_documents")." (`video_id`,`document_id`) SELECT ".$newvid.", document_id FROM ".tbl("video_documents")." WHERE video_id=".$vid.";";
			$db->Execute($query);
		}
		
	}
	
	
	/**
	 * Get a list of all video in /files.pending_videos folder that ar not yet linked to a video data
	 * This metohd search subfolders of /files.pending_videos. Each of them must be named 
	 * like a jobset value into the job database table. For each of these folders, the method request the job table
	 * in order to find if a jobset of the same name exists and if the idvideo of the jobs with this jobset are NULL or not.
	 * If the idvideo is NULL then the subfolder containing videos is really pending and the returned array is concatenated 
	 *	@return array
	 *		An array containing for each pending folder, the corresponding jobset (equal to the folder name) and the original video name.
	 */
	function getPendingVideos(){
		$PendingVideos=array();
		global $db;
		$pendingfolders = glob(VIDEO_EXTENSIONS_PENDING_VIDEOS_DIR.'/*'); // get all /files/pendigd_videos subfolders
		foreach($pendingfolders as $folder){ // iterate on folders
			if (is_dir($folder)){
				$jobset=pathinfo($folder,PATHINFO_FILENAME);
				$query='SELECT * FROM '.table("job").' WHERE jobset="'.$jobset.'" AND idvideo IS NULL';
				$result=$db->_select($query);
				if (count($result)>0){
					$originalVideoName=pathinfo($result[0]["src"],PATHINFO_BASENAME);
					$data=array("jobset"=> $result[0]["jobset"],
							"originalVideoName" => $originalVideoName);
					$PendingVideos[] = $data; 
				}
			}
		}
		return $PendingVideos;
	}
	
	/**
	 * 
	 */
	function setVideoFile($vid, $jobset){
		global $db;
		$query='SELECT * FROM '.table("job").' WHERE jobset="'.$jobset.'" AND idvideo IS NULL';
		$result=$db->_select($query);
		if (count($result)>0){
			$originalVideoName=pathinfo($result[0]["src"],PATHINFO_BASENAME);
			$query='UPDATE '.table("job").' SET idvideo = '.$vid.' wHERE jobset="'.$jobset.'" AND idvideo IS NULL';
			$db->Execute($query);
			$query='UPDATE '.table("video").' SET original_videoname = "'.$originalVideoName.'" WHERE videoid="'.$vid.'"';
			$db->Execute($query);
		}
		
	}
}

?>