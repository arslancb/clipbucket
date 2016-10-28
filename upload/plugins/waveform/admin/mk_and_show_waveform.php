<?php
	/**
	*	Demonstration on "How to construct your plugin" in
	*	order to use with the Expand Video Manager plugin
	*/
	
	/**
	*	The $_POST array won't be accessible by the default way.
	*	
	*	Now, all POST and GET values is in the $_POST['data'] sub array.
	*	TODO: Check file from URL
	*/
	if ($_POST['data']['video']){
	
		/**
		*	From here, the code of your plugin, 
		*	make the stuff you want
		*/
	
		// Get information about the video
		$video_details = get_video_details($_POST['data']['video']);

		$width = ($video_details['duration'] > 0) ? round($video_details['duration']) : 640;
		
//		echo '<pre>';
//		print_r($video_details);
//		print_r($width);
//		echo '</pre>';


	
		// Video file directory
		$file_dir = $video_details['file_directory'];
		// Video prefix file name
		$file_name = $video_details['file_name'];
		// *** Output path and filename
		$waveform_folder = BASEDIR.'/files/waveform';
		$waveform_filename = 'waveform_'.$_POST['data']['video'].'.png';

		
		// If FOLDER exist
		if (file_exists($waveform_folder)){
			// If FILE doesn't exist
			if (!file_exists(BASEDIR.'/files/waveform/'.$waveform_filename)){
		
				// Path to the video : it's a filter
				$file_video_filter = BASEDIR.'/files/videos/'.$file_dir.'/'.$file_name.'-*';
			
				// FFMPEG bin executable path
				$ffmpeg_path = $GLOBALS['Cbucket']->configs['ffmpegpath'];
				
				// Real name of one video file (the bigger)
				$max_video_file_by_size = shell_exec('ls -S '.$file_video_filter.' | head -n 1');
				// Delete a line break
				$input = str_replace("\n", "", $max_video_file_by_size);
			
				// Path to write file
				$output = $waveform_folder.'/'.$waveform_filename;
				$command = $ffmpeg_path.' -i '.$input.' -filter_complex "[0:a]aformat=channel_layouts=mono, compand, showwavespic=s='.$width.'x120, colorkey=black, colorchannelmixer=rr=66/255:gr=139/255:br=202/255[fg]; color=s='.$width.'x120:color=#ffffff, drawgrid=width=iw/10:height=ih/5:color=#555555@0.1[bg]; [bg][fg]overlay=format=rgb,drawbox=x=(iw-w)/2:y=(ih-h)/2:w=iw:h=1:color=#2d608b" -frames:v 1 '.$output;

				// Execute the commande
				$cmd = shell_exec($command);
			}	// *** FIN FICHIER EXISTE PAS
		}
		
		// If the file exist, assign the Smarty template var
		if (file_exists(BASEDIR.'/files/waveform/'.$waveform_filename)){
			assign('waveform', BASEURL.'/files/waveform/'.$waveform_filename);
		}

		$duration = $video_details['duration'];
		assign('duration', $duration);
		
		
		assign('video_id', $_POST['data']['video']);
	}

	
	/**
	 *	/!\ Important to use Expand Video Manager
	 *
	 *	Do not display the template, just compute and assign to a variable
	 */
	$mavar = $cbtpl->fetch(PLUG_DIR.'/waveform/admin/waveform.html');
	/**
	 *	Display the variable
	 */
	echo $mavar;
	
	/**
	 *	Note that the template is compute in the Expand Video Manager plugin directory.
	 *	(a "templates_c" folder is created)
	 */
?>
