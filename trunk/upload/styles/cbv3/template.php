<?php

/**
 * Template PHP File for template functions...
 */

include("template_functions.php");


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


//Registering our first time ever sidebar
$sidebar = array(
    'title' => lang('Watch Video'),
    'description'   => lang('Displays a side bar on "watch video" page'),
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'id'            => 'watch-video-sidebar'
);

register_sidebar($sidebar);
$sidebar['title'] = 'View photo';
$sidebar['description'] = 'View Photos';
$sidebar['id'] = 'view-photo-sidebar';   
register_sidebar($sidebar);

/**
 * Registering our Widget :)
 */

register_widget(array(
    'id' => 'user-details-box',
    'title' => 'User details box',
    'description' => 'Displays a user box with few details and a
     a message and subscribe button ba bla...',
    'icon' => FRONT_TEMPLATEURL.'/images/widgets/user-box.png',
    'callback' => 'displayUserBox',
    'callback_admin' => 'displayUserBoxAdmin'
));

register_widget(array(
    'id' => 'user-details-box-2',
    'title' => 'User details box 2',
    'description' => 'Displays a user box with few details and a
     a message and subscribe button ba bla...',
    'icon' => FRONT_TEMPLATEURL.'/images/widgets/user-box.png',
    'callback' => 'displayUserBox',
    'callback_admin' => 'displayUserBoxAdmin'
));


?>