<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:47:28
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/language_settings.html" */ ?>
<?php /*%%SmartyHeaderCode:200103647852d65930343f32-41590298%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6f158774f73f106dff21760e0ab49a13c713918' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/language_settings.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '200103647852d65930343f32-41590298',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'edit_lang' => 0,
    'language_list' => 0,
    'lang_details' => 0,
    'lang_phrases' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d659303b10b6_56232804',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d659303b10b6_56232804')) {function content_52d659303b10b6_56232804($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['edit_lang']->value!='yes') {?>
<h2>Language Settings</h2>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:15px">
  <tr>
    <td width="20" class="left_head" style="padding-left:5px">ID</td>
    <td width="100" align="left" class="head"><div class="head_sep_left">Default</div></td>
    <td class="head"><div class="head_sep_left" style="width:100px">Language</div></td>    
  </tr>
</table>

<form name="default_lang" method="post" action="?default">
<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['name'] = 'l_list';
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['language_list']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['l_list']['total']);
?>
<div class="row_white">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="20" height="25" valign="top" style="padding-left:5px"><?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
</td>
    <td width="100" align="left" valign="top"><label>
      <input type="radio" name="make_default" id="radio" value="<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_default']=='yes') {?>checked="checked"<?php }?> onclick="document.default_lang.submit()"/>
    </label></td>
    <td valign="top"><?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_name'];?>
(<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_code'];?>
)
      <div><a href="?create_package=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_code'];?>
">Recreate Pack</a>
       -  <a href="?recreate_from_pack=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_code'];?>
" >Recreate from pack</a>
        -  <a href="?edit_language=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
" >Edit Phrases</a>
        - <a href="?edit_language=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
">Edit</a>
        - <a href="javascript:void(0)" onclick="if(confirm_it('Are you sure you want to delete \'<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_name'];?>
\' pack')) window.location = '?delete=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
'">Delete</a>
        - <a href="?download=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
" target="_blank">Export</a><?php if ($_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_active']!='yes') {?>
        - <a href="?action=activate&id=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
">Activate</a><?php }?><?php if ($_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_active']=='yes') {?>
        - <a href="?action=deactivate&id=<?php echo $_smarty_tpl->tpl_vars['language_list']->value[$_smarty_tpl->getVariable('smarty')->value['section']['l_list']['index']]['language_id'];?>
">Dectivate</a><?php }?></div></td>
    </tr>
</table>
</div>
<?php endfor; endif; ?>  
</form>

<div style="height:50px"></div>
<h2> Add New Language </h2>
<form action="?import" method="post" enctype="multipart/form-data" name="import_language" id="import_language">
	<fieldset>
    	<legend>Upload file</legend>
        	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="block">
            <tr>
              <td>Browse ClipBucket Language File ( must be .xml format ) 
              <input type="file" name="lang_file" id="lang_file" />
              <input type="submit" name="add_language" id="add_language" value="Add Language" class="button" /></td>
            </tr>
            </table>
    </fieldset>
</form>
<?php } else { ?>
<span class="page_title">Edit <?php echo $_smarty_tpl->tpl_vars['lang_details']->value['language_name'];?>
</span>

<div class="search_box">  <form id="form1" name="form1" method="post" action="">
    <label>Language Name 
      <input name="name" type="text" id="name" value="<?php echo $_smarty_tpl->tpl_vars['lang_details']->value['language_name'];?>
" />
Language Code   </label>
    <input name="code" type="text" id="code" value="<?php echo $_smarty_tpl->tpl_vars['lang_details']->value['language_code'];?>
" />
Language Regex 
<input name="regex" type="text" id="regex" value="<?php echo $_smarty_tpl->tpl_vars['lang_details']->value['language_regex'];?>
" /> 
<input type="submit" name="update_language" class="button" value="Update"  style="margin-top:10px" id="update_language"/>
<br />
</form></div>
  
<div style="height:10px">

</div>
<span class="page_title">Editing <?php echo $_smarty_tpl->tpl_vars['lang_details']->value['language_name'];?>
 Phrases</span>

<div style="height:10px">

</div>


<div class="search_box">  <form id="form1" name="form1" method="get" action="?">
    <label>Phrase Code - Name<br />
<input name="varname" type="text" id="varname" value="<?php echo form_val($_GET['varname']);?>
" />
<input name="edit_language" type="hidden" value="<?php echo $_GET['edit_language'];?>
" />
    </label>
    <br />
    <label>Phrase Text<br />
<input name="text" type="text" id="text" value="<?php echo form_val($_GET['text']);?>
" />
    </label>
    <br />
    <label>
      <input type="submit" name="search_phrase" class="button" value="Submit"  style="margin-top:10px" id="search_phrase"/>
    </label>
  </form></div>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="5" class="left_head" ></td>
    <td width="200" class="head">Phrase Code</td>
    <td align="left" class="head">Phrase</td>
    <td width="200"  class="right_head"></td>
  </tr>
</table>

<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['name'] = 'p_list';
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['lang_phrases']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['p_list']['total']);
?>
<div class="row_white">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="5"  ></td>
    <td width="200" ><?php echo $_smarty_tpl->tpl_vars['lang_phrases']->value[$_smarty_tpl->getVariable('smarty')->value['section']['p_list']['index']]['varname'];?>
</td>
    <td align="left"><div class="edit_lang" id="<?php echo $_smarty_tpl->tpl_vars['lang_phrases']->value[$_smarty_tpl->getVariable('smarty')->value['section']['p_list']['index']]['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['lang_phrases']->value[$_smarty_tpl->getVariable('smarty')->value['section']['p_list']['index']]['text'];?>
</div></td>
    <td width="200" ></td>
  </tr>
</table>
</div>
<?php endfor; endif; ?>

<!-- DIsplaying Videos Ends-->
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/blocks/pagination.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<?php }?><?php }} ?>
