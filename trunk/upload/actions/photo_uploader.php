<?php

/**
 * @Author : Fawaz Tahir, Arslan Hassan
 * License : SWFUpload  <http://swfupload.org/>
 * This file is used to upload file using SWFUpload
 * you dont need to edit this file, edit it at yout own risk :)
 */
include('../includes/config.inc.php');

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
	HandleError("No upload found in \$_FILES for " . $form);
	exit(0);
}
elseif(isset($_FILES[$form]['error']) && $_FILES[$form]['error'] != 0) {
	HandleError($upErrors[$_FILES[$form]['error']]);
	exit(0);
}
elseif(!isset($_FILES[$form]["tmp_name"]) || !@is_uploaded_file($_FILES[$form]["tmp_name"])) {
	HandleError("Upload failed is_uploaded_file test.");
	exit(0);
} elseif(empty($_FILES[$form]['name'])) {
	HandleError("File name is empty");
	exit(0);	
}

// Time to check if Filesize is according to demands
//$filesize = filesize($_FILES[$form]['tmp_name']);
//if(!$filesize || $filesize > $max_size)
//{
//	HandleError("File exceeds the maximum allowed size");
//	exit(0);
//}
//
//if($filesize < 0)
//{
//	HandleError("File size outside allowed lower bound");
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
	HandleError("Invalid file extension");
	exit(0);	
}

$filename = $cbphoto->create_filename();


//Now uploading the file
if(move_uploaded_file($_FILES[$form]['tmp_name'],$path.$filename.".".$extension))
{
	// Photo Details
	$userid = $_POST['userid'];
	$collection = $_POST['collection'];
	$name = mysql_clean(substr($info['filename'],0,40));
	$desc = $name." description";
	$tag = strtolower($name);
	$key = $cbphoto->photo_key();
			
	//Making Array for inserting
	$flds = array("photo_key","photo_title","photo_description","photo_tags","userid","date_added","filename","ext","owner_ip");
	$vls  = array($key,$name,$desc,$tag,$userid,NOW(),$filename,$extension,$_SERVER['REMOTE_ADDR']);
	
	if(!empty($collection))
	{
		$flds[] = "collection_id";
		$vls[] = $collection;	
	}
	$total = count($flds);
	
	for($i=0;$i<$total;$i++)
	{
		$detailsArray[$flds[$i]] = $vls[$i];	
	}
	
	//$FileArray[$key] = $detailsArray;
	$FinalVar = base64_encode(serialize($detailsArray));	
	echo $FinalVar;
	
	
	// Creating Thumb and Med Size Image

	//$cbphoto->createThumb($path.$filename.".".$extension,$path.$filename."_t.".$extension,$extension,$cbphoto->thumb_width,$cbphoto->thumb_height);
	//$cbphoto->createThumb($path.$filename.".".$extension,$path.$filename."_m.".$extension,$extension,$cbphoto->mid_width,$cbphoto->mid_height);

	
	
	//Inserting into Database
	//$db->insert(tbl("photos"),$flds,$vls);
	//$insert_id = $db->insert_id();
	
	//Sending ID of photo to SWFupload
	
} else {	
	HandleError("File could not be saved.");
	exit(0);	
}


/* Handles the error output. This error message will be sent to the uploadSuccess event handler.  The event handler
will have to check for any error messages and react as needed. */
function HandleError($message) {
	echo $message;
} 
?> 