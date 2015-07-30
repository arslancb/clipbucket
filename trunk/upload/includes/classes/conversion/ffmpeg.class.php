<?php
define('FFMPEG_BINARY', get_binaries('ffmpeg'));
define("thumbs_number",config('num_thumbs'));
define('PROCESSESS_AT_ONCE',config('max_conversion'));

$size12 = "0";
class FFMpeg{
	private $command = "";
	public $defaultOptions = array();
	public $videoDetails = array();
	public $num = thumbs_number;
	private $options = array();
	private $outputFile = false;
	private $inputFile = false;
	private $conversionLog = "";
	public $ffMpegPath = FFMPEG_BINARY;
	private $mp4BoxPath = MP4Box_BINARY;
	private $flvTool2 = FLVTool2_BINARY;
	private $videosDirPath = VIDEOS_DIR;
	private $logDir = "";
	public $log = "";
	public $logs = "";
	private $logFile = "";
	private $sdFile = false;
	private $hdFile = false;
	public $file_name = "";
	private $resolution16_9 = array(
		'240' => array('428','240'),
		'360' => array('640','360'),
		'480' => array('854','480'),
		'720' => array('1280','720'),
		'1080' => array('1920','1080'),
		);
	// this is test comment

	private $resolution4_3 = array(
		'240' => array('428','240'),
		'360' => array('640','360'),
		'480' => array('854','480'),
		'720' => array('1280','720'),
		'1080' => array('1920','1080'),
		);

	/*
	Coversion command example
	/usr/local/bin/ffmpeg 
	-i /var/www/clipbucket/files/conversion_queue/13928857226cc42.mp4  
	-f mp4  
	-vcodec libx264 
	-vpre normal 
	-r 30 
	-b:v 300000 
	-s 426x240 
	-aspect 1.7777777777778 
	-vf pad=0:0:0:0:black 
	-acodec libfaac 
	-ab 128000 
	-ar 22050  
	/var/www/clipbucket/files/videos/13928857226cc42-sd.mp4  
	2> /var/www/clipbucket/files/temp/139288572277710.tmp
	*/

	public function __construct($options = false, $log = false){
		$this->setDefaults();
		if($options && !empty($options)){
			$this->setOptions($options);
		}else{
			$this->setOptions($this->defaultOptions);
		}
		if($log) $this->log = $log;
		$str = "/".date("Y")."/".date("m")."/".date("d")."/";
		//$this->logs->writeLine("in class", "ffmpeg");
		$this->logDir = BASEDIR . "/files/logs/".$str;
	}
	function exec( $cmd ) {
		# use bash to execute the command
		# add common locations for bash to the PATH
		# this should work in virtually any *nix/BSD/Linux server on the planet
		# assuming we have execute permission
		//$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\" ";
		return shell_exec( $cmd);
	}
	function parse_format_info( $output ) {
		$this->raw_info;
		$info =  $this->raw_info;
		# search the output for specific patterns and extract info
		# check final encoding message
		if($args =  $this->pregMatch( 'Unknown format', $output) ) {
			$Unkown = "Unkown";
		} else {
			$Unkown = "";
		}
		if( $args = $this->pregMatch( 'video:([0-9]+)kB audio:([0-9]+)kB global headers:[0-9]+kB muxing overhead', $output) ) {
			$video_size = (float)$args[1];
			$audio_size = (float)$args[2];
		} else {
			return false;
		}

		# check for last enconding update message
		if($args =  $this->pregMatch( '(frame=([^=]*) fps=[^=]* q=[^=]* L)?size=[^=]*kB time=([^=]*) bitrate=[^=]*kbits\/s[^=]*$', $output) ) {
			$frame_count = $args[2] ? (float)ltrim($args[2]) : 0;
			$duration    = (float)$args[3];
		} else {
			return false;
		}

		
		if(!$duration)
		{
			$duration = $this->pregMatch( 'Duration: ([0-9.:]+),', $output );
			$duration    = $duration[1];
			
			$duration = explode(':',$duration);
			//Convert Duration to seconds
			$hours = $duration[0];
			$minutes = $duration[1];
			$seconds = $duration[2];
			
			$hours = $hours * 60 * 60;
			$minutes = $minutes * 60;
			
			$duration = $hours+$minutes+$seconds;
		}

		$info['duration'] = $duration;
		if($duration)
		{
			$info['bitrate' ] = (integer)($info['size'] * 8 / 1024 / $duration);
			if( $frame_count > 0 )
				$info['video_rate']	= (float)$frame_count / (float)$duration;
			if( $video_size > 0 )
				$info['video_bitrate']	= (integer)($video_size * 8 / $duration);
			if( $audio_size > 0 )
				$info['audio_bitrate']	= (integer)($audio_size * 8 / $duration);
				# get format information
			if($args =  $this->pregMatch( "Input #0, ([^ ]+), from", $output) ) {
				$info['format'] = $args[1];
			}
		}

		# get video information
		if(  $args= $this->pregMatch( '([0-9]{2,4})x([0-9]{2,4})', $output ) ) {
			
			$info['video_width'  ] = $args[1];
			$info['video_height' ] = $args[2];
			
		/*	if( $args[5] ) {
				$par1 = $args[6];
				$par2 = $args[7];
				$dar1 = $args[8];
				$dar2 = $args[9];
				if( (int)$dar1 > 0 && (int)$dar2 > 0  && (int)$par1 > 0 && (int)$par2 > 0 )
					$info['video_wh_ratio'] = ( (float)$dar1 / (float)$dar2 ) / ( (float)$par1 / (float)$par2 );
			}
			
			
			# laking aspect ratio information, assume pixel are square
			if( $info['video_wh_ratio'] === 'N/A' )*/
				$info['video_wh_ratio'] = (float)$info['video_width'] / (float)$info['video_height'];
		}
		
		if($args= $this->pregMatch('Video: ([^ ^,]+)',$output))
		{
			$info['video_codec'  ] = $args[1];
		}

		# get audio information
		if($args =  $this->pregMatch( "Audio: ([^ ]+), ([0-9]+) Hz, ([^\n,]*)", $output) ) {
			$audio_codec = $info['audio_codec'   ] = $args[1];
			$audio_rate = $info['audio_rate'    ] = $args[2];
			$info['audio_channels'] = $args[3];
		}
		
		if(!$audio_codec || !$audio_rate)
		{
			$args =  $this->pregMatch( "Audio: ([a-zA-Z0-9]+)(.*), ([0-9]+) Hz, ([^\n,]*)", $output);
			$info['audio_codec'   ] = $args[1];
			$info['audio_rate'    ] = $args[3];
			$info['audio_channels'] = $args[4];
		}
		
		
		$this->raw_info = $info;
		# check if file contains a video stream
		return $video_size > 0;

		#TODO allow files with no video (only audio)?
		#return true;
	}
	public function get_file_info( $path_source =NULL) {
		
		if(!$path_source)
		$path_source = $this->input_file;

		# init the info to N/A
		$info['format']			= 'N/A';
		$info['duration']		= 'N/A';
		$info['size']			= 'N/A';
		$info['bitrate']		= 'N/A';
		$info['video_width']	= 'N/A';
		$info['video_height']	= 'N/A';
		$info['video_wh_ratio']	= 'N/A';
		$info['video_codec']	= 'N/A';
		$info['video_rate']		= 'N/A';
		$info['video_bitrate']	= 'N/A';
		$info['video_color']	= 'N/A';
		$info['audio_codec']	= 'N/A';
		$info['audio_bitrate']	= 'N/A';
		$info['audio_rate']		= 'N/A';
		$info['audio_channels']	= 'N/A';
		$info['path']			= $path_source;

		# get the file size
		$stats = @stat( $path_source );
		if( $stats === false )
		$this->log .= "Failed to stat file $path_source!\n";
		$info['size'] = (integer)$stats['size'];
		$this->ffmpeg." -i $path_source -acodec copy -vcodec copy -f null /dev/null 2>&1";
		$this->raw_output = $output = $this->exec( $this->ffmpeg." -i $path_source -acodec copy -vcodec copy -y -f null /dev/null 2>&1" );

		$this->raw_info = $info;
		# parse output
		if( $this->parse_format_info( $output ) === false )
		return false;
		$info = $this->raw_info;
		return $info;
	}
	public function convertVideo($inputFile = false, $options = array(), $isHd = false){
		$this->startLog($this->getInputFileName($inputFile));
		//logData($inputFile);
		//$this->log->newSection("Video Conversion", "Starting");
		if($inputFile){
			if(!empty($options)){
				$this->setOptions($options);
			}
			$this->inputFile = $inputFile;
       		//$myfile = fopen("testfile.txt", "w")
       		//fwrite($myfile, $inputFile);
			$this->logs->writeLine("input file", $inputFile);
			$this->outputFile = $this->videosDirPath . '/'. $this->options['outputPath'] . '/' . $this->getInputFileName($inputFile);
			$this->logs->writeLine("outputFile", $this->outputFile);
			$videoDetails = $this->getVideoDetails($inputFile);
			//logData(json_encode($videoDetails));
			$this->videoDetails = $videoDetails;
			$this->logs->writeLine("videoDetails", $videoDetails);
			$this->output = new stdClass();
			$this->output->videoDetails = $videoDetails;

			//$this->logs->writeLine("Thumbs Generation", "Starting");
			try{
				$this->generateThumbs($this->inputFile, $videoDetails['duration']);
			}catch(Exception $e){
				$this->logs->writeLine("Errot Occured", $e->getMessage());
			}

			/*
				Low Resolution Conversion Starts here
			*/
			$this->logs->newSection("Low Resolution Conversion");

			$this->convertToLowResolutionVideo($videoDetails);
			/*
				High Resoution Coversion Starts here
			*/
			//logData(json_encode("end function"));
			$this->logs->writeLine("videoDetails", $videoDetails);

		}else{
			//$this->//logData("no input file");
			//logData(json_encode("no input file"));
		}
	}

