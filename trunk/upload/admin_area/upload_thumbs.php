<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('video_moderation');
$pages->page_redir();

if(@$_GET['msg']){
$msg[] = clean($_GET['msg']);
}	

$video = mysql_clean($_GET['video']);


//Check Video Exists or Not
if($myquery->VideoExists($video)){
	
	# Setting Default thumb
	if(isset($_POST['update_default_thumb']))
	{
		$myquery->set_default_thumb($video,$_POST['default_thumb']);
	}
	
	$data = get_video_details($video);
	#pr($data,true);
	$vid_file = VIDEOS_DIR.'/'.$data['file_directory'].'/'.get_video_file($data,false,false);
	# Uploading Thumbs
	if(isset($_POST['upload_thumbs'])){
	
		if($data['files_thumbs_path']!=''){

				

				$files_thumbs_path= $data['files_thumbs_path'];
				$serverApi = str_replace('/files/thumbs', '', $files_thumbs_path);
				$serverApi = $serverApi.'/actions/custom_thumb_upload.php';
				
				$file_thumb = $_FILES['vid_thumb']['tmp_name'][0];
				$postvars['mode'] = 'add';
				$postvars['file_thumb'] = "@".$file_thumb;
		        $postvars['files_thumbs_path'] = $files_thumbs_path;
		        $postvars['file_directory'] = $data['file_directory'];
		        $postvars['file_name'] = $data['file_name'];
		        $postvars['duration'] = $data['duration'];					
		        
				$ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, $serverApi);
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); 
			    /* Tell cURL to return the output */
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			     /* Tell cURL NOT to return the headers */
			    curl_setopt($ch, CURLOPT_HEADER, false);
			    $response = curl_exec($ch);
			    /* Check HTTP Code */
			    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			    curl_close($ch);
			    if(!$response)
					e(lang($response),'w');
				elseif((int)($response)){
					e(lang(' remote upload successfully'),'m');
					$query = "UPDATE " . tbl("video") . " SET file_thumbs_count = ".(int)($response)."  WHERE videoid = ".$data['videoid'];
					$db->Execute($query);
				}
				else
					e(lang($response),'e');
			}
			else{
				$Upload->upload_thumbs($data['file_name'],$_FILES['vid_thumb'],$data['file_directory']);
			}
	}
	
//	# Uploading Big Thumb
//	if(isset($_POST['upload_big_thumb'])) {
//		$Upload->upload_big_thumb($data['file_name'],$_FILES['big_thumb']);
//	}
	
	# Delete Thumb
	if(isset($_GET['delete'])){
		$data = get_video_details($video);
		if($data['files_thumbs_path']!=''){
				#pr($data,true);
				$files_thumbs_path= $data['files_thumbs_path'];
				$serverApi = str_replace('/files/thumbs', '', $files_thumbs_path);
				$serverApi = $serverApi.'/actions/custom_thumb_upload.php';
				
				$postvars['mode'] = 'delete';
		        $postvars['files_thumbs_path'] = $files_thumbs_path;
		        $postvars['file_directory'] = $data['file_directory'];
		        $postvars['delete_file_name'] = $_GET['delete'];
		        $postvars['file_name'] = $data['file_name'];
				$ch = curl_init();
			    curl_setopt($ch, CURLOPT_URL, $serverApi);
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); 
			    /* Tell cURL to return the output */
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			     /* Tell cURL NOT to return the headers */
			    curl_setopt($ch, CURLOPT_HEADER, false);
			    $response = curl_exec($ch);
			    /* Check HTTP Code */
			    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			    curl_close($ch);

			    if((int)($response)){
					e(lang($response),'w');
					$query = "UPDATE " . tbl("video") . " SET file_thumbs_count = ".(int)($response)."  WHERE videoid = ".$data['videoid'];
					$db->Execute($query);
				}
				else
					e(lang($response),'e');

		}else
		{
		   delete_video_thumb($data['file_directory'],$_GET['delete']);
		}
	}
	
	# Generating more thumbs
	if(isset($_GET['gen_more']))
	{
		$num = config('num_thumbs');
		$dim = '503x283';
		$big_dim = config('big_thumb_width').'x'.config('big_thumb_height');
		
		require_once(BASEDIR.'/includes/classes/sLog.php');
		$log = new SLog();
        $configs = array();

        require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.class.php');
        $ffmpeg = new FFMpeg($configs, $log);
        $ffmpeg->regenerateThumbs($vid_file,$data['file_directory'],$data['duration'],$dim,$num,$rand=NULL,$is_big=false,$data['file_name']);
        e(lang('Video thumbs has been regenrated successfully'),'m');
        
		

		/*
		  require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.class.php');
	      $ffmpeg = new FFMpeg();
		  //Generating Thumbs
		  $ffmpeg->generate_Thumbs($vid_file,$data['duration'],$dim,$num,$rand=NULL,$is_big=false);
          //Generating Big Thumb
		  $ffmpeg->generate_thumbs($vid_file,$data['duration'],$big_dim,$num,true,true);
	    */
	}
	
	Assign('data',$data);
	Assign('rand',rand(44,444));
			
}else{
	$msg[] = lang('class_vdo_del_err');
}
	



subtitle("Video Thumbs Manager");		
template_files('upload_thumbs.html');
display_it();
?>