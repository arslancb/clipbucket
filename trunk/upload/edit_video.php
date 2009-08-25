<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();
$user	= $_SESSION['username'];

$videoid = $_REQUEST['videoid'];

if($userquery->CheckVideoOwner($videoid,$user) == 1 || $is_admin == 1)
{
//Getting Video Id

		if($myquery->VideoExists($videoid)){
			//Updating Video
			if(isset($_POST['update'])){
					$msg = $Upload->ValidateUploadForm();
					if(empty($msg)){
					$msg = $myquery->UpdateVideo($videoid);
					}
			}
				
		$data = $myquery->GetVideoDetails($videoid);
		//Assigning Thumbs 
		$data['thumb1'] = GetThumb($data['flv'],1);
		$data['thumb2'] = GetThumb($data['flv'],2);
		$data['thumb3'] = GetThumb($data['flv'],3);
		$data['url'] 	= VideoLink($data['videokey'],$data['title']);
		Assign('data',$data);
		Assign('country',$signup->country());
		}else{
		$msg = $LANG['class_vdo_del_err'];
		Assign('show_form','no');
		}

	//Assigning Category List
	$sql = "SELECT * from category";
	$rs = $db->Execute($sql);
	$total_categories = $rs->recordcount() + 0;
	$category = $rs->getrows();
	Assign('category', $category);	
	
Assign('subtitle',$LANG['title_edit_video'].$data['title']);
@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('edit_video.html');
Template('footer.html');
}
else
{
header( 'Location: '.videos_link.'' ) ;
}
?>