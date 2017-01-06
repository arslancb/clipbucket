<?php
	require_once '../../includes/admin_config.php';
	$userquery->admin_login_check();
	$userquery->login_check('admin_access');
	$pages->page_redir();

	$ffmpeg_path = $GLOBALS['Cbucket']->configs['ffmpegpath'];	

	require_once '../../includes/classes/video.class.php';
	$cbvid = new CBvideo;
	
	
	
	
	
	// Si POST des informations, on continue
	if ( (isset($_POST['thumb_time'])) && (isset($_POST['video_id'])) ){
		
		// *** On conserve les var
		$time = $_POST['thumb_time'];
		$video = $_POST['video_id'];
		$data = get_video_details($video);					// Recuperation des informations de la video
		
		
		
		$thumbs = get_thumb($video,1,true,false,false,true,false);		// Liste des fichiers thumbnails
		$counter = (get_thumb_num($thumbs[count($thumbs)-1])+1);		// Connaitre le prochain element
		$thumbs_settings_28 = thumbs_res_settings_28();				// Dimension des vignettes a generer
		
		
		/**
		 *	On cherche le plus gros fichier video (suppose que c'est la meilleur resolution)
		 *
		 * 	NB : La fonction "get_high_res_file" ne semble pas operationnelle
		 */
		$file_name = $data['file_name'];
		$file_dir = $data['file_directory'];
		
		// *** Chemin de base de la video
		$file_video = BASEDIR."/files/videos/".$data['file_directory']."/".$data['file_name']."-*";
		
		
		
		// Nom du plus gros fichier
		$max_video_file_by_size = shell_exec('ls -S '.$file_video.' | head -n 1');
		$max_video_file_by_size = str_replace("\n", "", $max_video_file_by_size);


		if (trim($max_video_file_by_size) <> ''){
		
			/**
			 *	On a toutes les informations, lancement de ffmpeg iminent !
			 */
			// Pour chaque dimensions
			foreach ($thumbs_settings_28 as $key => $thumbs_size) {
				
				if ($key == 'original'){
					// Nom du fichier final
					$output = THUMBS_DIR.'/'.$file_dir.'/'.$file_name.'-original-'.$counter.'.jpg';
					$command = $ffmpeg_path." -ss ".$time." -i ".$max_video_file_by_size." -f image2 -vframes 1 ".$output;
				}
				else{
				
					$original = THUMBS_DIR.'/'.$file_dir.'/'.$file_name.'-original-'.$counter.'.jpg';
					$height_setting = $thumbs_size[1];
					$width_setting = $thumbs_size[0];
					$output = THUMBS_DIR.'/'.$file_dir.'/'.$file_name.'-'.$width_setting.'x'.$height_setting.'-'.$counter.'.jpg';
				
					// Pour eviter d'utiliser le fichier video
					if (file_exists($original)){
						$command = $ffmpeg_path." -i ".$original." -vf scale=".$width_setting.":".$height_setting." ".$output;
					}
					else{

						$command = $ffmpeg_path." -ss ".$time." -i ".$max_video_file_by_size." -vf scale=".$width_setting.":".$height_setting." -an -r 1 -y -f image2 -vframes 1 ".$output;

					}
				}

				/**
				 *	Creation des vignettes
				 */
				$cmd = shell_exec($command);
			}	// Fin foreach
			
			
			/**
			 *	Update BDD pour en faire le choix premier
			 */
			$cbvid->set_default_thumb($video,mysql_clean($counter));
		}
		else{
			if ($data['remote_play_url'] <> ''){
				//echo 'La videos est a l\'adresse : '.$data['remote_play_url'];
				
				$curl_path = shell_exec("which curl");
				
				if ($curl_path){
				
					/**
					*	On a toutes les informations, lancement de ffmpeg iminent !
					*/
					// Pour chaque dimensions
					foreach ($thumbs_settings_28 as $key => $thumbs_size) {
						//echo $key.' => '.$thumbs_size.'<br>';
						

						if ($key == 'original'){
							// Nom du fichier final
							$output = THUMBS_DIR.'/'.$file_name.'-original-'.$counter.'.jpg';

							$command = "curl --silent " . $data['remote_play_url'] . " | " . $ffmpeg_path." -ss ".$time." -i pipe:0 -f image2 -vframes 1 ".$output;
							
							$cmd = shell_exec($command);
							

							/**
							*	Update BDD pour en faire le choix premier
							*/
							$cbvid->set_default_thumb($video,mysql_clean($counter));
						}
						else{
						// TODO : Generer les autres formats de vignettes ; Bug a corriger enchainement des commandes relance curl a chaque fois.
						
						/*
							$height_setting = $thumbs_size[1];
							$width_setting = $thumbs_size[0];
							
							$original = THUMBS_DIR.'/'.$file_name.'-original-'.$counter.'.jpg';
							$output = THUMBS_DIR.'/'.$file_name.'-'.$width_setting.'x'.$height_setting.'-'.$counter.'.jpg';

							// Pour ne faire qu'un curl
							if (file_exists($original)){
								$command = $ffmpeg_path." -i ".$original." -vf scale=".$width_setting.":".$height_setting." ".$output;
							}
							else{
								$command = "curl --silent " . $data['remote_play_url'] . " | " . $ffmpeg_path." -ss ".$time." -i pipe:0 -vf scale=".$width_setting.":".$height_setting." -an -r 1 -y -f image2 -vframes 1 ".$output;
							}
						*/
						}

						/**
						*	Creation des vignettes
						*/
						//$cmd = shell_exec($command);
						//echo '<pre>'.$command.'</pre>';
					}	// Fin foreach
				
				}
				else{
				
					$protocol = substr($data['remote_play_url'], 0, strpos($data['remote_play_url'], ":"));
					//echo $protocol;
					$protocol_enabled = shell_exec($ffmpeg_path." -protocols | grep ".$protocol);
				}
			}
			else{
				echo 'Le fichier source n\'existe pas.';
			}
		}
	
	}	// Fin si POST
?>