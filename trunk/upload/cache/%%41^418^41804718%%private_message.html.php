<?php /* Smarty version 2.6.18, created on 2014-01-17 09:48:33
         compiled from /var/www/clipbucket/styles/cbv2new/layout/private_message.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/private_message.html', 8, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/private_message.html', 35, false),)), $this); ?>
<div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div class="account_box">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_head.html", 'smarty_include_vars' => array('user' => $this->_tpl_vars['user'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  

 <?php if ($this->_tpl_vars['mode'] == 'inbox'): ?>
 <h2><?php echo smarty_lang(array('code' => 'private_messages'), $this);?>
 &raquo; <?php echo smarty_lang(array('code' => 'inbox'), $this);?>
</h2>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/pm/messages.html", 'smarty_include_vars' => array('show_from' => 'true')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php endif; ?>
 
 <?php if ($this->_tpl_vars['mode'] == 'notification'): ?>
 <h2><?php echo smarty_lang(array('code' => 'private_messages'), $this);?>
 &raquo; <?php echo smarty_lang(array('code' => 'notifications'), $this);?>
</h2>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/pm/messages.html", 'smarty_include_vars' => array('show_from' => 'true')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php endif; ?>
 
 
 
 <?php if ($this->_tpl_vars['mode'] == 'sent'): ?>
 <h2><?php echo smarty_lang(array('code' => 'private_messages'), $this);?>
 &raquo; <?php echo smarty_lang(array('code' => 'sent'), $this);?>
</h2>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/pm/messages.html", 'smarty_include_vars' => array('show_to' => 'true')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php endif; ?>
 
 <?php if ($this->_tpl_vars['mode'] == 'new_msg'): ?>
 <h2><?php echo smarty_lang(array('code' => 'private_messages'), $this);?>
 &raquo; <?php echo smarty_lang(array('code' => 'title_crt_new_msg'), $this);?>
</h2>
 <?php $this->assign('form_fields', $this->_tpl_vars['cbpm']->load_compose_form()); ?>
 <div class="account_form">
 <form id="pm_msg" name="pm_msg" method="post" action="">
 
    <fieldset class="fieldset">
      <legend><?php echo smarty_lang(array('code' => 'new_private_msg'), $this);?>
</legend>
      <?php $_from = $this->_tpl_vars['form_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
      <div class="account_field_block">
        <div class="account_field_label" align="right"><strong><label for="<?php echo $this->_tpl_vars['field']['id']; ?>
"><?php echo $this->_tpl_vars['field']['title']; ?>
</label></strong></div>
        <div class="account_field"><?php echo $this->_tpl_vars['field']['hint_1']; ?>
<?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_before']), $this);?>
<?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['field']); ?>

          <br>
          <?php echo $this->_tpl_vars['field']['hint_2']; ?>
</div>
        <div class="clearfix"></div>
        </div>
      
      <?php endforeach; endif; unset($_from); ?>
      <div align="right"><button name="send_message" id="send_message" value="submit" class="cb_button"><?php echo smarty_lang(array('code' => 'com_send_message'), $this);?>
</button></div>
    </fieldset>
  </form>
 </div>
 <?php endif; ?>
 
 </div>

</div>