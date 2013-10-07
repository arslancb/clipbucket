<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan											
 | @ Software : ClipBucket , © PHPBucket.com						
 *******************************************************************
*/

define("THIS_PAGE",'view_page');
define("PARENT_PAGE",'home');
require 'includes/config.inc.php';
$pages->page_redir();

$pid = $_GET['pid'];
$pid = mysql_clean($pid);

$page = $cbpage->get_page($pid);
if($page)
{
	assign('page',$page);
	subtitle($page['page_title']);
}else{
	e(lang("page_doesnt_exist"));
	$Cbucket->show_page = false;
}

//Displaying The Template
template_files('view_page.html');
display_it();
?>