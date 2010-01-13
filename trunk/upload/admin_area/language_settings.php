<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

//Making Language Default
if(isset($_POST['make_default']))
{
	$id = mysql_clean($_POST['make_default']);
	$lang_obj->make_default($id);
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
	
	$curr_limit = ($current_page-1)*RESULTS .','.RESULTS;
	
	if(isset($_POST['search_phrase']))
	{
		$varname = mysql_clean($_POST['varname']);
		$text = mysql_clean($_POST['text']);
		
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
	
	$total_pages = $total_phrases/RESULTS;
	$total_pages = round($total_pages+0.49,0);
	$pages->paginate($total_pages,$current_page,'language_settings.php?edit_language='.$edit_id);
}


/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('language_settings.html');
Template('footer.html');*/
subtitle("Language Settings");
template_files('language_settings.html');
display_it();
?>