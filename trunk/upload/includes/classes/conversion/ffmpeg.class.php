<?php
#Author : Arslan^
#Copyright : ClipBucket
#License : CBLA
#
/*
Limitations:
-------------
You May Edit This File For your OWN WEBSITE
- You Are Not Able To Use This On Other Script
- You Cannnot Resale It or SHARE it
- For Details , Please Read our CBLA
*/
define('ENCODING_LOGGING','yes');
class ffmpeg {
	//Special Function Used TO get duration
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

	//THIS FUNCTION IS USED TO GENERATE THUMBS
	function AssignGeneratedThumbs($flv,$duration,$rand = "") {
		global $row;
		$filename_minus_ext	= substr($flv, 0, strrpos($flv, '.'));
		$thumbnail_temp_dir	= BASEDIR.'/files/temp';
		$thumbnail_output_dir	= BASEDIR.'/files/thumbs';
		$ffmpeg			= FFMPEG_BINARY;
		$flv_file		= BASEDIR.'/files/temp/'.$flv;
		if(!file_exists($flv_file)) {
			$flv_file	= BASEDIR.'/files/videos/'.$flv;
		}
		$t_height		= $row['thumb_height'];
		$t_width		= $row['thumb_width'];
		$t_dim = $t_width.'x'.$t_height;
		
		$thumb_log =  BASEDIR.'/logs/thumblog.txt';
		if($duration > 14 ) {
			$duration = $duration - 5;
			//Setting oF Thumbs Duration
			$division = $duration / 3;
			$count=1;
			for($id=3;$id<=$duration;$id++) {
				$id		= $id + $division - 1;
				if($rand != "") {
					$time = $this->ChangeTime($id,1);
				} elseif($rand == "") {
					$time		= $this->ChangeTime($id);
				}
				$command	= "$ffmpeg -i $flv_file -an -ss $time -an -r 1 -s $t_dim -y -f image2 -vframes 1 $thumbnail_temp_dir/$filename_minus_ext-$count.jpg  &> $thumb_log";
				$this->exec("$command",$output);
				$count = $count+1;
			}
		} else {
			$command = "$ffmpeg -i $flv_file -an -s $t_dim -y -f image2 -vframes 3 $thumbnail_temp_dir/$filename_minus_ext-%d.jpg &> $thumb_log";
			if(!isset($output))
				$output = "";
			$this->exec($command,$output);
		}
		$command2 = "$ffmpeg -i $flv_file -an -s 320x240 -y -f image2 -vframes 1 $thumbnail_temp_dir/$filename_minus_ext-big.jpg";
		$this->exec($command2);

		//Checkin IF Thumnails Have Been Generated Or Not
		for($id=1;$id<=3;$id++) {
			if(file_exists($thumbnail_temp_dir.'/'.$filename_minus_ext.'-'.$id.'.jpg')) {
				@unlink($thumbnail_output_dir/$filename_minus_ext.'-'.$id.'.jpg');
				copy($thumbnail_temp_dir.'/'.$filename_minus_ext.'-'.$id.'.jpg',$thumbnail_output_dir.'/'.$filename_minus_ext.'-'.$id.'.jpg');
				@unlink($thumbnail_temp_dir.'/'.$filename_minus_ext.'-'.$id.'.jpg');
			}
		}
		if(file_exists($thumbnail_temp_dir.'/'.$filename_minus_ext.'-big.jpg')) {
			@unlink($thumbnail_output_dir.'/'.$filename_minus_ext.'-big.jpg');
			copy($thumbnail_temp_dir.'/'.$filename_minus_ext.'-big.jpg',$thumbnail_output_dir.'/'.$filename_minus_ext.'-big.jpg');
			@unlink($thumbnail_temp_dir.'/'.$filename_minus_ext.'-big.jpg');
		}
	}

