<?php
	/**
	 *	Detect where no audio on video by the image waveform
	 *	/!\ Use Imagemagick (convert command)
	 */
	  if ($_POST['video_id']){
	  
		$seuil = ($_POST['seuil']) ? (53-$_POST['seuil']) : 53;
	  
		$command = 'convert -crop 640x1+0+'.$seuil.' ../../files/waveform/waveform_'.$_POST['video_id'].'.png ../../files/waveform/waveform_silence_'.$_POST['video_id'].'.txt';

		// Execute the commande
		$cmd = shell_exec($command);
		
		if (file_exists('../../files/waveform/waveform_silence_'.$_POST['video_id'].'.txt')){
		  
			// Lit une page web dans un tableau.
			$lines = file('../../files/waveform/waveform_silence_'.$_POST['video_id'].'.txt');
			$tmp = array();
			$memo = 0;

			// Affiche toutes les lignes du tableau comme code HTML, avec les numéros de ligne
			foreach ($lines as $line_num => $line) {
				if ( (strstr($line, 'white')) or (strstr($line, '#EEEEEE')) ){
				
					$x = substr($line, 0, strpos($line, ","));
					
					if (($memo+1) == $x){
//					  echo 'delete '.$x.' ?<br>';
					}
					else{
					  $tmp[] = intval($x);
					}
					
					$memo = $x;
				}
			}
			
			echo json_encode($tmp, JSON_FORCE_OBJECT);
		}
	  }	// End POST
	  
