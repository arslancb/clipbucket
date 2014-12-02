<?php
define('FFMPEG_BINARY', get_binaries('ffmpeg'));
define("thumbs_number",config('num_thumbs'));

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
	private $log = false;
	private $logFile = "";
	private $sdFile = false;
	private $hdFile = false;
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
		$this->log->writeLine("in class", "ffmpeg");
		$this->logDir = BASEDIR . "/files/logs/".$str;
	}

	public function convertVideo($inputFile = false, $options = array(), $isHd = false){
		$this->startLog($this->getInputFileName($inputFile));
		//$this->log->newSection("Video Conversion", "Starting");
		if($inputFile){
			if(!empty($options)){
				$this->setOptions($options);
			}
			$this->inputFile = $inputFile;
       		//$myfile = fopen("testfile.txt", "w")
       		//fwrite($myfile, $inputFile);
			$this->log->writeLine("input file", $inputFile);
			$this->outputFile = $this->videosDirPath . '/'. $this->options['outputPath'] . '/' . $this->getInputFileName($inputFile);
			$this->log->writeLine("outputFile", $this->outputFile);
			$videoDetails = $this->getVideoDetails($inputFile);
			
			$this->videoDetails = $videoDetails;
			$this->log->writeLine("videoDetails", $videoDetails);
			$this->output = new stdClass();
			$this->output->videoDetails = $videoDetails;

			//$this->log->writeLine("Thumbs Generation", "Starting");
			try{
				$this->generateThumbs($this->inputFile, $videoDetails['duration']);
			}catch(Exception $e){
				$this->log->writeLine("Errot Occured", $e->getMessage());
			}

			/*
				Low Resolution Conversion Starts here
			*/
			$this->log->newSection("Low Resolution Conversion");

			$this->convertToLowResolutionVideo($videoDetails);
			/*
				High Resoution Coversion Starts here
			*/
			
			$this->log->writeLine("videoDetails", $videoDetails);

		}else{
			//$this->logData("no input file");
		}
	}

	private function convertToLowResolutionVideo($videoDetails = false){
		
		if($videoDetails)
		{
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
			if($dura[1] >= "720")
			{
				
				$this->log->writeLine("Generating low resolution video", "Starting");
				$this->sdFile = "{$this->outputFile}-sd.{$this->options['format']}";
				$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, false) . " {$this->sdFile}";

				$this->log->writeLine("command", $fullCommand);

				$conversionOutput = $this->executeCommand($fullCommand);
				$this->log->writeLine("ffmpeg output", $conversionOutput);
				
				$this->log->writeLine("MP4Box Conversion for SD", "Starting");
				$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->sdFile}  -tmp ".TEMP_DIR;
				if (PHP_OS == "WINNT")
				{
					$fullCommand = str_replace("/","\\",$fullCommand);	
				}
				$this->log->writeLine("command", $fullCommand);
				$output = $this->executeCommand($fullCommand);
				$this->log->writeLine("output", $output);
				
				if (file_exists($this->sdFile))
				{
					$this->sdFile1 = "{$this->outputFile}.{$this->options['format']}";
					$path = explode("/", $this->sdFile1);
					$name = array_pop($path);
					$name = substr($name, 0, strrpos($name, "."));
					$status = "Successful";
					$this->log->writeLine("Conversion Result", 'conversion_status : '.$status);

				
				}
				$this->log->newSection("High Resolution Conversion");
				$this->log->writeLine("Generating high resolution video", "Starting");
				$this->hdFile = "{$this->outputFile}-hd.{$this->options['format']}";
				$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, true) . " {$this->hdFile}";
				$this->log->writeLine("Command", $fullCommand);
				$conversionOutput = $this->executeCommand($fullCommand);
				$this->log->writeLine("ffmpeg output", $conversionOutput);
				$this->log->writeLine("MP4Box Conversion for HD", "Starting");
				$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->hdFile}  -tmp ".TEMP_DIR;
				if (PHP_OS == "WINNT")
				{
					$fullCommand = str_replace("/","\\",$fullCommand);	
				}
				$this->log->writeLine("command", $fullCommand);
				$output = $this->executeCommand($fullCommand);
				$this->log->writeLine("output", $output);
				if (file_exists($this->hdFile))
				{
					$this->sdFile1 = "{$this->outputFile}.{$this->options['format']}";
					$path = explode("/", $this->sdFile1);
					$name = array_pop($path);
					$name = substr($name, 0, strrpos($name, "."));
					$status = "Successful";
					$this->log->writeLine("Conversion Result", 'conversion_status : '.$status);

				
				}
			}
			else
			{

				$this->log->writeLine("Generating low resolution video", "Starting");
				$this->sdFile = "{$this->outputFile}-sd.{$this->options['format']}";
				$fullCommand = $this->ffMpegPath . " -i {$this->inputFile}" . $this->generateCommand($videoDetails, false) . " {$this->sdFile}";

				$this->log->writeLine("command", $fullCommand);

				$conversionOutput = $this->executeCommand($fullCommand);
				$this->log->writeLine("ffmpeg output", $conversionOutput);
				
				$this->log->writeLine("MP4Box Conversion for SD", "Starting");
				$fullCommand = $this->mp4BoxPath . " -inter 0.5 {$this->sdFile}  -tmp ".TEMP_DIR;
				if (PHP_OS == "WINNT")
				{
					$fullCommand = str_replace("/","\\",$fullCommand);	
				}
				$this->log->writeLine("command", $fullCommand);
				$output = $this->executeCommand($fullCommand);
				$this->log->writeLine("output", $output);
				if (file_exists($this->sdFile))
				{
					$this->sdFile1 = "{$this->outputFile}.{$this->options['format']}";
					$path = explode("/", $this->sdFile1);
					$name = array_pop($path);
					$name = substr($name, 0, strrpos($name, "."));
					$status = "Successful";
					$this->log->writeLine("Conversion Result", 'conversion_status : '.$status);

				
				}
				
			}
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
			foreach ($options as $key => $value) {
				if(isset($this->defaultOptions[$key]) && !empty($value)){
					$this->options[$key] = $value;
				}
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
				//$this->logData($output);
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

				//$this->logData($output);
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
		$this->log->setLogFile($this->logFile);
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