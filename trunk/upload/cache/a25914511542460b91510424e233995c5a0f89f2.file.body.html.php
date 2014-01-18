<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:46:42
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/body.html" */ ?>
<?php /*%%SmartyHeaderCode:73118167552d659025de120-18418939%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a25914511542460b91510424e233995c5a0f89f2' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/body.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '73118167552d659025de120-18418939',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'mode' => 0,
    'Cbucket' => 0,
    'contentcolumn_class' => 0,
    'template_files' => 0,
    'file' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d6590264add6_71027048',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d6590264add6_71027048')) {function content_52d6590264add6_71027048($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/global_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<body>
<!-- Including Commong header -->
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/msg.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<div style="<?php if ($_smarty_tpl->tpl_vars['mode']->value!='watermark_settings') {?>min-height:600px;<?php }?> min-width:1000px">

<?php if ($_COOKIE['admin_menu']=='hide') {?>
	<?php if (isset($_smarty_tpl->tpl_vars['left_menu_class'])) {$_smarty_tpl->tpl_vars['left_menu_class'] = clone $_smarty_tpl->tpl_vars['left_menu_class'];
$_smarty_tpl->tpl_vars['left_menu_class']->value = 'left_menu_0'; $_smarty_tpl->tpl_vars['left_menu_class']->nocache = null; $_smarty_tpl->tpl_vars['left_menu_class']->scope = 0;
} else $_smarty_tpl->tpl_vars['left_menu_class'] = new Smarty_variable('left_menu_0', null, 0);?>
    <?php if (isset($_smarty_tpl->tpl_vars['contentcolumn_class'])) {$_smarty_tpl->tpl_vars['contentcolumn_class'] = clone $_smarty_tpl->tpl_vars['contentcolumn_class'];
$_smarty_tpl->tpl_vars['contentcolumn_class']->value = 'contentcolumn0'; $_smarty_tpl->tpl_vars['contentcolumn_class']->nocache = null; $_smarty_tpl->tpl_vars['contentcolumn_class']->scope = 0;
} else $_smarty_tpl->tpl_vars['contentcolumn_class'] = new Smarty_variable('contentcolumn0', null, 0);?>
<?php } else { ?>
	<?php if (isset($_smarty_tpl->tpl_vars['left_menu_class'])) {$_smarty_tpl->tpl_vars['left_menu_class'] = clone $_smarty_tpl->tpl_vars['left_menu_class'];
$_smarty_tpl->tpl_vars['left_menu_class']->value = 'left_menu'; $_smarty_tpl->tpl_vars['left_menu_class']->nocache = null; $_smarty_tpl->tpl_vars['left_menu_class']->scope = 0;
} else $_smarty_tpl->tpl_vars['left_menu_class'] = new Smarty_variable('left_menu', null, 0);?>
    <?php if (isset($_smarty_tpl->tpl_vars['contentcolumn_class'])) {$_smarty_tpl->tpl_vars['contentcolumn_class'] = clone $_smarty_tpl->tpl_vars['contentcolumn_class'];
$_smarty_tpl->tpl_vars['contentcolumn_class']->value = 'contentcolumn'; $_smarty_tpl->tpl_vars['contentcolumn_class']->nocache = null; $_smarty_tpl->tpl_vars['contentcolumn_class']->scope = 0;
} else $_smarty_tpl->tpl_vars['contentcolumn_class'] = new Smarty_variable('contentcolumn', null, 0);?>
<?php }?>

<div class="clearfix"></div>

<!-- Setting Body File -->
<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->show_page) {?>
    <div id="contentwrapper">
        <div id="contentcolumn" class="<?php echo $_smarty_tpl->tpl_vars['contentcolumn_class']->value;?>
">
            <div class="innertube" style="padding-right:10px">
            <?php  $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['file']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['template_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['file']->key => $_smarty_tpl->tpl_vars['file']->value) {
$_smarty_tpl->tpl_vars['file']->_loop = true;
?>
            	<?php echo include_template_file(array('file'=>$_smarty_tpl->tpl_vars['file']->value),$_smarty_tpl);?>

            <?php } ?>
            </div>
        </div>
    </div>
<?php }?>
<!-- Setting Body File -->


<!-- Changing Left Menu -->
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/left_menu.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


</div>
<div class="clearfix"></div>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html><?php }} ?>
