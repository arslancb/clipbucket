<?php

/**
 * Template PHP File for template functions...
 */

include("template_functions.php");
include("configs.php");

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
    'before_widget' => '<div class="cb-widget">',
    'after_widget'  => '</div>',
    'id'            => 'watch-video-sidebar'
);
register_sidebar($sidebar);

/**
 * Registering our Widget :)
 */

register_widget(array(
    'id' => 'user-details-box',
    'title' => 'User details box',
    'description' => 'Displays a user box on watch video page with brief details.',
    'icon' => FRONT_TEMPLATEURL.'/images/widgets/user-box.png',
    'callback' => 'displayUserBox',
    'callback_admin' => 'displayUserBoxAdmin'
));

register_widget(array(
    'id' => 'related-videos',
    'title' => 'Related videos',
    'description' => 'List related videos on watch video page',
    'icon' => FRONT_TEMPLATEURL.'/images/widgets/related-videos.png',
    'callback' => 'displayRelatedVideos',
));




//regsitering cb custom functions
cb_register_function('cbv3_show_rating','show_rating');


?>