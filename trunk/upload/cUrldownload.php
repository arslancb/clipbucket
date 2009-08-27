<?php

ini_set('allow_url_fopen','On');

if(function_exists(curl_init)){
$snatch_system = 'curl';
}else{
$snatch_system = 'url_open';
}

require_once('includes/conversion.conf.php');

// Get arguments from the argv array 
$url		= $_SERVER['argv'][1];
$flv 		= $_SERVER['argv'][2]; 


if(!empty($url) && !empty($flv)){
//Snatching File From FLV URL
	$defaultDest = $path = BASEDIR."/files/temp";
	$password = '';
	$os = '1';

	if (!file_exists($defaultDest)) {
		mkdir($defaultDest);
	}
	//chdir($defaultDest);
	$sizelimit_mb = MAX_UPLOAD_SIZE;
	$sizelimit = $sizelimit_mb * 1024 * 1024;

	$file = rawurldecode($url);

	$uploadfile = explode('/', $file);
	$filename = array_pop($uploadfile);

	$flvname		= $flv;
	$new_name		= substr($flvname, 0, strrpos($flvname, '.'));
	$ext			= substr($filename, strrpos($filename,'.') + 1);
	$newfilename	= $new_name.".".$ext;


	if(strstr($newfilename, '/')) {
		$new_path = explode('/', $newfilename);
		array_pop($new_path);
		$new_path = implode('/', $new_path);
		if(!file_exists($new_path)) {
			mkdir($new_path);
		}
	}

	$newfilename = str_replace('*', $filename, $newfilename);
	if (!$newfilename) {
		$newfilename = $filename;
	}
	$error = false;
	if (!isset($file))
		$error = true;
	if (!isset($newfilename))
		$error = true;
	if ($error == false) {
		$dest = $defaultDest;
		$ds = array($dest, '/', $newfilename);
		$ds = implode('', $ds);
		$newname_count = 0;
		if (file_exists($ds)) {
			//Dev Add - next 1 line
			$new_name_needed = true;
			$newname_count++;
			$newfile = array($newname_count, $newfilename);
			$newfile = implode('~', $newfile);
			$newfile_ds = array($dest, '/', $newfile);
			$newfile_ds = implode('', $newfile_ds);
			$renamed = "";
			while($renamed == false) {
				if (file_exists($newfile_ds)) {
					$newname_count++;
					$newfile = array($newname_count, $newfilename);
					$newfile = implode('~', $newfile);
					$newfile_ds = array($dest, '/', $newfile);
					$newfile_ds = implode('', $newfile_ds);
				} else {
					$renamed = true;
				}
			}
			$newfilename = $newfile;
			$ds = $newfile_ds;
		}
		if ($snatch_system == 'curl') {
			// Start Remote File Size //
			ob_start();
			$chF = curl_init($file);
			$header = array(
				"User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
				"Accept: " . $_SERVER['HTTP_ACCEPT'],
				"Accept-Language: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'],
				"Accept-Charset: ". $_SERVER ['HTTP_ACCEPT_CHARSET'],
				"Accept-Encoding: " . $_SERVER['HTTP_ACCEPT_ENCODING'],
				"Connection: " . $_SERVER['HTTP_CONNECTION']
			);
			curl_setopt($chF, CURLOPT_HEADER,1);
			curl_setopt($chF, CURLOPT_HTTPHEADER, $header);
			curl_setopt($chF, CURLOPT_NOBODY, 1);

			$ok = curl_exec($chF);
			curl_close($chF);
			$head = ob_get_contents();
			ob_end_clean();

			$regex = '/Content-Length:\s([0-9].+?)\s/';
			$count = preg_match($regex, $head, $matches);

			if($matches[1] > 0) {
				if (isset($matches)) {
					$file_size = $matches[1];
					$file_size_kb = round($file_size/1024, 2);
				} else {
					$no_filesize = true;
					unset($file_size);
				}
			} else {
			}
			$copy_fail = false;
			if($file_size > $sizelimit && $sizelimit > 0)
				$copy_fail = true;

			if($copy_fail == false && $file_size > 0) {
				$header = array(
					"User-Agent: ". $_SERVER['HTTP_USER_AGENT'],
					"Accept: " . $_SERVER['HTTP_ACCEPT'],
					"Accept-Language: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'],
					"Accept-Charset: ". $_SERVER ['HTTP_ACCEPT_CHARSET'],
					"Accept-Encoding: " . $_SERVER['HTTP_ACCEPT_ENCODING'],
					"Connection: " . $_SERVER['HTTP_CONNECTION']
				);
				$ch = curl_init($file);
				$fp = fopen($ds, 'w');
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_exec($ch);
				$curl_info =  curl_getinfo($ch);
				curl_close($ch);
				fclose($fp);
			}
			
			//incase curl doesnt not work
		}elseif($snatch_system == 'url_open' || !file_exists($newfile_ds)){
			if (!copy($file, $ds))
				$copy_fail = true;
		}

		if ($copy_fail == false) {
			if ($sizelimit > 0 && filesize($ds) > $sizelimit) {
				unlink($ds);
			} else {
				if ($snatch_system == 'curl') {
					$size_dl = round($curl_info['size_download']/1024, 2);
					$speed_dl = round($curl_info['speed_download']/1024, 2);
				}
			}
		}

		$_SESSION['is_upload'] = "Success";
		
		$php_path = PHP_PATH;
		exec("$php_path convert_process.php $newfilename $flvname >> ".BASEDIR."/logs/logs.txt &");
	}	
}

?>