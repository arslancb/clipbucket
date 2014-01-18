<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 12:18:25
         compiled from "/var/www/clipbucket/styles/cb_2013/layout/footer.html" */ ?>
<?php /*%%SmartyHeaderCode:94303819352d6364141ece7-90176136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c5bd419a74ee86a740e2641d9f8ab800a1232d11' => 
    array (
      0 => '/var/www/clipbucket/styles/cb_2013/layout/footer.html',
      1 => 1389359118,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '94303819352d6364141ece7-90176136',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'Cbucket' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d63641422645_63823947',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d63641422645_63823947')) {function content_52d63641422645_63823947($_smarty_tpl) {?>
<footer>
    <div id="footer" class="row">
        <div class="col-md-6">
            <ul>
                <li><a href="#">ClipBucket</a></li>
                <li><a href="#">Good Page</a></li>
                <li><a href="#">Bad Page</a></li>
                <li><a href="#">Faqs Page</a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul class="pull-right">
                <li><a href="#">Terms of Use</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Bad Page</a></li>
                <li><a href="#">&copy; 2013 Clipbucket.com</a></li>
            </ul>
        </div>
    </div>
</footer>
<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->footer();?>
<?php }} ?>
