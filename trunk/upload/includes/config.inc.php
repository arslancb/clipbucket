<?php
/*
Config.Inc.php
*/
define("FRONT_END",TRUE);
define("BACK_END",FALSE);

if(!defined('PARENT_PAGE'))
	define("PARENT_PAGE","home");
	
include('common.php');

include('plugins.php');		

define('TEMPLATEDIR',BASEDIR.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template);
define('TEMPLATEURL',BASEURL.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template);
define('LAYOUT',TEMPLATEDIR.'/layout');
define('ADMINLAYOUT',BASEDIR.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template.'/layout');

Assign('baseurl',BASEURL);
Assign('imageurl',TEMPLATEURL.'/images');
Assign('admimageurl',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template.'/images');
Assign('layout',TEMPLATEURL.'/layout');
Assign('theme',TEMPLATEURL.'/theme');
Assign('admtheme',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template.'/theme');
Assign('template_dir',TEMPLATEDIR);
Assign('style_dir',LAYOUT);




//Assigning JS Files
Assign('jsArray',$Cbucket->JSArray);
//Assigning Module Files
Assign('module_list',$Cbucket->moduleList);


//Checking Website is closed or not
if(config('closed') && THIS_PAGE!='ajax' && !$in_bg_cron && THIS_PAGE!='cb_install')
{
	
	if(!has_access("admin_access",TRUE))
	{	e($row['closed_msg'],"w");
		template("global_header.html");
		template("message.html");
		exit();
	}else{
		e(lang("ATTENTION: THIS WEBSITE IS IN OFFLINE MODE"),"w");
	}
}

//Configuring Uploader
uploaderDetails();
isSectionEnabled(PARENT_PAGE,true);

//setting quicklist
assign('total_quicklist',$cbvid->total_quicklist());

?>