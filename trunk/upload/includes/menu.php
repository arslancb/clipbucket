<?php

/**
 * @Author  Arslan Hassan
 * @since v3.0
 * @package ClipBucket
 * 
 * Creates Admin and Front Menu
 */

//Adding Main Menus in Admin Panel;
$menus = array(
    'settings' => array(
        'title' => lang('Settings'),
        'icon'  => 'icon-settings icon-v3',
        'id'    => 'settings',
        'access' => 'admin_access',
    ),
    'members' => array(
        'title' => lang('Members Manager'),
        'id'    => 'members-manager',
        'icon'  => 'icon-user',
        'access' => 'member_moderation',
    ),
    'videos' => array(
        'title' => lang('Videos Manager'),
        'id'    => 'videos-manager',
        'icon' => 'icon-film',
        'access' => 'video_moderation',
    ),
    'groups' => array(
        'title' => lang('Groups Manager'),
        'id'    => 'groups-manager',
        'icon'  => 'icon-groups icon-v3',
        'access' => 'group_moderation'
    ),
    'ads'   => array(
        'title' => lang('Ads Manager'),
        'id' => 'ads-manager',
        'icon' => 'icon-coins icon-v3',
        'access' => 'ad_manager_access',
    ),
    'templates' => array(
        'title' => lang('Template Manager'),
        'id' => 'template-manager',
        'icon' => 'icon-adjustment icon-v3',
        'access' => 'manage_template_access'
    ),
    'plugins' => array(
        'title' => lang('Plugins Manager'),
        'id' => 'plugins-manager',
        'icon' => 'icon-plugins icon-v3',
        'access' => 'plugins_moderation'
    ),
    'tools' => array(
        'title' => lang('Tool Box'),
        'id' => 'tool-box',
        'icon' => 'icon-wrench',
        'access' => 'tool_box',
    ),
    'misc' => array(
        'title' => lang('Miscellaneous'),
        'id' => 'miscellaneous',
        'icon' => 'icon-cog',
        'access' => 'admin_access'
    )
    
);

add_admin_menus($menus);

$submenus = array(
    'settings' => array(
        array('title' => lang('Reports'),'link' => 'reports.php'),
        array('title' => lang('Settings'),'link' => 'main.php'),
        array('title' => lang('Email Settings'),'link' => 'email_settings.php'),
        array('title' => lang('Language Settings'), 'link' => 'language_settings.php'),
        
    ),
    'members-manager' => array(
        array('title' => lang('Manage Members'),'link'  => 'members.php'),
        array('title' => lang('Add Member'),'link' =>'add_member.php'),
        array('title' => lang('Manage categories'),'link' => 'user_category.php'),
        array('title' => lang('User Levels'),'link' =>'user_levels.php'),
        array('title' => lang('Search Members'),'link' =>'members.php?view=search'),
        array('title' => lang('Inactive Only'),'link' =>'members.php?search=yes&status=ToActivate'),
        array('title' => lang('Active Only'),'link' =>'members.php?search=yes&status=Ok'),
        array('title' => lang('Reported Users'),'link' =>'flagged_users.php'),
        array('title' => lang('Mass Email'),'link' =>'mass_email.php'),
     ),
    'videos-manager' => array(
        array('title' => lang('Videos Manager'),'link' =>'video_manager.php'),
        array('title' => lang('Manage Categories'),'link' =>'category.php'),
        array('title' => lang('List Flagged Videos'),'link' =>'flagged_videos.php'),
        array('title' => lang('Upload Videos'),'link' =>'mass_uploader.php'),
        array('title' => lang('List Inactive Videos'),'link' => 'video_manager.php?search=search&active=no'),
    ),
    'groups-manager' =>array(
        array('title' => lang('Add Group'),'link' =>'add_group.php'),
        array('title' => lang('Manage Groups'),'link' =>'groups_manager.php'),
        array('title' => lang('Manage Categories'),'link' =>'group_category.php?view=show_category'),
        array('title' => lang('View Inactive Groups'),'link'  => 'groups_manager.php?active=no&search=yes'),
        array('title' => lang('View Reported Groups'),'link'  => 'flagged_groups.php'),
     ),
    'ads-manager' => array(
        array('title' => lang('Manage Advertisments'),'link' =>'ads_manager.php'),
        array('title' => lang('Manage Placements'),'link' =>'ads_add_placements.php'),
        array('title' => lang('icon'),'link'   => 'icon-coins icon-v3')
     ),
    'template-manager' => array(
        array('title' => lang('Templates Manager'),'link'=>'templates.php'),
        array('title' => lang('Templates Editor'),'link'=>'template_editor.php'),
        array('title' => lang('Players Manager'),'link' => 'manage_players.php'),
        array('title' => lang('Player Settings'),'link' => 'manage_players.php?mode=show_settings'),
        array('title' => lang('icon'),'link'=>'icon-adjustment icon-v3') 
    ),
    'plugins-manager' => array(
       array('title' => lang('Plugin Manager'),'link'=>'plugin_manager.php'),
    ),
    'tool-box' => array(				 
        array('title' => lang('PHP Info'),'link'=> 'phpinfo.php'),
        array('title' => lang('View online users'),'link'=> 'online_users.php'),
        array('title' => lang('Server Modules Info'),'link'=> 'cb_mod_check.php'),
        array('title' => lang('Conversion Queue Manager'),'link'=> 'cb_conversion_queue.php'),
        array('title' => lang('ReIndexer'),'link'	=> 'reindex_cb.php'),
        array('title' => lang('Conversion Lab &alpha;'),'link'=> 'conversion_lab.php'),
        array('title' => lang('Repair video duration'),'link'=> 'repair_vid_duration.php'),
        array('title' => lang('Maintenance'),'link'=>'maintenance.php','access'=>'web_config_access')
        
    )
    
);

add_admin_sub_menus($submenus);

            
?>
