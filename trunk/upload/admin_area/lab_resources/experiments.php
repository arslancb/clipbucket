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
			if(file_exists($victimOutput))
				unlink($victimOutput);
			$ffmpeg = $Cbucket->configs['ffmpegpath'];
			$ffmpegCommand = $ffmpeg." -i $victimFile -f flv ".$victimOutput;
			$msg[] = "FFMPEG Command So far<br><pre class='code'>$ffmpegCommand</pre>";
			//Lets start conversing videos
			$converDetails = shell_output($ffmpegCommand);
			$msg[] = "<br>".nl2br($converDetails);
			//now lets get details of video
			$ffmpegObj = new ffmpeg($victimOutput);
			$vidDetails = $ffmpegObj->get_file_info();
			if(!$vidDetails)
			{
				
				if(!file_exists($victimFile) && $victim)
				$err[] = "<div class=\"expErr\">No output file...your ffmpeg is not compatible</div>";
				else
				$err[] = "<div class=\"expErr\">No output file...hmm post the log to dev team</div>";
				if(filesize($victimOutput)>0)
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