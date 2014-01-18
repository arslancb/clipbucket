<?php /* Smarty version Smarty-3.1.15, created on 2014-01-18 15:12:50
         compiled from "/var/www/clipbucket/admin_area/styles/cb_2014/layout/blocks/pagination.html" */ ?>
<?php /*%%SmartyHeaderCode:32026289652da534cb8d017-13984246%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7c770d83432aa56ecbaf058838b405c61a45615f' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cb_2014/layout/blocks/pagination.html',
      1 => 1390039968,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32026289652da534cb8d017-13984246',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52da534cb977e2_63157289',
  'variables' => 
  array (
    'pagination' => 0,
    'first_link' => 0,
    'pre_link' => 0,
    'next_link' => 0,
    'last_link' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52da534cb977e2_63157289')) {function content_52da534cb977e2_63157289($_smarty_tpl) {?><div class="pagination" align="center"> 
<?php if ($_smarty_tpl->tpl_vars['pagination']->value) {?>
  Pages : <?php if ($_smarty_tpl->tpl_vars['first_link']->value!='') {?><a <?php echo $_smarty_tpl->tpl_vars['first_link']->value;?>
>&laquo;</a><?php }?>  <?php if ($_smarty_tpl->tpl_vars['pre_link']->value!='') {?><a <?php echo $_smarty_tpl->tpl_vars['pre_link']->value;?>
 >&#8249;</a><?php }?> <?php echo $_smarty_tpl->tpl_vars['pagination']->value;?>
  <?php if ($_smarty_tpl->tpl_vars['next_link']->value!='') {?><a <?php echo $_smarty_tpl->tpl_vars['next_link']->value;?>
>&#8250;</a><?php }?> <?php if ($_smarty_tpl->tpl_vars['last_link']->value!='') {?><a <?php echo $_smarty_tpl->tpl_vars['last_link']->value;?>
>&raquo;</a><?php }?>
<?php }?>
</div>
<?php }} ?>
