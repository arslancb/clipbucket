<?php /* Smarty version Smarty-3.1.15, created on 2014-01-18 17:52:14
         compiled from "/var/www/clipbucket/admin_area/styles/cb_2014/layout/email_settings.html" */ ?>
<?php /*%%SmartyHeaderCode:71723681452da51972459c6-23645268%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82baf8adfe0a1fcf17dde4455e2c75ba5aaff230' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cb_2014/layout/email_settings.html',
      1 => 1390049532,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '71723681452da51972459c6-23645268',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52da51972465c3_77057041',
  'variables' => 
  array (
    'row' => 0,
    'cbemail' => 0,
    'templates' => 0,
    'template' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52da51972465c3_77057041')) {function content_52da51972465c3_77057041($_smarty_tpl) {?><h2>Email Settings</h2>
<form action="" method="post" enctype="multipart/form-data" name="player_settings">
  <fieldset class="fieldset" style="border:none">

    <div class="form-group">
      <label for="mail_type">Mailer <br />Select Mailer Type php Mail() or SMTP</label>
      <select class="form-control" name="mail_type" id="mail_type">
        <option value="mail" <?php if ($_smarty_tpl->tpl_vars['row']->value['mail_type']=='mail') {?> selected="selected"<?php }?>>PHP mail()</option>
        <option value="smtp" <?php if ($_smarty_tpl->tpl_vars['row']->value['mail_type']=='smtp') {?> selected="selected"<?php }?>>smtp</option>
      </select>
    </div>
    <div class="form-group">
      <label for="smtp_host">SMTP Host<br />if using smtp, Set SMTP server port</label>
      <input class="form-control" type="text" name="smtp_host" id="smtp_host" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['smtp_host'];?>
" />
    </div>
    <div class="form-group">
      <label for="smtp_user">SMTP Username<br />if using smtp, please enter SMTP username</label>
      <input type="text" class="form-control" name="smtp_user" id="smtp_user" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['smtp_user'];?>
" />  
    </div>

    <div class="form-group">
      <label for="smtp_pass">SMTP Password<br />Enter SMTP password</label>
      <input type="password" class="form-control" name="smtp_pass" id="smtp_pass" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['smtp_pass'];?>
" />
    </div>
    <div class="form-group">
      <label for="smtp_auth">SMTP Auth<br />Enable SMTP Authentication</label>
      <select class="form-control" name="smtp_auth" id="smtp_auth">
        <option value="yes" <?php if ($_smarty_tpl->tpl_vars['row']->value['smtp_auth']=='yes') {?> selected="selected"<?php }?>>yes</option>
        <option value="no" <?php if ($_smarty_tpl->tpl_vars['row']->value['smtp_auth']=='no') {?> selected="selected"<?php }?>>no</option>
      </select>
    </div>
    <div class="form-grou">
      <label for=""></label>
    </div>
  <input type="submit" class="btn btn-info" value="Update" name="update_settings" />
  </fieldset>

</form>


<h2>Email Templates Settings</h2>

<div>
<?php if (isset($_smarty_tpl->tpl_vars['templates'])) {$_smarty_tpl->tpl_vars['templates'] = clone $_smarty_tpl->tpl_vars['templates'];
$_smarty_tpl->tpl_vars['templates']->value = $_smarty_tpl->tpl_vars['cbemail']->value->get_templates(); $_smarty_tpl->tpl_vars['templates']->nocache = null; $_smarty_tpl->tpl_vars['templates']->scope = 0;
} else $_smarty_tpl->tpl_vars['templates'] = new Smarty_variable($_smarty_tpl->tpl_vars['cbemail']->value->get_templates(), null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['templates']->value) {?>
<form name="email_templates" method="post">
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
          <div class="templateCodeCont" style="padding:3px">
            <input type="text" name="subject<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
" id="subject<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
"
            value="<?php echo form_val($_smarty_tpl->tpl_vars['template']->value['email_template_subject']);?>
" style="border:0px; background:none; width:100%" />
          </div>
          <br />    
          <label for="message<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
">Email message</label>
          <div class="templateCodeCont">
            <textarea class="form-control" name="message<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
" cols="60" rows="8" id="message<?php echo $_smarty_tpl->tpl_vars['template']->value['email_template_id'];?>
" class="templateCode"><?php echo form_val($_smarty_tpl->tpl_vars['template']->value['email_template']);?>
</textarea>
          </div>   
      </div>     
    <?php } ?>
    <div align="right" style="margin-top:5px">
      <input type="submit" class="btn btn-info" value="Save Templates" name="update" /></div>
    </div>
  </form>
<?php }?>
</div><?php }} ?>
