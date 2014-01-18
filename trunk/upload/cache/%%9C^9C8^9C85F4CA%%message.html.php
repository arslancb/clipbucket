<?php /* Smarty version 2.6.18, created on 2014-01-10 19:00:19
         compiled from /var/www/clipbucket/styles/cbv2new/layout/message.html */ ?>
<?php $this->assign('msg', $this->_tpl_vars['eh']->message_list); ?>
<?php $this->assign('err', $this->_tpl_vars['eh']->error_list); ?>
<?php $this->assign('war', $this->_tpl_vars['eh']->warning_list); ?>

<?php if ($this->_tpl_vars['err']['0'] != '' || $this->_tpl_vars['err']['1'] != ''): ?>
<div class="cb_error">
<ul>
<?php $_from = $this->_tpl_vars['err']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['show_msg']):
?>
<li> <?php echo $this->_tpl_vars['show_msg']; ?>
 </li>
<?php endforeach; endif; unset($_from); ?>
</ul>
</div>
<?php endif; ?>


<?php if ($this->_tpl_vars['msg']['0'] != ''): ?>
<div class="cb_message">
<ul>
<?php $_from = $this->_tpl_vars['msg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['show_msg']):
?>
<li><?php echo $this->_tpl_vars['show_msg']; ?>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
</div>
<?php endif; ?>


<?php if ($this->_tpl_vars['war']['0'] != ''): ?>
<div class="cb_warning">
<ul>
<img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/messagebox_warning.png" style="float:left" />
<?php $_from = $this->_tpl_vars['war']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['show_msg']):
?>
<li><?php echo $this->_tpl_vars['show_msg']; ?>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
<div class="clearfix"></div>
</div>
<?php endif; ?>