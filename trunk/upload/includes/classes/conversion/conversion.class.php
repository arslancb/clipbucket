<?php

/**
 * @author : Arslan Hassan
 * @copyright : ClipBucket Conversion Kit 2012
 * @license : AAL (Attribute Assurance License)
 * @package : ClipBucket CSDK
 * @version : 3.0
 * @name : CBConverter
 * @todo : Convert Videos to Mp4,mp4-mobile, Flv, Ogg with H264
 * 
 */
/*
 * if(!defined('IN_CLIPBUCKET')) exit("Sorry, visitors not allowed");
 */
define('FFMPEG', get_binaries('ffmpeg'));
define('MPLAYER', config('mplayerpath'));
define('MP4BOX', get_binaries('MP4Box'));
define('MEDIAINFO', '/usr/bin/mediainfo');

/**
 * These static variables are already defined in ClipBucket,
 * if you are not using this SDK with ClipBucket, please uncomment
 * following static variables and define them accordingly
 * 
 * define("TEMP_DIR","/path/to/temp_dir"); 
 * 
 */
class CBConverter
{

    var $input; //Input File
    var $output; //Output File
    var $inDetails; //Details and Information of Video Input
    var $outDetails; //Details and Information of Video Output
    //Errors, Warnings and Messages Log, its an array
    var $log;
    //Max FPS, we are coverting videos for web streaming and 30 fps is more than
    //Enough to get a good quality video for online streaming, if you have
    //Plans to incrase it, simply change max_fps value and then make changes
    //In your input values..
    var $max_fps = 30;
    //Max Bitrate, for web streaming 512k gives you a very good video quality
    //Youtube uses around 365k which is also not bad, here will we set
    //640k as max, where k => 1000;
    var $max_bitrate = 640000;
    //Allowed list of Audio Rates, any input which does not match
    //With any of the following will be replaced with the default
    //Audio Rate
    var $audio_rates = array('22050', '44100', '48000');
    //Setting up max audio bitrate, usually 128k is good but incase you want 
    //To enhance the video quality you can incrase it. read more about
    //Audio bitrate on google
    var $max_audio_bitrate = 396000;
    //IN some cases ffmpeg is not abelt convert video with more than 5 channels
    //In this situtation we have to force use 2 channels for the output video
    //As for web video, 2 channel video is actualyl pretty good
    //By setting it to true, we will use force 2 channelf or videos more than
    // 5 channels otherwise it will skipp the step
    var $valid_two_channels = true;
    //Log file where output of every command will be given.
    //By default is set to false so that defined output file can be used
    //if value set, this file will be used to save outptu.
    var $output_log = false;
    //Preset paths are location where ffmpeg can load
    //Preset files from, usually we don't need to
    //Add the path as it is already defined in $HOME variable
    //But due to limitation on server this variable is quite
    //$this->set_preset_path() to set the preset path
    //Helpful.
    var $preset_path = NULL;

    /**
     * Constructor and sets the input file and get information..
     * @param Video File
     */
    function CBConverter($file = NULL)
    {
        if ($file)
        {
            $this->input = $file;
            $info = $this->getInfo();
            $this->inDetails = $info;
        }
    }

    /**
     * @name : getInfo
     * @todo : Get Video Information
     * @param STRING input
     * @return ARRAY video details
     */
    function getInfo($input = false)
    {
        if (!$input)
            $input = $this->input;

        if (!file_exists($input))
        {
            $this->log('<strong>' . $input . '</strong> File does not exist...');
            return $this->log();
        }
        $info = $this->_getInfo($input);
        return $info;
    }

    /**
     * @name : _getInfo
     * @todo : Generate video info using FFMPEG or php-ffmpeg (if available)
     * @param STRING input video
     * @return ARRAY of video details
     */
    private function _getInfo($input)
    {
        //Checking if php-ffmpeg extension is available
        //there is no ffmpeg-php for windows..will work on it later...
        $CMD = FFMPEG . ' -i "' . $input . '" -acodec copy' .
                ' -vcodec copy -f null ';

        //Add /dev/null in non win os
        if (!stristr(php_uname('s'), 'windows'))
            $CMD .= '/dev/null';

        $rawOutput = $this->exec($CMD, true);
        $parsedData = $this->parseData($rawOutput);


        //Now Getting information from media info
        $CMD = MEDIAINFO . ' ' . $input;
        $rawOutput = $this->exec($CMD, true);
        //Get Parsed Data from media Info
        $miParsedData = $this->parseData($rawOutput, true);

        //Merge two arrays
        if ($miParsedData)
            $parsedData = array_merge($parsedData, $miParsedData);

        return $parsedData;
    }

