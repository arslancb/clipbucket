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

		

//Checking Website is closed or not
if($row['closed'] == 1){
	
	$msg = $row['closed_msg'];
	
	if($_SESSION['admin'] !='')
	$msg = 'Attention: Site in Offline Mode';
	
	Assign('msg',$msg);
	Assign('subtitle','Site Closed');
	if($_SESSION['admin'] ==''){
		Template('header.html');
		Template('message.html');
		Template('footer.html');
		exit();
	}
}

include('plugins.php');

//Assigning JS Files
Assign('jsArray',$Cbucket->JSArray);
//Assigning Module Files
Assign('module_list',$Cbucket->moduleList);

?>