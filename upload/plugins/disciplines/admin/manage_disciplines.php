<?php
/*
 * @since : 2016
 * @author : YB
 */

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Videos');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', lang('manage_disciplines'));
}

//error messages
$e_ok = lang("discipline_added");
$e_mod = lang("discipline_modified");
$e_name = lang("discipline_name_is_required");
$e_copy = lang("unable_to_copy_file");
$e_size = lang("file_is_too_big");
$e_upl = lang("upload_failed");

//upload path verification
$uploaddir = BASEDIR."/files/thumbs/disciplines";
if (!is_dir($uploaddir)) {
	die($uploaddir.lang("not_a_valid_floder"));
}

//assign thumb path var to template
assign("thumbdir",BASEURL."/files/thumbs/disciplines");
//
require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('admin_access');
$pages->page_redir();

//escaping $_POST
function mysql_escape_mimic($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 
    if(!empty($inp) && is_string($inp))  
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    return $inp; 
} 

//detect edit disciplines
if(isset($_GET['discipline'])) {
	if(!empty($_GET['discipline'])){
		$id = $_GET['discipline'];
		$disc = $db->_select("SELECT * FROM ".tbl("disciplines")." WHERE id = $id");
		//assign var to open dropdown
		assign('edit_discipline','on');
		//assign var to populate form
		assign('mod_discipline',$disc[0]);
	}
} else {
	assign('edit_discipline','off');
}

//Set default discipline
if(isset($_GET['default'])) {
	if(!empty($_GET['default'])){
		$id = $_GET['default'];
		$db->Execute("UPDATE ".tbl('disciplines')." SET is_default = NULL");
		$db->Execute("UPDATE ".tbl('disciplines')." SET is_default = 1 WHERE id = $id");
		// Also change the default vaule of the discipline field in the video table so that new video will still be of the default ddiscipline
		$db->Execute("ALTER TABLE ".tbl('video')." MODIFY COLUMN discipline VARCHAR(255) NOT NULL DEFAULT '".$id."'");
	}
}

//Change the "in menu" status of that discipline
if(isset($_GET['inmenu'])) {
	$id = $_GET['id'];
	$in = $_GET['inmenu'];
	//echo "UPDATE ".tbl('disciplines')." SET in_menu = $in WHERE id = $id";
	$db->Execute("UPDATE ".tbl('disciplines')." SET in_menu = $in WHERE id = $id");
}

//add discipline
if(isset($_POST['add_disc'])){
	//file upload
	$file_name = $_FILES['disc_thumb']['name'];     	//The file name like it is on the user's disk (ie: my_icon.png)
	$file_type = $_FILES['disc_thumb']['type'];     	//The file mime type (ie: image/png)
	$file_size = $_FILES['disc_thumb']['size'];     	//The file size in bytes.
	$file_tmpname = $_FILES['disc_thumb']['tmp_name']; //The address in the temporary folder where the file was uploader
	$file_error = $_FILES['disc_thumb']['error'];    	//The error code (used to know if the file was correctly uploaded)
	$max_size = $_POST['MAX_FILE_SIZE'];
	$replace_file = $_POST['replace_thumb'];
	$existing_file = $_POST['existing_thumb'];
	$nom = md5(uniqid(rand(), true)); //randomize name
	//vars posted
	$name = mysql_escape_mimic($_POST['disc_name']);
	$desc = mysql_escape_mimic($_POST['disc_description']);
	$in_menu = 0;
	if(!empty($_POST['is_menu'])) $in_menu = 1; 
	$color = $_POST['disc_color'];
	if ($replace_file == "replace") {  //if checkbox "replace thumb" is checked
		if ($file_error == 0) {  //file > 0kb
			if ($file_size < $max_size) { //file < form max size
				if(move_uploaded_file($file_tmpname, $uploaddir."/".$nom.$file_name)){  //moving file from tmp folder to thumbs folder
					if(!empty($_POST['disc_name'])){
						global $db;
						$result=$db->insert(tbl('disciplines'),array("name","description","in_menu","color","thumb","thumb_url"),array($name,$desc,$in_menu,$color,1,$nom.$file_name));
						if ($result) $msg = e($e_ok." (replace)",'m');   //success
					}  
					else $msg = e($e_name,'e'); 						//error
				} 
				else $msg = e($e_copy,'e');
			} 
			else $msg = e($e_size,'e');
		} 
		else $msg = e($e_upl,'e');
	} 
	else {
		if(!empty($_POST['disc_name'])){
			global $db;
			$result=$db->insert(tbl('disciplines'),array("name","description","in_menu","color"),array($name,$desc,$in_menu,$color));
			if ($result) 
				$msg = e($e_ok." (no replace)",'m'); //success
		}  
		else 
			$msg = e($e_name,'e'); //error
	}
}

