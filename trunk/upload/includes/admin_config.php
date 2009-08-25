<?php

	//Admin Area
	$admin_area	=	TRUE;
	
	/*
	Config.Inc.php
	*/
	include('common.php');
	//Setting Number of Records
	
	//Including Massuploader Class,
	require_once('classes/mass_upload.class.php');
	require_once('classes/ads.class.php');
	//require_once('classes/sysinfo.class.php');
	
	$MassUpload 	= new MassUpload();
	$ads_query 		= new AdsManager();
	
	
	$admin_pages = $row['admin_pages'];
	if(isset($_POST['set_number'])){
		if(!is_numeric($_POST['number']) || $_POST['number']<1){
			$num = '20';
		$msg = "Please Type Number from 1 to Maximum";
		}else{
			$num = $_POST['number'];
			$admin_pages = $num;
		}
	if(!mysql_query("UPDATE config SET value='".$num."' WHERE name='admin_pages' "))die(mysql_error());
	}
	
	define('RESULTS', $admin_pages);
	Assign('admin_pages',$admin_pages);
	
 	//Do No Edit Below This Line
 	
	define('TEMPLATEDIR',BASEDIR.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/cbadmin');
	define('SITETEMPLATEDIR',BASEDIR.'/'.TEMPLATEFOLDER.'/'.$row['template_dir']);
	define('TEMPLATEURL',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/cbadmin');
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
	
	
?>