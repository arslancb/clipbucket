<?php

class FFMpeg{
	private $command = "";
	public $defaultOptions = array();
	private $options = array();
	private $outputFile = false;
	private $inputFile = false;
	private $conversionLog = "";
	public $ffMpegPath = FFMPEG_BINARY;
	private $mp4BoxPath = MP4Box_BINARY;
	private $flvTool2 = FLVTool2_BINARY;
	private $videosDirPath = VIDEOS_DIR;
	private $log = "";
	private $logDir = "";
	private $logFile = "";
	private $resolution16_9 = array(
		'240' => array('428','240'),
		'360' => array('640','360'),
		'480' => array('853','480'),
		'720' => array('1280','720'),
		'1080' => array('1920','1080'),
		);

	private $resolution4_3 = array(
		'240' => array('320','240'),
		'360' => array('480','360'),
		'480' => array('640','480'),
		'720' => array('960','720'),
		'1080' => array('1440','1080'),
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

	public function __construct($options = false){
		$this->setDefaults();
		if($options && !empty($options)){
			$this->setOptions($options);
		}else{
			$this->setOptions($this->defaultOptions);
		}
		$this->logDir = BASEDIR . "/files/logs/";
	}

	public function convertVideo($inputFile = false, $options = array(), $isHd = false){
		$this->startLog($this->getInputFileName($inputFile));
		$this->logData("Starting Conversion \n ============================");
		if($inputFile){
			if(!empty($options)){
				$this->setOptions($options);
			}
			$this->inputFile = $inputFile;
			$this->outputFile = $this->videosDirPath . '/'. $this->options['outputPath'] . '/' . $this->getInputFileName($inputFile);
			//logData($this->outputFile);
			$videoDetails = $this->getVideoDetails($inputFile);
			//logData($videoDetails);

			$this->logData("Starting Thumbs Generation \n ================================");
			try{
				$this->generateThumbs($this->inputFile, $videoDetails['duration']);
			}catch(Exception $e){
				$this->logData("Error Occured : ". $e->getMessage());
			}

			/*
				Low Resolution Conversion Starts here
			*/
			$this->convertToLowResolutionVideo($videoDetails);
			/*
				High Resoution Coversion Starts here
			*/
			$this->convertToHightResolutionVideo($videoDetails);
		}else{
			$this->logData("no input file");
		}
	}

	private function convertToLowResolutionVideo($videoDetails = false){
		if($videoDetails){
			$this->logData("============================== Generating low resolution video \n ===============================");
			$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, false) . " {$this->outputFile}-sd.{$this->options['format']}";
			//logData($fullCommand);
			$conversionOutput = $this->executeCommand($fullCommand);
			//logData($conversionOutput);
			$this->logData("============================== Mp4Box Starting \n ==============================");
			$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->outputFile}-sd.{$this->options['format']}";
			$output = $this->executeCommand($fullCommand);
			$this->logData($output);
			file_put_contents("/home/sajjad/Desktop/log.txt", $output);
			
		}
	}

	private function convertToHightResolutionVideo($videoDetails = false){
		if($videoDetails && ((int)$videoDetails['video_height'] >= "720")){
			$this->logData("============================== Generating high resolution video \n ===============================");
			$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, true) . " {$this->outputFile}-hd.{$this->options['format']}";
			//logData($fullCommand);
			$conversionOutput = $this->executeCommand($fullCommand);
			//logData($conversionOutput);
			$this->logData("============================== Mp4Box Starting \n ==============================");
			$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->outputFile}-hd.{$this->options['format']}";
			$output = $this->executeCommand($fullCommand);
			$this->logData($output);
		}
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
			foreach ($options as $key => $value) {
				if(isset($this->defaultOptions[$key]) && !empty($value)){
					$this->options[$key] = $value;
				}
			}
		}
	}

	private function generateCommand($videoDetails = false, $isHd = false){
		if($videoDetails){
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
			$commandSwitches .= " -aspect {$aspectRatio}";

			if(isset($this->options['video_codec'])){
				$commandSwitches .= " -vcodec " .$this->options['video_codec'];
			}
			if(isset($this->options['audio_codec'])){
				$commandSwitches .= " -acodec " .$this->options['audio_codec'];
			}


			/*
				Setting Size Of output video
			*/
			if($isHd){
				$defaultVideoHeight = $this->options['high_res'];
				$size = "{$ratio[$defaultVideoHeight][0]}x{$ratio[$defaultVideoHeight][1]}";
				$vpre = "hq";
			}else{
				$defaultVideoHeight = $this->options['normal_res'];
				$size = "{$ratio[$defaultVideoHeight][0]}x{$ratio[$defaultVideoHeight][1]}";
				$vpre = "normal";
			}

			$commandSwitches .= " -s {$size} -vpre {$vpre}";
			
			/*$videoHeight = $videoDetails['video_height'];
			if(array_key_exists($videoHeight, $ratio)){
				//logData($ratio[$videoHeight]);
				$size = "{$ratio[$videoHeight][0]}x{$ratio[$videoHeight][0]}";
			}*/

			if(isset($this->options['format'])){
				$commandSwitches .= " -f " .$this->options['format'];
			}
			
			if(isset($this->options['video_bitrate'])){
				$videoBitrate = (int)$this->options['video_bitrate'];
				if($isHd){
					$videoBitrate = (int)($this->options['video_bitrate_hd']);
					//logData($this->options);
				}
				$commandSwitches .= " -b:v " . $videoBitrate;
			}
			if(isset($this->options['audio_bitrate'])){
				$commandSwitches .= " -b:a " .$this->options['audio_bitrate'];
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

	private function executeCommand($command = false){
		// the last 2>&1 is for forcing the shell_exec to return the output 
		if($command) return shell_exec($command . " 2>&1");
		return false;
	}

	private function setDefaults(){
		$this->defaultOptions = array(
			'format' => 'mp4',
			'video_codec'=> 'libx264',
			'audio_codec'=> 'libfaac',
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
				$info = $this->parseVideoInfo($ffmpegOutput);
				$info['size'] = (integer)$stats['size'];
				return $info;
			}
		}
		return false;
	}

	private function parseVideoInfo($output = "") {
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
		
		if(!$audio_codec || !$audio_rate)
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

	private function generateThumbs($input_file,$duration,$dim='120x90',$num=3,$rand=NULL,$is_big=false){
		//logData($duration);
		$tmpDir = TEMP_DIR.'/'.getName($input_file);
		//logData($input_file);
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
				$this->logData($output);
				//checking if file exists in temp dir
				if(file_exists($tmpDir.'/00000001.jpg'))
				{
					rename($tmpDir.'/00000001.jpg',THUMBS_DIR.'/'.$file_name);
				}
				$count = $count+1;
			}
		}else{
			$this->logData("in elese");
			$file_name = getName($input_file).".jpg";
			$file_path = THUMBS_DIR.'/' . $this->options['outputPath'] . "/" . $file_name;
			$command = $this->ffMpegPath." -i $input_file -an -s $dim -y -f image2 -vframes $num $file_path ";
			$output = $this->executeCommand($command);
			$this->logData($output);
		}
		
		rmdir($tmpDir);
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
		$handle = fopen($this->logFile, "w+");
		fclose($handle);
	}

	private function logData($data = false){
		if($data){
			if(is_array($data)) $data = json_encode($data);
			$handle = fopen($this->logFile, "a+");
			fwrite($handle, $data);
			fclose($handle);
		}
	}

}