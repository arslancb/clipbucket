<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author   : ArslanHassan									
 | @ Software : ClipBucket , © PHPBucket.com					
 *****************************************************************
*/
define('THIS_PAGE','search_result');
require 'includes/config.inc.php';
$pages->page_redir();
						
$page = mysql_clean($_GET['page']);
$type = mysql_clean($_GET['type']) ;
$type = $type ? $type : 'videos';
$search = cbsearch::init_search($type);

$search->key = mysql_clean($_GET['query']);

if(!is_array($_GET['category']))
	$_GET['category'] = mysql_clean($_GET['category']);
	
$search->category = $_GET['category'];
$search->date_margin = mysql_clean($_GET['datemargin']);
$search->sort_by = mysql_clean($_GET['sort']);
$search->limit = create_query_limit($page,$search->results_per_page);
$results = $search->search();

//Collecting Data for Pagination
$total_rows = $search->total_results;
$total_pages = count_pages($total_rows,$search->results_per_page);

//Pagination
$pages->paginate($total_pages,$page);


Assign('results',$results );	
Assign('template_var',$search->template_var);
Assign('display_template',$search->display_template);
if(empty($search->key))
	Assign('search_type_title',$search->search_type[$type]['title']);
else
	Assign('search_type_title',sprintf(lang('searching_keyword_in_obj'),$search->key,$search->search_type[$type]['title']));


//Displaying The Template
template_files('search.html');
display_it();
//pr($db);
?>