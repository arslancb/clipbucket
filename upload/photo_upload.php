<?php
/* 
 *******************************************************************
 | Copyright (c) 2007-2016 Clip-Bucket.com. All rights reserved.	
 | @ Author   : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com
 | @ Modified : June 9, 2016 by Saqib Razzaq    
 *******************************************************************
*/

	define("THIS_PAGE","photo_upload");
	define("PARENT_PAGE","upload");

	require 'includes/config.inc.php';
	$userquery->logincheck();
	subtitle(lang('photos_upload'));
	if(isset($_GET['collection'])) {
		$c = $cbphoto->decode_key($_GET['collection']);
		assign('c',$cbphoto->collection->get_collection($c));	
	}

	if(isset($_POST['EnterInfo'])) {
		assign('step',2);
		$datas = $_POST['photoIDS'];
		$moreData = explode(",",$datas);
		$details = array();
		
		foreach($moreData as $key=>$data) {
			$data = str_replace(' ','',$data);
			$data = $cbphoto->decode_key($data);
			$details[] = $data;
		}

		assign('photos',$details);
	}

	if(isset($_POST['updatePhotos'])) {	
		assign('step',3);
	}
	$brace = 1;
	$collections = $cbphoto->collection->get_collections(array("type"=>"photos","public_upload"=>"yes","user"=>userid()),$brace);

	assign('collections',$collections);
	subtitle(lang('photos_upload'));

	//Displaying The Template
	if (!isSectionEnabled('photos')) {
		e("Photo are disabled the moment");
		$Cbucket->show_page = false;
	}

	template_files('photo_upload.html');
	display_it();

?>
