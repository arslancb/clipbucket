<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define('THIS_PAGE','index');
require 'includes/config.inc.php';

$pages->page_redir();

if(is_installed('editorspick'))
{
	assign('editor_picks',get_ep_videos());
}

//i love coding :)

//Displaying The Template
template_files('index.html');
display_it();
?>