    /**
     * function get ffmpeg version
     * @name getVersion
     * @return ffmpeg version
     */
    function getVersion()
    {
        $CMD = FFMPEG . ' -version';
        $output = $this->exec($CMD, true);
        $array = array();


        //Checking for revision
        preg_match('/ffmpeg version N\-([0-9]+)\-/i', $output, $rev);
        $rev = $rev[1];

        if ($rev)
            $array['rev'] = $rev;

        //Checking for version..
        preg_match('/ffmpeg version ([0-9.]+)/', $output, $ver);
        $ver = $ver[1];

        if ($ver)
            $array['ver'] = $ver;

        //Checking for built date
        preg_match('/built on ([A-Za-z]{3}) ([0-9]{1,3}) ([0-9]{4}) /', $output, $date);

        $date = $date[1] . ' ' . $date[2] . ' ' . $date[3];

        $time = strtotime($date);


        if ($date)
        {
            $array['date'] = date('Y-m-d', $time);
            $array['timestamp'] = $time;
        }

        return $array;
    }

    /**
     * @name : log
     * @todo : Log Message or Error and Insert it into log (Array)
     * @param STRING message
     * @param STRING type (e,w,m) for (errors,warnings and messages) res..
     * @return ARRAY of log
     */
    function log($msg = NULL, $type = e, $index = null)
    {
        if (!$msg)
            return $this->log;

        if ($index)
            $this->log[$type][$index] = $msg;
        else
            $this->log[$type][] = $msg;

        return $this->log;
    }

    /**
     * @name exec
     * @param type $cmd
     * @param type $output
     * @return type 
     */
    function exec($cmd, $output = false)
    {
        # use bash to execute the command
        # add common locations for bash to the PATH
        # this should work in virtually any *nix/BSD/Linux server on the planet
        # assuming we have execute permission
        //$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\" ";

        $output_cmd = "";
        if ($output)
        {
            if (function_exists('RandomString'))
                $outputFile = TEMP_DIR . '/' . time() . RandomString(5);
            else
                $outputFile = TEMP_DIR . '/' . time() . mt_rand(5, 6);

            if ($this->output_log)
                $outputFile = $this->output_log;

            $output_cmd = " 2> " . $outputFile;
        }

        $result = shell_exec($cmd . $output_cmd);
        
        if (!$result && $output)
        {
            //Read output file..
            if (file_exists($outputFile))
                $result = file_get_contents($outputFile);
        }
        
        if (file_exists($outputFile) && !$this->output_log)
            unlink($outputFile)
                    or $this->log('Unable to remove tmp bash output file', 'e');

        return $result;
    }

