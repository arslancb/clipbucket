<?php 

	/**
	* File : Conversion Class
	* Description : ClipBucket conversion system fully depends on this class. All conversion related
	* processes pass through here like generating thumbs, extrating video meta, extracting
	* video qualities and other similar actions
	* @since : ClipBucket 2.8.2 January 17th, 2017
	* @author : Saqib Razzaq
	* @modified : { 19th January, 2017 } { Progressed video conversion, added thumb function } { Saqib Razzaq }
	* @notice : File to be maintained
	*/

	class ffmpeg {

		# stores path for ffmepg binary file, used for basic conversion actions
		private $ffmpegPath = '';

		# stores path for ffprobe binary file, used for video meta extraction
		private $ffprobePath = '';

		# stores path for mediainfo, also used for video meta exraction
		private $mediainfoPath = '';

		# stores number of maximum allowed processes for ffmpeg
		private $maxProsessesAtOnce = '';

		# stores filename of video being currently being processed
		private $fileName = '';

		# stores directory of video file currently being processed
		private $fileDirectory = '';

		# stores directory where output (processed / converted) file is to be stored
		private $outputDirectory = '';

		# stores directory to save video conversion logs
		private $logsDir = LOGS_DIR;

		# stores name of file that should be used for dumping video conversion log
		private $logFile = '';

		# stores path to temporary directory where file stay before they are moved
		# either to conversion qeue or final destination
		private $tempDirectory = TEMP_DIR;

		# stores path to conversion lock file which is used to check if more processes
		# are allowed at a time or not
		private $ffmpegLockPath = '';
		private $ffmpegLock = '';

		# stores settings for generating video thumbs
		private $thumbsResSettings = '';

		# stores settings for 16:9 ratio conversion
		private $res169 = '';

		# stores settings for 4:3 ratio conversion
		private $resolution4_3 = '';

		# stores basic ffmpeg configurations for processing video
		private $ffmpegConfigs = '';

		# physical file that is under processing at time
		private $inputFile = '';

		# log data will be stored here while video converts
		public $log = '';

		# maximum video duration allowed by admin settings
		private $maxDuration = '';

		/**
		* Action : Function that runs everytime class is initiated
		* Description : 
		* @param : { array } { $ffmpegParams } { an array of paramters }
		* @param : { string } { $ffmpegParams : fileName } { fileName of video to process }
		* @param : { string } { $ffmpegParams : fileDirectory } { Directory name of video to process }
		* @param : { string } { $ffmpegParams : outputDirectory } { Directory name where converted video is to be saved }
		* @param : { string } { $ffmpegParams : logFile } { file path to log file for dumping conversion logs }
		*/

		function __construct( $ffmpegParams ) {
			global $log;

			$this->ffmpegPath = get_binaries( 'ffmpeg' );
			$this->ffprobePath = get_binaries( 'ffprobe_path' );
			$this->mediainfoPath = get_binaries( 'media_info' );
			$this->maxProsessesAtOnce = config( 'max_conversion' );
			$this->fileName = $ffmpegParams['fileName'];
			$this->fileDirectory = $ffmpegParams['fileDirectory'];
			$this->fullUploadedFilePath = $this->fileDirectory.'/'.$this->fileName;
			$this->outputDirectory = $ffmpegParams['outputDirectory'];

			$this->logFile = $ffmpegParams['logFile'];
			$this->ffmpegLockPath = TEMP_DIR.'/conv_lock';
			$this->maxDuration = config( 'max_video_duration' ) * 60;
			$this->log = $log;

			# Set thumb resoloution settings
			$this->thumbsResSettings = array(
				"original" => "original",
				'105' => array('168','105'),
				'260' => array('416','260'),
				'320' => array('632','395'),
				'480' => array('768','432')
				);

			# Set 16:9 ratio conversion settings
			$this->res169 = array(
				'240' => array('428','240'),
				'360' => array('640','360'),
				'480' => array('854','480'),
				'720' => array('1280','720'),
				'1080' => array('1920','1080'),
				);

			# Set 4:3 ratio conversion settings
			$this->resolution4_3 = array(
				'240' => array('428','240'),
				'360' => array('640','360'),
				'480' => array('854','480'),
				'720' => array('1280','720'),
				'1080' => array('1920','1080'),
				);

			# Set basic ffmpeg configurations
			$this->ffmpegConfigs = array(
				'use_video_rate' => true,
				'use_video_bit_rate' => true,
				'use_audio_rate' => true,
				'use_audio_bit_rate' => true,
				'use_audio_codec' => true,
				'use_video_codec' => true,
				'format' => 'mp4',
				'videoCodec'=> config( 'video_codec' ),
				'audioCodec'=> config( 'audio_codec' ),
				'audioRate'=> config( 'srate' ),
				'audioBitrate'=> config( 'sbrate' ),
				'videoRate'=> config( 'vrate' ),
				'videoBitrate'=> config( 'vbrate' ),
				'videoBitrateHd'=> config( 'vbrate_hd' ),
				'normalRes' => config( 'normal_resolution' ),
				'highRes' => config( 'high_resolution' ),
				'maxVideoDuration' => config( 'max_video_duration' ),
				'resize'=>'max',
				'outputPath' => $this->outputDirectory,
				'cbComboRes' => config( 'cb_combo_res' ),
				'gen240' => config( 'gen_240' ),
				'gen360' => config( 'gen_360' ),
				'gen480' => config( 'gen_480' ),
				'gen720' => config( 'gen_720' ),
				'gen1080' => config( 'gen_1080' )
			);
		}

		/**
		* Action : Execute a command and return output 
		* Description : Its better to keep shell_exec at one place instead pulling string everywhere
		* @param : { string } { $command } { command to run }
		* @author : Saqib Razzaq
		* @since : 17th January, 2017
		*
		* @return : { mixed } { output of command ran }
		*/

		private function executeCommand( $command ) {
			return shell_exec( $command );
		}

		/**
		* Action : Parse required meta details of a video
		* Description : Conversion system can't proceed to do anything without first properly
		* knowing what kind of video it is dealing with. It is used to ensures that video resoloutions are 
		* extracted properly, thumbs positioning is proper, video qualities are legit etc.
		* If we bypass this information, we can end up with unexpected outputs. For example, you upload
		* a video of 240p and system will try to convert it to 1080 which means? You guessed it, DISASTER!
		* Hence, we extract details and then do video processing accordingly
		* @param : { boolean } { $filePath } { false by default, file to extract information out of }
		* @param : { boolean } { $durationOnly } { false by default, returns only duration of video }
		* @author : Saqib Razzaq
		* @since : 17th January, 2017
		*
		* @return : { array } { $responseData } { an array with response according to params }
		*/

		public function extractVideoDetails( $filePath = false, $durationOnly = false ) {
			
			if ( $filePath ) {
				$fileFullPath = $filePath;
			} else {
				$fileFullPath = $this->fileDirectory.'/'.$this->fileName;
			}

			if ( file_exists( $fileFullPath ) ) {
				$responseData = array();
				# if user passed paramter to get duration only
				if ( $durationOnly ) {
					# build mediainfo command for duration extraction
					$mediainfoDurationCommand = $this->mediainfoPath."   '--Inform=General;%Duration%'  '". $fileFullPath."' 2>&1 ";
					
					# execute command and store duration in array after rounding
					$responseData['duration'] = round( $this->executeCommand($mediainfoDurationCommand ) / 1000,2);

					# return resposneData array containing duration only
					return $responseData;
				} else {

					# Set default values for all required indexes before checking if they were found
					$responseData['format'] = 'N/A';
					$responseData['duration'] = 'N/A';
					$responseData['size'] = 'N/A';
					$responseData['bitrate'] = 'N/A';
					$responseData['videoWidth'] = 'N/A';
					$responseData['videoHeight'] = 'N/A';
					$responseData['videoWhRatio'] = 'N/A';
					$responseData['videoCodec'] = 'N/A';
					$responseData['videoRate'] = 'N/A';
					$responseData['videoBitrate'] = 'N/A';
					$responseData['videoColor'] = 'N/A';
					$responseData['audioCodec'] = 'N/A';
					$responseData['audioBitrate'] = 'N/A';
					$responseData['audioRate'] = 'N/A';
					$responseData['audioChannels'] = 'N/A';
					$responseData['path'] = $fileFullPath;

					# Start building ffprobe command for extracting extensive video meta
					$ffprobeMetaCommand = $this->ffprobePath;
					$ffprobeMetaCommand .= " -v quiet -print_format json -show_format -show_streams ";
					$ffprobeMetaCommand .= " '$fileFullPath' ";

					# Execute command and store data into variable
					$ffprobeMetaData = $this->executeCommand( $ffprobeMetaCommand );

					# Since returned data is json, we need to decode it to be able to use it
					$videoMetaCleaned = json_decode( $ffprobeMetaData );

					# stores name of codecs and indexes
					$firstCodecType = $videoMetaCleaned->streams[0]->codec_type;
					$secondCodecType = $videoMetaCleaned->streams[1]->codec_type;

					# assign codecs to variable with values accordingly
					$$firstCodecType = $videoMetaCleaned->streams[0];
					$$secondCodecType = $videoMetaCleaned->streams[1];

					# start to store required data into responseData array
					$video->width = (int) $video->width;
					$video->height = (int) $video->height;
					$responseData['format'] = $videoMetaCleaned->format->format_name;
					$responseData['duration'] = (float) round($video->duration,2);
					$responseData['bitrate'] = (int) $videoMetaCleaned->format->bit_rate;
					$responseData['videoBitrate'] = (int) $video->bit_rate;
					$responseData['videoWidth'] = $video->width;
					$responseData['videoHeight'] = $video->height;

					if( $video->height ) {
						$responseData['videoWhRatio'] = $video->width / $video->height;
					}

					$responseData['videoCodec'] = $video->codec_name;
					$responseData['videoRate'] = $video->r_frame_rate;
					$responseData['size'] = filesize($fileFullPath);
					$responseData['audioCodec'] = $audio->codec_name;;
					$responseData['audioBitrate'] = (int) $audio->bit_rate;;
					$responseData['audioRate'] = (int) $audio->sample_rate;;
					$responseData['audioChannels'] = (float) $audio->channels;
					$responseData['rotation'] = (float) $video->tags->rotate;

					/*
					* in some rare cases, ffprobe won't be able to extract video duration
					* we'll check if duration is empty and if so, we'll try extracting duration
					* via mediainfo instead
					*/

					if( !$responseData['duration'] )	{
						$mediainfoDurationCommand = $this->mediainfoPath."   '--Inform=General;%Duration%'  '". $fileFullPath."' 2>&1 ";
						$duration = $responseData['duration'] = round($this->executeCommand($mediainfoDurationCommand) / 1000,2);
					}

					$videoRate = explode('/',$responseData['video_rate']);
					$int_1_videoRate = (int) $videoRate[0];
					$int_2_videoRate = (int) $videoRate[1];
					
					/*
					* There are certain info bits that are not provided in ffprobe Json Streams
					* like video's original height and width. When dealing with videos like SnapChat
					* and Instagram or other mobile formats, it becomes crucial to fetch video height
					* and width properly or video will be stretched or blurred out due to poor params
					* Lets build command for exracting video meta using mediainfo
					*/
					$mediainfoMetaCommand = $this->mediainfoPath . "   '--Inform=Video;'  ". $fileFullPath;

					# extract data and store into variable
					$mediainfoMetaData = $this->executeCommand( $mediainfoMetaCommand );

					# parse out video's original height and save in responseData array
					$needleStart = "Original height";
					$needleEnd = "pixels"; 
					$originalHeight = find_string( $needleStart,$needleEnd,$mediainfoMetaData );
					$originalHeight[1] = str_replace( ' ', '', $originalHeight[1] );

					if ( !empty($originalHeight) && $originalHeight != false ) {
						$origHeight = trim( $originalHeight[1] );
						$origHeight = (int) $origHeight;
						if( $origHeight !=0 && !empty( $origHeight ) ) {
							$responseData['videoHeight'] = $origHeight;
						}
					}

					# parse out video's original width and save in responseData array
					$needleStart = "Original width";
					$needleEnd = "pixels"; 
					$originalWidth = find_string( $needleStart, $needleEnd, $mediainfoMetaData );
					$originalWidth[1] = str_replace( ' ', '', $originalWidth[1] );

					if( !empty( $originalWidth ) && $originalWidth != false ) {
						$origWidth = trim( $originalWidth[1] );
						$origWidth = (int)$origWidth;
						if( $origWidth > 0 && !empty( $origWidth ) ) {
							$responseData['videoWidth'] = $origWidth;
						}
					}

					if( $int_2_videoRate > 0 ) {
						$responseData['videoRate'] = $int_1_videoRate / $int_2_videoRate;
					}
				}

				return $responseData;
			}
		}

		/**
		* Check if conversion is locked or not
		* @param : { integer } { $defaultLockLimit } { Limit of number of max process }
		*/

		private final function isLocked( $defaultLockLimit = 1 ) {
			for ( $i=0; $i<$defaultLockLimit; $i++ )	{
				$convLockFile = $this->ffmpegLockPath.$i.'.loc';
				if ( !file_exists($convLockFile) ) {
					$this->ffmpegLock = $convLockFile;
					file_put_contents($file,"Video conversion processes running. Newly uploaded videos will stack up into qeueu for conversion until this lock clears itself out");
					return false;
				}
			}
			
			return true;
		}

		/**
		* Creates a conversion loc file
		* @param : { string } { $file } { file to be created }
		*/

		private static final function createLock( $file ) {
			file_put_contents($file,"converting..");
		}

		private function timeCheck() {
			$time = microtime();
			$time = explode( ' ',$time );
			$time = $time[1]+$time[0];
			return $time;
		}

		/**
		* Function used to start log that is later modified by conversion
		* process to add required details. Conversion logs are available
		* in admin area for users to view what went wrong with their video
		*/

		private function startLog() {
			$this->TemplogData  = "Started on ".NOW()." - ".date("Y M d")."\n\n";
			$this->TemplogData  .= "Checking File...\n";
			$this->TemplogData  .= "File : {$this->inputFile}";
			$this->log->writeLine("Starting Conversion",$this->TemplogData , true);
		}

		/**
		* Function used to convert seconds into proper time format
		* @param : INT duration
		* @parma : rand
		*/
		 
		private function ChangeTime( $duration, $rand = "" ) {
			if( $rand != "" ) {
				if( $duration / 3600 > 1 ) {
					$time = date( "H:i:s", $duration - rand( 0,$duration ) );
				} else {
					$time =  "00:";
					$time .= date( "i:s", $duration - rand( 0,$duration ) );
				}

				return $time;
			} elseif ( $rand == "" ) {
				if( $duration / 3600 > 1 ) {
					$time = date( "H:i:s",$duration );
				} else {
					$time = "00:";
					$time .= date( "i:s",$duration );
				}

				return $time;
			}
		}


		/**
		* Function used to log video info
		*/

		function logFileInfo() {
			$details = $this->inputDetails;
			if ( is_array( $details ) ) {
				foreach( $details as $name => $value ) {
					$configLog .= "<strong>{$name}</strong> : {$value}\n";
				}
			} else {
				$configLog = "Unknown file details - Unable to get video details using FFMPEG \n";
			}

			$this->log->writeLine('Preparing file...',$configLog,true);
		}

		/**
		* Prepare file to be converted this will first get info of the file
		* @param : { string } { $file } { false by default, file to prepare }
		*/

		private function prepare( $file = false ) {
			global $db;
			
			if( $file ) {
				$this->inputFile = $file;
			}
				
			if( file_exists( $this->inputFile ) ) {
				$this->inputFile = $this->inputFile;
				$this->log->writeLine('File Exists','Yes',true);
			} else {
				$this->inputFile = TEMP_DIR.'/'.$this->inputFile;
				$this->log->writeLine('File Exists','No',true);
			}

			//Get File info
			$this->inputDetails = $this->extractVideoDetails();

			//Loging File Details
			$this->logFileInfo();

			//Gett FFMPEG version
			$ffmpegVersionCommand = FFMPEG_BINARY." -version";
			$result = $this->executeCommand( $ffmpegVersionCommand );
			$version = parse_version( 'ffmpeg',$result );
			
			$this->vconfigs['map_meta_data'] = 'map_meta_data';
			
			if( strstr( $version,'Git' ) ) {
				$this->vconfigs['map_meta_data'] = 'map_metadata';
			}
		}

		/**
		* Generates upto 5 thumbs for a given video. Those thumbs are taken
		* from same time stamp with different sizez
		*
		* @param : { array } { $array } { an array of parameters }
		* @param : { string } { $array : videoFile } { video file path }
		* @param : { integer } { $array : duration } { video file duration } 
		* @param : { string } { $array : dim } { dimensions of thumbs }
		* @author : Arslan Hassan or Awais Tariq or Fawaz Tahir or is it me? [ Saqib Razzaq ] (wondering)
		*/

		private function generateThumbs( $array ) {
			$inputFile = $array['videoFile'];
			$duration = $array['duration'];
			$dimension = $array['dim'];

			$num = $array['num'];

			if ( !empty( $array['sizeTag'] ) ) {
				$sizeTag = $array['sizeTag'];
			}

			if ( !empty( $array['fileDirectory'] ) ) {
				$regenerateThumbs = true;
				$fileDirectory = $array['fileDirectory'];
			}

			if ( !empty( $array['videoFileName'] ) ) {
				$filename = $array['videoFileName'];
			}

			if ( !empty( $array['rand'] ) ){
				$random = $array['rand'];		
			}

			$dimTemporary = explode( 'x', $dimension );
			$height = $dimTemporary[1];
			$suffix = $width  = $dimTemporary[0];
			
			$temporaryDirectory = TEMP_DIR.'/'.getName($inputFile);	
			mkdir($temporaryDirectory,0777);	

			if( !empty($sizeTag) ) {
				$sizeTag = $sizeTag.'-';
			}

			if ( !isset( $fileDirectory ) ) {
				$fileDirectory = THUMBS_DIR.'/'.$this->outputDirectory.'/'.$videoFileName;
			}

			if( $dimension != 'original' ) {
				$dimension = " -s $dimension  ";
			} else {
				$dimension = '';
			}

			/**
			* Files larger than 14 seconds have enough data for ClipBucket to generate thumbs 
			* smartly and videos less than that are thrown in else case to generate thumbs
			* without providing specific directions to ffmpeg
			*/

			if( $num > 1 && $duration > 14 ) {
				$duration = $duration - 5;
				$division = $duration / $num;
				$count = 1;

				for ( $id=3; $id <= $duration; $id++ ) {

					if ( empty( $filename ) ){
						$videoFileName = getName($inputFile)."-{$sizeTag}{$count}.jpg";	
					} else {
						$videoFileName = $filename."-{$sizeTag}{$count}.jpg";	
					}
					
					$fileDirectory = THUMBS_DIR.'/'.$this->outputDirectory.'/'.$videoFileName;

					$id	= $id + $division - 1;

					if( $random != "" ) {
						$time = $this->ChangeTime( $id,1 );
					} elseif( $random == "" ) {
						$time = $this->ChangeTime( $id );
					}

					# finall, time to build ffmpeg command
					
					$command = $this->ffmpegPath." -ss {$time} -i $inputFile -an -r 1 $dimension -y -f image2 -vframes 1 $fileDirectory ";
					pr($command,true);
					$output = $this->executeCommand( $command );	

					//checking if file exists in temp dir
					if( file_exists( $temporaryDirectory.'/00000001.jpg' ) ) {
						rename( $temporaryDirectory.'/00000001.jpg', THUMBS_DIR.'/'.$videoFileName );
					}

					$count = $count+1;

					if ( !$regenerateThumbs ) {
						$this->TemplogData .= "\r\n Command : $command ";
						$this->TemplogData .= "\r\n File : $file_path ";	
					}
				}
			} else {
				# This section handles conversion for files less than 14 seconds
				if ( empty( $filename ) ){
					$videoFileName = getName($inputFile)."-{$sizeTag}1.jpg";	
				} else {
					$videoFileName = $filename."-{$sizeTag}1.jpg";	
				}

				$thumbsOutputPath = $fileDirectory . $videoFileName;
				$command = $this->ffmpegPath." -i $inputFile -an $dimension -y -f image2 -vframes 5 $thumbsOutputPath ";

				$output = $this->executeCommand( $command );

				if ( !$regenerateThumbs ) {
					$this->TemplogData .= "\r\n Command : $command ";
					$this->TemplogData .= "\r\n File : $thumbsOutputPath ";
				}
			}
			
			# Time to throw out temporary directory
			rmdir( $temporaryDirectory );
		}

		/**
		* This is where all begins and video conversion is initiated.
		* This function then takes care of everything like setting resoloutions,
		* generating thumbs and other stuff
		* @param : { none }
		*/

		public function convert() {
			$useCrons = config( 'use_crons' );
			if( !$this->isLocked( $this->maxProsessesAtOnce ) || $useCrons == 'yes' ) {
				if( $useCrons == 'no' ) {
					//Lets make a file
					$locFile = $this->ffmpegLockPath.'.loc';
					$this->createLock( $locFile );
					$this->startTime = $this->timeCheck();
					$this->startLog();
					$this->prepare( $this->fullUploadedFilePath );
				
					$maxDuration = $this->maxDuration;
					$currentDuration = $this->inputDetails['duration'];

					/**
					* ClipBucket allows admins to set max duration video which is saved
					* in global admin configs. Our top priority should be to make sure
					* video larger than that duration never makes it to database but there
					* can always be glitches. This below part is final check for that.
					* If video longer than allowed duration, it simply won't convert
					* and error will be stored in video conversion log
					*/

					if ( $currentDuration > $this->maxDuration ) {
						$maxDurationMinutes = $this->maxDuration / 60; 
						$this->TemplogData   = "Video duration was ".$currentDuration." minutes and Max video duration is {$maxDurationMinutes} minutes, Therefore Video cancelled... Wohooo.\n";
						$this->TemplogData  .= "Conversion_status : failed\n";
						$this->TemplogData  .= "Failed Reason : Max Duration Configurations\n";
						
						$this->log->writeLine( "Max Duration configs", $this->TemplogData , true );
						$this->failedReason = 'max_duration';

						return false;
					} else {

						$ratio = substr( $this->inputDetails['videoWhRatio'], 0,7 );
						$ratio = (float) $ratio;

						$videoHeight = $this->configs['normal_res'];
						if( $videoHeight == '320' ) {
							$videoHeight='360';
						}

						$this->log->writeLine( "Thumbs Generation", "Starting" );
						$this->TemplogData = "";
						
						try {

							$thumbsSettings = $this->thumbsResSettings;
							foreach ( $thumbsSettings as $key => $thumbSize ) {
								$heightSetting = $thumbSize[1];
								$widthSetting = $thumbSize[0];
								$dimensionSetting = $widthSetting.'x'.$heightSetting;

								if( $key == 'original' ){
									$dimensionSetting = $key;
									$dimensionIdentifier = $key;	
								} else {
									$dimensionIdentifier = $widthSetting.'x'.$heightSetting;	
								}

								$thumbsSettings['videoFile'] = $this->inputFile;
								$thumbsSettings['duration'] = $this->inputDetails['duration'];
								$thumbsSettings['num'] = 2;
								$thumbsSettings['dim'] = $dimensionSetting;
								$thumbsSettings['sizeTag'] = $dimensionIdentifier;
								$this->generateThumbs( $thumbsSettings );
							}
							
						} catch(Exception $e) {
							$this->TemplogData .= "\r\n Errot Occured : ".$e->getMessage()."\r\n";
						}

						exit("TADA");
						$this->TemplogData .= "\r\n ====== End : Thumbs Generation ======= \r\n";
						$this->log->writeLine("Thumbs Files", $this->TemplogData , true );
						
						$hr = $this->configs['high_res'];
						$this->configs['video_width'] = $res[$nr][0];
						$this->configs['format'] = 'mp4';
						$this->configs['video_height'] = $res[$nr][1];
						$this->configs['hq_video_width'] = $res[$hr][0];
						$this->configs['hq_video_height'] = $res[$hr][1];
						$orig_file = $this->inputFile;
						
						// setting type of conversion, fetching from configs
						$this->resolutions = $this->configs['cb_combo_res'];

						$res169 = $this->res169;
						switch ($this->resolutions) {
							case 'yes': {
								$res169 = $this->reindex_required_resolutions($res169);
								
								$this->ratio = $ratio;
								foreach ($res169 as $value) 
								{
									$video_width=(int)$value[0];
									$video_height=(int)$value[1];

									$bypass = $this->check_threshold($this->input_details['video_height'],$video_height);
									logData($bypass,'reindex');
									if($this->input_details['video_height'] > $video_height-1 || $bypass)
									{
										$more_res['video_width'] = $video_width;
										$more_res['video_height'] = $video_height;
										$more_res['name'] = $video_height;
										logData($more_res['video_height'],'reindex');
										$this->convert(NULL,false,$more_res);
									
									}
								}
							}
							break;

							case 'no':
							default :
							{
								$this->convertVideo($orig_file);
							}
							break;
						}
						
						
						

						$this->end_time_check();
						$this->total_time();
						
						//Copying File To Original Folder
						if($this->keep_original=='yes')
						{
							$this->log->TemplogData .= "\r\nCopy File to original Folder";
							if(copy($this->inputFile,$this->original_output_path))
								$this->log->TemplogData .= "\r\nFile Copied to original Folder...";
							else
								$this->log->TemplogData.= "\r\nUnable to copy file to original folder...";
						}
						
						
						$this->log->TemplogData .= "\r\n\r\nTime Took : ";
						$this->log->TemplogData .= $this->total_time.' seconds'."\r\n\r\n";
						
					

						if(file_exists($this->output_file) && filesize($this->output_file) > 0)
							$this->log->TemplogData .= "conversion_status : completed ";
						else
							$this->log->TemplogData .= "conversion_status : failed ";
						
						$this->log->writeLine("Conversion Completed", $this->log->TemplogData , true );
						//$this->create_log_file();
						
						break;
					}
				}
			}

		}
	}


?>