<?php

/**
 * This is new file for conversion
 * now , FFMPEG will only be used for video conversion
 * @Author : Arslan Hassan
 * @Software : ClipBucket
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php - You Cannot MODIFY - REUSE THIS FILE
 * @website : http://clip-bucket.com/
 */
 
 
define("KEEP_MP4_AS_IS",config('keep_mp4_as_is'));
define("MP4Box_BINARY",get_binaries('MP4Box'));
define("FLVTool2_BINARY",get_binaries('flvtool2'));
define('FFMPEG_BINARY', get_binaries('ffmpeg'));

class ffmpeg 
{
	var $input_details = array(); //Holds File value
	var $output_details = array(); // Holds Converted File Details
	var $ffmpeg ; // path to ffmpeg binary
	var $input_file; //File to be converted
	var $output_file; //File after $file is converted
	var $tbl = 'video_files';
	var $row_id  ; //Db row id
	var $log; //Holds overall status of conversion
	var $start_time;
	var $end_time;
	var $total_time;
	var $configs = array();
	var $gen_thumbs; //If set to TRUE , it will generate thumbnails also
	var $remove_input = TRUE;
	var $gen_big_thumb = FALSE;
	var $h264_single_pass = FALSE;
	var $hq_output_file = '';
	var $log_file = '';
	var $input_ext = '';
	var $tmp_dir = '/';
	var $flvtool2 = '';
	var $thumb_dim = '120x90'; //Thumbs Dimension
	var $num_of_thumbs = '3'; //Number of thumbs
	var $big_thumb_dim = 'original'; //Big thumb size , original will get orginal video window size thumb othersie the dimension
	
	/**
	 * Initiating Class
	 */
	function ffmpeg($file)
	{
		$this->ffmpeg = FFMPEG_BINARY;
		$this->mp4box = MP4Box_BINARY;
		$this->flvtool2 = FLVTool2_BINARY;
		$this->input_file = $file;
		
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
	}
	
	
	/**
	 * Function used to convert video 
	 */
	function convert($file=NULL)
	{
		global $db;
		if($file)
			$this->input_file = $file;
		
		$this->log .= "\r\nConverting Video\r\n";
		

		$p = $this->configs;
		$i = $this->input_details;
		
		
		# Prepare the ffmpeg command to execute
		if(isset($p['extra_options']))
			$opt_av .= " -y {$p['extra_options']} ";

		# file format
		if(isset($p['format']))
			$opt_av .= " -f {$p['format']} ";
			
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
				$opt_av .= " -b $vbrate ";
		}
		
		
		# video size, aspect and padding
		
