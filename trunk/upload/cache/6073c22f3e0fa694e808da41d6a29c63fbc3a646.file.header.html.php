<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:46:42
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/header.html" */ ?>
<?php /*%%SmartyHeaderCode:133679439852d659026a1ed5-34645731%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6073c22f3e0fa694e808da41d6a29c63fbc3a646' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/header.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '133679439852d659026a1ed5-34645731',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'imageurl' => 0,
    'title' => 0,
    'userquery' => 0,
    'baseurl' => 0,
    'Cbucket' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d659026b1713_62382854',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d659026b1713_62382854')) {function content_52d659026b1713_62382854($_smarty_tpl) {?><div class="header_grey_bar">
	<img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/dot.gif" class="cbicon" /><?php echo $_smarty_tpl->tpl_vars['title']->value;?>

    <div class="welcome" style="float:right">
    	Hello <?php echo $_smarty_tpl->tpl_vars['userquery']->value->username;?>
 <a href="logout.php"><img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/dot.gif" border="0" class="logout_button" /></a>
	</div>
</div>

<div class="header_menu clearfix">
  <ul>
    <li><a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/admin_area"><?php echo $_smarty_tpl->tpl_vars['userquery']->value->permission['user_level_name'];?>
 Home</a></li>
    <li><a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
">Website Home</a></li>
  </ul>
  <div class="dp_opts"><a href="javascript:void(0)" onclick="$('#dp_opts').slideToggle()">Display Options</a></div>
</div>
<div class="header_menu_after">
<div id="dp_opts">
  <form id="display_opts" name="display_opts" method="post" action="">
  	Results Per Page : 
  	<input name="admin_pages" type="text" style="width:50px" value="<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['admin_pages'];?>
"/>
    <input type="submit" name="update_dp_options" id="button" value="Update"  class="button"/>
  </form>
</div>
</div>
<?php }} ?>
