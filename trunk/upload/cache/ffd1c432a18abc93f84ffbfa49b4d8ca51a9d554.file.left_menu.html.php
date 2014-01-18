<?php /* Smarty version Smarty-3.1.15, created on 2014-01-18 15:26:22
         compiled from "/var/www/clipbucket/admin_area/styles/cb_2014/layout/left_menu.html" */ ?>
<?php /*%%SmartyHeaderCode:138271609552da4949b16f27-09647708%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ffd1c432a18abc93f84ffbfa49b4d8ca51a9d554' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cb_2014/layout/left_menu.html',
      1 => 1390040776,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '138271609552da4949b16f27-09647708',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52da4949b25486_53132295',
  'variables' => 
  array (
    'Cbucket' => 0,
    'adminMenu' => 0,
    'name' => 0,
    'oneMenuItem' => 0,
    'oneSubMenuItem' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52da4949b25486_53132295')) {function content_52da4949b25486_53132295($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['adminMenu'])) {$_smarty_tpl->tpl_vars['adminMenu'] = clone $_smarty_tpl->tpl_vars['adminMenu'];
$_smarty_tpl->tpl_vars['adminMenu']->value = $_smarty_tpl->tpl_vars['Cbucket']->value->AdminMenu; $_smarty_tpl->tpl_vars['adminMenu']->nocache = null; $_smarty_tpl->tpl_vars['adminMenu']->scope = 0;
} else $_smarty_tpl->tpl_vars['adminMenu'] = new Smarty_variable($_smarty_tpl->tpl_vars['Cbucket']->value->AdminMenu, null, 0);?>

<ul class="nav nav-list" id="sidebar">
    <li class="active">
        <a href="index.php">
            <i class="icon-dashboard"></i>
            <span class="menu-text"> Dashboard </span>
        </a>
    </li>
<?php  $_smarty_tpl->tpl_vars['oneMenuItem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['oneMenuItem']->_loop = false;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adminMenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['oneMenuItem']->key => $_smarty_tpl->tpl_vars['oneMenuItem']->value) {
$_smarty_tpl->tpl_vars['oneMenuItem']->_loop = true;
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['oneMenuItem']->key;
?>
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="icon-list"></i>
            <span class="menu-text"> <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
 </span>
            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
        <?php  $_smarty_tpl->tpl_vars['oneSubMenuItem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['oneSubMenuItem']->_loop = false;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['oneMenuItem']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['oneSubMenuItem']->key => $_smarty_tpl->tpl_vars['oneSubMenuItem']->value) {
$_smarty_tpl->tpl_vars['oneSubMenuItem']->_loop = true;
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['oneSubMenuItem']->key;
?>
            <li>
                <a href="<?php echo $_smarty_tpl->tpl_vars['oneSubMenuItem']->value;?>
">
                    <i class="icon-leaf"></i>
                    <?php echo $_smarty_tpl->tpl_vars['name']->value;?>

                </a>
            </li>
        <?php } ?>
        </ul>
    </li>
<?php } ?>
</ul>
<div id="sidebar-collapse" class="sidebar-collapse">
    <i data-icon2="icon-double-angle-right" data-icon1="icon-double-angle-left" class="icon-double-angle-left"></i>
</div><?php }} ?>
