<?php /* Smarty version 2.6.18, created on 2014-01-15 12:18:18
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/msg.html */ ?>
<?php $this->assign('msg', $this->_tpl_vars['eh']->message_list); ?>
<?php $this->assign('err', $this->_tpl_vars['eh']->error_list); ?>
<?php $this->assign('war', $this->_tpl_vars['eh']->warning_list); ?>

<?php if ($this->_tpl_vars['err']['0'] != '' || $this->_tpl_vars['err']['1'] != ''): ?>

<div class="msg error">
    <ul>
    <?php $_from = $this->_tpl_vars['err']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['show_msg']):
?>
   		<li><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/cross.png" /><?php echo $this->_tpl_vars['show_msg']; ?>
</li>
    <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>

<?php endif; ?>

<?php if ($this->_tpl_vars['msg']['0'] != ''): ?>
<div class="msg blue">
	<ul>
        <?php $_from = $this->_tpl_vars['msg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['show_msg']):
?>
        	<li><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/ok.png" /><?php echo $this->_tpl_vars['show_msg']; ?>
</li>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['war']['0'] != ''): ?>
<div class="msg blue">
	<ul>
        <?php $_from = $this->_tpl_vars['war']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['show_msg']):
?>
        	<li><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/error.png" /><?php echo $this->_tpl_vars['show_msg']; ?>
</li>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>
<?php endif; ?>