<?php
require UL_SPEAKER_DIR.'/speaker_class.php';
//require '../includes/admin_config.php';
$userquery->admin_login_check();
// Controle de permission probablement non fonctionnel sur les plugins
//$userquery->login_check('member_moderation');
$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Speaker');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Manage Speakers');
}


//Deleting selected speaker
if (isset($_GET['del_speaker'])) {
	$delspeaker = mysql_clean($_GET['del_speaker']);
	$speakerquery->delete_speaker($delspeaker);
	
	$dfield = $_GET['del_speaker'];
	if (is_numeric($dfield)) {
		delete_custom_field($dfield);
		e("Successfuly deleted custom field with ID [".$dfield."]","m");
	} else {
		e("Unable to delete custom field with ID [".$dfield."]");
	}
}

//Deleting Multiple speakers
if(isset($_POST['delete_selected'])){
	$cnt=count($_POST['check_speaker']);
	if ($cnt>0){
		for($id=0;$id<=$cnt;$id++)
			$speakerquery->delete_speaker($_POST['check_speaker'][$id]);
		$eh->flush();
		e("Selected speakers have been deleted","m");
	}
	else
		e("No speakers have been selected","w");
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
//Getting Video List
$result_array['limit'] = $get_limit;
//pr($result_array,true);
$speakers = get_speakers($result_array);
//pr("ee".$z."zz",true);
Assign('speakers', $speakers);

//Collecting Data for Pagination
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = get_speakers($mcount);
$total_pages = count_pages($total_rows,RESULTS);
$pages->paginate($total_pages,$page);

//Pagination
$pages->paginate($total_pages,$page);



error_reporting(E_ERROR & E_WARNING & E_STRING);
ini_set('display_errors', True);
template_files('manage_speakers.html',UL_SPEAKER_ADMIN_DIR);
?>