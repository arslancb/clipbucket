<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 12:18:25
         compiled from "/var/www/clipbucket/styles/cb_2013/layout/index.html" */ ?>
<?php /*%%SmartyHeaderCode:48413440152d636413fbba7-13354528%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5d00e49e8f02cfa3c0d333a0cd4d6db74aaa8817' => 
    array (
      0 => '/var/www/clipbucket/styles/cb_2013/layout/index.html',
      1 => 1389359118,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48413440152d636413fbba7-13354528',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d63641403603_47504191',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d63641403603_47504191')) {function content_52d63641403603_47504191($_smarty_tpl) {?><!-- Including a video -->

<div class="container">
    
    <div class="row">
        <div class="video_items">
        <?php $_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['foo']->step = 1;$_smarty_tpl->tpl_vars['foo']->total = (int) ceil(($_smarty_tpl->tpl_vars['foo']->step > 0 ? 9+1 - (1) : 1-(9)+1)/abs($_smarty_tpl->tpl_vars['foo']->step));
if ($_smarty_tpl->tpl_vars['foo']->total > 0) {
for ($_smarty_tpl->tpl_vars['foo']->value = 1, $_smarty_tpl->tpl_vars['foo']->iteration = 1;$_smarty_tpl->tpl_vars['foo']->iteration <= $_smarty_tpl->tpl_vars['foo']->total;$_smarty_tpl->tpl_vars['foo']->value += $_smarty_tpl->tpl_vars['foo']->step, $_smarty_tpl->tpl_vars['foo']->iteration++) {
$_smarty_tpl->tpl_vars['foo']->first = $_smarty_tpl->tpl_vars['foo']->iteration == 1;$_smarty_tpl->tpl_vars['foo']->last = $_smarty_tpl->tpl_vars['foo']->iteration == $_smarty_tpl->tpl_vars['foo']->total;?>
            <div class="the_item col-md-3 col-lg-3 col-sm-6 col-xs-12">
            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/blocks/videos/video.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
        <?php }} ?>
        </div>
    </div>
    
</div>

<div class="clearfix"></div><?php }} ?>
