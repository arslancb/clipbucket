<?php

 /**
 * Registering a sidebar
 * 
 * @todo Right the documentation
 */
function register_sidebar($param)
{
    global $Cbucket;
    extract($param);

    if($title && $description && $id)
    {
        if(!$before_title)
            $before_title = '<h3>';
        if(!$after_title)
            $after_title  = '</h3>';

        if(!$before_widget)
            $before_widget = '<div>';
        if(!$after_widget)
            $after_widget = '</div>';

        if($id=='default')
            $id = 'default-1';

        $sidebar = array(
            'title'         => $title,
            'description'   => $description,
            'id'            => $id,
            'before_widget' => $before_widget,
            'after_widget'  => $after_widget,
            'before_title'  => $before_title,
            'after_title'   => $after_title,
        );
        
        $Cbucket->sidebars[$id] = $sidebar;
    }
}

/**
 * Display a sider bar
 * 
 * @todo Write documentation
 */
function sidebar($place=NULL,$echo=true)
{
    global $Cbucket;
    if(!$place)
        $place = 'default';
   
    $sidebar = $Cbucket->sidebars[$place];
    if(!$sidebar)
        $sidebar = $Cbucket->sidebars[$place.'-sidebar'];
    
    if($sidebar)
    {
        //Getting list of widgets...
        $widgets = get_sidebar_widgets($sidebar['id']);
        foreach($widgets as $w)
        {
            $widget = get_widget_details($w['id']);
            //for now, we will call our callback function only
            if(function_exists($widget['callback']))
            {
                //Setting up options for widget that can be used
                //$widget[{option}] in php or $widget.{option} in smarty
                //$widget['before_title'] or $widget.before_title
                
                $widget_details = get_widget_details($widget['id'],$sidebar['id']);
                
                //Adding Some more options from sidebar...
                $widget_details['before_title'] = $sidebar['before_title'];
                $widget_details['after_title'] = $sidebar['after_title'];
                $widget_details['before_widget'] = $sidebar['before_widget'];
                $widget_details['after_widget'] = $sidebar['after_widget'];
                
                assign('widget',$widget_details);
                if($widget['params'])
                    return $widget['callback']($widget_details,$widget['params']);
                else
                    return $widget['callback']($widget_details);
            }
        }
    }
}


/**
 * check if there is a siderbar or not
 * @todo Write documentation
 */
function has_sidebar($place)
{
    global $Cbucket;
    $sidebar = $Cbucket->sidebars[$place];
    if($sidebar)
        return $sidebar;
    else
        return false;
}


/**
 * register a widget
 * @todo Write documentation
 */
function register_widget($id,$title=NULL,$callback=NULL,$callback_admin=NULL,$options=NULL,$params=NULL)
{
    global $Cbucket;        
    
    if(is_array($id))
    {
        $title = $id['title'];
        $callback = $id['callback'];
        $callback_admin = $id['callback_admin'];
        $options = $id;
        $params = $id['params'];
        $id = $id['id'];
    }

    if($title && $id)
    {
        if(function_exists($callback))
        {
            $widget = array(
                'title' => $title,
                'id'    => $id,
                'callback'  => $callback,
                'options'   => $options,
                'params'    => $params
            );

            $Cbucket->widgets[$id] = $widget;
        }
    }
}


/**
 * Get list of sidebars..with all widgets in case they have any
 */
function get_sidebars()
{
    global $Cbucket;
    $sidebars = $Cbucket->sidebars;
   
    $sidebars = apply_filters($sidebars, 'get_sidebars');
    
    return $sidebars;
}


/**
 * Get list of all sidebars and widgets options
 */
function get_sidebars_configs()
{
    global $Cbucket;
    //Get Widgets..
    $themeConfigs = config($Cbucket->template.'-options');
    $themeConfigs = json_decode($themeConfigs,true);
    $widgets = $themeConfigs['widgets'];
    
    return $widgets;
}

/**
 * Get list of widgets in a side bar...
 */
        
function get_sidebar_widgets($sidebarId)
{
    $widgets = get_sidebars_configs();
    return $widgets[$sidebarId];
}

/**
 * Function used to get widget details from its ID
 */
function get_widget_details($id,$sidebar = false)
{
    global $Cbucket;
  
    if($Cbucket->widgets[$id] && !$sidebar)
    {
        return $Cbucket->widgets[$id];
    }else
    {
        $sidebarWidgets = get_sidebar_widgets($sidebar);
        $configs = $sidebarWidgets[$id]['configs'];
        $widget = $Cbucket->widgets[$id];
        $widget['configs'] = $configs;
        return $widget;
    }
    
}


/**
 * Execute admin callback function
 */
function widget_callback_admin($widget,$sidebar)
{
    $widget = get_widget_details($widget,$sidebar);
    $adminCallback = $widget['callback_admin'];
    if(!$adminCallback)
        $adminCallback = $widget['options']['callback_admin'];
    
    if($adminCallback)
    {
        if(function_exists($adminCallback))
        {         
            return $adminCallback($widget);
        }
    }
}
?>