    /**
     * @name parseData
     * @todo Parse data and list them in array
     * @param STRING $content
     * @param BOOLEAN $mi MediaInfo data
     * @return ARRAY 
     */
    function parseData($content, $mi = false)
    {
        $info = array();

        //Things we need to extract
        /**
         * duration
         * width and height
         * bitrate
         * frame rate
         * audio bitrate
         * audio rate
         * audio channels
         * video codec
         * audio codec
         * format
         */
        #Getting Duration
        $duration = $this->pregMatch('Duration: ([0-9.:]+),', $content);
        $duration = $duration[1];

        $duration = explode(':', $duration);
        //Convert Duration to seconds
        $hours = $duration[0];
        $minutes = $duration[1];
        $seconds = $duration[2];

        $hours = $hours * 60 * 60;
        $minutes = $minutes * 60;

        $duration = $hours + $minutes + $seconds;

        $info['duration'] = $duration;

        #Video Bitrate
        //Setting has_video bydefault to no 
        $info['has_video'] = 'no';

        $args = $this->pregMatch('bitrate: ([0-9]+) kb\/s', $content);
        if ($args)
        {
            $info['bitrate'] = $args[1] * 1000 / 8;
        }

        #Checking fps
        $args = $this->pregMatch(', ([0-9.]+) fps', $content);
        if ($args)
        {
            $info['fps'] = $args[1];
        }
        #Using TBR to determine fps incase there is no value for above method
        if (!$info['fps'])
        {
            $args = $this->pregMatch(', ([0-9.]+) tbr', $content);
            if ($args)
            {
                $info['fps'] = $args[1];
            }
        }

        #Total Frames
        if ($info['fps'])
            $info['frames'] = $duration * $info['fps'];


        #Getting Video Width, Height and the Ratio
        $args = $this->pregMatch('([0-9]{2,4})x([0-9]{2,4})', $content);
        if ($args)
        {

            $info['width'] = $args[1];
            $info['height'] = $args[2];
            $info['wh_ratio'] = (float) $info['width'] / (float) $info['height'];
            if ($args[1])
                $info['has_video'] = 'yes';
        }

        #Getting Video Codec
        $args = $this->pregMatch('Video: ([^ ^,]+)', $content);
        if ($args)
        {
            $info['video_codec'] = $args[1];
        }

        # get audio information
        //Setting has audio bydefault to no
        $info['has_audio'] = 'no';

        $args = $this->pregMatch("Audio: ([^ ]+), ([0-9]+) Hz, " .
                "([0-9+.^\n]*)(.*)?, ([0-9]+) kb\/s", $content);

        if ($args)
        {
            $audio_codec = $info['audio_codec'] = $args[1];
            $audio_rate = $info['audio_rate'] = $args[2];
            $info['audio_channels'] = $args[3];
            $info['audio_bitrate'] = $args[5];

            if ($args[1])
                $info['has_audio'] = 'yes';

            //Checking if there is streo or mono stuff
            //Then make changes for channel key
            if (!$info['audio_channels'])
            {
                if (strstr($args[4], 'stereo'))
                    $channels = 2;

                if (strstr($args[4], 'mono'))
                    $channels = 1;

                $info['audio_channels'] = $channels;
            }
        }

        //Incase we are not able to fetch info from above method, then
        //Try an alternative..
        if (!$audio_codec || !$audio_rate)
        {
            $args = $this->pregMatch("Audio: ([a-zA-Z0-9]+)(.*), " .
                    "([0-9]+) Hz, ([0-9+]*)(.*)?, ([0-9]+) kb\/s", $content);

            $info['audio_codec'] = $args[1];
            $info['audio_rate'] = $args[3];
            $info['audio_channels'] = $args[4];
            $info['audio_bitrate'] = $args[6];
            if ($args[1])
                $info['has_audio'] = 'yes';

            //Checking if there is streo or mono stuff
            //Then make changes for channel key
            if (!$info['audio_channels'])
            {
                if (strstr($args[5], 'stereo'))
                    $channels = 2;

                if (strstr($args[5], 'mono'))
                    $channels = 1;

                $info['audio_channels'] = $channels;
            }
        }

        $info['audio_bitrate'] *= 1000;

        /**
         * We use MediaInfo in addition with FFMPEG to extract information
         * Because ffmpeg is not able to extract all info that we need
         * such as rotation language etc..
         */
        //Parsing from mdiainfo
        /** Dumping, just extracting rotation for now ;) 
          if(!$info['duration'])
          {
          $duration = $this->pregMatch('Duration(\s+)\" : \"([0-9a-z ]+)', $content);
          $durations = explode(' ',$duration[2]);

          $mins = 0;
          $hrs = 0;
          $secs = 0;
          $msecs = 0;
          foreach($durations as $d)
          {
          if(strstr($d,'mn'))
          {
          $mins = str_replace('mn', '', $d);
          }elseif(strstr($d,'h'))
          {
          $hrs = str_replace('h', '', $d);
          }elseif(strstr($d,'ms'))
          {
          $msecs = str_replace('ms', '', $d);
          }elseif(strstr($d,'s'))
          {
          $secs = str_replace('s', '', $d);
          }
          }

          $duration = ($hrs*60*60) + ($mins*60) + $secs + ($msecs/1000);
          $info['duration'] = $duration;
          }

          //Checking if input has audio and or video
          if($info['has_video']=='no')
          {
          $fps = $this->pregMatch('Frame rate(\s+)\" : \"([0-9.]+)\"',
          $content);

          if($fps[2] && strstr($content,'Video'))
          {
          $info['has_video']  = 'yes';
          $info['fps']        = $fps[2];
          }

          //Checking for width and height..
          $width =  $this->pregMatch('Width(\s+)\" : \"([0-9]+)\"',
          $content);
          $height =  $this->pregMatch('Height(\s+)\" : \"([0-9]+)\"',
          $content);

          $info['height']     = $height[2];
          $info['width']      = $width[2];
          }

          if($info['has_audio']=='no')
          {
          $channels = $this->pregMatch('Channel\(s\)(\s+)\" : \"([0-9]+)\"',
          $content);

          if($channels[2] && strstr($content,'Audio'))
          $info['has_video'] = 'yes';
          $info['audio_channels']      = $channels[2];
          }
         */
        if ($mi)
        {
            $info = '';
            $rotation = $this->pregMatch('Rotation(\s+)\"? : \"?([0-9]+)', $content);

            if ($rotation[2])
                $info['rotation'] = $rotation[2];
            else
                $info['rotation'] = 0;
        }

        return $info;
    }

    /**
     * Get video duration, from inDetails/outDetails array...
     * @param STRING in/out
     * @return STRING Duration
     */
    function getDuration($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['duration'];
    }

    /**
     * Get video frames count
     * @param STRING in/out
     * @return STRING frames count
     */
    function getFramesCount($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['frames'];
    }

    /**
     * Get Video Frame Rate per Second
     * @param STRING in/out
     * @return STRING fps
     */
    function getFrameRate($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['fps'];
    }

    /**
     * Get Video File Name
     * @param STRING in/inout
     * @return STRING filename
     */
    function getFilename($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['filename'];
    }

    /**
     * Get Video Height
     * @param STRING in/out
     * @return STRING video height
     */
    function getHeight($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['height'];
    }

    /**
     * Get video width
     * @param STRING in/out
     * @return STRING video width
     */
    function getWidth($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['width'];
    }

    /**
     * Get WHRatio
     * @param STRING in/out
     * @return STRING WHRatio
     */
    function getWHRatio($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['width'] / $arr['height'];
    }

    /**
     * Get Audio Bitrate
     * @param STRING in/out
     * @return STRING audio bitrate
     */
    function getAudioBitrate($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['audio_bitrate'];
    }

    /**
     * Get Audio Sample Rate
     * @params STRING in/out
     * @return STRING audio rate
     */
    function getAudioRate($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['audio_rate'];
    }

