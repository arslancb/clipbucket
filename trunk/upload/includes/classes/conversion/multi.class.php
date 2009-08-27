<?php
/**
 **************************************************************************************************
 Class : FFMpeg
 Don Not Edit This Class , It May cause your script not to run properly
 This source file is subject to the ClipBucket End-User License Agreement, available online at:
 http://www.clip-bucket.com/eula.html
 By using this software, you acknowledge having read this Agreement and agree to be bound thereby.
 **************************************************************************************************
 Copyright (c) 2007 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/
if (function_exists('set_time_limit') AND get_cfg_var('safe_mode')==0)
{
	@set_time_limit(0);
} 
 define('ENCODING_LOGGING','yes');
class ffmpeg
{

			//Special Function Used TO get duration
			function ChangeTime($duration,$rand = null){
                if($rand != null)
                {
				if($duration / 3600 > 1 ){
				$time = date("H:i:s",$duration - rand(0,$duration));
				}else{
				$time = "00:";
				$time .= date("i:s",$duration - rand(0,$duration));
				}
				return $time;
                }

                if($rand == null)
                {
				if($duration / 3600 > 1 ){
				$time = date("H:i:s",$duration);
				}else{
				$time = "00:";
				$time .= date("i:s",$duration);
				}
				return $time;
                }
			}


			//THIS FUNCTION IS USED TO GENERATE THUMBS

			function AssignGeneratedThumbs($flv,$duration,$rand = null)
			{

			$filename_minus_ext = substr($flv, 0, strrpos($flv, '.'));
			$thumbnail_output_dir = BASEDIR.'/files/thumbs';
			$ffmpeg 		= FFMPEG_BINARY;
			$flv_file		= BASEDIR.'/files/videos/'.$flv;

			if($duration > 14 ){
				$duration = $duration - 5;
				//Setting oF Thumbs Duration
				$division = $duration / 3;
				$count=1;
				for($id=3;$id<=$duration;$id++){
				$id 	= $id +$division-1;
                if($rand != null)
                {
				$time 	= $this->ChangeTime($id,1);
                }
                if($rand == null)
                {
				$time 	= $this->ChangeTime($id);
                }
				$command = "$ffmpeg -i $flv_file -an -ss $time -an -r 1 -s 120x90 -y -f image2 -vframes 1 $thumbnail_output_dir/$filename_minus_ext-$count.jpg";
				exec("$command",$output);
				$count = $count+1;
				}
				}else{
				$command = "$ffmpeg -i $flv_file -an -s 120x90 -y -f image2 -vframes 3 $thumbnail_output_dir/$filename_minus_ext-%d.jpg";
				exec($command,$output);
				}
				$command2 = "$ffmpeg -i $flv_file -an -s 320x240 -y -f image2 -vframes 1 $thumbnail_output_dir/$filename_minus_ext-big.jpg";
				exec($command2,$output2);

			 //debugging
					$debug_1 = $command . "\n";//file line of debug
					$debug_1 .= $command2 . "\n";//file line of debug
					foreach ($output as $outputline) {
						$debug_1 = $debug_1 . $outputline . "\n";
						if ($debugmodex == 1) {//no debug mode
							echo ("$outputline<br>");
						}
					}
			if (ENCODING_LOGGING == "yes") {
						//check if file exists
						$file_contents = 'ClipBucket debug' . "\n" . $command . "\n" . $command2 . "\n".
							'Commands were executed.See rest of log for output details' . "\n" .
							'=================================================================' . "\n";
						$log_file = BASEDIR."/logs/logs.txt";
						if (@file_exists($log_file)) {//append to log file
							$fo = @fopen($log_file, 'a');
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
						else {
							$fo = @fopen($log_file, 'w');//else create new log
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
					}
			}
			
			//THIS FUNCTION IS USED TO GENERATE DEFAULT THUMBS
			function AssignDefaultThumb($flv){
			
			//Minus Extension
			$filename_minus_ext = substr($flv, 0, strrpos($flv, '.'));
			$proccesing_thumb = TEMPLATEDIR.'/images/'.LANG.'/processing.png';
			$proccesing_thumb_big = TEMPLATEDIR.'/images/'.LANG.'/processing-big.png';
			copy($proccesing_thumb,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-1.jpg');
			copy($proccesing_thumb,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-2.jpg');
			copy($proccesing_thumb,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-3.jpg');	
			copy($proccesing_thumb_big,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-big.jpg');
			
			}
			
			//THIS FUNCTION IS USED TO VALIDATE FILETYPE
			//THIS FUNCTION IS USED TO VALIDATE FILETYPE
			function ValidateFile($file) {
				global $row;
				$ph = substr($file, strrpos($file,'.') + 1);
				$ph = strtolower($ph); // Added line to fix case
				$types = strtolower($row['allowed_types']);
				$types_array = preg_replace('/,/',' ',$types);
				$types_array = explode(' ',$types_array);
				foreach($types_array as $type) {
					$return = false;
					if($type == $ph) {
						$return = true;
						break;
					}
				}
				return $return;
			}
			
			//THE REAL ENCODING GOES HERE
			function ConvertFile($file,$flv){
				
				$video_file = BASEDIR.'/files/temp/'.$file;
				$flv_file = BASEDIR.'/files/videos/'.$flv;
				
				if($this->ValidateFile($file)){
			
				$mencoder 		= FFMPEG_MENCODER_BINARY;
				$mplayer 		= FFMPEG_MPLAYER_BINARY;
				$flvtool2 		= FFMPEG_FLVTOOLS_BINARY;
				$vbrate 		= VBRATE;
				$srate			= SRATE;
				$r_height		= R_HEIGHT;
				$ffmpeg 		= FFMPEG_BINARY;
				$r_width		= R_WIDTH;
				$resize			= RESIZE;
				$keep_original	= KEEP_ORIGINAL;
				$max_size		= MAX_UPLOAD_SIZE;
				
				
				//Check VIdeo File Size
				$size = @filesize($flv_file);
				if($size > $max_size*1024*1024){
				$status = "Failed";
				$flv_file = "failed.flv";
				}else{
				
				$scale = "";
                $f_scale = "";
				if($resize == 'yes'){	
						$scale = "scale=".$r_width.":".$r_height;
						$f_scale = "-s ".$r_width."x".$r_height;
				}
					$extension = substr($file, strrpos($file,'.') + 1);
					
					
				
				//LOG THAT THIS PAGE WAS LOADED (debugging for CLI)
				if (ENCODING_LOGGING == "yes") {
					//check if file exists
					$file_contents = "\n\n\n\n" . 'ClipBucket Conversion debug' . "\n" .
						'CLI for convertor OK' . "\n" . date("l, F d, Y / h:i:s A T (O)") . "\n" .
						'================================================================================' .
						"\n";
					$log_file = BASEDIR."/logs/logs.txt";
					if (@file_exists($log_file)) {//append to log file
						$fo = @fopen($log_file, 'a');
						@fwrite($fo, $file_contents);
						@fclose($fo);
					}
					else {
						$fo = @fopen($log_file, 'w');//else create new log
						@fwrite($fo, $file_contents);
						@fclose($fo);
					}
				}

					/////////////////////////////////////////////////////////////
					//                        STEP 1                           //
					//                  encode video to flv                    //
					/////////////////////////////////////////////////////////////
	
						//This Will Check The Extension of the uploaded video and checks its encoding command against each case
					$extension = strtolower($extension);
					switch($extension){
					
					//For WMV FILES
					case 'wmv';
					//For High Quality Video
					//$command  = "$ffmpeg -i $video_file -ab 64 -ar 44100 -b 300k -r 30 -s 720x480 -sameq $flv_file";
					$command = "$ffmpeg -i $video_file  -ab 32 -ar $srate $f_scale $flv_file";
					@exec("$command",$output);
					
					if(!file_exists($flv_file)){
					$command = "$ffmpeg -i $video_file -ar $srate -ab 32 -f flv $f_scale  $flv_file";
					@exec("$command",$output);
					}
					 //debugging
					$debug_1 = $command . "\n";//file line of debug
					foreach ($output as $outputline) {
						$debug_1 = $debug_1 . $outputline . "\n";
						if ($debugmodex == 1) {//no debug mode
							echo ("$outputline<br>");
						}
					}
					
					if (ENCODING_LOGGING == "yes") {
						//check if file exists
						$file_contents = 'ClipBucket debug' . "\n" . $command . "\n" .
							'Command was executed.See rest of log for output details' . "\n" .
							'=================================================================' . "\n";
						$log_file = BASEDIR."/logs/logs.txt";
						if (@file_exists($log_file)) {//append to log file
							$fo = @fopen($log_file, 'a');
							@fwrite($fo, $file_contents);


							@fclose($fo);
						}
						else {
							$fo = @fopen($log_file, 'w');//else create new log
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
					}
			
					break;
					
					//For Avi Files
					case 'avi';
                    if($resize == 'yes')
                    {
					$command = "$ffmpeg -i $video_file  -ab 32 -ar $srate $f_scale $flv_file";
                    }
                    else
                    {
                    $command = "$ffmpeg -i $video_file  -ab 32 -ar $srate $flv_file";
                    }
					@exec("$command",$output);
					
					if(!file_exists($flv_file)){
                    if($resize == 'yes')
                    {
					$command = "$ffmpeg -i $video_file -ar $srate -ab 32 -f flv $f_scale $flv_file";
                    }
                    else
                    {
                    $command = "$ffmpeg -i $video_file -ar $srate -ab 32 -f flv $flv_file";
                    }
					@exec("$command",$output);
					}
					
					 //debugging
					$debug_1 = $command . "\n";//file line of debug
					foreach ($output as $outputline) {
						$debug_1 = $debug_1 . $outputline . "\n";
						if ($debugmodex == 1) {//no debug mode
							echo ("$outputline<br>");
						}
					}
					
					if (ENCODING_LOGGING == "yes") {
						//check if file exists
						$file_contents = 'ClipBucket debug' . "\n" . $command . "\n" .
							'Command was executed.See rest of log for output details' . "\n" .
							'=================================================================' . "\n";
						$log_file = BASEDIR."/logs/logs.txt";
						if (@file_exists($log_file)) {//append to log file
							$fo = @fopen($log_file, 'a');
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
						else {
							$fo = @fopen($log_file, 'w');//else create new log
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
					}
					
					break;
					
					//For FLV Files
					case 'flv';
					copy($video_file,$flv_file);
					break;
					
					
					default:
					//the following can be changed (vbitrate, vop scale, SRATE)
					
                    if($resize == 'yes')
                    {
					$command = "$mencoder $video_file -o $flv_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts 	vcodec=flv:vbitrate=$vbrate:mbd=2:mv0:trell:v4mv:keyint=10:cbp:last_pred=3 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames -vf $scale -srate $srate";
                    }
                    else
                    {
                    $command = "$mencoder $video_file -o $flv_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts 	vcodec=flv:vbitrate=$vbrate:mbd=2:mv0:trell:v4mv:keyint=10:cbp:last_pred=3 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames -srate $srate";
                    }
					@exec("$command",$output);
	
	   
					//If no flv was created. Attempt to convert with -vop swicth and not -vf
					if(!file_exists($flv_file)){
                    if($resize == 'yes')
                    {
					$command = "$mencoder $video_file -o $flv_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts vcodec=flv:vbitrate=$vbrate:mbd=2:mv0:trell:v4mv:keyint=10:cbp:last_pred=3 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames -vop $scale -srate $srate";
                    }
                    else
                    {
                    $command = "$mencoder $video_file -o $flv_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts vcodec=flv:vbitrate=$vbrate:mbd=2:mv0:trell:v4mv:keyint=10:cbp:last_pred=3 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames -srate $srate";
                    }
					 @exec("$command",$output);
					}
	
					  //If no flv was created. Attempt to convert with no -lavcopts i_certify_etc_etc
					if(!file_exists($flv_file)){
                    if($resize == 'yes')
                    {
					$command = "$mencoder $video_file -o $flv_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts vcodec=flv:vbitrate=$vbrate:mbd=2:mv0:trell:v4mv:keyint=10:cbp:last_pred=3 -vf $scale -srate $srate";
                    }
                    else
                    {
                    $command = "$mencoder $video_file -o $flv_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts vcodec=flv:vbitrate=$vbrate:mbd=2:mv0:trell:v4mv:keyint=10:cbp:last_pred=3 -srate $srate";
                    }
					@exec("$command",$output);
					}
					}
					
/*					 //debugging
					$debug_1 = @$command . "\n";//file line of debug
					foreach ($output as $outputline) {
						$debug_1 = $debug_1 . $outputline . "\n";
						if ($debugmodex == 1) {//no debug mode
							echo ("$outputline<br>");
						}
					}*/
					
					if (ENCODING_LOGGING == "yes") {
						//check if file exists
						$file_contents = 'ClipBucket debug' . "\n" . @$command . "\n" .
							'Command was executed.See rest of log for output details' . "\n" .
							'=================================================================' . "\n";
						$log_file = BASEDIR."/logs/logs.txt";
						if (@file_exists($log_file)) {//append to log file
							$fo = @fopen($log_file, 'a');
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
						else {
							$fo = @fopen($log_file, 'w');//else create new log
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
					}
					
					/////////////////////////////////////////////////////////////
					//                        STEP 2                           //
					//                  FLVTOOL2 INJECTION                     //
					/////////////////////////////////////////////////////////////;
					$flv_cmd = "$flvtool2 -U $flv_file";
					@exec("$flv_cmd 2>&1", $output);
				
					//debugging
					$debug_2 = $flv_cmd . "\n";//file line of debug
					foreach ($output as $outputline) {
						$debug_2 = $debug_2 . $outputline . "\n";
				
						if ($debugmodex == 1) {//no debug mode
							echo ("$outputline<br>");
						}
					}
					
					if (ENCODING_LOGGING== "yes") {
						//check if file exists
						$file_contents = 'ClipBucket debug' . "\n" . $flv_cmd . "\n" .
							'Command was executed.See rest of log for output details' . "\n" .
							'================================================================================' .
							"\n";
						$log_file = BASEDIR."/logs/logs.txt";
						if (@file_exists($log_file)) {//append to log file
							$fo = @fopen($log_file, 'a');
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
						else {
							$fo = @fopen($log_file, 'w');//else create new log
							@fwrite($fo, $file_contents);
							@fclose($fo);
						}
					}
					
				/////////////////////////////////////////////////////////////
				//                        STEP 3                           //
				//                  get video duration          			//
				/////////////////////////////////////////////////////////////
				//Try and read the time from the output files
/*				$shell_output = $debug_1 . $debug_2;//get as much sheel out put as possible to run search for duration
				if (@preg_match('/Video stream:.*bytes..(.*?).sec/', $shell_output, $regs)) {
					$sec = $regs[1];
					$duration = $sec;//covert to 00:00:00 i.e. hrs:min:sec
					$sec = date("s", strtotime($duration));//change back to seconds for use in getting middle of video
				}
				else {
					$sec = "";
					$duration = $sec;
				}
			
				//check if duration has been picked up...if not try second method  (ffmpeg -i video.flv)
				if ($sec == "" || !is_numeric($sec)) {
					$check_duration = $ffmpeg . ' -i ' . $flv_file;
					$durationfile = "";
					@exec("$check_duration 2>&1", $durationoutput);
			
					foreach ($durationoutput as $outputline) {
						$durationfile = $durationfile . $outputline . "\n";
			
					}
			
					if (preg_match('/uration:.(.*?)\./', $durationfile, $regs)) {
						$duration = $regs[1];//duration already in 00:00:00 format
						$sec = date("s", strtotime($duration));//change back to seconds for use in getting middle of video
						$duration = $sec ;
					}
			
				}*/
				
				//The Final Step, If Duration Still has not been picked up
				if (@$sec == "" || !is_numeric(@$sec) || @$sec == "00") {
						exec("$mplayer -vo null -ao null -frames 0 -identify $flv_file", $p);
    					while(list($k,$v)=each($p))
    						{
        	  			  if($length=strstr($v,'ID_LENGTH='))
        	    			break;
    						}
    					$lx = explode("=",$length);
						$duration = round($lx[1]);
						$sec = $duration ;
    			}
			
				//LOG THAT STEP 3 was ok
				if (ENCODING_LOGGING == "yes") {
				$main_duration  = $duration;
					//check if file exists
					$file_contents = 'ClipBucket debug' . "\n" .
						'MPLAYER - check - Video Duration = ' . $sec. "\n" .
						'================================================================================' .
						"\n";
					$log_file = BASEDIR."/logs/logs.txt";
					if (@file_exists($log_file)) {//append to log file
						$fo = @fopen($log_file, 'a');
						@fwrite($fo, $file_contents);
						@fclose($fo);
					}
					else {
						$fo = @fopen($log_file, 'w');//else create new log
						@fwrite($fo, $file_contents);
						@fclose($fo);
					}
				}
			
			
						if($keep_original == 1){
						$original_file = BASEDIR.'/files/original/'.$file;
						copy($video_file,$original_file);
						}
	
						$status = "Successful";
				
						
						//If MAX SIZE CONDITION ENDs	
						mysql_query("INSERT INTO video_detail(flv,status,duration,original) VALUES ('".$flv."','".$status."','".$sec."','".$file."')");
						mysql_query("UPDATE video SET duration='".$sec."' , status='".$status."' WHERE flv = '".$flv."'");
						
						
						//$this->AssignDefaultThumb($flv);
						$this->AssignGeneratedThumbs($flv,$sec);
					}
					}
						unlink($video_file);
						if(!file_exists($flv_file)){
						$status = "Failed";
						$this->AssignDefaultThumb($flv);						
						mysql_query("UPDATE video_detail SET status='".$status."' WHERE flv = '".$flv."'");
						mysql_query("UPDATE video SET active='no'  AND status='".$status."' WHERE flv = '".$flv."'");
						}
						
				//LOG FINAL 
				if (ENCODING_LOGGING == "yes") {
				$main_duration  = $duration;
					//check if file exists
					$file_contents = 'ClipBucket debug' . "\n" .
						'Video PROCESS Status = ' . $status. "\n" .
						'================================================================================' .
						"\n";
					$log_file = BASEDIR."/logs/logs.txt";
					if (@file_exists($log_file)) {//append to log file
						$fo = @fopen($log_file, 'a');
						@fwrite($fo, $file_contents);
						@fclose($fo);
					}
					else {
						$fo = @fopen($log_file, 'w');//else create new log
						@fwrite($fo, $file_contents);
						@fclose($fo);
					}
				}
				}
				
}
 				
/*
$ffmpeg = new ffmpeg();
$file = "920657135.WMV";
$flv = "920657135.flv";
$ffmpeg->ConvertFile($file,$flv);
*/

?>