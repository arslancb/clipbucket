<?php
/* 
 **************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');
$pages->page_redir();

//Making Language Default
if(isset($_POST['make_default']))
{
	$id = mysql_clean($_POST['make_default']);
	$lang_obj->make_default($id);
}

//Importing language
if(isset($_POST['add_language']))
{
	$lang_obj->import_lang();
}

//Removig Langiage
if(isset($_GET['delete']))
{
	$id = mysql_clean($_GET['delete']);
	$lang_obj->delete_lang($id);
}

//Updateing Language
if(isset($_POST['update_language']))
{
	$_POST['lang_id'] = $_GET['edit_language'];
	$lang_obj->update_lang($_POST);
}

//Downloading Language
if(isset($_GET['download']))
{
	$lang_obj->export_lang(mysql_clean($_GET['download']));
}

//Downloading Language
if(isset($_GET['action']))
{
	$lang_obj->action_lang($_GET['action'],mysql_clean($_GET['id']));
}

//Create package
if(isset($_GET['create_package']))
{
	if($lang_obj->createPack($_GET['create_package']))
		e("Language pack has been re-created","m");
}

//Create package
if(isset($_GET['recreate_from_pack']))
{
	if($lang_obj->updateFromPack($_GET['recreate_from_pack']))
		e("Language database has been updated","m");
}



//Get List Of Languages
assign('language_list',$lang_obj->get_langs());
Assign('msg',$msg);	



if($lang_obj->lang_exists(mysql_clean($_GET['edit_language'])))
{
	assign('edit_lang','yes');
	assign('lang_details',$lang_obj->lang_exists(mysql_clean($_GET['edit_language'])));
	$edit_id = mysql_clean($_GET['edit_language']);
	$limit = RESULTS;
	
	
	$current_page = $_GET['page'] ;
	$current_page = is_numeric($current_page) && $current_page>0 ? $current_page : 1 ;
	
	$curr_limit = ($current_page-1)*$limit .','.$limit;
	
	if(isset($_GET['search_phrase']))
	{
		$varname = mysql_clean($_GET['varname']);
		$text = mysql_clean($_GET['text']);
		
		if(!empty($varname))
			$varname_query = "varname LIKE '%$varname%'";
		if(!empty($text))
			$text_query = "text LIKE '%$text%'";
		
		if(!empty($text_query) || !empty($varname_query))
		{
			if(!empty($text_query) && !empty($varname_query) )
				$or = ' OR ';
			$extra_param = " AND ( $varname_query $or  $text_query )";
		}
	}
	
	$lang_phrases = $lang_obj->get_phrases($edit_id,'*',$curr_limit,$extra_param);
	$total_phrases = $lang_obj->count_phrases($edit_id,$extra_param);
	
	assign('lang_phrases',$lang_phrases);
	
	$total_pages = $total_phrases/$limit;
	$total_pages = round($total_pages+0.49,0);
	$pages->paginate($total_pages,$current_page);
}


subtitle("Language Settings");
template_files('language_settings.html');
display_it();
?>