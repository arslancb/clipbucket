<?php /* Smarty version Smarty-3.1.15, created on 2014-01-17 09:24:03
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/blocks/comments.html" */ ?>
<?php /*%%SmartyHeaderCode:131622014152d8f6b36e07d0-77319697%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '295ba930b7e2630dd3ffc77e1ef14f90e587e928' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/blocks/comments.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '131622014152d8f6b36e07d0-77319697',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'comments' => 0,
    'bgcolor' => 0,
    'comment' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d8f6b370d9e6_47858033',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d8f6b370d9e6_47858033')) {function content_52d8f6b370d9e6_47858033($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/clipbucket/includes/smartyv3/plugins/modifier.date_format.php';
?><?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = 'F2F2F2'; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable('F2F2F2', null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['comments']->value) {?>
<table width="99%" border="0" cellpadding="2" cellspacing="1" style="color:#fff">
  <tr>
    <td width="35" height="30" bgcolor="#0066cc">CID</td>
    <td width="35" height="30" bgcolor="#0066cc">UID</td> 
    <td width="100" height="30" bgcolor="#0066cc">Username</td>
    <td width="150" height="30" bgcolor="#0066cc">Email</td>
    <td height="30" bgcolor="#0066cc">Comment</td>
    <td width="200" height="30" bgcolor="#0066cc" >Date</td>
    <td width="70" height="30" bgcolor="#0066cc">Votes</td>
    <td width="100" height="30" bgcolor="#0066cc">&nbsp;</td>
  </tr>
</table>
<div>
<?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['comments']->value['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value) {
$_smarty_tpl->tpl_vars['comment']->_loop = true;
?>
	<table width="99%" border="0" cellpadding="2" cellspacing="1" >
          <tr style="background-color:#<?php echo $_smarty_tpl->tpl_vars['bgcolor']->value;?>
">
            <td width="35" align="left"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
</td>
            <td width="35" align="left"><?php echo $_smarty_tpl->tpl_vars['comment']->value['userid'];?>
</td>
            <td width="100" align="left"><?php if ($_smarty_tpl->tpl_vars['comment']->value['anonym_name']) {?><?php echo $_smarty_tpl->tpl_vars['comment']->value['anonym_name'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['comment']->value['username'];?>
<?php }?></td>
            <td  width="150" align="left"><?php if ($_smarty_tpl->tpl_vars['comment']->value['anonym_email']) {?><?php echo $_smarty_tpl->tpl_vars['comment']->value['anonym_email'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['comment']->value['email'];?>
<?php }?></td>
            <td ><div class="edit_comment" id="<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</div></td>
            <td width="200" align="left" ><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date_added']);?>
</td>
            <td width="70" align="left" ><?php echo $_smarty_tpl->tpl_vars['comment']->value['vote'];?>
</td>
            <td width="100" ><a href="?<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
&amp;delete_comment=<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
">Delete</a></td>
    </tr>
        
   	<?php if ($_smarty_tpl->tpl_vars['bgcolor']->value=='F2F2F2') {?>
        	<?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = 'FFF'; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable('FFF', null, 0);?>
       	<?php } elseif ($_smarty_tpl->tpl_vars['bgcolor']->value=='FFF') {?>
        	<?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = 'F2F2F2'; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable('F2F2F2', null, 0);?>
        <?php }?>
        	
<?php } ?>
</table>
</div>
<?php } else { ?>
<div align="center"><em>No User Comments</em></div>
<?php }?>


<script type="text/javascript">
$(document).ready(function() {
 $('.edit_comment').editable(baseurl+'/actions/edit_comment.php', { 
 indicator : '<img src="'+baseurl+'/images/icons/progIndicator.gif">',
 tooltip   : 'Click to edit...',
});
</script>
<?php }} ?>
