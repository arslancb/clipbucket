<?php

define("BACK_END", TRUE);
define("FRONT_END", FALSE);
define("SLOGAN", "Administration Panel");
//Admin Area
$admin_area = TRUE;

/*
  Config.Inc.php
 */
include('common.php');
include('functions_admin.php');

//Including Massuploader Class,
require_once('classes/mass_upload.class.php');
require_once('classes/ads.class.php');
//require_once('classes/sysinfo.class.php');

$cbmass = new mass_upload();
$ads_query = new AdsManager();


$admin_pages = $row['admin_pages'];

if (isset($_POST['update_dp_options'])) {
    if (!is_numeric($_POST['admin_pages']) || $_POST['admin_pages'] < 1) {
        $num = '20';
        $msg = "Please Type Number from 1 to Maximum";
    } else {
        $num = $_POST['admin_pages'];
        $admin_pages = $num;
    }

    $db->update(tbl("config"), array("value"), array($num), " name='admin_pages'");
    $ClipBucket->configs = $Cbucket->configs = $Cbucket->get_configs();
}

define('RESULTS', $admin_pages);
Assign('admin_pages', $admin_pages);

//Do No Edit Below This Line
define('ADMIN_TEMPLATE', 'cbv3');
define('TEMPLATEDIR', BASEDIR . '/' . ADMINDIR . '/' . TEMPLATEFOLDER . '/' . ADMIN_TEMPLATE);

define('SITETEMPLATEDIR', BASEDIR . '/' . TEMPLATEFOLDER . '/' . $row['template_dir']);
define('TEMPLATEURL', BASEURL . '/' . ADMINDIR . '/' . TEMPLATEFOLDER . '/' . ADMIN_TEMPLATE);
define('LAYOUT', TEMPLATEDIR . '/layout');

define('TEMPLATEFOLDER', 'styles');
define('FRONT_TEMPLATEDIR', BASEDIR . '/' . TEMPLATEFOLDER . '/' . $Cbucket->template);
define('FRONT_TEMPLATEURL', BASEURL . '/' . TEMPLATEFOLDER . '/' . $Cbucket->template);

Assign('baseurl', BASEURL);
assign('template_url', TEMPLATEURL);
assign('template_dir', TEMPLATEDIR);
Assign('admindir', ADMINDIR);
Assign('imageurl', TEMPLATEURL . '/images');
Assign('layout', TEMPLATEURL . '/layout');
Assign('layout_url', TEMPLATEURL . '/layout');
assign('layout_dir', TEMPLATEDIR . '/layout');
Assign('theme', TEMPLATEURL . '/theme');
Assign('style_dir', LAYOUT);
Assign('admin_baseurl', ADMIN_BASEURL);


Assign('logged_user', @$_SESSION['username']);
Assign('superadmin', @$_SESSION['superadmin']);

$AdminArea = true;

//Including Plugins
include('plugins.php');

//Including Flv Players
include('flv_player.php');


$Smarty->assign_by_ref('cbmass', $cbmass);

if ($Cbucket->template_details['php_file'])
    include($Cbucket->template_details['php_file']);



register_admin_block(array('title'  => 'Status Overview', 'function' => 'admin_home_overview'));
register_admin_block(array('title'  => 'Summary of Stats','function'=>'admin_home_stats'));
register_admin_block(array('title'  => 'Recent Activity', 'function' => 'admin_home_activity'));
register_admin_block(array('title'  => 'Personal Notes', 'function' => 'admin_home_notes'));

?>