<?php
/*
Simple Plugin System
@ Author :  Arslan
*/

//Getting Plugin Config Details
$plug_row = $myquery->Get_Plugin_Details();

if(!$AdminArea){

$pluginQueryNA = mysql_query("SELECT * FROM plugins WHERE plugin_active ='yes'");
$total_plugins = mysql_num_rows($pluginQueryNA);
while($pluginDataNa = mysql_fetch_array($pluginQueryNA)){
	
	if($pluginDataNa['plugin_folder'])
	$folder = $pluginDataNa['plugin_folder'].'/';
	else
	$folder = '';
	if(file_exists(BASEDIR.'/plugins/'.$folder.$pluginDataNa['plugin_file'])
	&& !empty($pluginDataNa['plugin_file']))
		{
		include(BASEDIR.'/plugins/'.$folder.$pluginDataNa['plugin_file']);
		$plugin_list[] = $pluginDataNa;	
		}
	}
}else{
$pluginQueryA = mysql_query("SELECT * FROM plugins");
$total_plugins = mysql_num_rows($pluginQueryA);
if($total_plugins > 0)
{
while($pluginDataA = mysql_fetch_array($pluginQueryA)){
	
	if($pluginDataA['plugin_folder'])
	$folder = $pluginDataA['plugin_folder'].'/';
	else
	$folder = '';
	
	if(file_exists(BASEDIR.'/plugins/'.$folder.$pluginDataA['plugin_file'])
	&& !empty($pluginDataA['plugin_file']))
		include(BASEDIR.'/plugins/'.$folder.$pluginDataA['plugin_file']);
	//$plugin_list[] = $pluginDataA;
    $plugin_list[] = $pluginDataA;
	}
    Assign('plugin_list',$plugin_list);
}    
}

$cbplugin = new CBPlugin();
$cbplugin->getPlugins();

Assign('total_plugins',$total_plugins);
?>