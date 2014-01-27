<?php
/**
 * View User Details
 * @author:Arslan
 * @Since : Oct 16 09
 */
require'../includes/admin_config.php';

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Tool Box');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Conversion Lab &alpha;');
}


//Getting list of available testing videos
$testVidsDir = ADMINBASEDIR.'/lab_resources/testing_videos';
$vidFiles = glob($testVidsDir.'/*');
$vdoFiles = array();
foreach($vidFiles as $vidFile)
{
	$vdoFiles[] = array('path'=>$vidFile,'name'=>getName($vidFile).'.'.getExt($vidFile));
}

assign('vdoFiles',$vdoFiles);


subtitle("Conversion lab for ClipBucket conversion testing");
template_files("conversion_lab.html");
display_it();
?>