	//THIS FUNCTION IS USED TO GENERATE DEFAULT THUMBS
			function AssignDefaultThumb($flv){
/*				global $LANG,$row;
				$site_template = BASEDIR.'/styles/'.$row['template_dir'];
				//Minus Extension
				$filename_minus_ext = substr($flv, 0, strrpos($flv, '.'));
				$proccesing_thumb = $site_template.'/images/'.LANG.'/processing.png';
				$proccesing_thumb_big = $site_template.'/images/'.LANG.'/processing-big.png';
				copy($proccesing_thumb,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-1.jpg');
				copy($proccesing_thumb,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-2.jpg');
				copy($proccesing_thumb,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-3.jpg');	
				copy($proccesing_thumb_big,BASEDIR.'/files/thumbs/'.$filename_minus_ext.'-big.jpg');
*/			
			}

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
	function ConvertFile($file,$flv) {
		global $stats,$db,$row;
		if($this->ValidateFile($file)) {
			$flvtool2 		= FFMPEG_FLVTOOLS_BINARY;
			$vbrate 		= VBRATE;
			$srate			= SRATE;
			$sbrate			= $row['sbrate'];
			$r_height		= R_HEIGHT;
			$ffmpeg 		= FFMPEG_BINARY;
			$r_width		= R_WIDTH;
			$resize			= RESIZE;
			$keep_original	= KEEP_ORIGINAL;
			$max_size		= MAX_UPLOAD_SIZE;
			$max_encode		= 3;
			$vcodec			= VCODEC;
			$acodec			= ACODEC;
			$video_file = BASEDIR.'/files/temp/'.$file;
			$flv_file = BASEDIR.'/files/videos/'.$flv;
			$extension = substr($video_file, strrpos($video_file,'.') + 1);
			//Check VIdeo File Size
			$size = filesize($video_file);
			if($size > $max_size*1024*1024) {
				$status = "Failed";
				$flv_file = "failed.flv";
			} else {
				
				$resize = $resize == 'yes' ? 'WxH' : 'no';
				$CONFIG['ffmpeg']['resize']			= $resize;
				$CONFIG['ffmpeg']['format']			= 'flv';
				$CONFIG['ffmpeg']['audio_codec'   ] = $row['audio_codec'];
				$CONFIG['ffmpeg']['audio_rate']		= $srate;
				$CONFIG['ffmpeg']['audio_bitrate']	= $sbrate;
				$CONFIG['ffmpeg']['video_codec']	= 'flv';
				$CONFIG['ffmpeg']['video_width']	= $r_width;
				$CONFIG['ffmpeg']['video_height']	= $r_height;
				$CONFIG['ffmpeg']['video_max_rate']	= 25;
				$CONFIG['ffmpeg']['video_bitrate']	= $vbrate;
				$CONFIG['ffmpeg']['video_rate']		= '800';
				$CONFIG['ffmpeg']['flvtool2']		= $flvtool2;
				$info = $this->get_file_info( $video_file );
				//print_r($info);

				$record['src_ext']			= substr( $file, strrpos( $file, '.' ) + 1 );
				$record['src_format']		= $info['format'];
				$record['src_duration']		= $info['duration'];
				$record['src_size']			= $info['size'];
				$record['src_bitrate']		= $info['bitrate'];
				$record['src_video_width']	= $info['video_width'];
				$record['src_video_height']	= $info['video_height'];
				$record['src_video_wh_ratio']	= $info['video_wh_ratio'];
				$record['src_video_codec']	= $info['video_codec'];
				$record['src_video_rate']	= $info['video_rate'];
				$record['src_video_bitrate']	= $info['video_bitrate'];
				$record['src_video_color']	= $info['video_color'];
				$record['src_audio_codec']	= $info['audio_codec'];
				$record['src_audio_bitrate']	= $info['audio_bitrate'];
				$record['src_audio_rate']	= $info['audio_rate'];
				$record['src_audio_channels']	= $info['audio_channels'];

				$parameters = $CONFIG['ffmpeg'];
				$parameters['path_source']	= $video_file;
				$parameters['path_log']		= BASEDIR."/logs/logs.txt";
				$parameters['path_target']	= $flv_file;
				//$lock = tempnam(BASEDIR . "/files/temp", "LOCK");
				if(!isset($extension))
					$extension = "";
				if($extension != 'flv') {
					if(isset($info['Unknown'])) {
						if($info['Unknown'] == "Unknwon") {
							exit;
						} 
					}
					//for(;;) {
						//if($max_encode >= count(glob(BASEDIR."/files/temp/LOCK*"))) {
							$this->start_encoding( $parameters, $info, $lock );
							//break;
						//}
					//}
				} else {
					copy($video_file,$flv_file);
				}
				
				$sec = $info['duration'];
				if(!isset($sec))
					$sec = "1";
				

				/////////////////////////////////////////////////////////////
				//                        STEP 2                           //
				//                  FLVTOOL2 INJECTION                     //
				/////////////////////////////////////////////////////////////
				/*if($flvtool2 != '') {
					while(1) {
						if(!is_file($lock))
							break;
					}
					$flv_cmd = "$flvtool2 -U $flv_file";
					if(!isset($output))
						$output = "";
					$this->exec("$flv_cmd >> ".BASEDIR."/logs/logs.txt 2>&1", $output);
				}*/

				$status = "Successful";
				
				$this->AssignGeneratedThumbs($file,$sec);
				$db->Execute("INSERT INTO `video_detail` (`flv`,`status`,`duration`,`original`) VALUES ('".$flv."','".$status."','".$sec."','".$file."')");
				$db->Execute("UPDATE LOW_PRIORITY `video` SET `duration` = '".$sec."' , status='".$status."' WHERE `flv` = '".$flv."'");
				
				if($status == "Successful") { $stats->UpdateVideoRecord(8); }
				
				if($keep_original == 1) {
					$original_file = BASEDIR.'/files/original/'.$file;
					copy($video_file,$original_file);
				} 
				
				if(is_file($video_file))
					unlink($video_file);
			}//If MAX SIZE CONDITION ENDs
		}
		if(!file_exists($flv_file)) {
			$status = "Failed";
			$stats->UpdateVideoRecord(10);
			$this->AssignDefaultThumb($flv);
			$db->Execute("UPDATE LOW_PRIORITY `video_detail` SET `status` = '".$status."' WHERE `flv` = '".$flv."'");
			$db->Execute("UPDATE LOW_PRIORITY `video` SET `active` = 'no' AND `status` = '".$status."' WHERE `flv` = '".$flv."'");
		}
	}