    /**
     * Get Video Bitrate
     * @param STRING in/out
     * @return STRING video bitrate
     */
    function getVideoBitrate($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['bitrate'];
    }

    /**
     * Get Video Rate
     * @params STRING in/out
     * @return STRING video rate
     */
    function getVideoRate($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['video_rate'];
    }

    /**
     * Get audio codec
     * @params STRING in/out
     * @return STRING audio codec
     */
    function getAudioCodec($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['audio_codec'];
    }

    /**
     * Get video codec
     * @params STRING in/out
     * @return STRING video codec
     */
    function getVideoCodec($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['video_codec'];
    }

    /**
     * Get audio channels
     * @params STRING in/out
     * @return STRING audio channels
     */
    function getAudioChannes($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        return $arr['audio_channels'];
    }

    /**
     * Check file has video or not
     * @params STRING in/out
     * @return BOOLEAN
     */
    function hasVideo($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        if ($arr['has_video'] == 'yes')
            return true;
        else
            return false;
    }

    /**
     * Check file has audio or not
     * @params STRING in/out
     * @return BOOLEAN
     */
    function hasAudio($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;

        if ($arr['has_audio'] == 'yes')
            return true;
        else
            return false;
    }

    /**
     * Check file has audio or not
     * @params STRING in/out
     * @return BOOLEAN
     */
    function getRotation($vid = 'in')
    {
        $arr = $this->inDetails;
        if ($vid != 'in')
            $arr = $this->outDetails;
        if ($arr['rotation'])
            return $arr['rotation'];
        else
            return '0';
    }

