<?php

/**
 * All ajax related stuff for widgets
 * @Author Arslan Hassan
 * @package ClipBucket Admin
 */

include("../../includes/admin_config.php");

$mode = post('mode');

switch($mode)
{
    case 'update-sidebar':
    {
        $sideBarId = mysql_clean(post('sidebar_id'));
        $widgets   = post('widgets');
        
        //Create widgets array.
        $themeConfigs = config($Cbucket->template.'-options');
        $themeConfigs = json_decode($themeConfigs,true);
        $themeWidgets = $themeConfigs['widgets'];
        
        
        if($themeWidgets)
        {
            $sideBarWidgets = $themeWidgets[$sideBarId];
        }else
        {
            $themeWidgets = array();
            $sideBarWidgets = array();
        }
        
        
        $newSidebar  = array();
        
        foreach($widgets as $widget)
        {
            //Creating Widgets
            if($widget['id']){
                $theWidget = array(
                    'id' => $widget,
                    'configs' => $sideBarWidgets[$widget]['configs']
                );

                $newSidebar[$widget] = $theWidget;
            }
        }
        
        
        if($sideBarId)
        $themeWidgets[$sideBarId] = $newSidebar;
        
        $themeConfigs['widgets'] = $themeWidgets;

        $themeConfigs = json_encode($themeConfigs);
        
        config($Cbucket->template.'-options', $themeConfigs);
        
        
        if(post('fetch-widget'))
        {
            assign('sidebar',  has_sidebar($sideBarId));
            assign('widget',  get_widget_details(post('fetch-widget'),$sideBarId));
            $template = fetch('/blocks/widget-form.html',LAYOUT);
            echo json_encode(array('status'=>'success','data'=>$template));
        }else
            echo json_encode(array('status'=>'success','data'=>'sidebar-saved'));
        
    }
    break;
    
    case "update-widget":
    {
        $sideBarId = mysql_clean(post('sidebar_id'));
        $widgetId = mysql_clean(post('widget_id'));
        
        if(!$sideBarId || !$widgetId) exit(json_encode(array('error'=>'yes','data'=>'invalid widget')));
        
        $themeConfigs = config($Cbucket->template.'-options');
        $themeConfigs = json_decode($themeConfigs,true);
        $themeWidgets = $themeConfigs['widgets'];
        
        
        $configs = post('configs');
       
        $themeWidgets[$sideBarId][$widgetId]['configs'] = $configs;
        $themeConfigs['widgets'] = $themeWidgets;
        
        
        $themeConfigs = json_encode($themeConfigs);
        
        
        config($Cbucket->template.'-options', $themeConfigs);
        
        echo json_encode(array('status'=>'success','data'=>array('a'=>'b')));
    }
    break;
    
    case 'fetch-widget-form':
    {
        $widgetId = post('widget_id');
        $sideBarId = post('sidebar_id');
        
        assign('widget',get_widget_details($widgetId,$sideBarId));
        assign('sidebar',  has_sidebar($sideBarId));
        
        echo Fetch(LAYOUT.'/blocks/widget-form.html');
    }
}

?>