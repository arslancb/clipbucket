<?php /* Smarty version 2.6.18, created on 2014-01-17 12:40:24
         compiled from /var/www/clipbucket/styles/cbv2new/layout/manage_contacts.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/manage_contacts.html', 6, false),array('modifier', 'number_format', '/var/www/clipbucket/styles/cbv2new/layout/manage_contacts.html', 34, false),)), $this); ?>
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
 
 <h2><?php echo smarty_lang(array('code' => 'user_manage_contacts'), $this);?>
</h2>
 <h3><?php echo smarty_lang(array('code' => 'friends'), $this);?>
</h3>
 <?php $this->assign('friends', $this->_tpl_vars['userquery']->get_contacts($this->_tpl_vars['user']['userid'],0)); ?>
 <?php if ($this->_tpl_vars['friends']): ?>
 	
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="manage_contacts_tbl_head">
      <tr>
        <td width="15"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
        <td width="50">&nbsp;</td>
        <td><?php echo smarty_lang(array('code' => 'username'), $this);?>
</td>
        <td width="100"><?php echo smarty_lang(array('code' => 'views'), $this);?>
</td>
        <td width="100">&nbsp;</td>
        <td width="50">&nbsp;</td>
      </tr>
    </table>
    
   <?php $_from = $this->_tpl_vars['friends']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['friend']):
?>
   

   <?php $this->assign('user_detail', $this->_tpl_vars['friend']); ?>

    
   <div class="manage_contacts_tbl">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15"><input type="checkbox" name="friend[]" id="check_userd-<?php echo $this->_tpl_vars['user_detail']['userid']; ?>
" value="<?php echo $this->_tpl_vars['user_detail']['userid']; ?>
" /></td>
        <td width="50" height="50" align="center" valign="middle"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user_detail']); ?>
"><img src="<?php echo $this->_tpl_vars['userquery']->getUserThumb($this->_tpl_vars['user_detail']); ?>
" alt="<?php echo $this->_tpl_vars['user_detail']['username']; ?>
" width="40" height="40" border="0"></a></td>
        <td><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user_detail']); ?>
"><?php echo $this->_tpl_vars['user_detail']['username']; ?>
</a></td>
        <td width="100"><?php echo ((is_array($_tmp=$this->_tpl_vars['user_detail']['profile_hits'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
        <td width="100">
        <?php if ($this->_tpl_vars['friend']['confirmed'] != 'yes'): ?>
        <span class="cb_pending"><?php echo smarty_lang(array('code' => 'pending'), $this);?>
</span>
        <?php endif; ?>
        </td>
        <td width="50" align="center" valign="middle"><a href="?mode=delete&userid=<?php echo $this->_tpl_vars['user_detail']['userid']; ?>
"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/cancel.png" width="16" height="16" border="0" /></a></td>
      </tr>
    </table>
    </div>
   <?php endforeach; endif; unset($_from); ?>
<?php else: ?>
<div><em><strong><?php echo smarty_lang(array('code' => 'no_contacts'), $this);?>
</strong></em></div>
<?php endif; ?>


<h3><?php echo smarty_lang(array('code' => 'pending_requests'), $this);?>
</h3>
 <?php $this->assign('friends', $this->_tpl_vars['userquery']->get_pending_contacts($this->_tpl_vars['user']['userid'],0)); ?>
 <?php if ($this->_tpl_vars['friends']): ?>
 	
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="manage_contacts_tbl_head">
      <tr>
        <td width="15"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
        <td width="50">&nbsp;</td>
        <td><?php echo smarty_lang(array('code' => 'username'), $this);?>
</td>
        <td width="100"><?php echo smarty_lang(array('code' => 'views'), $this);?>
</td>
        <td width="100">&nbsp;</td>
        <td width="50">&nbsp;</td>
      </tr>
    </table>
    
   <?php $_from = $this->_tpl_vars['friends']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['friend']):
?>
   

   <?php $this->assign('user_detail', $this->_tpl_vars['friend']); ?>

    
   <div class="manage_contacts_tbl">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15"><input type="checkbox" name="friend[]" id="check_userd-<?php echo $this->_tpl_vars['user_detail']['userid']; ?>
" value="<?php echo $this->_tpl_vars['user_detail']['userid']; ?>
" /></td>
        <td width="50" height="50" align="center" valign="middle"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user_detail']); ?>
"><img src="<?php echo $this->_tpl_vars['userquery']->getUserThumb($this->_tpl_vars['user_detail']); ?>
" alt="<?php echo $this->_tpl_vars['user_detail']['username']; ?>
" width="40" height="40" border="0"></a></td>
        <td><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user_detail']); ?>
"><?php echo $this->_tpl_vars['user_detail']['username']; ?>
</a></td>
        <td width="100"><?php echo ((is_array($_tmp=$this->_tpl_vars['user_detail']['profile_hits'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
        <td width="100">
		<?php if ($this->_tpl_vars['friend']['confirmed'] != 'yes'): ?>
        <span class="cb_fb_style_button"><a href="?mode=requests&confirm=<?php echo $this->_tpl_vars['friend']['userid']; ?>
"><?php echo smarty_lang(array('code' => 'confirm'), $this);?>
</a></span><?php endif; ?>
        
        </td>
        <td width="50" align="center" valign="middle"><a href="?mode=delete&userid=<?php echo $this->_tpl_vars['user_detail']['userid']; ?>
"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/cancel.png" width="16" height="16" border="0" /></a></td>
      </tr>
    </table>
    </div>
   <?php endforeach; endif; unset($_from); ?>
<?php else: ?>
<div><em><strong><?php echo smarty_lang(array('code' => 'no_contacts'), $this);?>
</strong></em></div>
<?php endif; ?>
 
 
 </div>
 

</div>