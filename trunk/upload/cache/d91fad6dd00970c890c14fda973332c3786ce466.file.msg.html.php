<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:46:42
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/msg.html" */ ?>
<?php /*%%SmartyHeaderCode:96108983352d659026b36e2-68827956%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd91fad6dd00970c890c14fda973332c3786ce466' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/msg.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '96108983352d659026b36e2-68827956',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'eh' => 0,
    'err' => 0,
    'imageurl' => 0,
    'show_msg' => 0,
    'msg' => 0,
    'war' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d659026dd579_41169216',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d659026dd579_41169216')) {function content_52d659026dd579_41169216($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['msg'])) {$_smarty_tpl->tpl_vars['msg'] = clone $_smarty_tpl->tpl_vars['msg'];
$_smarty_tpl->tpl_vars['msg']->value = $_smarty_tpl->tpl_vars['eh']->value->message_list; $_smarty_tpl->tpl_vars['msg']->nocache = null; $_smarty_tpl->tpl_vars['msg']->scope = 0;
} else $_smarty_tpl->tpl_vars['msg'] = new Smarty_variable($_smarty_tpl->tpl_vars['eh']->value->message_list, null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['err'])) {$_smarty_tpl->tpl_vars['err'] = clone $_smarty_tpl->tpl_vars['err'];
$_smarty_tpl->tpl_vars['err']->value = $_smarty_tpl->tpl_vars['eh']->value->error_list; $_smarty_tpl->tpl_vars['err']->nocache = null; $_smarty_tpl->tpl_vars['err']->scope = 0;
} else $_smarty_tpl->tpl_vars['err'] = new Smarty_variable($_smarty_tpl->tpl_vars['eh']->value->error_list, null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['war'])) {$_smarty_tpl->tpl_vars['war'] = clone $_smarty_tpl->tpl_vars['war'];
$_smarty_tpl->tpl_vars['war']->value = $_smarty_tpl->tpl_vars['eh']->value->warning_list; $_smarty_tpl->tpl_vars['war']->nocache = null; $_smarty_tpl->tpl_vars['war']->scope = 0;
} else $_smarty_tpl->tpl_vars['war'] = new Smarty_variable($_smarty_tpl->tpl_vars['eh']->value->warning_list, null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['err']->value[0]!=''||$_smarty_tpl->tpl_vars['err']->value[1]!='') {?>

<div class="msg error">
    <ul>
    <?php  $_smarty_tpl->tpl_vars['show_msg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['show_msg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['err']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['show_msg']->key => $_smarty_tpl->tpl_vars['show_msg']->value) {
$_smarty_tpl->tpl_vars['show_msg']->_loop = true;
?>
   		<li><img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/cross.png" /><?php echo $_smarty_tpl->tpl_vars['show_msg']->value;?>
</li>
    <?php } ?>
    </ul>
</div>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['msg']->value[0]!='') {?>
<div class="msg blue">
	<ul>
        <?php  $_smarty_tpl->tpl_vars['show_msg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['show_msg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['msg']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['show_msg']->key => $_smarty_tpl->tpl_vars['show_msg']->value) {
$_smarty_tpl->tpl_vars['show_msg']->_loop = true;
?>
        	<li><img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/ok.png" /><?php echo $_smarty_tpl->tpl_vars['show_msg']->value;?>
</li>
        <?php } ?>
    </ul>
</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['war']->value[0]!='') {?>
<div class="msg blue">
	<ul>
        <?php  $_smarty_tpl->tpl_vars['show_msg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['show_msg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['war']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['show_msg']->key => $_smarty_tpl->tpl_vars['show_msg']->value) {
$_smarty_tpl->tpl_vars['show_msg']->_loop = true;
?>
        	<li><img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/error.png" /><?php echo $_smarty_tpl->tpl_vars['show_msg']->value;?>
</li>
        <?php } ?>
    </ul>
</div>
<?php }?><?php }} ?>
