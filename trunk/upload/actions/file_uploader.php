<?php

/**
 * @Author : Arslan Hassan
 */
include('../includes/config.inc.php');

//var_dump($_FILES);die();

if($_FILES['Filedata'])
	$mode = "upload";
if($_POST['insertVideo'])
	$mode = "insert_video";
if($_POST['getForm'])
	$mode = "get_form";
if($_POST['updateVideo']=='yes')
	$mode = "update_video";
	
switch($mode)
{
	
	case "insert_video":
	{
		$title 	= getName($_POST['title']);
		$file_name	= $_POST['file_name'];
		$file_directory = createDataFolders();
		//dump($file_directory);
		$vidDetails = array
		(
			'title' => $title,
			'description' => $title,
			'tags' => genTags(str_replace(' ',', ',$title)),
			'category' => array($cbvid->get_default_cid()),
			'file_name' => $file_name,
			'file_directory' => $file_directory,
			'userid' => userid(),
			'video_version' => '2.7',
		);
		
		$vid = $Upload->submit_upload($vidDetails);
		
		// sending curl request to content .ok 
		$call_bk = PLUG_URL."/cb_multiserver/api/call_back.php";
		$ch = curl_init($call_bk);
		$ch_opts = array(
		 CURLOPT_POST=>true,
		 CURLOPT_RETURNTRANSFER=> true,
		 //CURLOPT_BINARYTRANSFER => true,
		 CURLOPT_HEADER => false,
		 CURLOPT_SSL_VERIFYHOST=> false, 
		 CURLOPT_SSL_VERIFYPEER=> false,
		 CURLOPT_HTTPHEADER => array("Expect:"),
		);
		
		$array = array("update"=>TRUE);
		//curl_setopt($ch,CURLOPT_POSTFIELDS,$array);
		$charray = $ch_opts;
		$charray[CURLOPT_POSTFIELDS] = $array;
		
		curl_setopt_array($ch,$charray);		
		curl_exec($ch);	
		curl_close($ch);

		// inserting into video views as well
		$query = "INSERT INTO " . tbl("video_views") . " (video_id, video_views, last_updated) VALUES({$vid}, 0, " . time() . ")";
		$db->Execute($query);
		
		if(error())
		{
			echo json_encode(array("error" => error("single")));
		}else
		{
			echo json_encode(array("videoid" => $vid));
		}

		exit();
	}
	break;
	
	case "get_form":
	{
		$title 	= getName($_POST['title']);
		if(!$title)
			$title = $_POST['title'];
		$desc = $_POST['desc'];
		$tags = $_POST['tags'];
		
		if(!$desc)
			$desc = $title;
		if(!$tags)
			$tags = $title;
		
		$vidDetails = array
		(
		'title'		=> $title,
		'description' => $desc,
		'tags'		  => $tags,
		'category' => array($cbvid->get_default_cid()),
		);
		
		assign("objId",$_POST['objId']);
		
		assign('input',$vidDetails);

		$videoFields = $Upload->load_video_fields($vidDetails);
		//$requiredFields = array_shift($videoFields);
		// echo "<pre>";
		// var_dump($videoFields[0]);
		// echo "</pre>";
		echo json_encode($videoFields);
		//Template('blocks/upload/form.html');
	}
	break;
	
	case "upload":
	{

		$file_name	= time().RandomString(5);
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$targetFileName = $file_name.'.'.getExt( $_FILES['Filedata']['name']);
		$targetFile = TEMP_DIR."/".$targetFileName;
		$logFile = $file_name . ".log";
		
		
		$max_file_size_in_bytes = config('max_upload_size')*1024*1024;
		$types = strtolower(config('allowed_types'));
		
		//Checking filesize
		$POST_MAX_SIZE = ini_get('post_max_size');
		$unit = strtoupper(substr($POST_MAX_SIZE, -1));
		$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
	
		if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
			header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
			upload_error("POST exceeded maximum allowed size.");
			exit(0);
		}
		
		//Checking uploading errors
		$uploadErrors = array(
        0=>"There is no error, the file uploaded with success",
        1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3=>"The uploaded file was only partially uploaded",
        4=>"No file was uploaded",
        6=>"Missing a temporary folder"
		);
		if (!isset($_FILES['Filedata'])) {
			upload_error("No file was selected");
			exit(0);
		} else if (isset($_FILES['Filedata']["error"]) && $_FILES['Filedata']["error"] != 0) {
			upload_error($uploadErrors[$_FILES['Filedata']["error"]]);
			exit(0);
		} else if (!isset($_FILES['Filedata']["tmp_name"]) || !@is_uploaded_file($_FILES['Filedata']["tmp_name"])) {
			upload_error("Upload failed is_uploaded_file test.");
			exit(0);
		} else if (!isset($_FILES['Filedata']['name'])) {
			upload_error("File has no name.");
			exit(0);
		}
		
		//Check file size
		$file_size = @filesize($_FILES['Filedata']["tmp_name"]);
		if (!$file_size || $file_size > $max_file_size_in_bytes) {
			upload_error("File exceeds the maximum allowed size") ;
			exit(0);
		}
		
		
		//Checking file type
		$types_array = preg_replace('/,/',' ',$types);
		$types_array = explode(' ',$types_array);
		$file_ext = strtolower(getExt($_FILES['Filedata']['name']));
		if(!in_array($file_ext,$types_array))
		{
			upload_error("Invalid file extension");
			exit(0);
		}
		
		
		move_uploaded_file($tempFile,$targetFile);

		
		$Upload->add_conversion_queue($targetFileName);
		$quick_conv = config('quick_conv');
		$use_crons = config('use_crons');
		if($quick_conv=='yes' || $use_crons=='no')
		{
			//exec(php_path()." -q ".BASEDIR."/actions/video_convert.php &> /dev/null &");
			if (stristr(PHP_OS, 'WIN')) {
				//echo php_path()." -q ".BASEDIR."/actions/video_convert_test.php $targetFileName";
				exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $targetFileName");
			}elseif(stristr(PHP_OS, 'darwin'))
			{
				exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $targetFileName </dev/null >/dev/null &");
				
			} else {
				// for ubuntu or linux
				//echo php_path()." -q ".BASEDIR."/actions/video_convert_test.php $targetFileName > /dev/null &";
				exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $targetFileName > /dev/null &");
			}
		}
		
		echo json_encode(array("success"=>"yes","file_name"=>$file_name, 'phpos' => PHP_OS));
		
	}
	break;
	
	case "update_video":
	{
		$Upload->validate_video_upload_form();
		$_POST['videoid'] = trim($_POST['videoid']);
		if(empty($eh->error_list))
		{
			$cbvid->update_video();
		}
		if(error())
			echo json_encode(array('error'=>error('single')));
		else
			echo json_encode(array('msg'=>msg('single')));
	}
}


//function used to display error
function upload_error($error)
{
	echo json_encode(array("error"=>$error));
}
?>