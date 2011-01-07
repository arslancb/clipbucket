<?php

/**
 * @Software : ClipBucket
 * @Author : Arslan Hassan
 * @Since : Jan 5 2009
 * @Function : Add Member
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 */
 
//setting timeout so that server just dont stuck, right for just 25 seconds
if(!ini_get('safe_mode'))
	set_time_limit(25); 

require'../../includes/admin_config.php';
require_once(BASEDIR.'/includes/classes/conversion/ffmpeg.class.php');
$testVidsDir = ADMINBASEDIR.'/lab_resources/testing_videos';

$userquery->admin_login_check();

if(isset($_POST['experiment']))
{
	$mode = $_POST['mode'];
	$victim = $_POST['victim'];
	
	switch($mode)
	{
		default:
		echo json_encode(array("err"=>"DUDE!!Why The Hell You Are Playing With The Code :/"));
		break;
		
		case "getPreDetails":
		{
			$err = array();
			$msg = array();
			
			if(!function_exists("exec"))
			{
				 $err[] = "<br>C'mon man! there shuld be some 'exec' functions :(";
				 $err[] = "<div class=\"expErr\">Exec function does not exist</div>";
				 theEnd();
			}else
			$msg[] = "<div class=\"expOK\">Exec function exists</div>";
			
			//testing php compatiblity
			$phpTest = shell_output(php_path()." -q ".ADMINBASEDIR.'/lab_resources/echo.php');
			if(!$phpTest)
			{
				$err[] = "<br>OMG! Your host is so chipless that it doesn't even allow you to use PHP CLI , very bad!";
				$err[] = "<div class=\"expErr\">PHP CLI is not enabled</div>";
				theEnd();
			}else
			$msg[] = "<div class=\"expOK\">PHP CLI is enabled</div>";
			
			//now get ffmpeg details
			$ffmpegDetails = shell_output($Cbucket->configs['ffmpegpath']." -version ");
			
			if(!$ffmpegDetails)
			{
				$err[] = '<br>How can human work without brain? same situation is with video conversion, no ffmpeg, no conversion.';
				$err[] = "<div class=\"expErr\">FFMPEG does not exist</div>";
				theEnd();
			}else{
				
				$msg[] = "<br>".nl2br($ffmpegDetails);
				$msg[] = "<div class=\"expOK\">FFMPEG Exists</div>";
			}
			
			$flvtool2Details = shell_output($Cbucket->configs['flvtool2path']." -version ");
			
			if(!$flvtool2Details)
			{
				$err[] = '<br>Ah, no flvtool2 :O, its like a "Salt" for video files..food without salt is tasteless, 
				so video is streamless without flvtool2';
				$err[] = "<div class=\"expErr\">flvtool2 does not exist</div>";
				theEnd();
			}else{
				
				$msg[] = "<br>".nl2br($flvtool2Details);
				$msg[] = "<div class=\"expOK\">Flvtool2 Exists</div>";
			}
			
			$victimFile = $testVidsDir.'/'.$victim;
			//getting video details..
			$ffmpegObj = new ffmpeg($victimFile);
			$vidDetails = $ffmpegObj->get_file_info();
			if(!$vidDetails)
			{
				
				$err[] = "<br>".nl2br(shell_output($Cbucket->configs['ffmpegpath'].' -i '.$victimFile));
				if(!file_exists($victimFile) && $victim)
				$err[] = "<div class=\"expErr\">File does not exist</div>";
				else
				$err[] = "<div class=\"expErr\">What the...post the above ffmpeg log and let scientist do their job</div>";
				theEnd();
			}else
			{
				$msg[] = "<br>".nl2br(shell_output($Cbucket->configs['ffmpegpath'].' -i '.$victimFile));
				$msg[] = "<div class=\"expOK\">Video file is convertable..</div>";
			}
			
			
			theEnd('convertVideo1');
		}
		break;
		
		case "convertVideo1":
		{
			
				
			$victimFile = $testVidsDir.'/'.$victim;
			$victimOutput = $testVidsDir.'/output.flv';
			$victimOutputHQ = $testVidsDir.'/output.mp4';
			
			if(file_exists($victimOutput))
				unlink($victimOutput);
			if(file_exists($testVidsDir.'/output.log'))
				unlink($testVidsDir.'/output.log');


			$res169 = array();
			$res169['240'] = array('427','240');
			$res169['360'] = array('640','360');
			$res169['480'] = array('853','480');
			$res169['720'] = array('1280','1280');
			$res169['1080'] = array('1920','1080');
			
			$res43 = array();
			$res43['240'] = array('320','240');
			$res43['360'] = array('480','360');
			$res43['480'] = array('640','480');
			$res43['720'] = array('960','1280');
			$res43['1080'] = array('1440','1080');
				
			$configs = array
			(
				'use_video_rate' => true,
				'use_video_bit_rate' => true,
				'use_audio_rate' => true,
				'use_audio_bit_rate' => true,
				'use_audio_codec' => true,
				'format' => 'flv',
				'video_codec'=> config('video_codec'),
				'audio_codec'=> config('audio_codec'),
				'audio_rate'=> config("srate"),
				'audio_bitrate'=> config("sbrate"),
				'video_rate'=> config("vrate"),
				'video_bitrate'=> config("vbrate"),
				'normal_res' => config('normal_resolution'),
				'high_res' => config('high_resolution'),
				'max_video_duration' => config('max_video_duration'),
				'res169' => $res169,
				'res43' => $res43,
				'resize'=>'max'
			);
			
			
			
			$ffmpeg = new ffmpeg($victimFile);
			$ffmpeg->configs = $configs;
			$ffmpeg->gen_thumbs = false;
			$ffmpeg->gen_big_thumb = false;
			$ffmpeg->num_of_thumbs = config('num_thumbs');
			$ffmpeg->thumb_dim = config('thumb_width')."x".config('thumb_height');
			$ffmpeg->big_thumb_dim = config('big_thumb_width')."x".config('big_thumb_height');
			$ffmpeg->tmp_dir = TEMP_DIR;
			$ffmpeg->input_ext = $ext;
			$ffmpeg->output_file = $victimOutput;
			$ffmpeg->hq_output_file = $victimOutputHQ;
			$ffmpeg->log_file = $testVidsDir.'/output.log';
			//$ffmpeg->remove_input = TRUE;
			$ffmpeg->keep_original = config('keep_original');
			$ffmpeg->original_output_path = ORIGINAL_DIR.'/'.$tmp_file.'.'.$ext;
			$ffmpeg->set_conv_lock = false;
			$ffmpeg->showpre=true;
			$ffmpeg->ClipBucket();
			

			$ffmpegpath = $Cbucket->configs['ffmpegpath'];
			$ffmpegCommand = $ffmpeg->raw_command;
			$msg[] = "FFMPEG Command So far<br><textarea class='code' onClick='$(this).select()'>$ffmpegCommand</textarea>";
			//Lets start conversing videos
			$converDetails = shell_output($ffmpegCommand);
			$msg[] = "<br>".nl2br($ffmpeg->log);
			
			$victimDetails = $ffmpeg->input_details;
			$vidDetails = $ffmpeg->output_details;
			if(!$vidDetails)
			{
				
				if($victimDetails['audio_codec']=='aac' || $victimDetails['audio_codec']=='ac3')
				$err[] = "<div class=\"expErr\">A possible reason is beacuse videos with 
				AAC Audio does not encode without 'libfaac', set audio codec as libfaac and then try again</div>";
				if(!file_exists($victimFile) && $victim)
				$err[] = "<div class=\"expErr\">No output file...your ffmpeg is not compatible</div>";
				else
				$err[] = "<div class=\"expErr\">No output file...hmm post the log to dev team</div>";
				if(@filesize($victimOutput)>0 && file_exists($victimFile))
				$err[] = "<br>".nl2br(shell_output($Cbucket->configs['ffmpegpath'].' -i '.$victimOutput));
				theEnd();
			}else
			{
				$msg[] = "<br>".nl2br(shell_output($Cbucket->configs['ffmpegpath'].' -i '.$victimOutput));
				$msg[] = "<div class=\"expOK\">Video file is converted =D..</div>";
			}
			
			theEnd();
		}
	}
}



function theEnd($status=false)
{
	global $msg, $err;
	if($err)
	$errors = implode('',$err);
	if($msg)
	$messgs = implode('',$msg);
	
	echo json_encode(array('err'=>$errors,'msg'=>$messgs,'status'=>$status));
	 exit(); 
}
?>