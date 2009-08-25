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
	
	if(file_exists(BASEDIR.'/plugins/'.$pluginDataNa['plugin_file'])
	&& !empty($pluginDataNa['plugin_file']))
		{
		include(BASEDIR.'/plugins/'.$pluginDataNa['plugin_file']);
		$plugin_list[] = $pluginDataNa;	
		}
	}
}else{
$pluginQueryA = mysql_query("SELECT * FROM plugins");
$total_plugins = mysql_num_rows($pluginQueryA);
if($total_plugins > 0)
{
while($pluginDataA = mysql_fetch_array($pluginQueryA)){
	if(file_exists(BASEDIR.'/plugins/'.$pluginDataA['plugin_file'])
	&& !empty($pluginDataA['plugin_file']))
		include(BASEDIR.'/plugins/'.$pluginDataA['plugin_file']);
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