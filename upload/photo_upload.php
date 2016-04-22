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
define("PARENT_PAGE","upload");

require 'includes/config.inc.php';
$userquery->logincheck();
subtitle(lang('photos_upload'));

if(isset($_GET['collection']))
{
	$c = $cbphoto->decode_key($_GET['collection']);
	assign('c',$cbphoto->collection->get_collection($c));	
}

if(isset($_POST['EnterInfo']))
{
		assign('step',2);
		$datas = $_POST['photoIDS'];
		$moreData = explode(",",$datas);
		$details = array();
		
		foreach($moreData as $key=>$data)
		{
			$data = str_replace(' ','',$data);
			$data = $cbphoto->decode_key($data);
			$details[] = $data;
		}
		//pr($details,TRUE);
		assign('photos',$details);
}

if(isset($_POST['updatePhotos']))
{	
	assign('step',3);
}
$collections = $cbphoto->collection->get_collections(array("type"=>"photos","user"=>userid()));
assign('collections',$collections);
	
subtitle(lang('photos_upload'));
//Displaying The Template
template_files('photo_upload.html');
display_it();
?>