	###############################################################
	# start encoding
	# NOTE: see file av_encoder.class.php for interface details
	# Author : Arslan Hassan, Made For ClipBucket
	##############################################################
	function start_encoding( $parameters, $source_info, $lockfile ) {
		$ffmpeg = FFMPEG_BINARY;

		$p = & $parameters;
		$i = & $source_info;

		$opt_av = " -y ";
		$p['video_codec'];
		# Prepare the ffmpeg command to execute
		if(isset($p['extra_options']))
			$opt_av .= " -y {$p['extra_options']} ";

		# file format
		if(isset($p['format']))
			$opt_av .= " -f {$p['format']} ";
		# video codec, frame rate and bitrate
		$video_rate = min( $p['video_max_rate'], $i['video_rate'] );
		$opt_av .= " -vcodec {$p['video_codec']} -b {$p['video_bitrate']} -r $video_rate ";

		# video size, aspect and padding
		$this->calculate_size_padding( $p, $i, $width, $height, $ratio, $pad_top, $pad_bottom, $pad_left, $pad_right );
		$opt_av .= " -s {$width}x{$height} -aspect $ratio -padcolor 000000 -padtop $pad_top -padbottom $pad_bottom -padleft $pad_left -padright $pad_right ";
		
		# audio codec, rate and bitrate
		if(!empty($p['audio_codec']) && $p['audio_codec'] != 'None'){
		$opt_av .= " -acodec {$p['audio_codec']}";
		}
		# audio codec, rate and bitrate
		$opt_av .= " -ar {$p['audio_rate']} -ab {$p['audio_bitrate']} ";
		
	
		
		if(!isset($output))
			$output = "";
		//$lockfile = BASEDIR . "/files/temp/lock.tmp";
		# execute ffmpeg, send output to the log file, run in background, with low priority (niced)	
		//$this->exec("echo $ffmpeg -i {$p['path_source']} $opt_av {$p['path_target']} >> {$p['path_log']} ");
		$this->exec( "$ffmpeg -i '{$p['path_source']}' $opt_av '{$p['path_target']}' &> '{$p['path_log']}'" );
		
		# Adding FLVtool2 for addin video meta deta
		if(!empty($p['flvtool2'])){
		$this->exec( "{$p['flvtool2']} -U {$p['path_target']} >> '{$p['path_log']}'" );
		$opt_av .= " {$p['flvtool2']} -U {$p['path_target']} >> '{$p['path_log']}'";
		}
		
		$this->exec( "echo $ffmpeg -i {$p['path_source']} $opt_av {$p['path_target']} >> '{$p['path_log']}'" );
	}