	private function convertToLowResolutionVideo($videoDetails = false){
		
		if($videoDetails)
		{
			//logData(json_encode($videoDetails));
			$this->hdFile = "{$this->outputFile}-hd.{$this->options['format']}";
			$out= shell_exec($this->ffMpegPath ." -i {$this->inputFile} -acodec copy -vcodec copy -y -f null /dev/null 2>&1");
			$len = strlen($out);
			$findme = 'Video';
			$findme1 = 'fps';
			$pos = strpos($out, $findme);
			$pos = $pos + 48;
			$pos1 = strpos($out, $findme1);
			$bw = $len - ($pos1 - 5);
			$rest = substr($out, $pos, -$bw);
			$rest = ','.$rest;
			$dura = explode(',',$rest);
			$dura[1] = $dura[1].'x';
			$dura = explode('x',$dura[1]);
			if($dura[1] >= "720" || $videoDetails['video_height'] >= "720")
			{
				
				$this->logs->writeLine("Generating low resolution video", "Starting");
				$this->sdFile = "{$this->outputFile}-sd.{$this->options['format']}";
				$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, false) . " {$this->sdFile}";

				$this->logs->writeLine("command", $fullCommand);

				$conversionOutput = $this->executeCommand($fullCommand);
				$this->logs->writeLine("ffmpeg output", $conversionOutput);
				
				$this->logs->writeLine("MP4Box Conversion for SD", "Starting");
				$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->sdFile}  -tmp ".TEMP_DIR;
				if (PHP_OS == "WINNT")
				{
					$fullCommand = str_replace("/","\\",$fullCommand);	
				}
				$this->logs->writeLine("command", $fullCommand);
				$output = $this->executeCommand($fullCommand);
				$this->logs->writeLine("output", $output);
				
				if (file_exists($this->sdFile))
				{
					$this->sdFile1 = "{$this->outputFile}.{$this->options['format']}";
					$path = explode("/", $this->sdFile1);
					$name = array_pop($path);
					$name = substr($name, 0, strrpos($name, "."));
					$status = "Successful";
					$this->logs->writeLine("Conversion Result", 'conversion_status : '.$status);

				
				}
				$this->logs->newSection("High Resolution Conversion");
				$this->logs->writeLine("Generating high resolution video", "Starting");
				$this->hdFile = "{$this->outputFile}-hd.{$this->options['format']}";
				$log_file_tmp = TEMP_DIR."/".$this->file_name.".tmp";
				$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, true) . " {$this->hdFile} > {$log_file_tmp}";
				
				$this->logs->writeLine("Command", $fullCommand);
				//logData(json_encode($fullCommand));
				$conversionOutput = $this->executeCommand($fullCommand);
				if(file_exists($log_file_tmp))
				{
					$data = file_get_contents($log_file_tmp);
					unlink($log_file_tmp);
				}
				$this->logs->writeLine("ffmpeg output", $data);

