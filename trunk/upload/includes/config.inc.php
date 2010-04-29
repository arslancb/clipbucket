<?php
/*
Config.Inc.php
*/
define("FRONT_END",TRUE);
define("BACK_END",FALSE);

include('common.php');

define('TEMPLATEDIR',BASEDIR.'/'.TEMPLATEFOLDER.'/'.TEMPLATE);
define('TEMPLATEURL',BASEURL.'/'.TEMPLATEFOLDER.'/'.TEMPLATE);
define('LAYOUT',TEMPLATEDIR.'/layout');
define('ADMINLAYOUT',BASEDIR.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.TEMPLATE.'/layout');

Assign('baseurl',BASEURL);
Assign('imageurl',TEMPLATEURL.'/images');
Assign('admimageurl',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.TEMPLATE.'/images');
Assign('layout',TEMPLATEURL.'/layout');
Assign('theme',TEMPLATEURL.'/theme');
Assign('admtheme',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.TEMPLATE.'/theme');
Assign('template_dir',TEMPLATEDIR);
Assign('style_dir',LAYOUT);

		



include('plugins.php');

//Assigning JS Files
Assign('jsArray',$Cbucket->JSArray);
//Assigning Module Files
Assign('module_list',$Cbucket->moduleList);


//Checking Website is closed or not
if($row['closed'] == 1 && THIS_PAGE!='ajax' && !$in_bg_cron)
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
?>