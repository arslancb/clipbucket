<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 12:25:54
         compiled from "/var/www/clipbucket/styles/cb_2013/layout/videos.html" */ ?>
<?php /*%%SmartyHeaderCode:1946702952d6380246af16-57405728%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '310e2aaeb09ea9e80a7daadcae623f0c747ff610' => 
    array (
      0 => '/var/www/clipbucket/styles/cb_2013/layout/videos.html',
      1 => 1389359118,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1946702952d6380246af16-57405728',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'videos' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d6380248d194_28850793',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d6380248d194_28850793')) {function content_52d6380248d194_28850793($_smarty_tpl) {?><div class="container">
    <div class="row">
        <div class="side_bar col-md-2 col-sm-2 col-lg-2">

        </div>
        <div class="video_items">
            <?php  $_smarty_tpl->tpl_vars['video'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['video']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['videos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['video']->key => $_smarty_tpl->tpl_vars['video']->value) {
$_smarty_tpl->tpl_vars['video']->_loop = true;
?>
                <div class="the_item col-md-3 col-lg-3 col-sm-6 col-xs-12">
                    <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/blocks/videos/video.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                </div>
            <?php } ?>
        </div>
    </div>
</div><?php }} ?>
