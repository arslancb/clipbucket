<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 15:31:33
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/templates.html" */ ?>
<?php /*%%SmartyHeaderCode:84338975252d66385a62877-52485932%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '85b9414cdbdd4c0458e8a05dd83454a37544c5b0' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/templates.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '84338975252d66385a62877-52485932',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'Cbucket' => 0,
    'selected' => 0,
    'cbtpl' => 0,
    'curtpl' => 0,
    'templates' => 0,
    'template' => 0,
    'baseurl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d66385aa82b6_84091104',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d66385aa82b6_84091104')) {function content_52d66385aa82b6_84091104($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/clipbucket/includes/smartyv3/plugins/modifier.date_format.php';
?><h2>Selected Template</h2>
<div style="margin-top:10px">
<?php if (isset($_smarty_tpl->tpl_vars["selected"])) {$_smarty_tpl->tpl_vars["selected"] = clone $_smarty_tpl->tpl_vars["selected"];
$_smarty_tpl->tpl_vars["selected"]->value = $_smarty_tpl->tpl_vars['Cbucket']->value->configs['template_dir']; $_smarty_tpl->tpl_vars["selected"]->nocache = null; $_smarty_tpl->tpl_vars["selected"]->scope = 0;
} else $_smarty_tpl->tpl_vars["selected"] = new Smarty_variable($_smarty_tpl->tpl_vars['Cbucket']->value->configs['template_dir'], null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars["curtpl"])) {$_smarty_tpl->tpl_vars["curtpl"] = clone $_smarty_tpl->tpl_vars["curtpl"];
$_smarty_tpl->tpl_vars["curtpl"]->value = $_smarty_tpl->tpl_vars['cbtpl']->value->get_template_details($_smarty_tpl->tpl_vars['selected']->value); $_smarty_tpl->tpl_vars["curtpl"]->nocache = null; $_smarty_tpl->tpl_vars["curtpl"]->scope = 0;
} else $_smarty_tpl->tpl_vars["curtpl"] = new Smarty_variable($_smarty_tpl->tpl_vars['cbtpl']->value->get_template_details($_smarty_tpl->tpl_vars['selected']->value), null, 0);?>
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="190"><img src="<?php echo $_smarty_tpl->tpl_vars['cbtpl']->value->get_preview_thumb($_smarty_tpl->tpl_vars['curtpl']->value['dir']);?>
" class="preview_thumb_template" ></td>
    <td valign="top"><h2 style="display:inline"><?php echo $_smarty_tpl->tpl_vars['curtpl']->value['name'];?>
</h2> &#8212; <em><a href="<?php echo $_smarty_tpl->tpl_vars['curtpl']->value['website']['link'];?>
"><strong><?php echo $_smarty_tpl->tpl_vars['curtpl']->value['author'];?>
</strong></a></em><br />
<?php echo $_smarty_tpl->tpl_vars['curtpl']->value['description'];?>
<br />
Version : <?php echo $_smarty_tpl->tpl_vars['curtpl']->value['version'];?>
, Released on <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['curtpl']->value['released']);?>
<br />
Website : <a href="<?php echo $_smarty_tpl->tpl_vars['curtpl']->value['website']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['curtpl']->value['website']['title'];?>
</a></td>
  </tr>
</table>


</div>

<hr size="1" noshade />

    
<?php if (isset($_smarty_tpl->tpl_vars['templates'])) {$_smarty_tpl->tpl_vars['templates'] = clone $_smarty_tpl->tpl_vars['templates'];
$_smarty_tpl->tpl_vars['templates']->value = $_smarty_tpl->tpl_vars['cbtpl']->value->get_templates(); $_smarty_tpl->tpl_vars['templates']->nocache = null; $_smarty_tpl->tpl_vars['templates']->scope = 0;
} else $_smarty_tpl->tpl_vars['templates'] = new Smarty_variable($_smarty_tpl->tpl_vars['cbtpl']->value->get_templates(), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['templates']->value) {?> 
    <h2>Available Templates</h2>
	   
    <div class="templates_container">
    <?php  $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['template']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['templates']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['template']->key => $_smarty_tpl->tpl_vars['template']->value) {
$_smarty_tpl->tpl_vars['template']->_loop = true;
?>
    <?php if ($_smarty_tpl->tpl_vars['selected']->value!=$_smarty_tpl->tpl_vars['template']->value['dir']) {?>
        <div class="template_box" align="center">
            <img src="<?php echo $_smarty_tpl->tpl_vars['cbtpl']->value->get_preview_thumb($_smarty_tpl->tpl_vars['template']->value['dir']);?>
" class="preview_thumb_template" >
    <div align="center" style="" class="tpl_title">
                <?php echo $_smarty_tpl->tpl_vars['template']->value['name'];?>
 <br /><em>by <strong><?php echo $_smarty_tpl->tpl_vars['template']->value['author'];?>
</strong></em><br />
            <a href="templates.php?change=<?php echo $_smarty_tpl->tpl_vars['template']->value['dir'];?>
">Activate This Template</a><br />
            <a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
?template=<?php echo $_smarty_tpl->tpl_vars['template']->value['dir'];?>
" target="_blank">Preview</a><br />
          <a href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
?template=<?php echo $_smarty_tpl->tpl_vars['template']->value['dir'];?>
&amp;set_template=yes" target="_blank">Preview &amp; Activate</a></div>
        </div>
    <?php }?>
    <?php } ?>
    </div>
<?php } else { ?>
	<div align="center">No New Template Found</div>
<?php }?>
<?php }} ?>
