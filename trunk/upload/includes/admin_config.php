<?php

	define("BACK_END",TRUE);
	define("FRONT_END",FALSE);
	define("SLOGAN","Administration Panel");
	//Admin Area
	$admin_area	=	TRUE;
	
	/*
	Config.Inc.php
	*/
	include('common.php');
		
	//Including Massuploader Class,
	require_once('classes/mass_upload.class.php');
	require_once('classes/ads.class.php');
	//require_once('classes/sysinfo.class.php');
	
	$cbmass 	= new mass_upload();
	$ads_query 		= new AdsManager();
	
	
	$admin_pages = $row['admin_pages'];
	
	if(isset($_POST['update_dp_options']))
	{
		if(!is_numeric($_POST['admin_pages']) || $_POST['admin_pages']<1)
		{
			$num = '20';
			$msg = "Please Type Number from 1 to Maximum";
		}else{
			$num = $_POST['admin_pages'];
			$admin_pages = $num;
		}
		
		$db->update(tbl("config"),array("value"),array($num)," name='admin_pages'");
		$ClipBucket->configs = $Cbucket->configs = $Cbucket->get_configs();
	}
	
	define('RESULTS', $admin_pages);
	Assign('admin_pages',$admin_pages);
	
 	//Do No Edit Below This Line
 	
	define('TEMPLATEDIR',BASEDIR.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/cbv2');
	define('SITETEMPLATEDIR',BASEDIR.'/'.TEMPLATEFOLDER.'/'.$row['template_dir']);
	define('TEMPLATEURL',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/cbv2');
	define('LAYOUT',TEMPLATEDIR.'/layout');
	
	Assign('baseurl',BASEURL);
	Assign('admindir',ADMINDIR);
	Assign('imageurl',TEMPLATEURL.'/images');
	Assign('layout',TEMPLATEURL.'/layout');
	Assign('theme',TEMPLATEURL.'/theme');
	Assign('style_dir',LAYOUT);
	
	
	Assign('logged_user',@$_SESSION['username']);
	Assign('superadmin',@$_SESSION['superadmin']);
	
	$AdminArea = true;
	
	//Including Plugins
	include('plugins.php');
	
	//Including Flv Players
	include('flv_player.php');
	
	
$Smarty->assign_by_ref('cbmass',$cbmass)	
	
?>