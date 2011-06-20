<?php

/**
 * @Author : Fawaz Tahir, Arslan Hassan
 */
  
include('../includes/config.inc.php');

if($_FILES['photoUpload'])
	$mode = "uploadPhoto";
if($_POST['photoForm'])
	$mode = "get_photo_form";
if($_POST['insertPhoto'])
	$mode = "insert_photo";
if($_POST['updatePhoto'])
	$mode = "update_photo";			

switch($mode)
{
	case "get_photo_form":
	{
		$name = getName($_POST['name']);
		if(!$name)
			$name = $_POST['name'];
		$desc = $name;
		$tags = $name;
		$collection = $_POST['collection'];
		$photoArray = array
		(
			"photo_title" => $name,
			"photo_description" => $name,
			"photo_tags" => $name,
			"collection_id" => $collection 
		);
		assign("uniqueID",$_POST['objID']);
		assign("photoArray",$photoArray);
		$form = Fetch("/blocks/upload/photo_form.html");
		echo json_encode(array("form"=>$form));	
	}
	break;
	case "insert_photo":
	{
		$_POST['photo_title'] = genTags(str_replace(array('_','-'),' ',$_POST['photo_title']));
		$_POST['photo_description'] = genTags(str_replace(array('_','-'),' ',$_POST['photo_description']));
		$_POST['photo_tags'] = genTags(str_replace(array(' ','_','-'),', ',$_POST['photo_tags']));
		$_POST['server_url'] = mysql_clean($_POST['server_url']);
		$_POST['folder'] = str_replace('..','',mysql_clean($_POST['folder']));
		
		$insert_id = $cbphoto->insert_photo();
		
		if(error())
			$response['error'] = error('single');
		if(msg())
		{
			$response['success'] = msg('single');
			$response['photoID'] = $insert_id;
			
			$details = $cbphoto->get_photo($insert_id);
			
			$params = array("details"=>$details,"size"=>"m");
			$response['photoPreview'] = get_photo($params);	
		}
		
		echo json_encode($response);
	}
	break;
	case "update_photo":
	{
		$_POST['photo_title'] = genTags(str_replace(array('_','-'),' ',$_POST['photo_title']));
		$_POST['photo_description'] = genTags(str_replace(array('_','-'),' ',$_POST['photo_description']));
		$_POST['photo_tags'] = genTags(str_replace(array(' ','_','-'),', ',$_POST['photo_tags']));
				
		$cbphoto->update_photo();
		
		if(error())
			$error = error('single');
		if(msg())
			$success = msg('single');
			
		$updateResponse['error'] = $error;
		$updateResponse['success'] = $success;
		
		echo json_encode($updateResponse);		
	}
	break;
	case "uploadPhoto":
	{
		$exts = $cbphoto->exts;
		$max_size = 1048576; // 2MB in bytes
		$form = "photoUpload";
		$path = PHOTOS_DIR."/";
		
		// These are found in $_FILES. We can access them like $_FILES['file']['error'].
		$upErrors = array(
						  0 => "There is no error, the file uploaded with success.",
						  1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
						  2 => " The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
						  3 => "The uploaded file was only partially uploaded.",
						  4 => "No file was uploaded.",
						  6 => "Missing a temporary folder.",
						  7 => "Failed to write file to disk."
						  );
						  
		// Let's see if everything is working fine by checking $_FILES.
		if(!isset($_FILES[$form])) {
			upload_error("No upload found in \$_FILES for " . $form);
			exit(0);
		}
		elseif(isset($_FILES[$form]['error']) && $_FILES[$form]['error'] != 0) {
			upload_error($upErrors[$_FILES[$form]['error']]);
			exit(0);
		}
		elseif(!isset($_FILES[$form]["tmp_name"]) || !@is_uploaded_file($_FILES[$form]["tmp_name"])) {
			upload_error("Upload failed is_uploaded_file test.");
			exit(0);
		} elseif(empty($_FILES[$form]['name'])) {
			upload_error("File name is empty");
			exit(0);	
		}
		
		// Time to check if Filesize is according to demands
		//$filesize = filesize($_FILES[$form]['tmp_name']);
		//if(!$filesize || $filesize > $max_size)
		//{
		//	upload_error("File exceeds the maximum allowed size");
		//	exit(0);
		//}
		//
		//if($filesize < 0)
		//{
		//	upload_error("File size outside allowed lower bound");
		//	exit(0);
		//}
		
		//Checking Extension of File
		$info = pathinfo($_FILES[$form]['name']);
		$extension  = strtolower($info['extension']);
		$valid_extension = false;
		
		foreach ($exts as $ext) {
			if (strcasecmp($extension, $ext) == 0) {
				$valid_extension = true;
				break;
			}
		}
		
		if(!$valid_extension)
		{
			upload_error("Invalid file extension");
			exit(0);	
		}
		
		$filename = $cbphoto->create_filename();
		
		
		//Now uploading the file
		if(move_uploaded_file($_FILES[$form]['tmp_name'],$path.$filename.".".$extension))
		{
			echo json_encode(array("success"=>"yes","filename"=>$filename,"extension"=>$extension));
			
		} else {	
			upload_error("File could not be saved.");
			exit(0);	
		}	
	}
	break;
}




//function used to display error
function upload_error($error)
{
	echo json_encode(array("error"=>$error));
} 
?> 