<?php
/**
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
define('LAYOUTURL',BASEURL.'/layout');
define('ADMINLAYOUT',BASEDIR.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template.'/layout');
define('IMAGEURL',TEMPLATEURL.'/images');
define('THEMEURL',TEMPLATEURL.'/theme');

Assign('baseurl',BASEURL);
Assign('imageurl',IMAGEURL);
Assign('admimageurl',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template.'/images');
Assign('layout',TEMPLATEURL.'/layout');
Assign('layout_url',TEMPLATEURL.'/layout');
Assign('layouturl',TEMPLATEURL.'/layout');
Assign('theme',THEMEURL);
Assign('theme_url',THEMEURL);
Assign('admtheme',BASEURL.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/'.$Cbucket->template.'/theme');
Assign('template_dir',TEMPLATEDIR);
Assign('style_dir',LAYOUT);
Assign('template_dir',TEMPLATEDIR);
Assign('template_url',TEMPLATEURL);
Assign('layout_dir',LAYOUT);

if ( USE_PHOTO_TAGGING == true ) {
	$Cbucket->addJS( array('jquery_plugs/jquery.cbtagger.js' => 'view_item') );
}
$Cbucket->addJS( array('jquery_plugs/jquery.Jcrop.js' => 'edit_account') );
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
if(!IN_MODULE)
isSectionEnabled(PARENT_PAGE,true);

//setting quicklist
assign('total_quicklist',$cbvid->total_quicklist());

//Adding Template functions
if($Cbucket->template_details['php_file'])
    include($Cbucket->template_details['php_file']);

?>