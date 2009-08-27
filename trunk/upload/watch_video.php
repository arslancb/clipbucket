<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

require 'includes/config.inc.php';

$pages->page_redir();
if(VIDEO_REQUIRE_LOGIN == 1){
	$userquery->logincheck();
}

//Getting Video Key
$vkey = @$_GET['v'];

if(isset($_REQUEST['delete_video']) && $is_admin == 1){
    $userquery->admin_login_check();
	$video = $_REQUEST['delete_video'];
	$myquery->DeleteVideo($video);
    header( 'Location: '.BASEURL.videos_link.'' ) ;
    }

if(isset($_REQUEST['delete_video']) && $is_admin != 1)
    {
    header( 'Location: '.BASEURL.videos_link.'' );
    }

//Checking Video Key
if(!empty($vkey) && $myquery->CheckVideoExists($vkey) && $vkey !=""){
	$videos 	= $myquery->GetVideDetails($vkey);
	$video_flv  = $videos['flv'];
		if($videos['active'] == 'yes'){
	$myquery->UpdateVideoViews($vkey);
	
//assigning Tags
$tags = $videos['tags'];
$tags = explode(" ", $tags );
Assign("tags",$tags);

//Getting Categories
$categories[] = $myquery->GetCategory($videos['category01']);
$categories[] = $myquery->GetCategory($videos['category02']);
$categories[] = $myquery->GetCategory($videos['category03']);
Assign("categories",$categories);
$videos['url'] 		= VideoLink($vkey,$videos['title']);
//Adding Comment
if(isset($_POST['add_comment'])){
	$comment = $_POST['comment'];
	$reply_to = $_POST['reply_to'];
	$msg = $myquery->add_comment($comment,$videos['videoid'],$reply_to);
}

//Rating Comments
	if(isset($_POST['thumb_up'])){
		$rate = 1;
		$commentid = $_POST['comment_id'];
		$msg = $myquery->RateComment($rate,$commentid);
		}	
	if(isset($_POST['thumb_down'])){
		$rate = -1;
		$commentid = $_POST['comment_id'];
		$msg = $myquery->RateComment($rate,$commentid);
		}

//Deleting Comment
if(isset($_GET['delete_comment'])){
	$cid 	= $_GET['delete_comment'];
	$query 	= mysql_query("SELECT videoid FROM video_comments WHERE comment_id='".$cid."'");
	$data 	= mysql_fetch_array($query);
	$query 	= mysql_query("SELECT username FROM video WHERE videoid='".$data['videoid']."'");
	$data 	= mysql_fetch_array($query);
	if(!is_numeric($cid)){
		$msg = $LANG['vdo_cheat_msg'];
	}
    elseif($is_admin)
    {
    $msg = "Comment Has Been Deleted";
    $myquery->DeleteComment($cid,$videos['videoid']);
    }
    elseif($_SESSION['username'] != $videos['username'] || $_SESSION['username'] !=$data['username']){
		$msg = $LANG['vdo_limits_warn_msg'];
	}else{
		$msg = $LANG['vdo_cmt_del_msg'];
		$myquery->DeleteComment($cid,$videos['videoid']);
	}
}

//Getting Comments
$sql = "Select * FROM comments WHERE type='v' AND type_id='".$videos['videoid']."' ORDER BY date_added DESC";
$rs = $db->Execute($sql);
$total_comments = $rs->recordcount() + 0;
$comments = $rs->getrows();

	
Assign('total_comments',$total_comments);
Assign('comments',$comments);	

//Check Video Favourites
$sql = "Select * from  video_favourites WHERE videoid='".$videos['videoid']."'";
$rs = $db->Execute($sql);
$favourited = $rs->recordcount() + 0;
Assign('favourited',$favourited);



//Getting Duration and Original File
$details = $myquery->GetVideoDetail($videos['flv']);
$details['duration'] = SetTime($details['duration']);
$flv_status = @$details['status'];
Assign("details",$details);

//Get User Data
assign("udetails",$userquery->get_user_details($videos['userid']));

//Subscribing User
if(isset($_POST['subscribe'])){
		$userquery->logincheck();
		$sub_user	= $_COOKIE['username'];
		$sub_to		= $user_data['username'];
		$msg = $userquery->SubscribeUser($sub_user,$sub_to);
	}
		
//Get Rating Details
$rating 	= $videos['rating']/2;
$rated_by 	= $videos['rated_by'];
Assign("rating",$rating);
Assign("rated_by",$rated_by);

if(VIDEO_RATING == '1' && $videos['allow_rating']=='yes'){
$vote = '';
}else{
$vote = 'novote';
}
Assign('show_rating',pullRating($videos['videoid'],false,false,false,$vote));

	//Setting Login 
	if(isset($_COOKIE['userid'])){
		Assign('isLogged',1);
	}
		$niddle = "|";
		$niddle .= @$_COOKIE['userid'];
		$niddle .= "|";
		$flag = strstr($videos['voter_ids'], $niddle);
		if(!empty($flag)){
		Assign('a_v','yes');
		}
	//Add Br in Description
	$videos['description'] = nl2br($videos['description']);
	//Decoding Embedcode
	$videos['embed_code_encoded']  = htmlentities($videos['embed_code']);	
	//Decoding Url
	$videos['flv_file_url']  = urlencode($videos['flv_file_url']);
Assign("videos",$videos);

$query_param = "broadcast='public' AND active='yes' AND status='Successful'";
//Getting Featured Vieo List 0,5
$sql = "SELECT * FROM video WHERE featured = 'yes' AND $query_param  ORDER BY date_added DESC LIMIT 0,5";
$rs = $db->Execute($sql);
$featured = $rs->getrows();
$total_featured = $rs->recordcount() + 0;
	for($id=0;$id<$total_featured;$id++){
	$flv = $featured[$id]['flv'];
	$thumb = GetThumb($flv);
	$featured[$id]['thumb'] = $thumb;
	$featured[$id]['url'] 		= VideoLink($featured[$id]['videokey'],$featured[$id]['title']);
	}
Assign('featured',$featured);


//Getting Related Videos List
$title 	= mysql_clean($videos['title']);
$tags 	= mysql_clean($videos['tags']);
$c1 	= $videos['category01'];
$c2 	= $videos['category02'];
$c3 	= $videos['category03'];

$limit = VLISTPT;
$limit = "LIMIT ".$limit;
$query = "SELECT * FROM video WHERE
					broadcast  	= 'public' AND 
					(title 		like '%$title%' OR 
					tags  		like '%$tags%'  OR
					category 	like '%$c1%'  )  AND $query_param  AND videokey!='".$videos['videokey']."' ORDER BY date_added DESC $limit";
					
$rs = $db->Execute($query);
$related = $rs->getrows();
$total_related = $rs->recordcount() + 0;
	for($id=0;$id<$total_related;$id++){
	$flv = $related[$id]['flv'];
	$thumb = GetThumb($flv);
	$related[$id]['thumb'] = $thumb;
	$flv_details = $myquery->GetVideoDetail($related[$id]['flv']);
	$related[$id]['duration'] = SetTime($flv_details['duration']);
	$related[$id]['url'] 		= VideoLink($related[$id]['videokey'],$related[$id]['title']);
	}
Assign('related',$related);

//Assigning TagClouds
$TagClouds = TagClouds("SELECT tags FROM video WHERE
					broadcast  	= 'public' AND 
					(title 		like '%$title%' OR 
					tags  		like '%$tags%'  OR
					category 	like '%$c1%' )  AND $query_param  ORDER BY videoid ASC LIMIT 0,20");
Assign('TagClouds', $TagClouds);

//Adding Video To Favourite
if(isset($_POST['add_to_fav']) || isset($_POST['add_to_fav_x']) || isset($_POST['add_to_fav_y'])){
	$userquery->logincheck();
	$msg=$myquery->AddToFavourite($_COOKIE['userid'],$videos['videoid']);
	}

if(isset($_POST['flag_video']) || isset($_POST['flag_video_x']) || isset($_POST['flag_video_y'])){
	$userquery->logincheck();
	$msg=$myquery->FlagAsInappropriate($_COOKIE['username'],$videos['videoid']);
	}

//Share Video
if(isset($_POST['share_video']) || isset($_POST['share_video_x']) || isset($_POST['share_video_y'])){
	$email 		= mysql_clean($_POST['emails']);
	$message 	= mysql_clean($_POST['message']);
	$userquery->logincheck();
	if(isset($_SESSION['username'])){
	$msg=$myquery->ShareVideo($_COOKIE['username'],$videos['videoid'],$message,$email);
	}
}

//Update Number Of Watched Videos of User
	if(isset($_COOKIE['userid'])){
		$userquery->UpdateWatched($_COOKIE['userid']);
		}
	
	}else{
	$msg = $LANG['vdo_iac_msg'];
		//if(!isset($_SESSION['admin'])){
		Assign('show_video','no');
        header( 'Location:' .BASEURL.'/inactive.php' ) ;
		//}
	}
}else{
$msg = $LANG['class_vdo_del_err'];
Assign('show_video','no');
}

//This Function Create in some critical situation When Video Failed to get the duration and then this function initialized
if($myquery->CheckVideoExists($vkey) && @$videos['status'] != 'Successful'){
		//$msg = $LANG['vdo_is_in_process'];
		if(!isset($_SESSION['admin'])){
			Assign('show_video','no');
            header( 'Location:' .BASEURL.'/inactive.php' ) ;
		}
}

@Assign('msg',$msg);
subtitle('watch_video',$videos['title']);
Template('header.html');
Template('message.html');
Template('watch_video.html');
Template('footer.html');
?>