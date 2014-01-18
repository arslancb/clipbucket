<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 12:18:25
         compiled from "/var/www/clipbucket/styles/cb_2013/layout/global_header.html" */ ?>
<?php /*%%SmartyHeaderCode:21506813252d63641218953-43850209%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b48c26d27c8f5d3aa21c2ced95fbfcae51b5d9e1' => 
    array (
      0 => '/var/www/clipbucket/styles/cb_2013/layout/global_header.html',
      1 => 1389359118,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21506813252d63641218953-43850209',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'Cbucket' => 0,
    'baseurl' => 0,
    'theme' => 0,
    'cache_buster' => 0,
    'js' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d63641288797_71206046',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d63641288797_71206046')) {function content_52d63641288797_71206046($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/clipbucket/includes/smartyv3/plugins/modifier.date_format.php';
?><!DOCTYPE HTML>

<html>


<head>

    <?php if (in_dev()) {?>
        <?php if (isset($_smarty_tpl->tpl_vars['cache_buster'])) {$_smarty_tpl->tpl_vars['cache_buster'] = clone $_smarty_tpl->tpl_vars['cache_buster'];
$_smarty_tpl->tpl_vars['cache_buster']->value = microtime(true); $_smarty_tpl->tpl_vars['cache_buster']->nocache = null; $_smarty_tpl->tpl_vars['cache_buster']->scope = 0;
} else $_smarty_tpl->tpl_vars['cache_buster'] = new Smarty_variable(microtime(true), null, 0);?>
    <?php } else { ?>
        <?php if (isset($_smarty_tpl->tpl_vars['cache_buster'])) {$_smarty_tpl->tpl_vars['cache_buster'] = clone $_smarty_tpl->tpl_vars['cache_buster'];
$_smarty_tpl->tpl_vars['cache_buster']->value = $_smarty_tpl->tpl_vars['Cbucket']->value->version; $_smarty_tpl->tpl_vars['cache_buster']->nocache = null; $_smarty_tpl->tpl_vars['cache_buster']->scope = 0;
} else $_smarty_tpl->tpl_vars['cache_buster'] = new Smarty_variable($_smarty_tpl->tpl_vars['Cbucket']->value->version, null, 0);?>
    <?php }?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- ClipBucket v<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->version;?>
 -->
    <meta name="copyright" content="ClipBucket - Integrated Units 2007 - <?php echo smarty_modifier_date_format(time(),"%Y");?>
" />
    <meta name="author" content="Arslan Hassan - http://clip-bucket.com/arslan-hassan" />
    <meta name="author" content="Fawaz Tahir - http://clip-bucket.com/fawaz-tahir" />
    <link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/favicon.ico">
    <link rel="icon" type="image/ico" href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/favicon.ico" />

    <!-- RSS FEEDS -->
    <?php echo rss_feeds(array('link_tag'=>true),$_smarty_tpl);?>


    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['keywords'];?>
" />
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['description'];?>
" />
    <meta name="distribution" content="global" />


    <title><?php echo cbtitle(array(),$_smarty_tpl);?>
</title>


    <link href='http://fonts.googleapis.com/css?family=Rambla:400,700|Roboto:400,700' rel='stylesheet' type='text/css'>
    
    
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/css/bootstrap.min.css?cache=<?php echo $_smarty_tpl->tpl_vars['cache_buster']->value;?>
" />

    <?php if (!in_dev()) {?>
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/css/main.css?cache=<?php echo $_smarty_tpl->tpl_vars['cache_buster']->value;?>
" />
    <?php }?>

    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/css/font-awesome.min.css">


    <?php if (in_dev()) {?>
    <link rel="stylesheet/less" href="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/less/main.less?cache=<?php echo $_smarty_tpl->tpl_vars['cache_buster']->value;?>
" />
    <script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/less.min.js"></script>
    <?php }?>

    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/js/readmore.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/js/bootstrap.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/js/masonary.desandro.min.js"></script>


    <script>
        $(document).ready(function(){

            loading = '<i class="fa fa-spinner fa-spin"></i> <?php echo smarty_lang(array('code'=>"loading"),$_smarty_tpl);?>
...';
           /* var container = document.querySelector('.the_');
            var msnry = new Masonry( container, {
                // options
                
                itemSelector: '.the-item',
                stamp : '.ad-box'
                
            });
*/
//            less.watch({
//            poll : 300
//            });

        })

    </script>


    <?php echo include_header(array('file'=>'global_header'),$_smarty_tpl);?>


</head><?php }} ?>