		$this->calculate_size_padding( $p, $i, $width, $height, $ratio, $pad_top, $pad_bottom, $pad_left, $pad_right );
		$use_vf = config('use_ffmpeg_vf');
		if($use_vf=='no')
		{
		$opt_av .= " -s {$width}x{$height} -aspect $ratio -padcolor 000000 -padtop $pad_top -padbottom $pad_bottom -padleft $pad_left -padright $pad_right ";
		}else
		{
			$opt_av .= "-s {$width}x{$height} -aspect  $ratio -vf 'pad=0:0:0:0:black'";
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
		if($p['use_audio_rate'])
		{
			if(isset($p['audio_rate']))
				$arate = $p['audio_rate'];
			elseif(isset($i['audio_rate']))
				$arate = $i['audio_rate'];
			if(!empty($arate))
				$opt_av .= " -ar $arate ";
		}
		$tmp_file = time().RandomString(5).'.tmp';
		
		$opt_av .= " -map_meta_data ".$this->output_file.":".$this->input_file;
	
		$command = $this->ffmpeg." -i ".$this->input_file." $opt_av ".$this->output_file."  2> ".TEMP_DIR."/".$tmp_file;
		
		//Updating DB
		//$db->update($this->tbl,array('command_used'),array($command)," id = '".$this->row_id."'");
		
		$output = $this->exec($command);
		if(file_exists(TEMP_DIR.'/'.$tmp_file))
		{
			$output = $output ? $output : join("", file(TEMP_DIR.'/'.$tmp_file));
			unlink(TEMP_DIR.'/'.$tmp_file);
		}
		
		
		#FFMPEG GNERETAES Damanged File
		#Injecting MetaData ysing FLVtool2 - you must have update version of flvtool2 ie 1.0.6 FInal or greater
		if($this->flvtool2)
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
	
	/**
	 * Function used to get file information using FFMPEG
	 * @param FILE_PATH
	 */
	 function get_file_info( $path_source =NULL) {
		
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
		$output = $this->exec( $this->ffmpeg." -i $path_source -acodec copy -vcodec copy -f null /dev/null 2>&1" );
		
		

		# parse output
		if( $this->parse_format_info( $output, $info ) === false )
			return false;
		
		return $info;
	}
	
	
	
	
	
	/**
	 * Function used to excute SHELL Scripts
	 */
	function exec( $cmd ) {
		# use bash to execute the command
		# add common locations for bash to the PATH
		# this should work in virtually any *nix/BSD/Linux server on the planet
		# assuming we have execute permission
		//$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\" ";
		return shell_exec( $cmd);
	}
	
	
	
	/**
	 * Author : Arslan Hassan
	 * parse format info
	 * 
	 * output (string)
	 *  - the ffmpeg output to be parsed to extract format info
	 * 
	 * info (array)
	 *  - see function get_encoding_progress
	 * 
	 * returns:
	 *  - (bool) false on error
	 *  - (bool) true on success
	 */
	 
	function parse_format_info( $output, & $info ) {

		# search the output for specific patterns and extract info
		# check final encoding message
		if( ereg( 'Unknown format', $output, $args) ) {
			$Unkown = "Unkown";
		} else {
			$Unkown = "";
		}
		if( ereg( 'video:([0-9]+)kB audio:([0-9]+)kB global headers:[0-9]+kB muxing overhead', $output, $args ) ) {
			$video_size = (float)$args[1];
			$audio_size = (float)$args[2];
		} else {
			return false;
		}

		# check for last enconding update message
		if( ereg( '(frame=([^=]*) fps=[^=]* q=[^=]* L)?size=[^=]*kB time=([^=]*) bitrate=[^=]*kbits/s[^=]*$', $output, $args ) ) {
			$frame_count = $args[2] ? (float)$args[2] : 0;
			$duration    = (float)$args[3];
		} else {
			return false;
		}

		$info['duration'] = $duration;
		$info['bitrate' ] = (integer)($info['size'] * 8 / 1024 / $duration);
		if( $frame_count > 0 )
			$info['video_rate']	= (float)$frame_count / (float)$duration;
		if( $video_size > 0 )
			$info['video_bitrate']	= (integer)($video_size * 8 / $duration);
		if( $audio_size > 0 )
			$info['audio_bitrate']	= (integer)($audio_size * 8 / $duration);
			# get format information
		if( ereg( "Input #0, ([^ ]+), from", $output, $args ) ) {
			$info['format'] = $args[1];
		}

		# get video information
		if( ereg( 'Video: ([^ ]+), ([^ ]+), ([0-9]+)x([0-9]+)( \[PAR ([0-9]+):([0-9]+) DAR ([0-9]+):([0-9]+)\])?', $output, $args ) ) {
			$info['video_codec'  ] = $args[1];
			$info['video_color'  ] = $args[2];
			$info['video_width'  ] = $args[3];
			$info['video_height' ] = $args[4];
			if( $args[5] ) {
				$par1 = $args[6];
				$par2 = $args[7];
				$dar1 = $args[8];
				$dar2 = $args[9];
				if( (int)$dar1 > 0 && (int)$dar2 > 0  && (int)$par1 > 0 && (int)$par2 > 0 )
					$info['video_wh_ratio'] = ( (float)$dar1 / (float)$dar2 ) / ( (float)$par1 / (float)$par2 );
			}
			# laking aspect ratio information, assume pixel are square
			if( $info['video_wh_ratio'] === 'N/A' )
				$info['video_wh_ratio'] = (float)$info['video_width'] / (float)$info['video_height'];
		}

		# get audio information
		if( ereg( "Audio: ([^ ]+), ([0-9]+) Hz, ([^\n,]*)", $output, $args ) ) {
			$info['audio_codec'   ] = $args[1];
			$info['audio_rate'    ] = $args[2];
			$info['audio_channels'] = $args[3];
		}

		# check if file contains a video stream
		return $video_size > 0;

		#TODO allow files with no video (only audio)?
		#return true;
	}
	
	/**
	 * Function used to insert data into database
	 * @param ARRAY
	 */
	
	function insert_data()
	{
		global $db;
		//Insert Info into database
		if(is_array($this->input_details))
		{
			foreach($this->input_details as $field=>$value)
			{
				$fields[] = 'src_'.$field;
				$values[] =  $value;
			}
			$fields[] = 'src_ext';
			$values[] = getExt($this->input_details['path']);
			$fields[] = 'src_name';
			$values[] = getName($this->input_details['path']);
			
			$db->insert(tbl($this->tbl),$fields,$values);	
			$this->row_id = $db->insert_id();
		}
	}
	
	/**
	 * Function used to update data of
	 */
	function update_data($conv_only=false)
	{
		global $db;
		//Insert Info into database
		if(is_array($this->output_details) && !$conv_only)
		{
			foreach($this->output_details as $field=>$value)
			{
				$fields[] = 'output_'.$field;
				$values[] = $value;
			}		
			$fields[] = 'file_conversion_log';
			$values[] = $this->log;
			$db->update(tbl($this->tbl),$fields,$values," id = '".$this->row_id."'");	
		}else
			$fields[] = 'file_conversion_log';
			$values[] = $this->log;
			$db->update(tbl($this->tbl),$fields,$values," id = '".$this->row_id."'");	
	}
	
	
	/**
	 * Function used to add log in log var
	 */
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
	 
	
	
	
	/**
	 * Function used to time check
	 */
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
	
	
	/**
	 * Function used to calculate video padding
	 */
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
	
	
	
	/** 
	 * Function used to perform all actions when converting a video
	 */
	function ClipBucket()
	{
		$conv_file = TEMP_DIR.'/conv_lock.loc';
		//We will now add a loop
		//that will check weather
		
		while(1)
		{
			$use_crons = config('use_crons');
			if(!file_exists($conv_file) || $use_crons=='yes' )
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
				$this->convert();
				$this->end_time_check();
				$this->total_time();
				
				//Copying File To Original Folder
				if($this->keep_original=='yes')
				{
					$this->log .= "\r\nCopy File to original Folder";
					if(copy($this->input_file,$this->original_output_path))
						$this->log .= "\r\File Copied to original Folder...";
					else
						$this->log .= "\r\Unable to copy file to original folder...";
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
					$this->generate_thumbs($this->input_file,$this->input_details['duration'],$big_th_dim,'big');
				
				if(!file_exists($this->output_file))
					$this->log("conversion_status","failed");
				else
					$this->log("conversion_status","completed");
					
				$this->create_log_file();

				
				if($use_crons=='no')
				{
					unlink($conv_file);
				}
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
	
	
	
	/**
	 * Function used to generate video thumbnails
	 */
	function generate_thumbs($input_file,$duration,$dim='120x90',$num=3,$rand=NULL)
	{
		$output_dir = THUMBS_DIR;
		$dimension = '';
		if($num=='big')
		{
		
			$file_name = getName($input_file)."-big.jpg";
			$file_path = THUMBS_DIR.'/'.$file_name;
			if($dim!='original')
				$dimension = " -s $dim  ";
			$time = $this->ChangeTime($duration,1);
			$command = $this->ffmpeg." -i $input_file -an -ss $time $dimension -y -f image2 -vframes 1 $file_path ";
			$this->exec($command);
		}else{
				
			if($num > 1 && $duration > 14)
			{
				$duration = $duration - 5;
				$division = $duration / $num;
				$count=1;
				for($id=3;$id<=$duration;$id++)
				{
					$file_name = getName($input_file)."-$count.jpg";
					$file_path = THUMBS_DIR.'/'.$file_name;
					
					$id	= $id + $division - 1;
					if($rand != "") {
						$time = $this->ChangeTime($id,1);
					} elseif($rand == "") {
						$time = $this->ChangeTime($id);
					}
					
					if($dim!='original')
						$dimension = " -s $dim  ";
						
					$command = $this->ffmpeg." -i $input_file -an -ss $time -an -r 1 $dimension -y -f image2 -vframes 1 $file_path ";
					$this->exec($command);
					$count = $count+1;
				}
			}else{

				$file_name = getName($input_file)."-%d.jpg";
				$file_path = THUMBS_DIR.'/'.$file_name;
				$command = $this->ffmpeg." -i $input_file -an -s $dim -y -f image2 -vframes $num $file_path ";
				$this->exec($command);
			}
		}
	}
	
	
	
	/**
	 * Function used to convert seconds into proper time format
	 * @param : INT duration
	 * @parma : rand
	 */
	 
	function ChangeTime($duration, $rand = "") {
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
	
	
	/**
	 * Function used to convert video in HD format
	 */
	
	function convert_to_hd($input=NULL,$output=NULL,$p=NULL,$i=NULL)
	{
		global $db;
		if(!$input)
			$input = $this->input_file;
		if(!$output)
			$output = $this->hq_output_file;
		if(!$p)
			$p = $this->configs;

		if(!$i)
			$i = $this->input_details;
		$convert  = false;
		//Checkinf for HD or Not
		$opt_av = '';

		if(substr($i['video_wh_ratio'],0,5) == '1.777' && $i['video_width'] > '500')
		{
			
			//All Possible Hd Dimensions
			$widths = 
			array(
			1280,
			1216,
			1152,
			1088,
			1024,
			960,
			896,
			832,
			768,
			704,
			640,
			576,
			512,
			);
			$heights = 
			array(
			720,
			684,
			648,
			612,
			576,
			540,
			504,
			468,
			432,
			396,
			360,
			324,
			288,
			);		
			
			$convert = true;
			$type = 'HD';
			//Checking if video is HQ then convert it in Mp4
		}elseif($i['video_width'] > '500' && substr($i['video_wh_ratio'],0,5) != '1.777')
		{
			$widths = array(
			640 ,
			624 ,
			608 ,
			592 ,
			576 ,
			560 ,
			544 ,
			528 ,
			512 ,
			);
			 
			$heights = array(
			 480,
			 468,
			 456,
			 444,
			 432,
			 420,
			 408,
			 396,
			);
			
			$convert = true;
			$type = 'HQ';
		}
		
		if($convert)
		{
			$total_dims = count($widths);
			
			//Checking wich dimension is suitable for the video
			for($id=0;$id<=$total_dims;$id++)
			{
				$cur_dim = $widths[$id];
				$next_dim = $widths[$id+1];
				$iwidth  = $i['video_width'];
				
				if($iwidth==$cur_dim || ($iwidth>$cur_dim && $iwidth<$next_dim))
				{
					$key = $id;
					$out_width = $widths[$id];
					$out_height = $heights[$id];
					break;
				}
			}
			$out_width = $out_width ? $out_width : $widths[0];
			$out_height = $out_height ? $out_height : $heights[0];
			$p['video_width'   ] = $out_width ;
			$p['video_height'  ] = $out_height;
			$p['resize']		 = 'WxH';
						
			//Calculation Size Padding
			$this->calculate_size_padding( $p, $i, $width, $height, $ratio, $pad_top, $pad_bottom, $pad_left, $pad_right );
			$opt_av .= "-s {$width}x{$height} -aspect  $ratio -padcolor 000000 -padtop $pad_top -padbottom $pad_bottom -padleft $pad_left -padright $pad_right";
			
			$output = "";
			$command = $this->ffmpeg." -i ".$this->input_file." $opt_av -acodec libfaac -ab 96k -vcodec libx264 -vpre hq -crf 22 -threads 0 ".$this->hq_output_file."  2> ".TEMP_DIR."/output.tmp ";	
			
			if(KEEP_MP4_AS_IS=="yes" && $this->input_ext=='mp4')
				copy($this->input_file,$this->hq_output_file);
			else
				$output = $this->exec($command);
				
			if(file_exists(TEMP_DIR.'/output.tmp'))
			{
				$output = $output ? $output : join("", file(TEMP_DIR.'/output.tmp'));
				unlink(TEMP_DIR.'/output.tmp');
			}
			
			/**
			 * Mp4 files are mostly not converted properly
			 * we have to use Mp4box in order
			 * to play them in regular manner, otherwise Flash players will 
			 * load whole video before playing it
			 */
			$mp4_output = "";
			$command = $this->mp4box." -inter 0.5 ".$this->hq_output_file." -tmp ".$this->tmp_dir." 2> ".TEMP_DIR."/mp4_output.tmp ";
			$mp4_output .= $this->exec($command);
			if(file_exists(TEMP_DIR.'/mp4_output.tmp'))
			{
				$mp4_output = $mp4_output ? $mp4_output : join("", file(TEMP_DIR.'/mp4_output.tmp'));
				unlink(TEMP_DIR.'/mp4_output.tmp');
			}
			$ouput .= $mp4_output;
			
			$this->log .= "\r\n\r\n\n=========STARTING $type CONVERSION==============\r\n\r\n\n";
			$this->log("$type Video -- Conversion Command",$command);
			$this->log .="\r\n\r\nConversion Details\r\n\r\n";
			$this->log .= $output;
			$this->log .= "\r\n\r\n\n=========ENDING $type CONVERSION==============\n\n";
			
			$fields = array('file_conversion_log',strtolower($type));
			$values = array(mysql_clean($this->log),'yes');
			//$db->update($this->tbl,$fields,$values," id = '".$this->row_id."'");
			$this->create_log_file();
			return true;
		}else
			return false;
	}
	
	/**
	 * Function used to create log for a file
	 */
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
}
?>