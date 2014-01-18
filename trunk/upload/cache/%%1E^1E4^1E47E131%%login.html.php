<?php /* Smarty version 2.6.18, created on 2014-01-15 15:35:46
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/login.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '/var/www/clipbucket/admin_area/styles/cbv2/layout/login.html', 39, false),)), $this); ?>
<body>

<div class="header_grey_bar">
	<img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="cbicon" /> <?php echo $this->_tpl_vars['title']; ?>
 &#8212; <a href="<?php echo $this->_tpl_vars['baseurl']; ?>
">Back To Main Website</a>
	<div class="clearfix"></div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/msg.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div style="height:400px; background-image:url(<?php echo $this->_tpl_vars['imageurl']; ?>
/bgs/login.png); background-repeat:repeat-x" align="center">
<div id="login_box">
  <form name="form1" method="post" action="">
    <table width="78%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left"><label for="username">Username</label></td>
      </tr>
      <tr>
        <td align="left">
          <input type="text" name="username" id="username" class="textfield">
        </td>
      </tr>
      <tr>
        <td align="left"><label for="password">Password</label></td>
      </tr>
      <tr>
        <td>
          <input type="password" name="password" id="password" class="textfield">
        </td>
      </tr>
      <tr>
        <td align="center"><label>
          <input type="submit" name="login" class="button" value="    Login    ">
        </label></td>
      </tr>
    </table>
  </form>
</div>
</div>
<div align="center" class="login_footer">&copy; ClipBucket Copyright 2007 &#8211; <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
 | Arslan Hassan</div>
</body>
</html>