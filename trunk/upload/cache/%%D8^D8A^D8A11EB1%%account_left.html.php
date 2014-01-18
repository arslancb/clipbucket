<?php /* Smarty version 2.6.18, created on 2014-01-17 12:39:49
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/manage/account_left.html */ ?>
<div class="my_account_left">
<ul>
    <?php $_from = $this->_tpl_vars['userquery']->my_account_links(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['link']):
?>
    <li class="li">
    	<span class="heading"><?php echo $this->_tpl_vars['name']; ?>
</span>
        <ul>
        <?php $_from = $this->_tpl_vars['link']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link_name'] => $this->_tpl_vars['alink']):
?>
        	<li><a href="<?php echo $this->_tpl_vars['alink']; ?>
"><?php echo $this->_tpl_vars['link_name']; ?>
</a></li>
        <?php endforeach; endif; unset($_from); ?>
        </ul>
    </li>
    <?php endforeach; endif; unset($_from); ?>
</ul>
</div>