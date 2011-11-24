<?php
	
	/**
	 * Function used to verify captcha
	 */
	function verify_captcha()
	{
		$var = post('cb_captcha_enabled');
		if($var=='yes')
		{
			$captcha = get_captcha();
			$val = $captcha['validate_function'](post(GLOBAL_CB_CAPTCHA));
			return $val;
		}else
			return true;
	}
	
	
	/**
	 * Function used to ingore errors
	 * that are created when there is wrong action done
	 * on clipbucket ie inavalid username etc
	 */
	function ignore_errors()
	{
		global $ignore_cb_errors;
		$ignore_cb_errors = TRUE;
	}
	
	/**
	 * Function used to set $ignore_cb_errors 
	 * back to TRUE so our error catching system
	 * can generate errors
	 */
	function catch_error()
	{
		global $ignore_cb_errors;
		$ignore_cb_errors = FALSE;
	}
	
	
	/**
	 * Function used to call sub_menu_easily
	 */
	function sub_menu()
	{
		/**
		 * Submenu function used to used to display submenu links
		 * after navbar
		 */
		$funcs = get_functions('sub_menu');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					return $func($u);
				}
			}
		}
	}
	
	
	/**
	 * Function used to load clipbucket title
	 */
	function cbtitle($params=false)
	{
		global $cbsubtitle;
		
		$sub_sep = $params['sub_sep'];
		$pattern = $params['pattern'];
		if(!$sub_sep)
			$sub_sep = '-';
		
		
		if($pattern && $cbsubtitle)
		{
			return str_replace(array('[subtitle]','[title]'),array($cbsubtitle,TITLE),$pattern);
		}
		
		//Getting Subtitle
		$title = TITLE;
		if(!$cbsubtitle)
			$title .= " $sub_sep ".SLOGAN;
		else
			$title .= " $sub_sep ".$cbsubtitle;
		
		return $title;
		//echo " ".SUBTITLE;
	}
	
	
	/**
	 * @Script : ClipBucket
	 * @Author : Arslan Hassan
	 * @License : CBLA
	 * @Since : 2007
	 *
	 * function whos_your_daddy
	 * Simply tells the name of  script owner
	 * @return INTELLECTUAL BADASS
	 */
	function whos_your_daddy()
	{
		echo  "<h1>Arslan Hassan</h1>";
	}
	
	/**
	 * function used to set website subtitle
	 */
	function subtitle($title)
	{
		global $cbsubtitle;
		$cbsubtitle = $title;
	}
	
	
	/**
	 * FUnction used to get username from userid
	 */
	function get_username($uid)
	{
		global $userquery;
		return $userquery->get_username($uid,'username');
	}
	
	/**
	 * Function used to get collection name from id
	 * Smarty Function
	 */
	function get_collection_field($cid,$field='collection_name')
	{
		global $cbcollection;
		return $cbcollection->get_collection_field($cid,$field);
	}
	
	/**
	 * Function used to delete photos if
	 * whole collection is being deleted
	 */
	function delete_collection_photos($details)
	{
		global $cbcollection,$cbphoto;
		$type = $details['type'];

		if($type == 'photos')
		{
			$ps = $cbphoto->get_photos(array("collection"=>$details['collection_id']));
			if(!empty($ps))
			{	
				foreach($ps as $p)
				{
					$cbphoto->make_photo_orphan($details,$p['photo_id']);	
				}
				unset($ps); // Empty $ps. Avoiding the duplication prob
			}
		}
	}
	
	/**
	 * FUnction used to get head menu
	 */
	function head_menu($params=NULL)
	{
		global $Cbucket;
		return $Cbucket->head_menu($params);
	}
	
	function cbMenu($params=NULL)
	{
		global $Cbucket;
		return $Cbucket->cbMenu($params);
	}
	
	/**
	 * FUnction used to get foot menu
	 */
	function foot_menu($params=NULL)
	{
		global $Cbucket;
		return $Cbucket->foot_menu($params);
	}
	
	
	
	
	/**
	 * FUNCTION Used to convert XML to Array
	 * @Author : http://www.php.net/manual/en/function.xml-parse.php#87920
	 */
	function xml2array($url, $get_attributes = 1, $priority = 'tag',$is_url=true)
	{
		$contents = "";
		if (!function_exists('xml_parser_create'))
		{
			return false;
		}
		$parser = xml_parser_create('');
		
		if($is_url)
		{
			if (!($fp = @ fopen($url, 'rb')))
			{
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_USERAGENT, 
				'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/3.0.0.2');
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
				$contents = curl_exec($ch);
				curl_close($ch);
				
				if(!$contents)
					return false;
			}
			while (!feof($fp))
			{
				$contents .= fread($fp, 8192);
			}
			fclose($fp);
		}else{
			$contents = $url;
		}

		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
		if (!$xml_values)
			return; //Hmm...
		$xml_array = array ();
		$parents = array ();
		$opened_tags = array ();
		$arr = array ();
		$current = & $xml_array;
		$repeated_tag_index = array ();
		foreach ($xml_values as $data)
		{
			
			unset ($attributes, $value);
			extract($data);
			$result = array ();
			$attributes_data = array ();
			if (isset ($value))
			{
				if ($priority == 'tag')
					$result = $value;
				else
					$result['value'] = $value;
			}
			if (isset ($attributes) and $get_attributes)
			{
				foreach ($attributes as $attr => $val)
				{
					if ($priority == 'tag')
						$attributes_data[$attr] = $val;
					else
						$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}
			if ($type == "open")
			{
				$parent[$level -1] = & $current;
				if (!is_array($current) or (!in_array($tag, array_keys($current))))
				{
					$current[$tag] = $result;
					if ($attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					$current = & $current[$tag];
				}
				else
				{
					if (isset ($current[$tag][0]))
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else
					{
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 2;
						if (isset ($current[$tag . '_attr']))
						{
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset ($current[$tag . '_attr']);
						}
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current = & $current[$tag][$last_item_index];
				}
			}
			elseif ($type == "complete")
			{
				if (!isset ($current[$tag]))
				{
					$current[$tag] = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' and $attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
				}
				else
				{
					if (isset ($current[$tag][0]) and is_array($current[$tag]))
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						if ($priority == 'tag' and $get_attributes and $attributes_data)
						{
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else
					{
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if ($priority == 'tag' and $get_attributes)
						{
							if (isset ($current[$tag . '_attr']))
							{
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset ($current[$tag . '_attr']);
							}
							if ($attributes_data)
							{
								$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
					}
				}
			}
			elseif ($type == 'close')
			{
				$current = & $parent[$level -1];
			}
		}
		
		return ($xml_array);
	}
	
	
	function array2xml($array, $level=1)
	{
		$xml = '';
		// if ($level==1) {
		//     $xml .= "<array>\n";
		// }
		foreach ($array as $key=>$value) {
		$key = strtolower($key);
		if (is_object($value)) {$value=get_object_vars($value);}// convert object to array
		
		if (is_array($value)) {
			$multi_tags = false;
			foreach($value as $key2=>$value2) {
			 if (is_object($value2)) {$value2=get_object_vars($value2);} // convert object to array
				if (is_array($value2)) {
					$xml .= str_repeat("\t",$level)."<$key>\n";
					$xml .= array2xml($value2, $level+1);
					$xml .= str_repeat("\t",$level)."</$key>\n";
					$multi_tags = true;
				} else {
					if (trim($value2)!='') {
						if (htmlspecialchars($value2)!=$value2) {
							$xml .= str_repeat("\t",$level).
									"<$key2><![CDATA[$value2]]>". // changed $key to $key2... didn't work otherwise.
									"</$key2>\n";
						} else {
							$xml .= str_repeat("\t",$level).
									"<$key2>$value2</$key2>\n"; // changed $key to $key2
						}
					}
					$multi_tags = true;
				}
			}
			if (!$multi_tags and count($value)>0) {
				$xml .= str_repeat("\t",$level)."<$key>\n";
				$xml .= array2xml($value, $level+1);
				$xml .= str_repeat("\t",$level)."</$key>\n";
			}
		
		 } else {
			if (trim($value)!='') {
			 echo "value=$value<br>";
				if (htmlspecialchars($value)!=$value) {
					$xml .= str_repeat("\t",$level)."<$key>".
							"<![CDATA[$value]]></$key>\n";
				} else {
					$xml .= str_repeat("\t",$level).
							"<$key>$value</$key>\n";
				}
			}
		}
		}
		//if ($level==1) {
		//    $xml .= "</array>\n";
		// }
		
		return $xml;
	}


	
	/**
	 * FUnction used to display widget
	 */
	function widget($params)
	{
		$name = $params['name'];
		$content = $params['content'];
		
		return
		'<div class="widget-box">
			<div class="widget-head">
				'.$name.'
			</div>
			<div class="widget-cont">
			  '.$content.'
			</div>
		</div>';
	}
	
	
	/**
	 * Function used to get latest ClipBucket version info
	 */
	function get_latest_cb_info()
	{
		if($_SERVER['HTTP_HOST']!='localhost')
			$url = 'http://clip-bucket.com/versions.xml';
		else
			$url = 'http://localhost/clipbucket/2.x/2/upload/tester/versions.xml';
		$version = xml2array($url);
		if(!$version)
		{
			return false;
		}else
		{
			return $version['phpbucket']['clipbucket'][0];
		}
	}
	
	
	/**
	 * function used to get allowed extension as in array
	 */
	function get_vid_extensions()
	{
		$exts = config('allowed_types');
		$exts = preg_replace("/ /","",$exts);
		$exts = explode(",",$exts);
		return $exts;
	}
	
	
	/**
	 * This function used to include headers in <head> tag
	 * it will check weather to include the file or not
	 * it will take file and its type as an array
	 * then compare its type with THIS_PAGE constant
	 * if header has TYPE of THIS_PAGE then it will be inlucded
	 */
	function include_header($params)
	{
		$file = $params['file'];
		$type = $params['type'];
		
		if($file=='global_header')
		{
			Template(BASEDIR.'/styles/global/head.html',false);
			return false;
		}
		
		if($file == 'admin_bar')
		{
			if(has_access('admin_access',TRUE))
				Template(BASEDIR.'/styles/global/admin_bar.html',false);
				return false;
		}
		
		if(!$type)
			$type = "global";
		
		if(is_includeable($type))
			Template($file,false);
		
		return false;
	}
	
	

	
	/**
	 * Function used to check weather to include
	 * given file or not
	 * it will take array of pages
	 * if array has ACTIVE PAGE or has GLOBAL value
	 * it will return true
	 * otherwise FALSE
	 */
	function is_includeable($array)
	{
		if(!is_array($array))
			$array = array($array);
		if(in_array(THIS_PAGE,$array) || in_array('global',$array))
		{
			return true;
		}else
			return false;
	}
	
	/**
	 * This function works the same way as include_header
	 * but the only difference is , it is used to include
	 * JS files only
	 */
	$the_js_files = array();
	function include_js($params)
	{
		global $the_js_files;
		
		$file = $params['file'];
		$type = $params['type'];
		
		if(!in_array($file,$the_js_files))
		{
			$the_js_files[] = $file;
			if($type=='global')
				return '<script src="'.JS_URL.'/'.$file.'" type="text/javascript"></script>';
			elseif(is_array($type))
			{
				foreach($type as $t)
				{
					if($t==THIS_PAGE)
						return '<script src="'.JS_URL.'/'.$file.'" type="text/javascript"></script>';
				}
			}elseif($type==THIS_PAGE)
				return '<script src="'.JS_URL.'/'.$file.'" type="text/javascript"></script>';
		}
		
		return false;
	}
		
	
	/**
	 * Function used to check weather FFMPEG has Required Modules installed or not
	 */
	function get_ffmpeg_codecs($data=false)
	{
		$req_codecs = array
		('libxvid' => 'Required for DIVX AVI files',
		 'libmp3lame'=> 'Required for proper Mp3 Encoding',
		 'libfaac'	=> 'Required for AAC Audio Conversion',
		// 'libfaad'	=> 'Required for AAC Audio Conversion',
		 'libx264'	=> 'Required for x264 video compression and conversion',
		 'libtheora' => 'Theora is an open video codec being developed by the Xiph.org',
		 'libvorbis' => 'Ogg Vorbis is a new audio compression format',
		 );
		 
		if($data)
			$version = $data;
		else
			$version = shell_output(  get_binaries('ffmpeg').' -i xxx -acodec copy -vcodec copy -f null /dev/null 2>&1' );
		preg_match_all("/enable\-(.*) /Ui",$version,$matches);
		$installed = $matches[1];
		
		$the_codecs = array();
		
		foreach($installed as $inst)
		{
			if(empty($req_codecs[$inst]))
				$the_codecs[$inst]['installed'] = 'yes';
		}
		
		foreach($req_codecs as $key=>$codec)
		{
			$the_req_codecs[$key] = array();
			$the_req_codecs[$key]['required'] = 'yes';
			$the_req_codecs[$key]['desc'] = $req_codecs[$key];
			if(in_array($key,$installed))
				$the_req_codecs[$key]['installed'] = 'yes';
			else
				$the_req_codecs[$key]['installed'] = 'no';
		}
		
		$the_codecs =  array_merge($the_req_codecs,$the_codecs);
		return $the_codecs;
	}
	
	
	/**
	 * Function used to cheack weather MODULE is INSTALLED or NOT
	 */
	function check_module_path($params)
	{
		$rPath = $path = $params['path'];
		
		if($path['get_path'])
			$path = get_binaries($path);
		$array = array();
		$result = shell_output($path." -version");
			
		if($result)
		{
			if(strstr($result,'error') || strstr(($result),'No such file or directory'))
			{
				$error['error'] = $result;
				
				if($params['assign'])
					assign($params['assign'],$error);
				
				return false;
			}
					
			if($params['assign'])
			{
				$array['status'] = 'ok';
				$array['version'] = parse_version($params['path'],$result);
				
				assign($params['assign'],$array);
				
			}else
			{
				return $result;
			}
		}else
		{
			if($params['assign'])
				assign($params['assign']['error'],"error");
			else
				return false;
		}
			
	}
	
	
	/**
	 * Function used to parse version from info
	 */
	function parse_version($path,$result)
	{
		switch($path)
		{
			case 'ffmpeg':
			{
				//Gett FFMPEG SVN version
				preg_match("/svn-r([0-9]+)/i",strtolower($result),$matches);
				//pr($matches);
				if(is_numeric(floatval($matches[1])) && $matches[1]) {
					return 'Svn '.$matches[1];
				}
				//Get FFMPEG version
				preg_match("/FFmpeg version ([0-9.]+),/i",strtolower($result),$matches);
				if(is_numeric(floatval($matches[1])) && $matches[1]) {
					return  $matches[1];
				}
				
				//Get FFMPEG GIT version
				preg_match("/ffmpeg version n\-([0-9]+)/i",strtolower($result),$matches);
				
				if(is_numeric(floatval($matches[1])) && $matches[1]) {
					return 'Git '.$matches[1];
				}
			}
			break;
			case 'php':
			{
				return phpversion(); 
			}
			break;
			case 'flvtool2':
			{
				preg_match("/flvtool2 ([0-9\.]+)/i",$result,$matches);
				if(is_numeric(floatval($matches[1]))){
					return $matches[1];
				} else {
					return false;	
				}
			}
			break;
			case 'mp4box':
			{
				preg_match("/version (.*) \(/Ui",$result,$matches);
				//pr($matches);
				if(is_numeric(floatval($matches[1]))){
					return $matches[1];
				} else {
					return false;	
				}
			}
		}
	}
	
	
	/**
	 * function used to call clipbucket footers
	 */
	function footer()
	{
		$funcs = get_functions('clipbucket_footer');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func();
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * Function used to generate RSS FEED links
	 */
	function rss_feeds($params)
	{
		/**
		 * setting up the feeds arrays..
		 * if you want to call em in your functions..simply call the global variable $rss_feeds
		 */
		$rss_link = cblink(array("name"=>"rss"));
		$rss_feeds = array();
		$rss_feeds[] = array("title"=>"Recently added videos","link"=>$rss_link."recent");
		$rss_feeds[] = array("title"=>"Most Viewed Videos","link"=>$rss_link."views");
		$rss_feeds[] = array("title"=>"Top Rated Videos","link"=>$rss_link."rating");
		$rss_feeds[] = array("title"=>"Videos Being Watched","link"=>$rss_link."watching");
		
		$funcs = get_functions('rss_feeds');
		if(is_array($funcs))
		{
			foreach($funcs as $func)
			{
				return $func($params);
			}
		}

		if($params['link_tag'])
		{
			foreach($rss_feeds as $rss_feed)
			{
				echo "<link rel=\"alternate\" type=\"application/rss+xml\"
				title=\"".$rss_feed['title']."\" href=\"".$rss_feed['link']."\" />\n";
			}
		}
	}
	
	/**
	 * Function used to insert Log
	 */
	function insert_log($type,$details)
	{
		global $cblog;
		$cblog->insert($type,$details);
	}
	
	/**
	 * Function used to get db size
	 */
	function get_db_size()
	{
		$result = mysql_query("SHOW TABLE STATUS");
		$dbsize = 0;
		while( $row = mysql_fetch_array( $result ) )
		{  
			$dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];
		}
		return $dbsize;
	}
	
	
	/**
	 * Function used to check weather user has marked comment as spam or not
	 */
	function marked_spammed($comment)
	{
		$spam_voters = explode("|",$comment['spam_voters']);
		$spam_votes = $comment['spam_votes'];
		$admin_vote = in_array('1',$spam_voters);
		if(userid() && in_array(userid(),$spam_voters)){
			return true;
		}elseif($admin_vote){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * function used to get all time zones
	 */
	function get_time_zones()
	{
		$timezoneTable = array(
			"-12" => "(GMT -12:00) Eniwetok, Kwajalein",
			"-11" => "(GMT -11:00) Midway Island, Samoa",
			"-10" => "(GMT -10:00) Hawaii",
			"-9" => "(GMT -9:00) Alaska",
			"-8" => "(GMT -8:00) Pacific Time (US &amp; Canada)",
			"-7" => "(GMT -7:00) Mountain Time (US &amp; Canada)",
			"-6" => "(GMT -6:00) Central Time (US &amp; Canada), Mexico City",
			"-5" => "(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima",
			"-4" => "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz",
			"-3.5" => "(GMT -3:30) Newfoundland",
			"-3" => "(GMT -3:00) Brazil, Buenos Aires, Georgetown",
			"-2" => "(GMT -2:00) Mid-Atlantic",
			"-1" => "(GMT -1:00 hour) Azores, Cape Verde Islands",
			"0" => "(GMT) Western Europe Time, London, Lisbon, Casablanca",
			"1" => "(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris",
			"2" => "(GMT +2:00) Kaliningrad, South Africa",
			"3" => "(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg",
			"3.5" => "(GMT +3:30) Tehran",
			"4" => "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi",
			"4.5" => "(GMT +4:30) Kabul",
			"5" => "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent",
			"5.5" => "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi",
			"6" => "(GMT +6:00) Almaty, Dhaka, Colombo",
			"7" => "(GMT +7:00) Bangkok, Hanoi, Jakarta",
			"8" => "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong",
			"9" => "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk",
			"9.5" => "(GMT +9:30) Adelaide, Darwin",
			"10" => "(GMT +10:00) Eastern Australia, Guam, Vladivostok",
			"11" => "(GMT +11:00) Magadan, Solomon Islands, New Caledonia",
			"12" => "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka"
		);
		return $timezoneTable;
	}
	
	
	/**
	 * Function used to get object type from its code
	 * ie v=>video
	 */
	function get_obj_type($type)
	{
		switch($type)
		{
			case "v":
			{
				return "video";
			}
			break;
		}
	}
	
	
	
	function check_cbvideo()
	{
		
		/**
		  * come, keep it for two more versions only
		  * it will be gone in next few updates by default :p
		  *
		  * but dont ever forget its name
		  * its a damn ClipBucket
		  */
		  
		if((!defined("isCBSecured") 
		|| count(get_functions('clipbucket_footer'))== 0 )
		&& !BACK_END) 
		{
				echo cbSecured(CB_SIGN_C);
		}
	}
	
	/**
	 * Gives coversion process output
	 */
	function conv_status($in)
	{
		switch($in)
		{
			case "p":
			return "Processing";
			break;
			case "no":
			return "Pending";
			break;
			case "yes":
			return "Done";
			break;
		}
	}

    function check_install($type)
    {
		global $while_installing,$Cbucket;
		switch($type)
		{
			case "before":
			{
				if(file_exists('files/temp/install.me') && !file_exists('includes/clipbucket.php'))
				{
					header('Location: '.get_server_url().'/cb_install');
				}
			}
			break;
			
			case "after":
			{
				if(file_exists('files/temp/install.me'))
				{
					$Cbucket->configs['closed'] = 1;
				}
			}
			break;
		}       
    }

    function get_server_url()
    {
        $DirName = dirname($_SERVER['PHP_SELF']);
        if(preg_match('/admin_area/i', $DirName))
        {
            $DirName = str_replace('/admin_area','',$DirName);
        }
		if(preg_match('/cb_install/i', $DirName))
        {
            $DirName = str_replace('/cb_install','',$DirName);
        }
        return get_server_protocol().$_SERVER['HTTP_HOST'].$DirName;
    }

    function get_server_protocol()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        {
            return 'https://';
        }
        else
        {
            $protocol = preg_replace('/^([a-z]+)\/.*$/', '\\1', strtolower($_SERVER['SERVER_PROTOCOL']));
            $protocol .= '://';
            return $protocol;
        }
    }
	
	
	/**
	 * Returns <kbd>true</kbd> if the string or array of string is encoded in UTF8.
	 *
	 * Example of use. If you want to know if a file is saved in UTF8 format :
	 * <code> $array = file('one file.txt');
	 * $isUTF8 = isUTF8($array);
	 * if (!$isUTF8) --> we need to apply utf8_encode() to be in UTF8
	 * else --> we are in UTF8 :)
	 * </code>
	 * @param mixed A string, or an array from a file() function.
	 * @return boolean
	 */
	function isUTF8($string)
	{
		if (is_array($string))
		{
			$enc = implode('', $string);
			return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
		}
		else
		{
			return (utf8_encode(utf8_decode($string)) == $string);
		}   
	}

    /*
        extract the file extension from any given path or url.
        source: http://www.php.net/manual/en/function.basename.php#89127
    */
    function fetch_file_extension($filepath)
    {
        preg_match('/[^?]*/', $filepath, $matches);
        $string = $matches[0];

        $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);

        # check if there is any extension
        if(count($pattern) == 1)
        {
            // no file extension found
            return;
        }

        if(count($pattern) > 1)
        {
            $filenamepart = $pattern[count($pattern)-1][0];
            preg_match('/[^?]*/', $filenamepart, $matches);
            return $matches[0];
        }
    }

    /*
        extract the file filename from any given path or url.
        source: http://www.php.net/manual/en/function.basename.php#89127
    */
    function fetch_filename($filepath)
    {
        preg_match('/[^?]*/', $filepath, $matches);
        $string = $matches[0];
        #split the string by the literal dot in the filename
        $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
        #get the last dot position
        $lastdot = $pattern[count($pattern)-1][1];
        #now extract the filename using the basename function
        $filename = basename(substr($string, 0, $lastdot-1));

        #return the filename part
        return $filename;
    }    
	
	/**
	 * Function used to generate
	 * embed code of embedded video
	 */
	function embeded_code($vdetails)
	{
		$code = '';
		$code .= '<object width="'.EMBED_VDO_WIDTH.'" height="'.EMBED_VDO_HEIGHT.'">';
		$code .= '<param name="allowFullScreen" value="true">';
		$code .= '</param><param name="allowscriptaccess" value="always"></param>';
		//Replacing Height And Width
		$h_w_p = array("{Width}","{Height}");
		$h_w_r = array(EMBED_VDO_WIDTH,EMBED_VDO_HEIGHT);	
		$embed_code = str_replace($h_w_p,$h_w_r,$vdetails['embed_code']);
		$code .= unhtmlentities($embed_code);
		$code .= '</object>';
		return $code;
	}
	
	
	/**
	 * function used to convert input to proper date created formate
	 */
	function datecreated($in)
	{
		
		$date_els = explode('-',$in);
		
		//checking date format
		$df = config("date_format");
		$df_els  = explode('-',$df);
		
		foreach($df_els as $key => $el)
			${strtolower($el).'id'} = $key;
		
		$month = $date_els[$mid];
		$day = $date_els[$did];
		$year = $date_els[$yid];

		if($in)
			return date("Y-m-d",strtotime($year.'-'.$month.'-'.$day));
		else
			return '0000-00-00';
	}
	
	
	/**
	 * After struggling alot with baseurl problem
	 * i finally able to found its nice and working solkution..
	 * its not my original but its a genuine working copy
	 * its still in beta mode 
	 */
	function baseurl()
	{
		$protocol = is_ssl() ? 'https://' : 'http://';
		if(!$sub_dir)
		return $base = $protocol.$_SERVER['HTTP_HOST'].untrailingslashit(stripslashes(dirname(($_SERVER['SCRIPT_NAME']))));
		else
		return $base = $protocol.$_SERVER['HTTP_HOST'].untrailingslashit(stripslashes(dirname(dirname($_SERVER['SCRIPT_NAME']))));

	}function base_url(){ return baseurl();}
	
	/**
	 * SRC (WORD PRESS)
	 * Appends a trailing slash.
	 *
	 * Will remove trailing slash if it exists already before adding a trailing
	 * slash. This prevents double slashing a string or path.
	 *
	 * The primary use of this is for paths and thus should be used for paths. It is
	 * not restricted to paths and offers no specific path support.
	 *
	 * @since 1.2.0
	 * @uses untrailingslashit() Unslashes string if it was slashed already.
	 *
	 * @param string $string What to add the trailing slash to.
	 * @return string String with trailing slash added.
	 */
	function trailingslashit($string) {
		return untrailingslashit($string) . '/';
	}

	/**
	 * SRC (WORD PRESS)
	 * Removes trailing slash if it exists.
	 *
	 * The primary use of this is for paths and thus should be used for paths. It is
	 * not restricted to paths and offers no specific path support.
	 *
	 * @since 2.2.0
	 *
	 * @param string $string What to remove the trailing slash from.
	 * @return string String without the trailing slash.
	 */
	function untrailingslashit($string) {
		return rtrim($string, '/');
	}
	
	
	/**
	 * Determine if SSL is used.
	 *
	 * @since 2.6.0
	 *
	 * @return bool True if SSL, false if not used.
	 */
	function is_ssl() {
		if ( isset($_SERVER['HTTPS']) ) {
			if ( 'on' == strtolower($_SERVER['HTTPS']) )
				return true;
			if ( '1' == $_SERVER['HTTPS'] )
				return true;
		} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 * This will update stats like Favorite count, Playlist count
	 */
	function updateObjectStats($type='favorite',$object='video',$id,$op='+')
	{
		global $db;
		
		switch($type)
		{
			case "favorite":  case "favourite":
			case "favorites": case "favourties":
			case "fav":
			{
				switch($object)
				{
					case "video": 
					case "videos": case "v":
					{
						$db->update(tbl('video'),array('favourite_count'),array("|f|favourite_count".$op."1")," videoid = '".$id."'");
					}
					break;
					
					case "photo":
					case "photos": case "p":
					{
						$db->update(tbl('photos'),array('total_favorites'),array("|f|total_favorites".$op."1")," photo_id = '".$id."'");
					}
					break;
					
					/*case "collection":
					case "collections": case "cl":
					{
						$db->update(tbl('photos'),array('total_favorites'),array("|f|total_favorites".$op."1")," photo_id = '".$id."'");
					}
					break;*/
				}
			}
			break;
			
			case "playlist": case "playList":
			case "plist":
			{
				switch($object)
				{
					case "video":
					case "videos": case "v":
					{
						$db->update(tbl('video'),array('playlist_count'),array("|f|playlist_count".$op."1")," videoid = '".$id."'");
					}
				}
			}
		}
	}
	
	
	/**
	 * FUnction used to check weather conversion lock exists or not
	 * if converson log exists it means no further conersion commands will be executed
	 *
	 * @return BOOLEAN
	 */
	function conv_lock_exists()
	{
		if(file_exists(TEMP_DIR.'/conv_lock.loc'))
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to return a well-formed queryString
	 * for passing variables to url
	 * @input variable_name
	 */
	function queryString($var=false,$remove=false)
	{
		$queryString = $_SERVER['QUERY_STRING'];
		
		if($var)
		$queryString = preg_replace("/&?$var=([\w+\s\b\.?\S]+|)/","",$queryString);
		
		if($remove)
		{
			if(!is_array($remove))
			$queryString = preg_replace("/&?$remove=([\w+\s\b\.?\S]+|)/","",$queryString);
			else
			foreach($remove as $rm)
				$queryString = preg_replace("/&?$rm=([\w+\s\b\.?\S]+|)/","",$queryString);
			
		}
		
		if($queryString)
			$preUrl = "?$queryString&";
		else
			$preUrl = "?";
		
		$preUrl = preg_replace(array("/(\&{2,10})/","/\?\&/"),array("&","?"),$preUrl);
		
		return $preUrl.$var;
	}
	
	
	/**
	 * Following two functions are taken from
	 * tutorialzine.com's post 'Creating a Facebook-like Registration Form with jQuery'
	 * These function are written by Martin Angelov.
	 * Read post here: http://tutorialzine.com/2009/08/creating-a-facebook-like-registration-form-with-jquery/
	 */
	function generate_options($params)
	{
		$reverse=false;
		
		if($params['from']>$params['to'])
		{
			$tmp=$params['from'];
			$params['from']=$params['to'];
			$params['to']=$tmp;
			
			$reverse=true;
		}
		
		
		$return_string=array();
		for($i=$params['from'];$i<=$params['to'];$i++)
		{
			//$return_string[$i] = ($callback?$callback($i):$i);
			$return_string[] = '<option value="'.$i.'">'.($params['callback']?$params['callback']($i):$i).'</option>';
		}
		
		if($reverse)
		{
			$return_string=array_reverse($return_string);
		}
		
		
		return join('',$return_string);
	}
	function callback_month($month)
	{
		return date('M',mktime(0,0,0,$month,1));
	}
	
	
	/**
	 * Function use to download file to server
	 * 
	 * @param URL
	 * @param destination
	 */
	function snatch_it($snatching_file,$destination,$dest_name,$rawdecode=true)
	{
		global $curl;
		if($rawdecode==true)
		$snatching_file= rawurldecode($snatching_file);
		$destination.'/'.$dest_name;
		$fp = fopen ($destination.'/'.$dest_name, 'w+');
		$ch = curl_init($snatching_file);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_USERAGENT, 
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2');
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	
	
	/**
	 * Function check curl
	 */
	function isCurlInstalled()
	{
		if  (in_array  ('curl', get_loaded_extensions())) {
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * Function for loading 
	 * uploader file url & other configurations
	 */
	function uploaderDetails()
	{
		global $uploaderType;

		$uploaderDetails = array
		(
			'uploadSwfPath' => JS_URL.'/uploadify/uploadify.swf',
			'uploadScriptPath' => BASEURL.'/actions/file_uploader.php',
		);
		
		$photoUploaderDetails = array
		(
			'uploadSwfPath' => JS_URL.'/uploadify/uploadify.swf',
			'uploadScriptPath' => BASEURL.'/actions/photo_uploader.php',
		);


		assign('uploaderDetails',$uploaderDetails);	
		assign('photoUploaderDetails',$photoUploaderDetails);		
		//Calling Custom Functions
		cb_call_functions('uploaderDetails');
	}
	
	
	/**
	 * Function isSectionEnabled
	 * This function used to check weather INPUT section is enabled or not
	 */
	function isSectionEnabled($input,$restrict=false)
	{
		global $Cbucket;
		
		$section = $Cbucket->configs[$input.'Section'];
		
		if(!$restrict)
		{
			if($section =='yes')
				return true;
			else
				return false;
		}else
		{
			if($section =='yes' || THIS_PAGE=='cb_install')
			{
				return true;
			}else
			{
				template_files('blocked.html');
				display_it();
				exit();
			}
		}
		
	}


	function array_depth($array) {
		$ini_depth = 0;
		
		foreach($array as $arr)
		{
			if(is_array($arr))
			{
				$depth = array_depth($arr) + 1;	
				
				if($depth > $ini_depth)
					$ini_depth = $depth;
			}
		}
		
		return $ini_depth;
	}
	
	
	/**
	 * JSON_ENCODE short
	 */
	function je($in){ return json_encode($in); }
	/**
	 * JSON_DECODE short
	 */
	function jd($in,$returnClass=false){ if(!$returnClass) return  json_decode($in,true); else return  json_decode($in); }
	
	
	/**
	 * function used to update last commented option 
	 * so comment cache can be refreshed
	 */
	function update_last_commented($type,$id)
	{
		global $db;
		
		if($type && $id)
		{
			switch($type)
			{
				case "v":
				case "video":
				case "vdo":
				case "vid":
				case "videos":
				$db->update(tbl("video"),array('last_commented'),array(now()),"videoid='$id'");

				break;
				
				case "c":
				case "channel":
				case "user":
				case "u":
				case "users":
				case "channels":
				$db->update(tbl("users"),array('last_commented'),array(now()),"userid='$id'");
				break;
				
				case "cl":
				case "collection":
				case "collect":
				case "collections":
				case "collects":
				$db->update(tbl("collections"),array('last_commented'),array(now()),"collection_id='$id'");
				break;
				
				case "p":
				case "photo":
				case "photos":
				case "picture":
				case "pictures":
				$db->update(tbl("photos"),array('last_commented'),array(now()),"photo_id='$id'");
				break;
				
				case "t":
				case "topic":
				case "topics":
				$db->update(tbl("group_topics"),array('last_post_time'),array(now()),"topic_id='$id'");
				break;
				
			}
		}
	}
	
	
	
	
	/**
	 * Function used to check 
	 * input users are valid or not
	 * so that only registere usernames can be set
	 */
	function video_users($users)
	{
		global $userquery;
		$users_array = explode(',',$users);
		$new_users = array();
		foreach($users_array as $user)
		{
			if($user!=username() && !is_numeric($user) && $userquery->user_exists($user))
			{
				$new_users[] = $user;
			}
		}
		
		$new_users = array_unique($new_users);
		
		if(count($new_users)>0)
			return implode(',',$new_users);
		else
			return " ";
	}
	
	/**
	 * function used to check weather logged in user is
	 * is in video users or not
	 */
	function is_video_user($vdo,$user=NULL)
	{
		
		if(!$user)
			$user = username();
		if(is_array($vdo))
		$video_users = $vdo['video_users'];
		else
		$video_users = $vdo;
		
		
		$users_array = explode(',',$video_users);

		if(in_array($user,$users_array))
			return true;
		else
			return false;
	}	
	/**
	 * Function used display privacy in text
	 * according to provided number
	 * 0 - Public
	 * 1 - Protected
	 * 2 - Private
	 */
	 function getGroupPrivacy($privacyID)
	 {
			{
				switch($privacyID)
				{
					case "0": default:
					{
						return lang("group_is_public");
					}
					break;
					
					case "1":
					{
						return lang("group_is_protected");
					}
					break;
					
					case "2":
					{
						return lang("group_is_private");
					}
					break;
				}
			}
	 }	
	
	/**
	 * Function used to create user feed
	 * this function simple takes ID as input
	 * and do the rest seemlessli ;)
	 */
	function addFeed($array)
	{
		global $cbfeeds,$cbphoto,$userquery;
		
		$action = $array['action'];
		if($array['uid'])
			$userid = $array['uid'];
		else
			$userid = userid();
			
		switch($action)
		{
			case "upload_photo":
			{

				$feed['action'] = 'upload_photo';
				$feed['object'] = 'photo';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			case "upload_video":
			case "add_favorite":
			{

				$feed['action'] = $action;
				$feed['object'] = 'video';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "signup":
			{

				$feed['action'] = 'signup';
				$feed['object'] = 'signup';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] =  $userid;;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "create_group":
			case "join_group":
			{
				$feed['action'] = $action;
				$feed['object'] = 'group';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "add_friend":
			{
				$feed['action'] = 'add_friend';
				$feed['object'] = 'friend';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "add_collection":
			{
				$feed['action'] = 'add_collection';
				$feed['object'] = 'collection';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;

				
				$cbfeeds->addFeed($feed);
			}
			
		}
	}
	
	/**
	 * function used to get plugin directory name
	 */
	function this_plugin($pluginFile=NULL)
	{
		if(!$pluginFile)
			global $pluginFile;
		return basename(dirname($pluginFile));
	}
	
	/**
	 * function used to create folder for video
	 * and files
	 */
	function createDataFolders()
	{
		$year = date("Y");
		$month = date("m");
		$day  = date("d");
		$folder = $year.'/'.$month.'/'.$day;
		@mkdir(VIDEOS_DIR.'/'.$folder,0777,true);
		@mkdir(THUMBS_DIR.'/'.$folder,0777,true);
		@mkdir(ORIGINAL_DIR.'/'.$folder,0777,true);
		@mkdir(PHOTOS_DIR.'/'.$folder,0777,true);
		
		return $folder;
	}
	
	
	/**
	 * function used to get user agent details
	 * Thanks to ruudrp at live dot nl 28-Nov-2010 11:31 PHP.NET
	 */
	function get_browser_details($in=NULL,$assign=false)
	{
		//Checking if browser is firefox
		if(!$in)
			$in = $_SERVER['HTTP_USER_AGENT'];
		
		$u_agent = $in;
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
	
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/iPhone/i', $u_agent)) {
			$platform = 'iphone';
		}
		elseif (preg_match('/iPad/i', $u_agent)) {
			$platform = 'ipad';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		if (preg_match('/Android/i', $u_agent)) {
			$platform = 'android';
		}
	   
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		elseif(preg_match('/Googlebot/i',$u_agent))
		{
			$bname = 'Googlebot';
			$ub = "bot";
		}elseif(preg_match('/msnbot/i',$u_agent))
		{
			$bname = 'MSNBot';
			$ub = "bot";
		}elseif(preg_match('/Yahoo\! Slurp/i',$u_agent))
		{
			$bname = 'Yahoo Slurp';
			$ub = "bot";
		}

	   
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!@preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
	   
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
	   
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
	   
		$array= array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'bname'		=> strtolower($ub),
			'pattern'    => $pattern
		);
		
		if($assign)	assign($assign,$array); else return $array;
	}
	
	function update_user_voted($array,$userid=NULL)
	{
		global $userquery;
		return $userquery->update_user_voted($array,$userid);	
	}
	
	/**
	  * function used to delete vidoe from collections
	  */
	function delete_video_from_collection($vdetails)
	{
		global  $cbvid;
		$cbvid->collection->deleteItemFromCollections($vdetails['videoid']);
	}
	
	
	/**
	 * function used to check
	 * remote link is valid or not
	 */
	
	function checkRemoteFile($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		if($result!==FALSE)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * function used to get counts from
	 * cb_counter table
	 */
	function get_counter($section,$query)
	{
		if(!config('use_cached_pagin'))
			return false;
			
		global $db;
		
		$timeRefresh = config('cached_pagin_time');
		$timeRefresh = $timeRefresh*60;
		
		$validTime = time()-$timeRefresh;
		
		unset($query['order']);
		$je_query = json_encode($query);
		$query_md5 = md5($je_query);
		$select = $db->select(tbl('counters'),"*","section='$section' AND query_md5='$query_md5' 
		AND '$validTime' < date_added");
		if($db->num_rows>0)
		{
			return $select[0]['counts'];
		}else
		return false;
	}
	
	/**
	 * function used to insert or update counter
	 */
	function update_counter($section,$query,$counter)
	{
		global $db;
		unset($query['order']);
		$je_query = json_encode($query);
		$query_md5 = md5($je_query);
		$count = $db->count(tbl('counters'),"*","section='$section' AND query_md5='$query_md5'");
		if($count)
		{
			$db->update(tbl('counters'),array('counts','date_added'),array($counter,strtotime(now())),
			"section='$section' AND query_md5='$query_md5'");
		}else
		{
			$db->insert(tbl('counters'),array('section','query','query_md5','counts','date_added'),
			array($section,'|no_mc|'.$je_query,$query_md5,$counter,strtotime(now())));
		}
	}
	
	/**
	 * function used to register a module file, that will be later called
	 * by load_modules() function
	 */
	function register_module($mod_name,$file)
	{
		global $Cbucket;
		$Cbucket->modules_list[$mod_name][] = $file;
		
	}
	
	/**
	 * function used to load module files
	 */
	function load_modules()
	{
		global $Cbucket,$lang_obj,$signup,$Upload,$cbgroup,
		$adsObj,$formObj,$cbplugin,$eh,$sess,$cblog,$imgObj,
		$cbvideo,$cbplayer,$cbemail,$cbpm,$cbpage,$cbindex,
		$cbcollection,$cbphoto,$cbfeeds,$userquery,$db,$pages,$cbvid;
		
		foreach($Cbucket->modules_list as $cbmod)
		{
			foreach($cbmod as $modfile)
				if(file_exists($modfile))
					include($modfile);
		}
	}
	
?>