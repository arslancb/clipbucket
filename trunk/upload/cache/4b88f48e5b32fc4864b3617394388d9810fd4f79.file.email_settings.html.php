<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:47:12
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/email_settings.html" */ ?>
<?php /*%%SmartyHeaderCode:19731798352d659200d5d47-67179605%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b88f48e5b32fc4864b3617394388d9810fd4f79' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/email_settings.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19731798352d659200d5d47-67179605',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'row' => 0,
    'cbemail' => 0,
    'templates' => 0,
    'template' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d65920129208_04177738',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d65920129208_04177738')) {function content_52d65920129208_04177738($_smarty_tpl) {?><h2>Email Settings</h2>
<form action="" method="post" enctype="multipart/form-data" name="player_settings">
<fieldset class="fieldset" style="border:none">
<table width="100%" border="0" cellpadding="2" cellspacing="0" class="block">
<tr>
<td width="301" valign="top"><strong><label for="mail_type">Mailer</label></strong><br />
  Select Mailer Type php Mail() or SMTP</label></td>
<td width="813" valign="top">
  <select name="mail_type" id="mail_type">
    <option value="mail" <?php if ($_smarty_tpl->tpl_vars['row']->value['mail_type']=='mail') {?> selected="selected"<?php }?>>PHP mail()</option>
    <option value="smtp" <?php if ($_smarty_tpl->tpl_vars['row']->value['mail_type']=='smtp') {?> selected="selected"<?php }?>>smtp</option>
  </select></td>
</tr>
<tr>
  <td valign="top"><strong><label for="smtp_host">SMTP Host</label><br />
    </strong>If using smtp, please enter its server</td>
  <td valign="top">
    <input type="text" name="smtp_host" id="smtp_host" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['smtp_host'];?>
" /></td>
</tr>
<tr>
  <td valign="top"><strong><label for="smtp_port">SMTP Port</label></strong><br />
    if using smtp, Set SMTP server port</td>
  <td valign="top">
    <input type="text" name="smtp_port" id="smtp_port" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['smtp_port'];?>
" /></td>
</tr>
<tr>
  <td valign="top"><strong><label for="smtp_user">SMTP Username</label></strong><br />
  if using smtp, please enter SMTP username</td>
  <td valign="top">
    <input type="text" name="smtp_user" id="smtp_user" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['smtp_user'];?>
" /></td>
</tr>
<tr>
  <td valign="top"><strong><label for="smtp_pass">SMTP Password</label></strong><br />
    Enter SMTP password</td>
  <td valign="top">
    <input type="password" name="smtp_pass" id="smtp_pass" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['smtp_pass'];?>
" /></td>
</tr>
<tr>
  <td valign="top"><strong>SMTP Auth</strong><br /> 
    Enable SMTP Authentication
</td>
  <td valign="top"><select name="smtp_auth" id="smtp_auth">
    <option value="yes" <?php if ($_smarty_tpl->tpl_vars['row']->value['smtp_auth']=='yes') {?> selected="selected"<?php }?>>yes</option>
    <option value="no" <?php if ($_smarty_tpl->tpl_vars['row']->value['smtp_auth']=='no') {?> selected="selected"<?php }?>>no</option>
  </select></td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td valign="top">&nbsp;</td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td valign="top">&nbsp;</td>
</tr>
</table>
<div align="left"><input type="submit" class="button" value="Update" name="update_settings" /></div>
</fieldset>

</form>
<div style="height:10px"></div>
<h2>Email Templates Settings</h2>

<div style="height:10px"></div>

<div style="padding-right:10px">
<?php if (isset($_smarty_tpl->tpl_vars['templates'])) {$_smarty_tpl->tpl_vars['templates'] = clone $_smarty_tpl->tpl_vars['templates'];
$_smarty_tpl->tpl_vars['templates']->value = $_smarty_tpl->tpl_vars['cbemail']->value->get_templates(); $_smarty_tpl->tpl_vars['templates']->nocache = null; $_smarty_tpl->tpl_vars['templates']->scope = 0;
} else $_smarty_tpl->tpl_vars['templates'] = new Smarty_variable($_smarty_tpl->tpl_vars['cbemail']->value->get_templates(), null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['templates']->value) {?><form name="email_templates" method="post">
<ul class="optionsLists" >
	
    <?php  $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['template']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['templates']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['etemp']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['template']->key => $_smarty_tpl->tpl_vars['template']->value) {
$_smarty_tpl->tpl_vars['template']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['etemp']['iteration']++;
?>
    	<li <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['etemp']['iteration']=='1') {?> class="selected"<?php }?>
        	onclick="
            	$('.tempselected').removeClass('selected').hide();
            	$('#template-<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
').show().addClass('tempselected');
            	
                $('.selected').removeClass('selected');
            	$(this).toggleClass('selected');
                
                "><?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_name'];?>
</li>
    <?php } ?>

</ul>
<div class="optionsListsCont">
	<?php  $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['template']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['templates']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['etemp']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['template']->key => $_smarty_tpl->tpl_vars['template']->value) {
$_smarty_tpl->tpl_vars['template']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['etemp']['iteration']++;
?>
    	<div id="template-<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
" 
        	<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['etemp']['iteration']!='1') {?> style="display:none" <?php } else { ?>class="tempselected"<?php }?>>
            
            <label for="subject<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
">Email Subject</label>
            <div class="templateCodeCont" style="padding:3px"><input type="text" name="subject<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
" id="subject<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
"
            	value="<?php echo form_val($_smarty_tpl->tpl_vars['template']->value['email_template_subject']);?>
" style="border:0px; background:none; width:100%" /></div>
                <br />

                
            <label for="message<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
">Email message</label>
			<div class="templateCodeCont"><textarea name="message<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
" cols="60" rows="8" id="message<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
" class="templateCode"><?php echo form_val($_smarty_tpl->tpl_vars['template']->value['email_template']);?>
</textarea></div></td>   
        </div>     
    <?php } ?>
    <div align="right" style="margin-top:5px"><input type="submit" class="button" value="Save Templates" name="update" /></div>
</div></form>
<?php }?>
</div><?php }} ?>
