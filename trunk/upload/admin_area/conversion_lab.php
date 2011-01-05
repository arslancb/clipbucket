<?php
/**
 * View User Details
 * @author:Arslan
 * @Since : Oct 16 09
 */
require'../includes/admin_config.php';


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