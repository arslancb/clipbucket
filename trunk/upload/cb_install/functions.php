<?php
	
	
	define("BASEDIR",dirname(dirname(__FILE__)));
	
	if(!file_exists(BASEDIR.'/files/temp/install.me'))
		$mode = "lock";
	
	//major functions goes here


	function get_cbla()
	{
		//$license	= @file_get_contents('http://clip-bucket.com/get_contents.php?mode=cbla');
		if(!@$license)
		{
			$license	= file_get_contents('LICENSE');
			$license	= str_replace("\n",'<BR>',$license);
			$license	= str_replace("{this_year}",date("Y",time()),$license);
		}
		return $license;
	}
	
	
	function button($text,$params=NULL,$alt=false)
	{
		echo '<span class="bttnleft" '.$params.'>&nbsp;</span>';
		echo '<span class="bttncenter" '.$params.'>'.$text.'</span>';
		if(!$alt)
			echo '<span class="bttnright" '.$params.'>&nbsp;</span>';
		else
			echo '<span class="bttnrightalt" '.$params.'>&nbsp;</span>';
	}
	
	function msg_arr($arr)
	{
		if(@$arr['msg'])
			return emsg($arr['msg'],'ok');
		else
			return emsg($arr['err'],'alert');
	}
	
	if(!function_exists('emsg'))
	{
		function emsg($text,$type='ok')
		{
			return '<span class="msg '.$type.'">'.$text.'</span>';
		}
	}
	
	
	function check_module($type)
	{
		$return = array();
		switch($type)
		{
			case "php":
			{
				
				$v =  phpversion();
				$req = '5.2.1';
				if($v<$req)
					$return['err'] = sprintf(_("Found PHP %s but required is PHP %s "),$v,$req);
				else
					$return['msg'] = sprintf(_("Found PHP %s "),$v);
			}
			break;
			
			case "ffmpeg":
			{
				$ffmpeg_path = exec("which ffmpeg");
				$ffmpeg_version = shell_output("$ffmpeg_path -version");
				
				$version = false;
				preg_match("/SVN-r([0-9]+)/i",$ffmpeg_version,$matches);
				if(@$matches[1])
					$version = 'r'.$matches[1];
				preg_match("/version ([0-9.]+)/i",$ffmpeg_version,$matches);
				if(@$matches[1])
					$version = $matches[1];
				
				if(!$version)
					$return['err'] = _("Unable to find ffmpeg");
				else
					$return['msg'] = sprintf(_("Found FFMPEG %s : %s"),$version,$$ffmpeg_path);
			}
			break;
			
			case "flvtool2":
			{
				$flvtool2_path = exec("which flvtool2");
				$flvtool2_version = shell_output("$flvtool2_path -version");
				$version = false;
				preg_match("/flvtool2 ([0-9.]+)/i",$flvtool2_version,$matches);
				if(@$matches[1])
					$version = $matches[1];
				if(!$version)
					$return['err'] = _("Unable to find flvtool2");
				else
					$return['msg'] = sprintf(_("Found flvtool2 %s : %s"),$version,$flvtool2_path);

			}
			break;
			
			case "mp4box":
			{
				$mp4boxpath = exec("which MP4Box");
				$mp4box_version = shell_output("$mp4boxpath -version");
				$version = false;
				preg_match("/GPAC version ([0-9.]+)/i",$mp4box_version,$matches);
				if(@$matches[1])
					$version = $matches[1];
				if(!$version)
					$return['err'] = _("Unable to find MP4Box");
				else
					$return['msg'] = sprintf(_("Found MP4Box %s : %s"),$version,$mp4boxpath);


			}
			break;
			
			case "curl":
			{
				$version = false;
				if(function_exists('curl_version'))
				$version = @curl_version();

				if(!$version)
					$return['err'] = _("cURL library is not neabled");
				else
					$return['msg'] = sprintf(_("cURL %s found"),$version['version']);

			}
			break;
			
			case "phpshield":
			{
				if(!function_exists('phpshield_load'))
					$return['err'] = _("PHPShield loaders are not installed (optional)");
				else
					$return['msg'] = _("PHPShield loaders are working (optional)");
			}
			break;
			
			
			
		}
		
		return $return;
	}
	
	if(!function_exists('_'))
	{
		function _($in)
		{
			return $in;
		}
	}
	
	
	if(!function_exists('shell_output'))
	{
		function shell_output($cmd)
		{
			if (stristr(PHP_OS, 'WIN')) { 
				$cmd = $cmd;
			}else{
				$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\"  2>&1";
			}
			$data = shell_exec( $cmd );
			return $data;
		}
	}
	
	
	/**
	 * Short form of print_r as pr
	 */
	if(!function_exists('pr'))
	{
		function pr($text,$wrap_pre=false)
		{
			if(!$wrap_pre)
			print_r($text);
			else
			{
				echo "<pre>";
				print_r($text);
				echo "</pre>";
			}
		}
	}
	
	
	/**
	 * Function used to check folder permissions
	 */
	function checkPermissions()
	{
		$files = array
		(
			'cache',
			'cache/comments',
			'cache/userfeeds',
			'files',
			'files/conversion_queue',
			'files/logs',
			'files/mass_uploads',
			'files/original',
			'files/photos',
			'files/temp',
			'files/temp/install.me',
			'files/thumbs',
			'files/videos',
			'images',
			'images/avatars',
			'images/backgrounds',
			'images/category_thumbs',
			'images/collection_thumbs',
			'images/groups_thumbs',
			'includes',
			'includes/langs/en.lang'
		);
		
		$permsArray = array();
		foreach($files as $file)
		{
			if(is_writeable(BASEDIR.'/'.$file))
			{
				$permsArray[] = array('path'=>$file,'msg'=>'writeable');
			}else
				$permsArray[] = array('path'=>$file,'err'=>'please chmod this file/directory to 777');				
		}
		return $permsArray;
	}
	
	
	/**
	 * FUNCTION USED TO CLEAN VALUES THAT CAN BE USED IN FORMS
	 */
	if(!function_exists('cleanForm'))
	{
		function cleanForm($string)
		{
			if(is_string($string))
				$string = htmlspecialchars($string);
			if(get_magic_quotes_gpc())
				$string = stripslashes($string);
			return $string;
		}
		function form_val($string){return cleanForm($string); }
	}
	
	
	
	function selected($selected)
	{
		global $mode;
		if($mode==$selected)
			return "class='selected'";
	}
	
	/**
	 * Function used to create list of files
	 * that have to be executed while upgrade
	 */
	function getUpgradeFiles()
	{
		global $versions,$upgrade;
		$version = VERSION;
		$oldVer = $upgrade;
		if($oldVer)
		{
			$total = count($versions);
			$files = array();
			
			$found = false;
			foreach($versions as $ver)
			{
				if($found)
					$files[] = $ver;
				if($ver==$oldVer)
					$found = true;
			}
			return $files;
		}
		
		return false;
	}
		
?>