				$this->logs->writeLine("MP4Box Conversion for HD", "Starting");
				$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->hdFile}  -tmp ".TEMP_DIR;
				//logData(json_encode($fullCommand));
				if (PHP_OS == "WINNT")
				{
					$fullCommand = str_replace("/","\\",$fullCommand);	
				}
				$this->logs->writeLine("command", $fullCommand);
				$output = $this->executeCommand($fullCommand);
				$this->logs->writeLine("output", $output);
				if (file_exists($this->hdFile))
				{

					$this->sdFile1 = "{$this->outputFile}.{$this->options['format']}";
					$path = explode("/", $this->sdFile1);
					$name = array_pop($path);
					$name = substr($name, 0, strrpos($name, "."));
					//logData(json_encode($this->sdFile1));
					$status = "Successful";
					$this->logs->writeLine("Conversion Result", 'conversion_status : '.$status);

				
				}
			}
			else
			{

				$this->logs->writeLine("Generating low resolution video", "Starting");
				$this->sdFile = "{$this->outputFile}-sd.{$this->options['format']}";
				$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, false) . " {$this->sdFile}";
				//logData(json_encode($videoDetails));
				$this->logs->writeLine("command", $fullCommand);

				$conversionOutput = $this->executeCommand($fullCommand);
				//logData(json_encode($fullCommand));

				$this->logs->writeLine("ffmpeg output", $conversionOutput);
				
				$this->logs->writeLine("MP4Box Conversion for SD", "Starting");
				$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->sdFile}  -tmp ".TEMP_DIR;
				//logData(json_encode($fullCommand));
				if (PHP_OS == "WINNT")
				{
					$fullCommand = str_replace("/","\\",$fullCommand);	
				}
				$this->logs->writeLine("command", $fullCommand);
				$output = $this->executeCommand($fullCommand);
				$this->logs->writeLine("output", $output);
				if (file_exists($this->sdFile))
				{
					$this->sdFile1 = "{$this->outputFile}.{$this->options['format']}";
					//logData(json_encode($this->sdFile1));
					$path = explode("/", $this->sdFile1);
					$name = array_pop($path);
					$name = substr($name, 0, strrpos($name, "."));
					$status = "Successful";
					$this->logs->writeLine("Conversion Result", 'conversion_status : '.$status);

				
				}
				
			}
			//logData(json_encode("end"));
		}
		
	}

	private function convertToHightResolutionVideo($videoDetails = false){
		
		return false;
	}

	private function getPadding($padding = array()){
		if(!empty($padding)){
			return " pad={$padding['top']}:{$padding['right']}:{$padding['bottom']}:{$padding['left']}:{$padding['color']} ";
		}
	}

	private function getInputFileName($filePath = false){
		if($filePath){
			$path = explode("/", $filePath);
			$name = array_pop($path);
			$name = substr($name, 0, strrpos($name, "."));
			return $name;
		}
		return false;
	}

	public function setOptions($options = array()){
		if(!empty($options)){
			if(is_array($options))
			{
				foreach ($options as $key => $value) 
				{
					if(isset($this->defaultOptions[$key]) && !empty($value)){
						$this->options[$key] = $value;
					}
				}
			}
			else
			{
				$this->options[0] = $options;
			}
		}
	}

	private function generateCommand($videoDetails = false, $isHd = false){
		if($videoDetails){
			$result = shell_output("ffmpeg -version");
			preg_match("/(?:ffmpeg\\s)(?:version\\s)?(\\d\\.\\d\\.(?:\\d|[\\w]+))/i", strtolower($result), $matches);
			if(count($matches) > 0)
				{
					$version = array_pop($matches);
				}
			$commandSwitches = "";
			$videoRatio = substr($videoDetails['video_wh_ratio'], 0, 3);
			/*
				Setting the aspect ratio of output video
			*/
			$aspectRatio = $videoDetails['video_wh_ratio'];
			if("1.7" === $videoRatio){
				$ratio = $this->resolution16_9;
			}elseif("1.6" === $ratio){
				$ratio = $this->resolution4_3;
			}else{
				$ratio = $this->resolution4_3;
			}
			$commandSwitches .= "";

			if(isset($this->options['video_codec'])){
				$commandSwitches .= " -vcodec " .$this->options['video_codec'];
			}
			if(isset($this->options['audio_codec'])){
				$commandSwitches .= " -acodec " .$this->options['audio_codec'];
			}


			/*
				Setting Size Of output video
			*/
			if ($version == "0.9")
			{
				if($isHd){
					$defaultVideoHeight = $this->options['high_res'];
					$size = "{$ratio[$defaultVideoHeight][0]}x{$ratio[$defaultVideoHeight][1]}";
					$vpre = "hq";
				}else{
					$defaultVideoHeight = $this->options['normal_res'];
					$size = "{$ratio[$defaultVideoHeight][0]}x{$ratio[$defaultVideoHeight][1]}";
					$vpre = "normal";
				}
			}
			else
				if($isHd){
					$defaultVideoHeight = $this->options['high_res'];
					$size = "{$ratio[$defaultVideoHeight][0]}x{$ratio[$defaultVideoHeight][1]}";
					$vpre = "slow";
				}else{
					$defaultVideoHeight = $this->options['normal_res'];
					$size = "{$ratio[$defaultVideoHeight][0]}x{$ratio[$defaultVideoHeight][1]}";
					$vpre = "medium";
				}
				if ($version == "0.9")
				{
					$commandSwitches .= " -s {$size} -vpre {$vpre}";
				}
				else
				{
					$commandSwitches .= " -s {$size} -preset {$vpre}";
				}
			/*$videoHeight = $videoDetails['video_height'];
			if(array_key_exists($videoHeight, $ratio)){
				////logData($ratio[$videoHeight]);
				$size = "{$ratio[$videoHeight][0]}x{$ratio[$videoHeight][0]}";
			}*/

			if(isset($this->options['format'])){
				$commandSwitches .= " -f " .$this->options['format'];
			}
			
			if(isset($this->options['video_bitrate'])){
				$videoBitrate = (int)$this->options['video_bitrate'];
				if($isHd){
					$videoBitrate = (int)($this->options['video_bitrate_hd']);
					////logData($this->options);
				}
				$commandSwitches .= " -b:v " . $videoBitrate." -minrate ".$videoBitrate. " -maxrate ".$videoBitrate;
			}
			if(isset($this->options['audio_bitrate'])){
				$commandSwitches .= " -b:a " .$this->options['audio_bitrate']." -minrate ".$this->options['audio_bitrate']. " -maxrate ".$this->options['audio_bitrate'];
			}
			if(isset($this->options['video_rate'])){
				$commandSwitches .= " -r " .$this->options['video_rate'];
			}
			if(isset($this->options['audio_rate'])){
				$commandSwitches .= " -ar " .$this->options['audio_rate'];
			}
			return $commandSwitches;
		}
		return false;
	}
	function log($name,$value)
	{
		$this->log .= $name.' : '.$value."\r\n";
	}
	
	/**
	 * Function used to start log
	 */
	function start_log()
	{
		$this->log = "Started on ".NOW()." - ".date("Y M d")."\r\n\n";
		$this->log .= "Checking File ....\r\n";
		$this->log('File',$this->input_file);
	}
	
	/**
	 * Function used to log video info
	 */
	function log_file_info()
	{
		$details = $this->input_details;
		if(is_array($details))
		{
			foreach($details as $name => $value)
			{
				$this->log($name,$value);
			}
		}else{
			$this->log .=" Unknown file details - Unable to get video details using FFMPEG \n";
		}
	}
	/**
	 * Function log outpuit file details
	 */
	function log_ouput_file_info()
	{
		$details = $this->output_details;
		if(is_array($details))
		{
			foreach($details as $name => $value)
			{
				$this->log('output_'.$name,$value);
			}
		}else{
			$this->log .=" Unknown file details - Unable to get output video details using FFMPEG \n";
		}
	}
	function time_check()
	{
		$time = microtime();
		$time = explode(' ',$time);
		$time = $time[1]+$time[0];
		return $time;
	}
	
	/**
	 * Function used to start timing
	 */
	function start_time_check()
	{
		$this->start_time = $this->time_check();
	}
	
	/**
	 * Function used to end timing
	 */
	function end_time_check()
	{
		$this->end_time = $this->time_check();
	}
	
	/** 
	 * Function used to check total time 
	 */
	function total_time()
	{
		$this->total_time = round(($this->end_time-$this->start_time),4);
	}
	function isLocked($num=1)
	{

		for($i=0;$i<$num;$i++)
		{
			$conv_file = TEMP_DIR.'/conv_lock'.$i.'.loc';
			//logData($conv_file);
			if(!file_exists($conv_file))
			{
				$this->lock_file = $conv_file;
				$file = fopen($conv_file,"w+");
				fwrite($file,"converting..");
				fclose($file);
				return false;
			}
		}
		
		return true;
	}
	function ClipBucket()
	{
		$conv_file = TEMP_DIR.'/conv_lock.loc';
		//logData("procees_atonce_".PROCESSESS_AT_ONCE);
		//We will now add a loop
		//that will check weather
		
		while(1)
		{
			$use_crons = config('use_crons');
			//logData($this->isLocked(PROCESSESS_AT_ONCE)."|| ".$use_crons."||".$this->set_conv_lock);
			if(!$this->isLocked(PROCESSESS_AT_ONCE) || $use_crons=='yes' || !$this->set_conv_lock)
			{
				
				if($use_crons=='no')
				{
					//Lets make a file
					$file = fopen($conv_file,"w+");
					fwrite($file,"converting..");
					fclose($file);
				}
				
				
				$this->start_time_check();
				$this->start_log();
				$this->prepare();
				
				$ratio = substr($this->input_details['video_wh_ratio'],0,3);
				
				$max_duration = config('max_video_duration') * 60;

				if($this->input_details['duration']>$max_duration)
				{
					$this->log .= "Video duration was ".$this->input_details['duration']." and Max video duration is $max_duration
					<br>Therefore Video cancelled";
					$this->log("conversion_status","failed");
					$this->log("failed_reason","max_duration");
					$this->create_log_file();
				
					if($this->lock_file && file_exists($this->lock_file))
					unlink($this->lock_file);
					
					$this->failed_reason = 'max_duration';
	
					break;
					return false;
				}
				
				if($ratio=='1.7' || $ratio=='1.6')
				{
					$res = $this->configs['res169'];
				}else
				{
					$res = $this->configs['res43'];
				}
				
				$nr = $this->configs['normal_res'];
				 /*Added by Hassan Baryar bug#268 **/
				if($nr=='320')
					$nr='360';
				/*End*/
				$hr = $this->configs['high_res'];
				$this->configs['video_width'] = $res[$nr][0];
				$this->configs['format'] = 'mp4';
				$this->configs['video_height'] = $res[$nr][1];
				$this->configs['hq_video_width'] = $res[$hr][0];
				$this->configs['hq_video_height'] = $res[$hr][1];
				
				$orig_file = $this->input_file;
				$this->convertVideo($orig_file);
				if($this->generate_iphone)
					$this->convertVideo($orig_file);
				$this->end_time_check();
				$this->total_time();
				
				//Copying File To Original Folder
				if($this->keep_original=='yes')
				{
					$this->log .= "\r\nCopy File to original Folder";
					if(copy($this->input_file,$this->original_output_path))
						$this->log .= "\r\nFile Copied to original Folder...";
					else
						$this->log .= "\r\nUnable to copy file to original folder...";
				}
				
				$this->output_details = $this->get_file_info($this->output_file);
				$this->log .= "\r\n\r\n";
				$this->log_ouput_file_info();
				$this->log .= "\r\n\r\nTime Took : ";
				$this->log .= $this->total_time.' seconds'."\r\n\r\n";
		
				//$this->update_data();
				
				$th_dim = $this->thumb_dim;
				$big_th_dim = $this->big_thumb_dim ;
				
				//Generating Thumb
				if($this->gen_thumbs)
					$this->generate_thumbs($this->input_file,$this->input_details['duration'],$th_dim,$this->num_of_thumbs);
				if($this->gen_big_thumb)
					$this->generate_thumbs($this->input_file,$this->input_details['duration'],$big_th_dim,$this->num_of_thumbs,NULL,true);
				
				if(!file_exists($this->output_file))
					$this->log("conversion_status","failed");
				else
					$this->log("conversion_status","completed");
					
				$this->create_log_file();
				
				if($this->lock_file && file_exists($this->lock_file))
				unlink($this->lock_file);

				break;
			}else
			{
				if($use_crons=='no')
					sleep(10);
				else
					break;
			}
		}
		
	}
	
	private function executeCommand($command = false){
		// the last 2>&1 is for forcing the shell_exec to return the output 
		if($command) return shell_exec($command . " 2>&1");
		return false;
	}

	private function setDefaults(){
		if(PHP_OS == "Linux")
		{
			$ac = 'libfaac';
		}
		elseif(PHP_OS == "Linux")
		{
			$ac = 'libvo_aacenc';
		}
		$this->defaultOptions = array(
			'format' => 'mp4',
			'video_codec'=> 'libx264',
			'audio_codec'=> $ac,
			'audio_rate'=> '22050',
			'audio_bitrate'=> '128000',
			'video_rate'=> '25',
			'video_bitrate'=> '300000',
			'video_bitrate_hd'=> '500000',
			'normal_res' => false,
			'high_res' => false,
			'max_video_duration' => false,
			'resolution16_9' => $this->resolution16_9,
			'resolution4_3' => $this->resolution4_3,
			'resize'=>'max',
			'outputPath' => false,
			);
	}
	function ffmpeg($file)
	{
		global $Cbucket;

		//$this->logs =  new log();
		$this->ffmpeg = FFMPEG_BINARY;
		$this->mp4box = MP4Box_BINARY;
		$this->flvtool2 = FLVTool2_BINARY;
		$this->flvtoolpp = $Cbucket->configs['flvtoolpp'];
		$this->mplayerpath = $Cbucket->configs['mplayerpath'];
		$this->input_file = $file;
		
	}
	function calculate_size_padding( $parameters, $source_info, & $width, & $height, & $ratio, & $pad_top, & $pad_bottom, & $pad_left, & $pad_right )	
	{
		$p = $parameters;
		$i = $source_info;

		switch( $p['resize'] ) {
			# dont resize, use same size as source, and aspect ratio
			# WARNING: some codec will NOT preserve the aspect ratio
			case 'no':
				$width      = $i['video_width'   ];
				$height     = $i['video_height'  ];
				$ratio      = $i['video_wh_ratio'];
				$pad_top    = 0;
				$pad_bottom = 0;
				$pad_left   = 0;
				$pad_right  = 0;
				break;
			# resize to parameters width X height, use same aspect ratio
			# WARNING: some codec will NOT preserve the aspect ratio
			case 'WxH':
				$width  = $p['video_width'   ];
				$height = $p['video_height'  ];
				$ratio  = $i['video_wh_ratio'];
				$pad_top    = 0;
				$pad_bottom = 0;
				$pad_left   = 0;
				$pad_right  = 0;
				break;
			# make pixel square
			# reduce video size if bigger than p[width] X p[height]
			# and preserve aspect ratio
			case 'max':
				$width        = (float)$i['video_width'   ];
				$height       = (float)$i['video_height'  ];
				$ratio        = (float)$i['video_wh_ratio'];
				$max_width    = (float)$p['video_width'   ];
				$max_height   = (float)$p['video_height'  ];

				# make pixels square
				if( $ratio > 1.0 )
					$width = $height * $ratio;
				else
					$height = @$width / $ratio;

				# reduce width
				if( $width > $max_width ) {
					$r       = $max_width / $width;
					$width  *= $r;
					$height *= $r;
				}

				# reduce height
				if( $height > $max_height ) {
					$r       = $max_height / $height;
					$width  *= $r;
					$height *= $r;
				}

				# make size even (required by many codecs)
				$width  = (integer)( ($width  + 1 ) / 2 ) * 2;
				$height = (integer)( ($height + 1 ) / 2 ) * 2;
				# no padding
				$pad_top    = 0;
				$pad_bottom = 0;
				$pad_left   = 0;
				$pad_right  = 0;
				break;
			# make pixel square
			# resize video to fit inside p[width] X p[height]
			# add padding and preserve aspect ratio
			case 'fit':
				# values need to be multiples of 2 in the end so
				# divide width and height by 2 to do the calculation
				# then multiply by 2 in the end
				$ratio        = (float)$i['video_wh_ratio'];
				$width        = (float)$i['video_width'   ] / 2;
				$height       = (float)$i['video_height'  ] / 2;
				$trt_width    = (float)$p['video_width'   ] / 2;
				$trt_height   = (float)$p['video_height'  ] / 2;

				# make pixels square
				if( $ratio > 1.0 )
					$width = $height * $ratio;
				else
					$height = $width / $ratio;
				
				# calculate size to fit
				$ratio_w = $trt_width  / $width;
				$ratio_h = $trt_height / $height;

				if( $ratio_h > $ratio_w ) {
					$width  = (integer)$trt_width;
					$height = (integer)($width / $ratio);
				} else {
					$height = (integer)$trt_height;
					$width  = (integer)($height * $ratio);
				}

				# calculate padding
				$pad_top    = (integer)(($trt_height - $height + 1) / 2);
				$pad_left   = (integer)(($trt_width  - $width  + 1) / 2);
				$pad_bottom = (integer)($trt_height  - $height - $pad_top );
				$pad_right  = (integer)($trt_width   - $width  - $pad_left);

				# multiply by 2 to undo division and get multiples of 2
				$width      *= 2;
				$height     *= 2;
				$pad_top    *= 2;
				$pad_left   *= 2;
				$pad_bottom *= 2;
				$pad_right  *= 2;
				break;
		}
	}
	function create_log_file()
	{
		$file = $this->log_file;
		$data = $this->log;
		$fo = fopen($file,"w");
		if($fo)
		{
			fwrite($fo,$data);
		}
	}
	function convert($file=NULL,$for_iphone=false)
	{
		global $db;
		if($file)
			$this->input_file = $file;
		//logData($this->input_file);
		$this->log .= "\r\nConverting Video\r\n";
		$fileDetails = json_encode($this->input_details);
		$p = $this->configs;

		
		$i = $this->input_details;
		
		# Prepare the ffmpeg command to execute
		if(isset($p['extra_options']))
			$opt_av .= " -y {$p['extra_options']} ";

		# file format
		if(isset($p['format']))
			$opt_av .= " -f {$p['format']} ";
			//$opt_av .= " -f mp4 ";
			
		# video codec
		if(isset($p['video_codec']))
			$opt_av .= " -vcodec ".$p['video_codec'];
		elseif(isset($i['video_codec']))
			$opt_av .= " -vcodec ".$i['video_codec'];
		if($p['video_codec'] == 'libx264')
			$opt_av .= " -vpre normal ";
                # video rate
                if($p['use_video_rate'])
                {
                    if(isset($p['video_rate']))
                        $vrate = $p['video_rate'];
                    elseif(isset($i['video_rate']))
                        $vrate = $i['video_rate'];
                    if(isset($p['video_max_rate']) && !empty($vrate))
                        $vrate = min($p['video_max_rate'],$vrate);
                    if(!empty($vrate))
                        $opt_av .= " -r $vrate ";
                }
                
                # video bitrate
                if($p['use_video_bit_rate'])
                {
                    if(isset($p['video_bitrate']))
                        $vbrate = $p['video_bitrate'];
                    elseif(isset($i['video_bitrate']))
                        $vbrate = $i['video_bitrate'];
                    if(!empty($vbrate))
                        $opt_av .= " -b:v $vbrate ";
                }
                
                
		# video size, aspect and padding
		
		$this->calculate_size_padding( $p, $i, $width, $height, $ratio, $pad_top, $pad_bottom, $pad_left, $pad_right );
		$use_vf = config('use_ffmpeg_vf');
		if($use_vf=='no')
		{
		$opt_av .= " -s {$width}x{$height} -aspect $ratio -padcolor 000000 -padtop $pad_top -padbottom $pad_bottom -padleft $pad_left -padright $pad_right ";
		}else
		{
			$opt_av .= "-s {$width}x{$height} -aspect  $ratio -vf  pad=0:0:0:0:black";
		}
		
		
		# audio codec, rate and bitrate
		if($p['use_audio_codec'])
		{
			if(!empty($p['audio_codec']) && $p['audio_codec'] != 'None'){
				$opt_av .= " -acodec {$p['audio_codec']}";
			}
		}
		
		# audio bitrate
		if($p['use_audio_bit_rate'])
		{
			if(isset($p['audio_bitrate']))
				$abrate = $p['audio_bitrate'];
			elseif(isset($i['audio_bitrate']))
				$abrate = $i['audio_bitrate'];
			if(!empty($abrate))
			{
				$abrate_cmd = " -ab $abrate ";
				$opt_av .= $abrate_cmd;
			}
		}

		# audio bitrate
		if(!is_numeric($this->input_details['audio_rate']))
		{
			
			$opt_av .= " -an ";
		}elseif($p['use_audio_rate'])
		{
			if(!$this->validChannels($this->input_details))
			{
				$arate = $i['audio_rate'];
				$opt_av .= $arate_cmd = " -ar $arate ";
			}else
			{
				if(isset($p['audio_rate']))
					$arate = $p['audio_rate'];
				elseif(isset($i['audio_rate']))
					$arate = $i['audio_rate'];
				if(!empty($arate))
					$opt_av .= $arate_cmd = " -ar $arate ";
			}
		}
		$tmp_file = time().RandomString(5).'.tmp';
		
		//$opt_av .= '-'.$this->vconfigs['map_meta_data']." ".$this->output_file.":".$this->input_file;
	
		$this->raw_command = $command = $this->ffmpeg." -i ".$this->input_file." $opt_av ".$this->output_file."  2> ".TEMP_DIR."/".$tmp_file;
		
		//Updating DB
		//$db->update($this->tbl,array('command_used'),array($command)," id = '".$this->row_id."'");
		
		if(!$for_iphone)
		{
			$output = $this->exec($command);
			//logData($command);
			if(file_exists(TEMP_DIR.'/'.$tmp_file))
			{
				$output = $output ? $output : join("", file(TEMP_DIR.'/'.$tmp_file));
				unlink(TEMP_DIR.'/'.$tmp_file);
			}
			
			
			#FFMPEG GNERETAES Damanged File
			#Injecting MetaData ysing FLVtool2 - you must have update version of flvtool2 ie 1.0.6 FInal or greater
			if($this->flvtoolpp && file_exists($this->output_file)  && @filesize($this->output_file)>0)
				{
					$tmp_file = time().RandomString(5).'flvtool2_output.tmp';
					$flv_cmd = $this->flvtoolpp." ".$this->output_file." ".$this->output_file."  2> ".TEMP_DIR."/".$tmp_file;
					$flvtool2_output = $this->exec($flv_cmd);
					if(file_exists(TEMP_DIR.'/'.$tmp_file))
					{
						$flvtool2_output = $flvtool2_output ? $flvtool2_output : join("", file(TEMP_DIR.'/'.$tmp_file));
						unlink(TEMP_DIR.'/'.$tmp_file);
					}
					$output .= $flvtool2_output;
					
			}elseif($this->flvtool2  && file_exists($this->output_file)  && @filesize($this->output_file)>0)
			{
				$tmp_file = time().RandomString(5).'flvtool2_output.tmp';
				$flv_cmd = $this->flvtool2." -U  ".$this->output_file."  2> ".TEMP_DIR."/".$tmp_file;
				$flvtool2_output = $this->exec($flv_cmd);
				if(file_exists(TEMP_DIR.'/'.$tmp_file))
				{
					$flvtool2_output = $flvtool2_output ? $flvtool2_output : join("", file(TEMP_DIR.'/'.$tmp_file));
					unlink(TEMP_DIR.'/'.$tmp_file);
				}
				$output .= $flvtool2_output;
			}
			
			$this->log('Conversion Command',$command);
			$this->log .="\r\n\r\nConversion Details\r\n\r\n";
			$this->log .=$output;
			$this->output_details = $this->get_file_info($this->output_file);
		}
		
		
		
		//Generating Mp4 for iphone
		if($this->generate_iphone && $for_iphone)
		{
			$this->log .="\r\n\r\n== Generating Iphone Video ==\r\n\r\n";
			$tmp_file = 'iphone_log.log';
			$iphone_configs = "";
			$iphone_configs .= " -acodec libfaac ";
			$iphone_configs .= " -vcodec mpeg4 ";
			$iphone_configs .= " -r 25  ";
			$iphone_configs .= " -b 600k  ";
			$iphone_configs .= " -ab 96k   ";
			
			if($this->input_details['audio_channels']>2)
			{
				$arate = $i['audio_rate'];
				$iphone_configs .= $arate_cmd = " -ar $arate ";
			}
			
			$p['video_width'] = '480';
			$p['video_height'] = '320';
			
			$this->calculate_size_padding( $p, $i, $width, $height, $ratio, $pad_top, $pad_bottom, $pad_left, $pad_right );
			$iphone_configs .= " -s {$width}x{$height} -aspect $ratio";
			

			$command = $this->ffmpeg." -i ".$this->input_file." $iphone_configs ".$this->raw_path."-m.mp4 2> ".TEMP_DIR."/".$tmp_file;
			$this->exec($command);
			
			if(file_exists(TEMP_DIR.'/'.$tmp_file))
			{
				$output = $output ? $output : join("", file(TEMP_DIR.'/'.$tmp_file));
				unlink(TEMP_DIR.'/'.$tmp_file);
			}
			
			if(file_exists($this->raw_path."-m.mp4") && filesize($this->raw_path."-m.mp4")>0)
			{
				$this->has_mobile = 'yes';
			}
			
			$this->log('== iphone Conversion Command',$command);
			$this->log .="\r\n\r\nConversion Details\r\n\r\n";
			$this->log .=$output;
			
			$this->log .="\r\n\r\n== Generating Iphone Video Ends ==\r\n\r\n";
		}
		
	}
	/**
	 * Prepare file to be converted
	 * this will first get info of the file
	 * and enter its info into database
	 */
	function prepare($file=NULL)
	{
		global $db;
		
		if($file)
			$this->input_file = $file;
			
		if(file_exists($this->input_file))
			$this->input_file = $this->input_file;
		else
			$this->input_file = TEMP_DIR.'/'.$this->input_file;
		
		
		
		//Checking File Exists
		if(!file_exists($this->input_file))
		{
			$this->log('File Exists','No');
		}else{
			$this->log('File Exists','Yes');
		}
		
		//Get File info
		$this->input_details = $this->get_file_info();
		//Loging File Details
		$this->log .= "\nPreparing file...\n";
		$this->log_file_info();
		
		//Insert Info into database
		//$this->insert_data();		
		
		//Gett FFMPEG version
		$result = shell_output(FFMPEG_BINARY." -version");
		$version = parse_version('ffmpeg',$result);
		
		
		$this->vconfigs['map_meta_data'] = 'map_meta_data';
		
		if(strstr($version,'Git'))
		{
			$this->vconfigs['map_meta_data'] = 'map_metadata';
		}
		
	}
	private function getVideoDetails( $videoPath = false) {	
		if($videoPath){
			# init the info to N/A
			$info['format']			= 'N/A';
			$info['duration']		= 'N/A';
			$info['size']			= 'N/A';
			$info['bitrate']		= 'N/A';
			$info['video_width']	= 'N/A';
			$info['video_height']	= 'N/A';
			$info['video_wh_ratio']	= 'N/A';
			$info['video_codec']	= 'N/A';
			$info['video_rate']		= 'N/A';
			$info['video_bitrate']	= 'N/A';
			$info['video_color']	= 'N/A';
			$info['audio_codec']	= 'N/A';
			$info['audio_bitrate']	= 'N/A';
			$info['audio_rate']		= 'N/A';
			$info['audio_channels']	= 'N/A';
			$info['path'] = $videoPath;

			/*
				get the information about the file
				returns array of stats
			*/
			$stats = stat($videoPath);
			if($stats && is_array($stats)){

				$ffmpegOutput = $this->executeCommand( $this->ffMpegPath . " -i {$videoPath} -acodec copy -vcodec copy -y -f null /dev/null 2>&1" );
				$info = $this->parseVideoInfo($ffmpegOutput,$stats['size']);
				$info['size'] = (integer)$stats['size'];
				$size12 = $info;
					return $info;
			}
		}
		return false;
	}

	private function parseVideoInfo($output = "",$size=0) {
		# search the output for specific patterns and extract info
		# check final encoding message
		$info['size'] = $size;
		$audio_codec = false;
		if($args =  $this->pregMatch( 'Unknown format', $output) ) {
			$Unkown = "Unkown";
		} else {
			$Unkown = "";
		}
		if( $args = $this->pregMatch( 'video:([0-9]+)kB audio:([0-9]+)kB global headers:[0-9]+kB muxing overhead', $output) ) {
			$video_size = (float)$args[1];
			$audio_size = (float)$args[2];
		}


		# check for last enconding update message
		if($args =  $this->pregMatch( '(frame=([^=]*) fps=[^=]* q=[^=]* L)?size=[^=]*kB time=([^=]*) bitrate=[^=]*kbits\/s[^=]*$', $output) ) {
			
			$frame_count = $args[2] ? (float)ltrim($args[2]) : 0;
			$duration    = (float)$args[3];
		}

		
		
		$duration = $this->pregMatch( 'Duration: ([0-9.:]+),', $output );
		$duration    = $duration[1];
		
		$len = strlen($output);
		$findme = 'Duration';
		$findme1 = 'start';
		$pos = strpos($output, $findme);
		$pos = $pos + 10;
		$pos1 = strpos($output, $findme1);
		$bw = $len - ($pos1 - 5);
		$rest = substr($output, $pos, -$bw);


		$duration = explode(':',$rest);
		//Convert Duration to seconds
		$hours = $duration[0];
		$minutes = $duration[1];
		$seconds = $duration[2];
		
		$hours = $hours * 60 * 60;
		$minutes = $minutes * 60;
		
		$duration = $hours+$minutes+$seconds;
	

		$info['duration'] = $duration;
		if($duration)
		{
			$info['bitrate' ] = (integer)($info['size'] * 8 / 1024 / $duration);
			if( $frame_count > 0 )
				$info['video_rate']	= (float)$frame_count / (float)$duration;
			if( $video_size > 0 )
				$info['video_bitrate']	= (integer)($video_size * 8 / $duration);
			if( $audio_size > 0 )
				$info['audio_bitrate']	= (integer)($audio_size * 8 / $duration);
				# get format information
			if($args =  $this->pregMatch( "Input #0, ([^ ]+), from", $output) ) {
				$info['format'] = $args[1];
			}
		}

		# get video information
		if(  $args= $this->pregMatch( '([0-9]{2,4})x([0-9]{2,4})', $output ) ) {
			
			$info['video_width'  ] = $args[1];
			$info['video_height' ] = $args[2];
			$info['video_wh_ratio'] = (float) $info['video_width'] / (float)$info['video_height'];
		}
		
		if($args= $this->pregMatch('Video: ([^ ^,]+)',$output))
		{
			$info['video_codec'  ] = $args[1];
		}

		# get audio information
		if($args =  $this->pregMatch( "Audio: ([^ ]+), ([0-9]+) Hz, ([^\n,]*)", $output) ) {
			$audio_codec = $info['audio_codec'   ] = $args[1];
			$audio_rate = $info['audio_rate'    ] = $args[2];
			$info['audio_channels'] = $args[3];
		}
		

		if((isset($audio_codec) && !$audio_codec) || !$audio_rate)
		{
			$args =  $this->pregMatch( "Audio: ([a-zA-Z0-9]+)(.*), ([0-9]+) Hz, ([^\n,]*)", $output);
			$info['audio_codec'   ] = $args[1];
			$info['audio_rate'    ] = $args[3];
			$info['audio_channels'] = $args[4];
		}

		return $info;
	}

	private function pregMatch($in = false, $str = false){
		if($in && $str){
			preg_match("/$in/",$str,$args);
			return $args;
		}
		return false;
	}

	 private function generateThumbs($input_file,$duration,$dim='501x283',$num=thumbs_number,$rand=NULL,$is_big=false){

		$tmpDir = TEMP_DIR.'/'.getName($input_file);
		

		/*
			The format of $this->options["outputPath"] should be like this
			year/month/day/ 
			the trailing slash is important in creating directories for thumbs
		*/
		if(substr($this->options["outputPath"], strlen($this->options["outputPath"]) - 1) !== "/"){
			$this->options["outputPath"] .= "/";
		}
		mkdir($tmpDir,0777);


		$output_dir = THUMBS_DIR;
		$dimension = '';

		$big = "";
		
		if($is_big=='big')
		{
			$big = 'big-';
		}
		
		if($num > 1 && $duration > 14)
		{
			$duration = $duration - 5;
			$division = $duration / $num;
			$count=1;
			
			
			for($id=3;$id<=$duration;$id++)
			{
				$file_name = getName($input_file)."-{$big}{$count}.jpg";
				$file_path = THUMBS_DIR.'/' . $this->options['outputPath'] . $file_name;
				$id	= $id + $division - 1;
				if($rand != "") {
					$time = $this->ChangeTime($id,1);
				} elseif($rand == "") {
					$time = $this->ChangeTime($id);
				}
				
				if($dim!='original')
				{
					$dimension = " -s $dim  ";
					$mplayer_dim = "-vf scale=$width:$height";
				}
				
				$command = $this->ffMpegPath." -i $input_file -an -ss $time -an -r 1 $dimension -y -f image2 -vframes 1 $file_path ";
				
				$output = $this->executeCommand($command);	
				//$this->//logData($output);
				//checking if file exists in temp dir
				if(file_exists($tmpDir.'/00000001.jpg'))
				{
					rename($tmpDir.'/00000001.jpg',THUMBS_DIR.'/'.$file_name);
				}
				$count = $count+1;
			}
		}else{
			$file_name = getName($input_file).".jpg";
			$file_path = THUMBS_DIR.'/' . $this->options['outputPath'] . "/" . $file_name;
			$command = $this->ffMpegPath." -i $input_file -an -s $dim -y -f image2 -vframes $num $file_path ";
			$output = $this->executeCommand($command);
		}
		
		rmdir($tmpDir);
	}




