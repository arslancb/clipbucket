<?php /* Smarty version Smarty-3.1.15, created on 2014-01-18 18:21:43
         compiled from "/var/www/clipbucket/admin_area/styles/cb_2014/layout/manage_pages.html" */ ?>
<?php /*%%SmartyHeaderCode:176913834952da525e34ad61-79874392%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cc468d7d5770f0280775596d8eab6ea9a283b10b' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cb_2014/layout/manage_pages.html',
      1 => 1390045056,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '176913834952da525e34ad61-79874392',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52da525e35f082_48390674',
  'variables' => 
  array (
    'mode' => 0,
    'page' => 0,
    'imageurl' => 0,
    'cbpages' => 0,
    'bgcolor' => 0,
    'cbpage' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52da525e35f082_48390674')) {function content_52da525e35f082_48390674($_smarty_tpl) {?>
<span class="page_title">Manage Pages</span>

<?php if ($_smarty_tpl->tpl_vars['mode']->value=="new") {?>
<form method="post">
<fieldset class="fieldset cbform">
  <legend>Add New Page
  </legend><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
    <tr>
      <td width="200"><label for="page_name">Page Name</label></td>
    </tr>
    <tr>
      <td>
      <input name="page_name" type="text" id="page_name" value="<?php echo post('page_name');?>
"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="page_title">Page Title</label></td>
    </tr>
    <tr>
      <td><input type="text" name="page_title" id="page_title" value="<?php echo post('page_title');?>
" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="page_content">Page Content</label></td>
    </tr>
    <tr>
      <td style="background-color:#FFF">
      <textarea name="page_content" id="page_content" style="width:100%; min-height:250px"><?php echo form_val(post('page_content'));?>
</textarea><script type="text/javascript">
	new nicEditor({fullPanel : true,maxHeight:350}).panelInstance('page_content');
</script></td>
    </tr>
    <tr>
      <td style="background-color:#FFF">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" style="background-color:#FFF">
        <input type="submit" name="add_page" id="add_page" value="Create new page" class="button"/></td>
    </tr>

  </table>
</fieldset>
</form>
<?php }?>





<?php if ($_smarty_tpl->tpl_vars['mode']->value=="edit"&&$_smarty_tpl->tpl_vars['page']->value['page_name']!='') {?>
<form method="post">
<fieldset class="fieldset cbform">
  <legend>Add New Page
  </legend><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
    <tr>
      <td width="200"><label for="page_name">Page Name</label></td>
    </tr>
    <tr>
      <td>
      <input name="page_name" type="text" id="page_name" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['page_name'];?>
"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="page_title">Page Title</label></td>
    </tr>
    <tr>
      <td><input type="text" name="page_title" id="page_title" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['page_title'];?>
" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><label for="page_content">Page Content</label></td>
    </tr>
    <tr>
      <td style="background-color:#FFF">
      <textarea name="page_content" id="page_content" style="width:100%; min-height:250px"><?php echo form_val($_smarty_tpl->tpl_vars['page']->value['page_content']);?>
</textarea><script type="text/javascript">
	new nicEditor({fullPanel : true,maxHeight:350}).panelInstance('page_content');
</script></td>
    </tr>
    <tr>
      <td style="background-color:#FFF">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" style="background-color:#FFF">
        <input type="submit" name="update_page" id="update_page" value="Update page" class="button"/></td>
    </tr>

  </table>
</fieldset>
</form>
<?php }?>




<?php if ($_smarty_tpl->tpl_vars['mode']->value=='manage') {?>
<form name="page_form" action="?" method="post">
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/arrow_return.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
     		<input type="submit" name="activate_selected" value="Activate"  class="button"/>
            <input type="submit" name="deactivate_selected" value="Deactivate" class="button" />
            <input type="submit" name="update_order" value="Update order" class="button" />
            <input type="submit" name="delete_selected" value="Delete"  class="button" onclick="return confirm_it('Are you sure you want to delete selected page(s)')"/>
    
    </td>
    <td align="right" style="padding-left:15px"><input type="button" value="Create New Page" class="button" onClick="window.location='?mode=new'"/></td>
    </tr>
</table>



<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle" class="left_head">
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
    <td width="50" class="head_sep_left">PID</td>
    <td width="50" class="head_sep_left">Order</td>
    <td class="head"><div class="head_sep_left" style="width:250px">Details</div></td>
    <td width="50" class="right_head">&nbsp;</td>
  </tr>
</table>





<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = ''; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable('', null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['list'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['list']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['name'] = 'list';
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['cbpages']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['list']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['list']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['list']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['list']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['list']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['list']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['list']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['list']['total']);
?>
<tr class="video_opt_td"  bgcolor="<?php echo $_smarty_tpl->tpl_vars['bgcolor']->value;?>
">
    <td width="30" align="center" valign="top" class="video_opt_td">    <input name="check_page[]" type="checkbox" id="check_video" value="<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
" /></td>
     <td width="50" align="center" valign="top" class="video_opt_td"><?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
</td>
	 <td width="50" align="center" valign="top" class="video_opt_td"><input type="text" style="border: 1px solid rgb(153, 153, 153); padding: 2px; width: 30px;" value="<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_order'];?>
" name="page_ord_<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
"/></td>
    <td valign="top"  class="video_opt_td" 
    onmouseover="$('#vid_opt-<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
').show()" 
    onmouseout="$('#vid_opt-<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
').hide()"  style="padding-left:10px">
    <a href="<?php echo $_smarty_tpl->tpl_vars['cbpage']->value->page_link($_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]);?>
" target="_blank" style="text-indent:10px">
    <?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_name'];?>

    </a> &#8212;
    <span class="vdo_sets">
    Active:<strong><?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['active'];?>
 </strong> &#8226;
    Added:<strong><?php echo niceTime($_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['date_added']);?>
 </strong>
    <?php if ($_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['delete_able']=='no') {?> &#8226; <strong>UNDELETE-ABLE</strong><?php }?>
     &#8226; Display  in footer:<strong><?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['display'];?>
 </strong>
    </span>
    
    <br />
    <div id="vid_opt-<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
" style="display:none" class="vid_opts">
   	 
      <a href="<?php echo $_smarty_tpl->tpl_vars['cbpage']->value->page_link($_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]);?>
">View</a> | 
      <a href="?mode=edit&pid=<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
">Edit</a>
      <?php if ($_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['active']=='yes') {?>  | 
      <a href="?deactivate=<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
">Deactivate</a> <?php } else { ?> | 
      <a href="?activate=<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
">Activate</a> <?php }?>  | 
      <a href="?delete=<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
">Delete</a> |
      
      <?php if ($_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['display']!='yes') {?>
      	<a href="?display=<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
">Display in footer</a>
      <?php } else { ?>
      	<a href="?hide=<?php echo $_smarty_tpl->tpl_vars['cbpages']->value[$_smarty_tpl->getVariable('smarty')->value['section']['list']['index']]['page_id'];?>
">Hide in footer</a>
      <?php }?>
    </div>
    </td>
    <td width="50" valign="top" class="video_opt_td">&nbsp;</td>
  </tr>
    <?php if ($_smarty_tpl->tpl_vars['bgcolor']->value=='') {?>
    <?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = "#EEEEEE"; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable("#EEEEEE", null, 0);?>
    <?php } else { ?>
    <?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = ''; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable('', null, 0);?>
    <?php }?>
        
<?php endfor; endif; ?>
</table>

<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/arrow_return_invert.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
     	<input type="submit" name="activate_selected" value="Activate"  class="button"/>
        <input type="submit" name="deactivate_selected" value="Deactivate" class="button" />
        <input type="submit" name="update_order" value="Update order" class="button" />
        <input type="submit" name="delete_selected" value="Delete"  class="button" onclick="return confirm_it('Are you sure you want to delete selected page(s)')"/>
    </td>
  </tr>
</table>
</form>
<?php }?><?php }} ?>