// Update discipline
if(isset($_POST['update_disc'])) {
	$file_name = $_FILES['disc_thumb']['name'];     	//The file name like it is on the user's disk (ie: my_icon.png)
	$file_type = $_FILES['disc_thumb']['type'];     	//The file mime type (ie: image/png)
	$file_size = $_FILES['disc_thumb']['size'];     	//The file size in bytes.
	$file_tmpname = $_FILES['disc_thumb']['tmp_name']; //The address in the temporary folder where the file was uploader
	$file_error = $_FILES['disc_thumb']['error'];    	//The error code (used to know if the file was correctly uploaded)
	$max_size = $_POST['MAX_FILE_SIZE'];
	$replace_file = $_POST['replace_thumb'];
	$existing_file = $_POST['existing_thumb'];
	$nom = md5(uniqid(rand(), true));
	$name = mysql_escape_mimic($_POST['disc_name']);
	$desc = mysql_escape_mimic($_POST['disc_description']);
	$in_menu = 0;
	if(!empty($_POST['is_menu'])) $in_menu = 1; 
	$color = $_POST['disc_color'];
	if ($replace_file == "replace") {
		if($existing_file != "default.png"){
			unlink($uploaddir."/".$existing_file);
		}
		if ($file_error == 0) {
			if ($file_size < $max_size) {
				if(move_uploaded_file($file_tmpname, $uploaddir."/".$nom.$file_name)){
					if(!empty($_POST['disc_name']) && !empty($_POST['disc_id'])){
						global $db;
						$db->Execute("UPDATE ".tbl('disciplines')." SET name = '".$name."', description = '".$desc."', color = '".$color."', in_menu = '".$in_menu."', thumb = 1, thumb_url = '".$nom.$file_name."' WHERE id = ".$_POST['disc_id']." ");			
						//success
						assign('edit_discipline','off');
						$msg = e($e_mod,'m');
					}  
					else 
						$msg = e($e_name,'e');  //error
				} 
				else 
					$msg = e($e_copy,'e');
			} 
			else 
				$msg = e($e_size,'e');
		} 
		else 
			$msg = e($e_upl,'e');
	} 
	else {
		if(!empty($_POST['disc_name']) && !empty($_POST['disc_id'])){
			global $db;
			$db->Execute("UPDATE ".tbl('disciplines')." SET name = '".$name."', description = '".$desc."', color = '".$color."', in_menu = '".$in_menu."' WHERE id = ".$_POST['disc_id']." ");			
			//success
			assign('edit_discipline','off');
			$msg = e($e_mod,'m');
		}  
		else 
			$msg = e($e_name,'e');
	}
}

global $db;
$disc = $db->_select("SELECT * FROM ".tbl("disciplines")." ORDER BY discipline_order ASC");
$total_disc = count($disc);

//update disciplines order for the main menu display
if(isset($_POST['update_order'])) {
	foreach($disc as $tmp) {
		if(!empty($tmp['id'])) {
			$index_new = $_POST['disc_order_'.$tmp['id']];
			global $db;
			$db->Execute("UPDATE ".tbl('disciplines')." SET discipline_order = ".$index_new." WHERE id = ".$tmp['id']." ");
		}
	}
	unset($tmp);
	assign('edit_discipline','off');
	global $db;
	$disc = $db->_select("SELECT * FROM ".tbl("disciplines")." ORDER BY discipline_order ASC");
}

//delete discipline
if(isset($_POST['delete_disc'])) {
	global $disciplinequery;
	if(!empty($_POST['check_disc'])){
		$msg="";
		foreach($_POST['check_disc'] as $did){
			$disc = $disciplinequery->get_discipline($did);
			$error=false;
			if ($disc[0]["is_default"]==1){
				$error=true;
				e(lang("cant_delete_default_discipline"),'w');
			}
			$nb=$disciplinequery->count_video_of_discipline($did);
			if ($nb>0) {
				$error=true;
				$vids = $disciplinequery->get_video_of_discipline($did);
				$videolist="";
				foreach ($vids as $vid){
					$videolist=$videolist.$vid["videoid"].", ";
				}
				e($nb." ".lang("videos_use_the_discipline")." '".$disc[0]["name"]."' ".
						lang("please_modify_the_following_videos_before_deleting")." : ".$videolist,'w');
			}
			if (!$error)
				$db->delete(tbl('disciplines'),array('id'),array($did));
		}
		$disc = $db->_select("SELECT * FROM ".tbl("disciplines")." ORDER BY discipline_order ASC");
	}
}

//Set variables to use in the template
assign('disciplines',$disc);
assign('total',$total_disc);
assign('msg',@$msg);
//Set HTML title
subtitle(lang("manage_disciplines"));

template_files(PLUG_DIR.'/disciplines/admin/manage_disciplines.html');
$Cbucket->add_admin_header(PLUG_DIR.'/disciplines/header.html');
// ???
//display_it();
?>