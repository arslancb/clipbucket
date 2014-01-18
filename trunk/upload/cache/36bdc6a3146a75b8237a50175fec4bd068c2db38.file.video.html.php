<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 12:18:25
         compiled from "/var/www/clipbucket/styles/cb_2013/layout/blocks/videos/video.html" */ ?>
<?php /*%%SmartyHeaderCode:186809890452d636414052f9-76645724%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '36bdc6a3146a75b8237a50175fec4bd068c2db38' => 
    array (
      0 => '/var/www/clipbucket/styles/cb_2013/layout/blocks/videos/video.html',
      1 => 1389769170,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '186809890452d636414052f9-76645724',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'video' => 0,
    'userquery' => 0,
    'vide' => 0,
    'imageurl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d63641418988_38645929',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d63641418988_38645929')) {function content_52d63641418988_38645929($_smarty_tpl) {?><div class="cb_item item_video">
    
    <div class="cb_item_container">
        <div class="item_title">
           <a href="<?php echo videoLink($_smarty_tpl->tpl_vars['video']->value);?>
"><?php echo title($_smarty_tpl->tpl_vars['video']->value['title']);?>
</a>
        </div>
        <div class="item_user">
           <a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['video']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['video']->value['username'];?>
</a>
        </div>
        <div class="item_text">
            <?php echo $_smarty_tpl->tpl_vars['vide']->value['description'];?>

        </div>
        
        <span class="item_icon item_video"></span>
        <span class="item_date"><?php echo niceTime($_smarty_tpl->tpl_vars['video']->value['date_added']);?>
</span>
    
    </div>
    
    <div class="cb_item_thumb video_thumb">
        <img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/preview_240.png"/>
    </div>
    
</div>
<?php }} ?>