	###############################################################
	# get encoding progress
	# NOTE: see file av_encoder.class.php for interface details
	# Author : Pedro,Arslan Hassan, Made For ClipBucket
	###############################################################
	function get_file_info( $path_source ) {
		$ffmpeg = FFMPEG_BINARY;
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
		$info['audio_rate']	= 'N/A';
		$info['audio_channels']	= 'N/A';
		# get the file size
		$stats = stat( $path_source );
		if( $stats === false )
			trigger_error( "Failed to stat file $path_source!", E_USER_ERROR );
		$info['size'] = (integer)$stats['size'];
		$output = $this->exec( "$ffmpeg -i '$path_source' -acodec copy -vcodec copy -f null /dev/null 2>&1" );
		# parse output
		if( $this->parse_format_info( $output, $info ) === false )
			return false;

		return $info;

	}

	###############################################################
	# Author : Pedro,ArslanHassan , Made For ClipBucket
	# parse format info
	#
	# output (string)
	#  - the ffmpeg output to be parsed to extract format info
	#
	# info (array)
	#  - see function get_encoding_progress
	#
	# returns:
	#  - (bool) false on error
	#  - (bool) true on success
	###############################################################
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

	function calculate_size_padding( $parameters, $source_info, & $width, & $height, & $ratio, & $pad_top, & $pad_bottom, & $pad_left, & $pad_right ) {
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
					$height = $width / $ratio;

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

	###############################################################
	# exec
	#
	# exec shell scripts using bash
	#
	###############################################################
	function exec( $cmd ) {
		# use bash to execute the command
		# add common locations for bash to the PATH
		# this should work in virtually any *nix/BSD/Linux server on the planet
		# assuming we have execute permission
		//$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\" ";
		return shell_exec( $cmd );
	}

	###############################################################
	# list_formats
	#
	# List Available File Formats (Do not use)
	#
	###############################################################
	function list_formats($format) {

		if($format == 'encoder') {
			$formats = "E";
		} elseif($format == 'decoder') {
			$formats = "D";
		} else {
			$formats = "DE";
		}

		$ffmpeg = FFMPEG_BINARY;
		exec("$ffmpeg -formats 2>&1", $output);

		$formatstart = array_search('File formats:', $output);
		$formatend = array_search('Codecs:', $output);

		$formatstart = $formatstart + 1;
		$formatend = $formatend - 2;

		$i = 0;
		foreach (range($formatstart,$formatend) as $number) {
			$output[$number] = preg_replace('/^\s+/', '', $output[$number]);
			list($enc[$number],$name[$number],$desc[$number]) = preg_split('/\s+/', $output[$number], 3);

			if(ereg($formats, $enc[$number])) {
				$listing[$i] = array('encode/decode' => $enc[$number], 'name' => $name[$number], 'description' => $desc[$number]);
				$i++;
			}
		}
		return $listing;
	}

	###############################################################
	# list_codecs
	#
	# List Available Codecs
	#
	###############################################################
	function list_codecs($format, $type) {

		if($format == "encode") {
			$formats = "E";
		} elseif ($format = "decode") {
			$formats = "D";
		} else {
			return false;
		}
		if($type == "video") {
			$types = "V";
		} elseif ($type == "audio") {
			$types = "A";
		}

		$ffmpeg = FFMPEG_BINARY;
		exec("$ffmpeg -formats 2>&1", $output);

		$codecstart = array_search('Codecs:', $output);
		$codecend = array_search('Bitstream filters:', $output);
		$codecstart = $codecstart + 1;
		$codecend = $codecend - 2;

		$i = 0;

		foreach (range($codecstart, $codecend) as $number) {
			$output[$number] = preg_replace('/^\s+/', '', $output[$number]);
			$enc[$number] = preg_replace('/^([D E V A S T]+) (.+?)$/', '$1', $output[$number]);
			$tmp[$number] = preg_replace('/^([D E V A S T]+) (.+?)$/', '$2', $output[$number]);
			list($name[$number],$desc[$number]) = preg_split('/\s+/', $tmp[$number], 2);

			if(ereg($formats, $enc[$number])) {
				if(ereg($types, $enc[$number])) {
					$listing[$i] = array('encode/decode' => $enc[$number], 'name' => $name[$number], 'description' => $desc[$number]);
					$i++;
				}
			}
		}
		return $listing;
	}
	
	
	
}
?>