/**
	 * Function used to regenrate thumbs for a video
	 * @param : 
	 * @parma : 
	 */

public function regenerateThumbs($input_file,$test,$duration,$dim,$num,$rand=NULL,$is_big=false,$filename){

		$tmpDir = TEMP_DIR.'/'.getName($input_file);
		
        $output_dir = THUMBS_DIR;
		$dimension = '';

		$big = "";
		
		if($is_big=='big')
		{
			$big = 'big-';
		}
		
		if($num > 1 && $duration > 14)
		{
			$duration = $duration - 5;
			$division = $duration / $num;
			$count=1;
			
			
			for($id=3;$id<=$duration;$id++)
			{
				$file_name = $filename."-{$big}{$count}.jpg";
				$file_path = THUMBS_DIR.'/' . $test .'/'. $file_name;
				
				$id	= $id + $division - 1;
                $time = $this->ChangeTime($id,1);
				
				
                

				if($dim!='original')
				{
					$dimension = " -s $dim  ";
					$mplayer_dim = "-vf scale=$width:$height";
				}
                
				
				
				$command = $this->ffMpegPath." -i $input_file -an -ss $time -an -r 1 $dimension -y -f image2 -vframes 1 $file_path ";
			
				$output = $this->executeCommand($command);	
					 //e(lang($output),'m');

				//$this->//logData($output);
				//checking if file exists in temp dir
				if(file_exists($tmpDir.'/00000001.jpg'))
				{
					rename($tmpDir.'/00000001.jpg',THUMBS_DIR.'/'.$file_name);
				}
				$count = $count+1;
			}
		}else

		{

			$time = $this->ChangeTime($duration,1);
			$file_name = getName($input_file).".jpg";
			$file_path = THUMBS_DIR.'/' . $test . "/" . $file_name;
			$command = $this->ffMpegPath." -i $input_file -an -ss $time -an -r 1 $dimension -y -f image2 -vframes $num $file_path ";
			$output = $this->executeCommand($command);
			$output;
			//e(lang($num),'m');
			

		}
		
		//rmdir($tmpDir);
	}





		/**
	 * Function used to convert seconds into proper time format
	 * @param : INT duration
	 * @parma : rand
	 */
	 
	private function ChangeTime($duration, $rand = "") {
		if($rand != "") {
			if($duration / 3600 > 1) {
				$time = date("H:i:s", $duration - rand(0,$duration));
			} else {
				$time =  "00:";
				$time .= date("i:s", $duration - rand(0,$duration));
			}
			return $time;
		} elseif($rand == "") {
			if($duration / 3600 > 1 ) {
				$time = date("H:i:s",$duration);
			} else {
				$time = "00:";
				$time .= date("i:s",$duration);
			}
			return $time;
		}
	}

	private function startLog($logFileName){
		$this->logFile = $this->logDir . $logFileName . ".log";
		$log = new SLog();
		$this->logs = $log;
		$log->setLogFile($this->logFile);
	}

	public function isConversionSuccessful(){
		$str = "/".date("Y")."/".date("m")."/".date("d")."/";
		$orig_file1 = BASEDIR.'/files/videos'.$str.$tmp_file.'-sd.'.$ext;
		if ($size12 = "0") {
			
			return true;
			
		}
		else
			return false;
	}

}