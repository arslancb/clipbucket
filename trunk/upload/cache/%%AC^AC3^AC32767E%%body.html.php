<?php /* Smarty version 2.6.18, created on 2014-01-15 12:18:18
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/body.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'include_template_file', '/var/www/clipbucket/admin_area/styles/cbv2/layout/body.html', 25, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/global_header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
<!-- Including Commong header -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/msg.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div style="<?php if ($this->_tpl_vars['mode'] != 'watermark_settings'): ?>min-height:600px;<?php endif; ?> min-width:1000px">

<?php if ($_COOKIE['admin_menu'] == 'hide'): ?>
	<?php $this->assign('left_menu_class', 'left_menu_0'); ?>
    <?php $this->assign('contentcolumn_class', 'contentcolumn0'); ?>
<?php else: ?>
	<?php $this->assign('left_menu_class', 'left_menu'); ?>
    <?php $this->assign('contentcolumn_class', 'contentcolumn'); ?>
<?php endif; ?>

<div class="clearfix"></div>

<!-- Setting Body File -->
<?php if ($this->_tpl_vars['Cbucket']->show_page): ?>
    <div id="contentwrapper">
        <div id="contentcolumn" class="<?php echo $this->_tpl_vars['contentcolumn_class']; ?>
">
            <div class="innertube" style="padding-right:10px">
            <?php $_from = $this->_tpl_vars['template_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file']):
?>
            	<?php echo include_template_file(array('file' => $this->_tpl_vars['file']), $this);?>

            <?php endforeach; endif; unset($_from); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Setting Body File -->


<!-- Changing Left Menu -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/left_menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div>
<div class="clearfix"></div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body>
</html>