<?php
/* 
 *******************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.	
 | @ Author   : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com
 | @ Modified : June 14, 2009 by Arslan Hassan
 *******************************************************************
*/

define("THIS_PAGE","photo_upload");
define("PARENT_PAGE","photo_upload");

require 'includes/config.inc.php';
$userquery->logincheck();
subtitle(lang('photos_upload'));

assign('max_upload',MAX_PHOTO_UPLOAD);

if(isset($_POST['EnterInfo']))
{
		assign('step',2);
		$datas = $_POST['photoIDS'];
		$moreData = explode(",",$datas);
		$details = array();
		
		foreach($moreData as $key=>$data)
		{
			$data = str_replace(' ','',$data);
			$data = unserialize(base64_decode($data));
			$details[] = $data;
		}
		//pr($details,TRUE);
		assign('photos',$details);
}

if(isset($_POST['update_photos']))
{
	
	//$newArray = $cbphoto->return_formatted_post($POST);	
	//$cbphoto->update_multiple_photos($newArray);	
	assign('step',2);
}
$collections = $cbphoto->collection->get_collections(array("type"=>"photos","user"=>userid()));
assign('c',$collections);
	
subtitle(lang('photos_upload'));
//Displaying The Template
template_files('photo_upload.html');
display_it();
?>