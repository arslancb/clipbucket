<?php
require_once DOCUMENT_DIR.'/document_class.php';
// Check if user has admin acces
$userquery->admin_login_check();
// Check that doesn't work on plugis
//$userquery->login_check('member_moderation');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', lang('video_addon'));
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', lang('document_manager'));
}


// Run after a post action called 'delete_document'
if (isset($_GET['delete_document'])) {
	$deldocument = mysql_clean($_GET['delete_document']);
	$documentquery->delete_document($deldocument);
}

// Run after a post action called 'delete_selected' (Deleting Multiple documents)
if(isset($_POST['delete_selected'])){
	$cnt=count($_POST['check_document']);
	if ($cnt>0){
		for($id=0;$id<$cnt;$id++) 
			$documentquery->delete_document($_POST['check_document'][$id]);
	}
	else
		e(lang("no_document_selected"),"w");
}

// Run after a post action called 'filter' (used to filter list of external documents)
if(isset($_POST['filter'])){
	$filtercond=" title like '%".$_POST['title']."%'";
	assign('title',$_POST['title']);
	assign('url',$_POST['url']);
	assign('showfilter',true);
	assign('showadd',false);
	assign('showedit',false);
}


// Run after a post action called 'add_document' (used to filter list of external documents)
if(isset($_POST['add_document'])){
	$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
	$array= array('title'=> $_POST['title'], 
			'filename' => mysql_clean($_FILES['filename']['name']),
			'storedfilename' => $hashname,
			'size' => $_FILES["filename"]["size"], 
			'mimetype' => $_FILES["filename"]["type"], 
	);
	if($id=$documentquery->add_document($array))	{
		move_uploaded_file($_FILES['filename']['tmp_name'], DOCUMENT_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		assign('showfilter',false);
		assign('showadd',false);
		assign('showedit',false);
	}
}

// Run after a post action called 'edit_document'
if (isset($_GET['edit_document'])) {
	if (error()){
		$details=$_POST;
		$details['id']=$details['documentid'];
	}
	else {
		$id = $_GET['edit_document'];
		$details = $documentquery->get_document_details($id);
	}

	if ($details){
		assign('document',$details);
	}
	assign('showedit',true);
	assign('showfilter',false);
	assign('showadd',false);
}

// Run after a post action called 'update_document'
if(isset($_POST['update_document'])){
	$array=$documentquery->get_document_details($_POST['documentid']);
	$array['documentid']=$_POST['documentid'];
	$oldfile=$array['storedfilename'];
	$array['title']= $_POST['title'];
	$hashname = RandomString(8)."_".$_FILES['filename']['name']; //randomize name
	if ($_FILES['filename']['size']>0){
		$array['filename'] =  mysql_clean($_FILES['filename']['name']);
		$array['storedfilename'] =  mysql_clean($hashname);
		$array['size'] =  mysql_clean($_FILES['filename']['size']);
		$array['mimetype'] =  mysql_clean($_FILES['filename']['type']);
	}
	if ($documentquery->update_document($array)) {
		if ($_FILES['filename']['size']>0){
			unlink(DOCUMENT_DOWNLOAD_DIR."/".$oldfile);
			move_uploaded_file($_FILES['filename']['tmp_name'], DOCUMENT_DOWNLOAD_DIR."/".$hashname);  //moving file from tmp folder to thumbs folder
		}
		e(lang("update_document"),"m");
		$_POST = '';
		assign('showfilter',false);
		assign('showadd',false);
		assign('showedit',false);
		assign('document',false);
	}
}



//Getting document List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$array=[];

$result_array = $array;
//Getting document List
$result_array['limit'] = $get_limit;
if ($filtercond) $result_array['cond']=$filtercond;
//pr($result_array,true);
$documents = $documentquery->get_documents($result_array);
Assign('documents', $documents);

//Collecting Data for Pagination
$mcount = $array;
$mcount['count_only'] = true;
$total_rows  = $documentquery->get_documents($mcount);
$total_pages = count_pages($total_rows,RESULTS);
//Pagination
$pages->paginate($total_pages,$page);


//Set HTML title
subtitle(lang("documents_manager"));

template_files('manage_documents.html',DOCUMENT_ADMIN_DIR);
?>