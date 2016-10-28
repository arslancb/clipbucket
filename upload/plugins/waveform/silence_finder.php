<?php
	/**
	 *	
	 *	Detect where no audio on video by the image waveform
	 *	/!\ Use Imagemagick (convert command)
	 */
	  if ($_POST['video_id']){
	  
//		$seuil = ($_POST['seuil']) ? (53-$_POST['seuil']) : 53;
		$seuil = ($_POST['seuil']) ? (58-$_POST['seuil']) : 58;
		$width = ($_POST['duration']) ? $_POST['duration'] : 640;
	  
		$command = 'convert -crop '.$width.'x1+0+'.$seuil.' ../../files/waveform/waveform_'.$_POST['video_id'].'.png ../../files/waveform/waveform_silence_'.$_POST['video_id'].'.txt';

		// Execute the commande
		$cmd = shell_exec($command);
		
		if (file_exists('../../files/waveform/waveform_silence_'.$_POST['video_id'].'.txt')){
		  
			// Lit une page web dans un tableau.
			$lines = file('../../files/waveform/waveform_silence_'.$_POST['video_id'].'.txt');
			$tmp = array();
			$memo = 0;
			$nbpxignore = 1;
			$i = 0;

			// Affiche toutes les lignes du tableau comme code HTML, avec les numéros de ligne
			foreach ($lines as $line_num => $line) {
			
			
				if ( (strstr($line, 'white')) or (strstr($line, '#EEEEEE')) ){

// 					echo 'Lines <b>'.$line_num.'</b> : '.$line.'<br>';
				
					$x = substr($line, 0, strpos($line, ","));
					
					if (($memo+1) == $x){
// 					  echo 'delete '.$x.' ?<br>';
					  $nbpxignore++;
					}
					else{
					  $i++;
					  $tmp['silence-'.$i]['pixel'] = intval($x);
					  
					  if ($i == 1){
						  $tmp['silence-'.($i)]['duree'] = $nbpxignore;
					  }
					  else{
						  $tmp['silence-'.($i-1)]['duree'] = $nbpxignore;
					  }
					  
					  $nbpxignore = 1;
					}
					
					$memo = $x;
				}
			}
			
			// push last index
			$tmp['silence-'.($i)]['duree'] = $nbpxignore;
			
			echo json_encode($tmp);
		}
	  }	// End POST
	  
