<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:46:42
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/left_menu.html" */ ?>
<?php /*%%SmartyHeaderCode:86610324752d659027b0d32-81521136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '438217f681786e030b4202065a3ec74a252f8d7b' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/left_menu.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86610324752d659027b0d32-81521136',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'left_menu_class' => 0,
    'imageurl' => 0,
    'Cbucket' => 0,
    'menus' => 0,
    'name' => 0,
    'menu' => 0,
    'sub_link' => 0,
    'sub_menu' => 0,
    'js' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d659027dd0c6_96732847',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d659027dd0c6_96732847')) {function content_52d659027dd0c6_96732847($_smarty_tpl) {?>
<div class="<?php echo $_smarty_tpl->tpl_vars['left_menu_class']->value;?>
" id="left_column">

<img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/dot.gif" class="menu_toggle_arrow" alt="Toggle Menu" title="Toggle Menu" onClick="toggle_menu()"/>
<img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/dot.gif" class="menu_toggle" alt="Toggle Menu" title="Toggle Menu" onClick="toggle_menu()"/>


<?php if (isset($_smarty_tpl->tpl_vars['menus'])) {$_smarty_tpl->tpl_vars['menus'] = clone $_smarty_tpl->tpl_vars['menus'];
$_smarty_tpl->tpl_vars['menus']->value = $_smarty_tpl->tpl_vars['Cbucket']->value->AdminMenu; $_smarty_tpl->tpl_vars['menus']->nocache = null; $_smarty_tpl->tpl_vars['menus']->scope = 0;
} else $_smarty_tpl->tpl_vars['menus'] = new Smarty_variable($_smarty_tpl->tpl_vars['Cbucket']->value->AdminMenu, null, 0);?>

<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value) {
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['menu']->key;
?>
  <!-- *********************************Start <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
 Menu****************************** -->
 <div class="mainDiv" >
    <div class="topItem"  ><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</div>
    <div class="dropMenu" >          
      <div class="subMenu">
        <?php  $_smarty_tpl->tpl_vars['sub_link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sub_link']->_loop = false;
 $_smarty_tpl->tpl_vars['sub_menu'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sub_link']->key => $_smarty_tpl->tpl_vars['sub_link']->value) {
$_smarty_tpl->tpl_vars['sub_link']->_loop = true;
 $_smarty_tpl->tpl_vars['sub_menu']->value = $_smarty_tpl->tpl_vars['sub_link']->key;
?>
        	<div class="subItem"><a href="<?php echo $_smarty_tpl->tpl_vars['sub_link']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['sub_menu']->value;?>
</a></div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- *********************************End <?php echo $_smarty_tpl->tpl_vars['name']->value;?>
 Menu****************************** -->
<?php } ?>
 <!-- *********************************End Menu****************************** -->
</div>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/slidemenu.js"></script><?php }} ?>
