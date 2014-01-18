<?php /* Smarty version 2.6.18, created on 2014-01-10 19:00:19
         compiled from /var/www/clipbucket/styles/cbv2new/layout/body.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'include_header', '/var/www/clipbucket/styles/cbv2new/layout/body.html', 4, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/body.html', 14, false),array('function', 'include_template_file', '/var/www/clipbucket/styles/cbv2new/layout/body.html', 19, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/global_header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
<?php echo include_header(array('file' => 'admin_bar'), $this);?>


<!-- Including Commong header -->
<div class="container_container">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <div id="container" class="container clearfix" >
        
        <div class="nav_shadow"></div>
        <div id="content" style="">
        
        <?php echo ANCHOR(array('place' => 'global'), $this);?>

        
        <!-- Message -->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/message.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php $_from = $this->_tpl_vars['template_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file']):
?>
				<?php echo include_template_file(array('file' => $this->_tpl_vars['file']), $this);?>
				 		
            <?php endforeach; endif; unset($_from); ?>
        </div> <!--CONTENT END-->
        <div class="clear" style="margin-top:10px"></div>

        <div id="footer">
            <div class="changer">
           
			    <?php if (config ( 'allow_language_change' )): ?>
                <form action="" method="post" name="change_lang">
                    <?php echo $this->_tpl_vars['cbobjects']->display_languages(); ?>
              
                </form>
                <?php endif; ?>       
                <div class="ch_left"></div>        	
                <div class="ch_right"></div>
            </div> <!--CHANGER END-->
            <div class="ch_shadow" style="height:10px"></div>
            <!--FOOTER CLASS END-->
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div> <!--FOOTER ID END-->
    </div>
</div>
</body>
</html>