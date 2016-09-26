<?php
	require_once '../../includes/admin_config.php';
	$userquery->admin_login_check();
	$userquery->login_check('admin_access');
	$pages->page_redir();

	$ffmpeg_path = $GLOBALS['Cbucket']->configs['ffmpegpath'];	

	require_once '../../includes/classes/video.class.php';
	$cbvid = new CBvideo;
	
	
	// *** Si POST des informations, on continue
	if ( (isset($_POST['thumb_time'])) && (isset($_POST['video_id'])) ){
		
		// *** On conserve les var
		$time = $_POST['thumb_time'];
		$video = $_POST['video_id'];
		$data = get_video_details($video);									// *** Recuperation des informations de la video
		$thumbs = get_thumb($video,1,true,false,false,true,false);			// *** Liste des fichiers thumbnails
		$counter = (get_thumb_num($thumbs[count($thumbs)-1])+1);			// *** Connaitre le prochain element
		$thumbs_settings_28 = thumbs_res_settings_28();						// *** Dimension des vignettes a generer
		
		
		/* ***
		*	On cherche le plus gros fichier video (suppose que c'est la meilleur resolution)
		*
		* 	NB : La fonction "get_high_res_file" ne semble pas operationnelle
		*/
		$file_name = $data['file_name'];
		$file_dir = $data['file_directory'];
		
		// *** Chemin de base de la video
		$file_video = BASEDIR."/files/videos/".$data['file_directory']."/".$data['file_name']."-*";
		
		// *** Nom du plus gros fichier
		$max_video_file_by_size = shell_exec('ls -S '.$file_video.' | head -n 1');
		$max_video_file_by_size = str_replace("\n", "", $max_video_file_by_size);

		
		/* ***
		*	On a toutes les informations, lancement de ffmpeg iminent !
		*/
		// *** Pour chaque dimensions
		foreach ($thumbs_settings_28 as $key => $thumbs_size) {
			
			if ($key == 'original'){
				// *** Nom du fichier final
				$output = THUMBS_DIR.'/'.$file_dir.'/'.$file_name.'-original-'.$counter.'.jpg';
				$command = $ffmpeg_path." -ss ".$time." -i ".$max_video_file_by_size." -f image2 -vframes 1 ".$output;
			}
			else{
				$height_setting = $thumbs_size[1];
				$width_setting = $thumbs_size[0];
				$output = THUMBS_DIR.'/'.$file_dir.'/'.$file_name.'-'.$width_setting.'x'.$height_setting.'-'.$counter.'.jpg';
				$command = $ffmpeg_path." -ss ".$time." -i ".$max_video_file_by_size." -an -r 1 $dimension -y -f image2 -vframes 1 ".$output;
			}

			/* ***
			*	Creation des vignettes
			*/
			$cmd = shell_exec($command);
		}	// *** Fin foreach
		
		
		/* ***
		*	Update BDD pour en faire le choix premier
		*/
		$cbvid->set_default_thumb($video,mysql_clean($counter));
	
	}	// *** Fin si POST
?>