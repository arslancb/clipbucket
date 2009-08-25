<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();


//Assing Counntry List
Assign('country',$signup->country());

//Assigning Category List
	$sql = "SELECT * from category";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);	

if(isset($_POST['upload_01'])){
	//Validatin Categories
		$sql = mysql_query("SELECT * from category");
		$total_categories = mysql_num_rows($sql);
		$selected = 0;
		for($id=0;$id<=$total_categories;$id++){
		$category = @$_POST['category'][$id];
		if(!empty($category)){
		$selected = $selected +1 ;
		}
		}
		if($selected == 0){
		$msg[] = "Please Choose At least 1 Category";
		}elseif($selected >3){
		$msg[] = "You Can Only Choose Up to 3 Categoriess";
		}
		
		if(empty($_POST['num']) || !is_numeric($_POST['num'])){
		$msg [] = "Please Enter a Valid Number In Number Field";
		}
		if(empty($msg)){
		Assign('step','2');
		Assign('loop',$_POST['num']);
		}
	//$youtube->UploadProcess();
	//}
}
			//Assign Form Chizzein
			@$values= array(
			'default_num'				=> mysql_clean($_POST['num']),
			'default_location'			=> mysql_clean($_POST['location']),
			'default_country'			=> mysql_clean($_POST['country']),
			'default_broadcast'			=> $_POST['broadcast'],
			'default_m'					=> $_POST['month'],
			'default_d'					=> $_POST['day'],
			'default_y'					=> $_POST['year'],
			'default_comment'			=> $_POST['comments'],
			'default_embedding'			=> $_POST['embedding'],
			'default_rating' 			=> $_POST['rating'],
			'default_comments_voting'	=> $_POST['comment_voting'],
			'default_date'				=> $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day']
			);
			while(list($name,$value) = each($values)){
			DoTemplate::assign($name,$value);
			}
			
			for($id=0;$id<=3;$id++){
				$category = @$_POST['category'][$id];
				Assign('category'.$id,$category );
			}
			
function AssignDefaultThumb($flv){
		global $row;
		//Minus Extension
		$site_template = BASEDIR.'/styles/'.$row['template_dir'];
		$filename_minus_ext = substr($flv, 0, strrpos($flv, '.'));
		$proccesing_thumb = $site_template.'/images/en/processing.png';
		$proccesing_thumb_big = $site_template.'/images/en/processing-big.png';
		copy($proccesing_thumb,'../files/thumbs/'.$filename_minus_ext.'-1.jpg');
		copy($proccesing_thumb,'../files/thumbs/'.$filename_minus_ext.'-2.jpg');
		copy($proccesing_thumb,'../files/thumbs/'.$filename_minus_ext.'-3.jpg');
		copy($proccesing_thumb_big,'../files/thumbs/'.$filename_minus_ext.'-big.jpg');			
}

//Form Checking And Processing
if(isset($_POST['upload_02'])){
	//Including FFMPEG CLASS
	if($row['con_modules_type'] == 0){
	require_once('../includes/classes/conversion/ffmpeg.class.php');
	}else{
	require_once('../includes/classes/conversion/multi.class.php');
	}
	
	for ($id = 0; $id <= $_POST['loop']; $id ++){
	$title 		= @$_POST['title'][$id];
	$des 		= @$_POST['des'][$id];
	$tags 		= @$_POST['tags'][$id];
	$file 		= @$_FILES['file']['name'][$id];
		if(!empty($title) && !empty($des) && !empty($file) ){
		
		$newflv[$id] 		= rand(1000000,999999999999999).'.flv';
		if($Upload->CheckFLV(@$flv)){
		$newflv[$id] 		= rand(1000000,999999999999999).'.flv';
		}
		
		$filename[$id] 	 	= $newflv[$id];
		$new_name 	 		= substr($filename[$id], 0, strrpos($filename[$id], '.'));
		$ext 				= substr($file, strrpos($file,'.') + 1);
		$newfilename[$id] 	= $new_name.".".$ext;
		$path = BASEDIR."/files/temp/".$newfilename[$id];
		copy($_FILES['file']['tmp_name'][$id], $path);
		$MassUpload->DataEntry($title,$des,$newflv[$id],$tags);
		AssignDefaultThumb($newflv[$id]);		
		}
	}
	for ($id = 0; $id <= $_POST['loop']; $id ++){
	if(!empty($newfilename[$id])){
	$ffmpeg = new ffmpeg();
	$ffmpeg->ConvertFile($newfilename[$id],$newflv[$id]);					
	}
	}
$msg = "All Files Has Been Uploaded";
}
	
Assign('msg',@$msg);
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('mass_uploader.html');
Template('footer.html');

?>