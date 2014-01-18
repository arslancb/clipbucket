<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:46:42
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/footer.html" */ ?>
<?php /*%%SmartyHeaderCode:168784784352d659027e2b16-30025043%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6680d7cb687b092aff005765a33b0dd9d3682913' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/footer.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168784784352d659027e2b16-30025043',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'imageurl' => 0,
    'Cbucket' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d659027ec105_17367852',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d659027ec105_17367852')) {function content_52d659027ec105_17367852($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/clipbucket/includes/smartyv3/plugins/modifier.date_format.php';
?><div style="height:10px"></div>
<div class="footer_grey_bar">
	<img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/dot.gif" class="cbicon" /> Thanks for using ClipBucket | &copy; Copyright 2007 &#8211; <?php echo smarty_modifier_date_format(time(),"%Y");?>

<div style="float:right" align="right">
<em><?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['version'];?>
</em>| <a href="http://forums.clip-bucket.com/">Forums</a> | <a href="http://clip-bucket.com/arslan-hassan">Arslan Hassan</a> | <a href="http://docs.clip-bucket.com/">Docs</a> | <a href="http://www.opensource.org/licenses/attribution.php">Attribution Assurance License</a></div>

</div><?php }} ?>