    /**
     * Function used to convert video in 
     * - MP4 , h264, aac for web streaming
     * - FLV , h264, aac for web streaming
     * - WebM, Standard Adopted by Google for webs streaming using HTML5
     * - mobile , H264/3GP for mobile streaming
     * 
     * Resize => 
     */
    function convert($params = false)
    {
        $defaults = array(
            'format' => 'flv',
            '2pass' => false,
            'h264' => true,
            'height' => '490',
            'width' => '640',
            'resize' => 'max',
            'bitrate' => 512000,
            'aspect_ratio' => '4:3',
            'arate' => '22050',
            'fps' => 24,
            'abitrate' => 128000,
            'preset' => 'normal',
            'auto_rotate' => true,
        );


        if ($params && $defaults)
            $params = array_merge($defaults, $params);
        elseif ($defaults)
            $params = $defaults;

        $output_file = $params['output_file'];

        //Creating array to pass to calculateResize func
        //and get resizeable width and height...

        $dimInfo = array(
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'resize_height' => $params['height'],
            'resize_width' => $params['width'],
            'wh_ratio' => $this->getWHRatio(),
            'resize_ratio' => $params['aspect_ratio'],
            'resize' => $params['resize'],
        );

        $resizedInfo = $this->calculateResize($dimInfo);


        /**
         * Here we will preset everything before checking for which
         * format we are going to convert into..
         * first we will set up basic values for all common inputs such
         * as width, height, bitrates, by default we will make
         * h264 and aac to be used as video and audio codecs, respectively.
         * we can later change them in switch statement.
         */
        //Setting Up the Codecs..
        $validCodecs = array('libx264', 'libvorbis', 'libfaac', 'mpeg4', 'aac',
            'libmp3lame', 'mp3', 'flv', 'libvpx');

        $vcodec = $params['vcodec'];
        if (!in_array($vcodec, $validCodecs) && $vcodec)
        {
            $vcodec = 'libx264';
        }

        if (!$vcodec && $params['h264'])
            $vcodec = 'libx264';

        $acodec = $params['acodec'];
        if (!in_array($acodec, $validCodecs) && $acodec
                || (!$acodec && $vcodec = 'libx264'))
        {
            $acodec = 'libfaac';
        }



        //Setting videobitrate and audiobitrate..
        //Learn more about bitrates on google ;)
        $fps = $params['fps'];
        $in_fps = $this->getFrameRate();

        $fps = min($fps, $in_fps);

        //As we are converting videos for web streaming, so we are keeping
        //Frame rates to max 30 chek max_fps var, if you have plans of changing to more
        //Simple change the second condition..
        if ($fps < 24)
            $fps = 24;
        if ($fps > $this->max_fps)
            $fps = $this->max_fps;

        //Bitrate controls the size and quality we are setting them 
        //To get the best output...max bitrate by default is 640k
        $bitrate = $params['bitrate'];
        $in_bitrate = $this->getVideoBitrate();

        $bitrate = min($bitrate, $in_bitrate);
        if ($bitrate > $this->max_bitrate)
            $bitrate = $this->max_bitrate;

        //Audio Rate also known as sample rate will make your audio 
        //Heard more clearly WRT media and in web streaming we 
        //use 22050,44100 or 48000
        $arate = $params['arate'];
        $in_arate = $this->getAudioRate();

        $arate = min($arate, $in_arate);

        if (!in_array($arate, $this->audio_rates))
        {
            $arate = $defaults['arate'];
        }

        //Audio Bitrate controls the audio quality, usually
        //96k bit audio is pretty good fod videos but if you want to get
        //More quality audio you can increase audio bitrate to 128, 164,
        //256, 396 etc...by default we will use 128k and for max 396k

        $abitrate = $params['abitrate'];
        $in_abitrate = $this->getAudioBitrate();

        $abitrate = min($abitrate, $in_abitrate);

        if (!$abitrate > $this->max_audio_bitrate)
            $abitrate = $this->max_audio_bitrate;


        $presets = array(
            'width' => $resizedInfo['width'],
            'height' => $resizedInfo['height'],
            'aspect_ratio' => $resizedInfo['ratio'],
            'vcodec' => $vcodec,
            'acodec' => $acodec,
            'preset' => $params['preset'],
            'fps' => $fps,
            'arate' => $arate,
            'bitrate' => $bitrate,
            'abitrate' => $abitrate,
        );


        //Setting the input option array which will later become
        //String/command to make things more easier

        $cmd_array = array(
            'i' => $this->input,
        );



        //Overwrite existing
        $cmd_array['y'] = ' ';

        if ($presets['acodec'])
            $cmd_array['acodec'] = $presets['acodec'];

        if ($presets['vcodec'])
            $cmd_array['vcodec'] = $presets['vcodec'];

        if ($presets['width'] && $presets['height'])
            $cmd_array['s'] = $presets['width'] . 'x' . $presets['height'];

        if ($presets['fps'])
            $cmd_array['r'] = $presets['fps'];

        if ($presets['bitrate'])
            $cmd_array['b:v'] = $presets['bitrate'];

        if ($presets['arate'])
            $cmd_array['ar'] = $presets['arate'];

        if ($presets['abitrate'])
            $cmd_array['ab'] = $presets['abitrate'];

        //If channels are greater than 2, video is not convertable
        //In many cases, so we always choose 2 channel audio
        if ($this->valid_two_channels)
            if ($this->getAudioChannes() > 2)
                $cmd_array['ac'] = 2;


        /**
         * Checking if there is some duration or size limit
         */
        if ($params['max_duration'] && is_numeric($params['max_duration']))
        {
            $cmd_array['t'] = $params['duration'];
        }

        if ($params['max_size'] && is_numeric($params['max_size']))
        {
            $cmd_array['fs'] = $params['max_size'];
        }


        //Padding
        $rinfo = $resizedInfo;
        if ($rinfo['pad_x'] || $rinfo['pad_y'])
        {
            $pad_width = $rinfo['width'] + ($rinfo['pad_x'] * 2);
            $pad_height = $rinfo['height'] + ($rinfo['pad_y'] * 2);

            if (!$params['padding_color'])
                $pad_color = 'black';
            else
                $pad_color = $params['padding_color'];

            $pad_cmd = "pad=$pad_width:$pad_height";
            $pad_cmd .= ":" . $rinfo['pad_x'] . ":" . $rinfo['pad_y'];
            $pad_cmd .= ":" . $pad_color;

            $cmd_array['vf']['pad'] = $pad_cmd;
        }

        /**
         * Attaching subtitles..
         * subtitles are array and must be only ttf
         */
        if ($params['subtitles'])
        {
            
        }

        //Adding Watermark

        if ($params['watermark'])
        {
            $cmd_array['vf']['overlay'] =
                    $this->_genWatermark($params['watermark'], $params['watermarl_position'], $params['watermark_offset']);
        }

        //Adding Rotation
        if ($params['auto_rotate'])
        {
            $rotationCMD = $this->_getRotation($this->inDetails['rotation']);
            if ($rotationCMD)
            {
                $cmd_array['vf']['rotation'] = $rotationCMD;
            }
        }



        //Disabling audio in case there is no audio
        if (!$this->hasAudio())
            $cmd_array['an'] = '';

        switch ($params['format'])
        {

            //FLV and Mp4 uses almost same method to get the output
            //unless Mp4 is for mobile, however both are converted
            //Using same codecs (mostly libx264) thats why
            //we will use same code for both
            case "flv":
            case "mp4":
            case "m4v":
            case "f4v":
            default:
                {
                    $f = $params['format'];
                    if ($params['preset'])
                    {
                        if ($this->preset_path)
                            $cmd_array['vpre'] = $this->preset_path . '/' . $vcodec . '-' . $params['preset'] . '.ffpreset';
                        else
                            $cmd_array['vpre'] = $params['preset'];
                    }

                    /**
                     * - f
                     * Force input or output file format. The format is normally 
                     * auto detected for input files and guessed from file extension
                     * for output files, so this option is not needed in most cases.
                     */
                    /* if($f=='f4v' || $f=='mp4' || $f=='m4v')
                      $cmd_array['f'] = 'mp4';

                      if($f=='flv')
                      $cmd_array['f'] = 'flv'; */
                }

                break;
            case "webm":
                {
                    $cmd_array['vcodec'] = 'libvpx';
                    $cmd_array['acodec'] = 'libvorbis';
                    $cmd_array['f'] = 'webm';
                }

            case "mobile":
            case "mobile-mp4":
                {
                    //Add mobile conditions here..
                }

                break;

            case "3gp":
                {
                    //Auto-detect by ffmpeg :)
                    /* $cmd_array['vcodec'] = 'h263';
                      $cmd_array['acodec'] = 'libamr_nb'; */
                    unset($cmd_array['vcodec']);
                    unset($cmd_array['acodec']);
                    unset($cmd_array['ar']);
                    //Ideal video bitrate for 3gp is 4.7k
                    $cmd_array['b:v'] = '4.7k';
                    //Idea audio rate for 3gp is 8k
                    $cmd_array['ar'] = 8000;
                    //3gp supports following dimensions
                    //128x96,176x144,352x288,704x576,1408x1152
                    $widths = array('128', '176', '352', '704', '1408');
                    $closest_width = $this->getClosest($params['width'], $widths);

                    $dims = array(
                        '128' => array('width' => 128, 'height' => 96),
                        '176' => array('width' => 176, 'height' => 144),
                        '352' => array('width' => 352, 'height' => 288),
                        '704' => array('width' => 704, 'height' => 576),
                        '1408' => array('width' => 1408, 'height' => 1152)
                    );

                    $dimInfo['resize_width'] = $dims[$closest_width]['width'];
                    $dimInfo['resize_height'] = $dims[$closest_width]['height'];
                    $dimInfo['resize_ratio'] = 1.222222222222222;
                    $dimInfo['resize'] = 'fit';

                    //Recalculating Video Dimension
                    $rinfo = $this->calculateResize($dimInfo);

                    //Setting Padding and Resize parameters
                    $cmd_array['s'] = $rinfo['width'] . 'x' . $rinfo['height'];

                    //Padding
                    if ($rinfo['pad_x'] || $rinfo['pad_y'])
                    {

                        $pad_width = $rinfo['width'] + ($rinfo['pad_x'] * 2);
                        $pad_height = $rinfo['height'] + ($rinfo['pad_y'] * 2);

                        if (!$params['padding_color'])
                            $pad_color = 'black';
                        else
                            $pad_color = $params['padding_color'];

                        $cmd_array['vf']['pad'] = "pad=$pad_width:$pad_height";
                        $cmd_array['vf']['pad'] .= ":" . $rinfo['pad_x'] . ":" . $rinfo['pad_y'];
                        $cmd_array['vf']['pad'] .= ":" . $pad_color;
                    }

                    $cmd_array['ac'] = 1;
                }
                break;

            case 'ogg';
                {
                    //Add ogg conditions here...
                    unset($cmd_array['vcodec']);
                    unset($cmd_array['acodec']);
                }
                break;
        }



        $CMD = FFMPEG;
        foreach ($cmd_array as $opt => $val)
        {

            if ($val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k => $v)
                    {
                        if ($v)
                        {
                            $CMD .= " -" . $opt;
                            $CMD .= " \"" . $v . "\" ";
                        }
                    }
                }
                else
                {
                    $CMD .= " -" . $opt;
                    $CMD .= " " . $val;
                }
            }
        }

        $CMD .= " " . $output_file;

        $log = $this->exec($CMD,true);
        $this->log($log,'m','conversion');
        return $log;
    }

    /**
     * Function used to calculate video padding
     */
    private function calculateResize($params)
    {
        $p = $params;

        switch ($p['resize'])
        {
            # dont resize, use same size as source, and aspect ratio
            # WARNING: some codec will NOT preserve the aspect ratio
            case 'no':
            default:
                $width = $p['width'];
                $height = $p['height'];
                $ratio = $p['wh_ratio'];
                $pad_x = 0;
                $pad_y = 0;
                break;

            # resize to parameters width X height, use same aspect ratio
            # WARNING: some codec will NOT preserve the aspect ratio
            case 'WxH':
            case "wxh":
                $width = $p['resize_width'];
                $height = $p['resize_height'];
                $ratio = $p['wh_ratio'];
                $pad_y = 0;
                $pad_x = 0;

                break;
            # make pixel square
            # reduce video size if bigger than p[width] X p[height]
            # and preserve aspect ratio
            case 'max':
                $width = (float) $p['width'];
                $height = (float) $p['height'];
                $ratio = (float) $p['wh_ratio'];
                $max_width = (float) $p['resize_width'];
                $max_height = (float) $p['resize_height'];

                # make pixels square
                if ($ratio > 1.0)
                    $width = $height * $ratio;
                else
                    $height = @$width / $ratio;

                # reduce width
                if ($width > $max_width)
                {
                    $r = $max_width / $width;
                    $width *= $r;
                    $height *= $r;
                }

                # reduce height
                if ($height > $max_height)
                {
                    $r = $max_height / $height;
                    $width *= $r;
                    $height *= $r;
                }

                # make size even (required by many codecs)
                $width = (integer) ( ($width + 1 ) / 2 ) * 2;
                $height = (integer) ( ($height + 1 ) / 2 ) * 2;
                break;

            case "fit":
                {
                    $width = (float) $p['width'];
                    $height = (float) $p['height'];
                    $ratio = (float) $p['wh_ratio'];
                    $max_width = (float) $p['resize_width'];
                    $max_height = (float) $p['resize_height'];

                    # make pixels square
                    if ($ratio > 1.0)
                        $width = $height * $ratio;
                    else
                        $height = @$width / $ratio;

                    # reduce width
                    if ($width > $max_width)
                    {
                        $r = $max_width / $width;
                        $width *= $r;
                        $height *= $r;
                    }

                    # reduce height
                    if ($height > $max_height)
                    {
                        $r = $max_height / $height;
                        $width *= $r;
                        $height *= $r;
                    }

                    # make size even (required by many codecs)
                    $width = (integer) ( ($width + 1 ) / 2 ) * 2;
                    $height = (integer) ( ($height + 1 ) / 2 ) * 2;

                    $output_width = $p['resize_width'];
                    $output_height = $p['resize_height'];

                    $pad_x = $output_width - $width;
                    $pad_x /= 2;

                    $pad_y = $output_height - $height;
                    $pad_y = $pad_y / 2;
                }
        }

        return array('width' => $width, 'height' => $height, 'pad_x' =>
            $pad_x, 'pad_y' => $pad_y, 'ratio' => $ratio);
    }

    /**
     * @name : pregMatch
     * @todo : Simplifies Pregmatch by added slashes
     * @return STRING
     */
    function pregMatch($in, $str)
    {
        preg_match("/$in/", $str, $args);
        return $args;
    }

    /**
     * Find closest value..
     * @param STRING search in numeric
     * @param ARRAY an array of values to be matched
     * @return Matched string
     */
    function getClosest($search, $arr)
    {
        $closest = null;
        foreach ($arr as $item)
        {
            if ($closest == null || abs($search - $closest) > abs($item - $search))
            {
                $closest = $item;
            }
        }
        return $closest;
    }

    /**
     * function used to generate watermark command
     * @params STRING watermark file
     * @params STRING position 
     * @params STRING offset array(x,y)
     */
    private function _genWatermark($file, $position = 'tr', $offset = NULL)
    {
        if (!$offset)
            $offset = array(10, 10);

        //This will only generate overlay command
        if (file_exists($file))
        {

            $yOffset = $offset[1] ? $offset[1] : '0';
            $xOffset = $offset[0] ? $offset[0] : '0';

            $positions = array(
                'tr' => 'W-w-' . $xOffset . ':' . $yOffset,
                'tl' => $xOffset . ':' . $yOffset,
                'bl' => $xOffset . ':H-h-' . $yOffset,
                'br' => 'W-w-' . $xOffset . ':H-h-' . $yOffset,
            );

            if (!$positions[$position])
                $position = 'tr';

            $LOGO = $file;

            $cmd = 'movie=' . $LOGO . '[logo];[in][logo]overlay=' . $positions[$position] . '[out]';

            return $cmd;
        }

        return false;
    }

    /**
     * Function used to add watermark on a video
     * @param ARRAY 
     * - input
     * - output
     * - watermark
     * - position (tl,tr,bl,bf)
     * - offest array(x,y)
     */
    function addWatermark($file, $output, $watermark, $position = 'tr', $offset = array(10, 10))
    {
        if (!$file || !file_exists($file))
            return false;
        if (!$watermark || !file_exists($watermark))
            return false;

        //using _getWatermark function to get the command
        $overlay_cmd = $this->_genWatermark($watermark, $position, $offset);

        $CMD = FFMPEG;
        $CMD .= " -i '$file' ";
        //force overwrite
        $CMD .= " -y ";
        $CMD .= $output;

        $output = $this->exec($CMD, true);

        //Todo : Create result parser and output resutls

        return true;
    }

    /**
     * Function used to generate rotate video command
     * @param FILE
     */
    private function _getRotation($rotation)
    {
        if ($rotation)
        {
            switch ($rotation)
            {
                case "90-vflip":
                    {
                        $cmd = "transpose=0";
                    }
                    break;


                case "90":
                    {
                        $cmd = "transpose=1";
                    }
                    break;

                case "270":
                    {
                        $cmd = "transpose=2";
                    }
                    break;


                case "270-vflip":
                    {
                        $cmd = "transpose=3";
                    }
                    break;

                case "vflip":
                    {
                        $cmd = "vflip";
                    }
                    break;

                case "hflip":
                    {
                        $cmd = "hflip";
                    }
            }

            if ($cmd)
            {
                return $cmd;
            }
        }

        return false;
    }

    /**
     * Function used to rotate video
     * 
     */
    function rotateVideo($file, $rotation)
    {
        if (!$file || !file_exists($file))
            return false;

        $rotateCmd = $this->_getRotation($rotation);

        $CMD = FFMPEG;
        $CMD .= " -i '$file' ";
        //force overwrite
        $CMD .= " -y ";
        $CMD .= $output;

        $output = $this->exec($CMD, true);
        //Todo : Create result parser and output resutls

        return true;
    }

    /**
     * function used to extract image from a video
     * @param STRING input file
     * @param array(
     * 'num' => number of images..
     * 'size' => WidthxHeight
     * 'time' => sepecific time
     * '
     * )
     */
    function extractThumb($input = false, $params = false)
    {
        $defaults = array
            (
            'size' => 'same',
            'time' => '5',
            'num' => '20',
            'increment' => true,
            'outname' => getName($input),
            'outdir' => TEMP_DIR,
            'resize' => 'fit'
        );

        if (!$input)
        {
            $input = $this->input;
            $iDetails = $this->inDetails;
        }
        else
        {
            if (file_exists($input))
            {
                $iDetails = $this->_getInfo($input);
            }
            else
            {
                //log the fishy error
            }
        }


        if (!$iDetails)
        {
            //Log an error...
            return false;
        }


        if ($params)
            $params = array_merge($defaults, $params);
        else
            $params = $defaults;

        //If there is no outname then re-create1
        if (!$params['outname'])
        {
            $params['outname'] = getname($this->input);
        }


        $count = 0;
        $outname = $params['outname'];
        $outputname = $outname . '.jpg';
        //Adding increment
        while (1)
        {
            if ($count > 0)
                $outputname = $outname . '-' . $count . '.jpg';

            if (!file_exists($params['outdir'] . '/' . $outputname))
            {
                break;
            }
            else
            {
                if (!$params['increment'])
                {
                    unlink($params['outdir'] . '/' . $outputname);
                    break;
                }
            }

            $count++;
        }

        //Setting upt the size
        $size = $params['size'];
        if ($size != 'same' && $size != 'original')
        {
            $sizes = explode('x', $size);
            $width = $sizes[0];
            $height = $sizes[1];


            $dimInfo = array(
                'height' => $iDetails['height'],
                'width' => $iDetails['width'],
                'resize_height' => $height,
                'resize_width' => $width,
                'wh_ratio' => $iDetails['width'] / $iDetails['height'],
                'resize_ratio' => $width / $height,
                'resize' => $params['resize'],
            );

            $rinfo = $this->calculateResize($dimInfo);

            $pad_width = $rinfo['width'] + ($rinfo['pad_x'] * 2);
            $pad_height = $rinfo['height'] + ($rinfo['pad_y'] * 2);

            if (!$params['padding_color'])
                $pad_color = 'black';
            else
                $pad_color = $params['padding_color'];

            $cmd_array['vf']['pad'] = "pad=$pad_width:$pad_height";
            $cmd_array['vf']['pad'] .= ":" . $rinfo['pad_x'] . ":" . $rinfo['pad_y'];
            $cmd_array['vf']['pad'] .= ":" . $pad_color;
        }


        //Setting Time
        if ($params['time'])
        {
            $time = $params['time'];
        }

        //Setting numbers
        $vframes = 1;
        $rateCMD = "";
        if ($params['num'])
        {
            if ($params['num'] > 1)
            {
                $outputname = $params['outdir'] . '/'
                        . $params['outname'] . '-%03d.jpg';

                $vframes = $params['num'];
                $rateCMD = " -r " . $vframes . '/' . $iDetails['duration'];
            }
        }


        $CMD = FFMPEG;

        if ($time)
        {
            $CMD .= ' -ss ' . $time;
        }

        $CMD .= ' -i ' . $input;
        $CMD .= " -y ";
        $CMD .= " -an ";
        $CMD .= " -sameq ";
        $CMD .= " -vframes " . $vframes;
        $CMD .= " -f image2";
        $CMD .= " -vcodec mjpeg";
        if ($rateCMD)
        {
            $CMD .= $rateCMD;
        }
        if ($size != 'same')
        {
            $CMD .= " -s " . $rinfo['width'] . "x" . $rinfo['height'];
            $CMD .= " -vf \"";
            $CMD .= "pad=$pad_width:$pad_height";
            $CMD .= ":" . $rinfo['pad_x'] . ":" . $rinfo['pad_y'];
            $CMD .= ":" . $pad_color;
            $CMD .= "\" ";
        }

        echo $CMD .= " " . $outputname;

        $this->exec($CMD);
        return $CMD;
    }

    /**
     * FFmpeg log praser
     * 
     * This function will look for errors, warnings and anything from ffmpeg
     * which causing problem while conversion or anything. We will keep
     * updating this functions as we will have more new errors in future
     * 
     * @param STRING $LOG
     * @return ARRAY $ERRORS
     */
    function ffmpegLogParser($log)
    {
        $errors = array();

        /* Checking if there is some ffmepg execution error */
        preg_match();

        /* Checking for codec missing error */

        /* Checking for bitrate/width/etc issue */

        /* Checking for preset error */

        /* Checking for file missing error */
    }

    /**
     * function used to set the log file
     * Simple set the value for output_log
     * 
     * @param STRING $file 
     */
    function set_log($file = false)
    {
        if (!$file)
            $file = $outputFile = TEMP_DIR . '/' . time() . mt_rand(5, 6);

        $this->output_log = $file;
        return $file;
    }

    /**
     * Set the preset path to load presets from
     * specified directory
     * 
     * @param STRING path
     */
    function set_preset_path($path)
    {
        $this->preset_path = $path;
    }

}

?>