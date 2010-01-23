<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');


$vid_dir = get_directory_size(VIDEOS_DIR);
$thumb_dir = get_directory_size(THUMBS_DIR);
$orig_dir = get_directory_size(ORIGINAL_DIR);

$user_thumbs = get_directory_size(USER_THUMBS_DIR);
$user_bg = get_directory_size(USER_BG_DIR);

$grp_thumbs = get_directory_size(GP_THUMB_DIR);
$cat_thumbs = get_directory_size(CAT_THUMB_DIR);


assign('vid_dir',$vid_dir);
assign('thumb_dir',$thumb_dir);
assign('orig_dir',$orig_dir);

assign('user_thumbs',$user_thumbs);
assign('user_bg',$user_bg);

assign('grp_thumbs',$grp_thumbs);
assign('cat_thumbs',$cat_thumbs);

assign('db_size',formatfilesize(get_db_size()));

template_files('reports.html');
display_it();
?>