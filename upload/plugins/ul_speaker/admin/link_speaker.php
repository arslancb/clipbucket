<?php
require UL_SPEAKER_DIR.'/speaker_class.php';
$userquery->admin_login_check();
// Controle de permission probablement non fonctionnel sur les plugins
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Speaker');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Link Speaker');
}

//get video object
$video = $cbvid->getVideo($_GET['video']);
Assign('video',$video);

//Link Multiple speaker functions
if(isset($_POST['link_selected'])){
	//remove unselected link from the first list
	$eh->flush();
	$cnt=count($_POST['checked_speaker']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$speakerquery->unlink_speaker($_POST['checked_speaker'][$id],$video['videoid']);
		//e("Selected speakers have been deleted","m");
	}
	//add selected link from the second list
	$cnt=count($_POST['check_speaker']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++)
			$speakerquery->link_speaker($_POST['check_speaker'][$id],$video['videoid']);
		//e("Selected speakers have been deleted","m");
	}
}

//Filter speaker list
if(isset($_POST['filter'])){
	$filtercond=" firstname like '%".$_POST['firstname']."%' AND lastname like '%".$_POST['lastname']."%' ";
	assign('speakfirstname',$_POST['firstname']);
	assign('speaklastname',$_POST['lastname']);
	assign('showfilter',true);
}


//Getting speaker List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];
/*if(isset($_GET['search'])){
 $array = array	(
 'name' 	=> $_GET['name'],
 );
}*/

$result_array = $array;
//Getting speaker List
$result_array['limit'] = $get_limit;
$result_array['videoid'] = $video['videoid'];
$result_array['selected'] = 'yes';
$result_array['assign'] = 'linkedspeakers';
//pr($result_array,true);
$linkedspeakers = $speakerquery->get_speaker_and_roles($result_array);
if ($filtercond) $result_array['cond']=$filtercond;
$result_array['selected'] = 'no';
$result_array['assign'] = 'unlinkedspeakers';
$unlinkedspeakers = $speakerquery->get_speaker_and_roles($result_array);


//Collecting Data for Pagination
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $speakerquery->get_speaker_and_roles($mcount);
$total_pages = count_pages($total_rows,RESULTS);
//Pagination
$pages->paginate($total_pages,$page);


//error_reporting(E_ERROR & E_WARNING & E_STRING);
//ini_set('display_errors', True);
template_files('link_speaker.html',UL_SPEAKER_ADMIN_DIR);
?>