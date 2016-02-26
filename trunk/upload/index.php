<?php

/**
* File: Index
* Description: FIle used for dispaying home page
* @author: Arslan Hassan
* @since: 2007
* @website: clip-bucket.com
* Copyright (c) 2007-2016 Clip-Bucket.com. All rights reserved
*/

define('THIS_PAGE','index');
require 'includes/config.inc.php';
$pages->page_redir();

if(is_installed('editorspick'))
{
	assign('editor_picks',get_ep_videos());
}
//Displaying The Template
template_files('index.html');
display_it();

?>
