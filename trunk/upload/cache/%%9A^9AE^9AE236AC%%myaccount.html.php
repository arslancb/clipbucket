<?php /* Smarty version 2.6.18, created on 2014-01-17 12:39:49
         compiled from /var/www/clipbucket/styles/cbv2new/layout/myaccount.html */ ?>
<div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 
 <div class="account_box clearfix">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_head.html", 'smarty_include_vars' => array('user' => $this->_tpl_vars['user'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 
 
   
    <?php $_from = $this->_tpl_vars['userquery']->my_account_links(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['link']):
?>
     <div class="account_option_box">
        <span class="its_title"><?php echo $this->_tpl_vars['name']; ?>
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
    </div>
    <?php endforeach; endif; unset($_from); ?>
    

 </div>
 <div class="clear"></div>
</div>