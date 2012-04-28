<?php

/**
 * Template PHP File for template functions...
 */

/**
 * add_thumb_size(336x44)
 */

if(!config($Cbucket->template.'-theme-options'))
{
    $options = array(
        
    );
    
    //Add Theme options
    $options = array(
        'options'=>$options,
        'widgets'=> array()
    );
}

//Loading configs..
$Cbucket->theme_configs = theme_configs();




?>