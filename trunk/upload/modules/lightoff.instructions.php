<?php
/* 
 ************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.		
 | @ Author : Frank white												
 | @ Module : Light Off													
 | @ Module File : 
 | @ Updated : Nov 26 2008											
 | @ License: Addon With ClipBucket										
 ************************************************************************
*/

define('LIGHT_OFF_MOD',FALSE);
Assign('LIGHT_OFF_MOD',TRUE);
//Instructions For Head Tags
$js_files = array('jquery.js','light_off/lightsoff.js');
$js_code  = array('jQuery(document).ready(function(){lightsoff();});');

Assign('light_off_js_files',$js_files);
Assign('light_off_js_code',$js_code);


//Smarty Code
Assign('light_off_module'
,'<div id="lightsoff" style="margin:4px 0px 5px 0px"><a href="#" ><img src="'.BASEURL.'/images/icons/ico_lightsoff.gif" align="absmiddle" height="30" width="95" alt="Lights off" border="0" /></a></div>');

?>