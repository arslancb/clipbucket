<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 12:18:25
         compiled from "/var/www/clipbucket/styles/cb_2013/layout/header.html" */ ?>
<?php /*%%SmartyHeaderCode:61572566252d63641399fd3-51495673%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47e34d0c3430920c257c90a2d10ca7cd782d2aed' => 
    array (
      0 => '/var/www/clipbucket/styles/cb_2013/layout/header.html',
      1 => 1389359118,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '61572566252d63641399fd3-51495673',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'website_title' => 0,
    'head_menu' => 0,
    'menu' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d636413f7e87_99253105',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d636413f7e87_99253105')) {function content_52d636413f7e87_99253105($_smarty_tpl) {?>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="navbar-container clearfix">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#khulja-sim-sim">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>


                <button type="button" class="navbar-toggle nav-toggle" data-toggle="collapse" data-target="#navbar-search-collapse">
                    <span class="glyphicon glyphicon-search"></span>
                </button>

                <a class="navbar-brand cb-logo" href="#"><?php echo $_smarty_tpl->tpl_vars['website_title']->value;?>
</a>
            </div>



                <?php if (isset($_smarty_tpl->tpl_vars['head_menu'])) {$_smarty_tpl->tpl_vars['head_menu'] = clone $_smarty_tpl->tpl_vars['head_menu'];
$_smarty_tpl->tpl_vars['head_menu']->value = cb_menu(); $_smarty_tpl->tpl_vars['head_menu']->nocache = null; $_smarty_tpl->tpl_vars['head_menu']->scope = 0;
} else $_smarty_tpl->tpl_vars['head_menu'] = new Smarty_variable(cb_menu(), null, 0);?>

                <div class="navbar-collapse navbar-sm-search collapse" id="navbar-search-collapse">
                    <form class="" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                        </div>
                    </form>
                </div>


                <ul class="nav navbar-nav main-nav navbar-collapse collapse" id="khulja-sim-sim">


                    <?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['head_menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['menu']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value) {
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menu']->iteration++;
?>
                        <?php if ($_smarty_tpl->tpl_vars['menu']->iteration<5) {?>
                            <li <?php if ($_smarty_tpl->tpl_vars['menu']->value['active']) {?>class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</a></li>
                        <?php }?>
                    <?php } ?>


                    <!-- Shown to small devices only Start  @todo : Add condition for logged in user -->

                    <li class="navbar-sm-login-links"><a href="#" data-toggle="modal" data-target="#login-modal"><?php echo smarty_lang(array('code'=>'login'),$_smarty_tpl);?>
</a></li>
                    <li class="navbar-sm-login-links"><a href="<?php echo cblink(array('name'=>"signup"),$_smarty_tpl);?>
"><?php echo smarty_lang(array('code'=>'Create new account'),$_smarty_tpl);?>
</a></li>
                    <!-- Ends -->

                    <?php if (count($_smarty_tpl->tpl_vars['head_menu']->value)>4) {?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
                        <ul class="dropdown-menu">

                            <?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['head_menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['menu']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value) {
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menu']->iteration++;
?>
                            <?php if ($_smarty_tpl->tpl_vars['menu']->iteration>4) {?>
                                <li <?php if ($_smarty_tpl->tpl_vars['menu']->value['active']) {?>class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['menu']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
</a></li>
                            <?php }?>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php }?>
                </ul>


                <!-- Shown to small devices only Start-->
                <form class="navbar-form navbar-search navbar-left" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Go!</button>
                        </span>
                    </div>

                </form>
                <!-- Ends -->


                <!-- Shown to Large displays only Start -->
                <ul class="nav navbar-nav navbar-right nav-login-btns">

                    <?php if (userid()) {?>
                    <li class="dropdown myaccount-dd">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img class="img-circle" src="http://lorempixel.com/output/people-q-c-40-40-7.jpg" /> Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </li>
                    <?php } else { ?>
                    <li>
                        <div>
                            <a href="<?php echo cblink(array('name'=>"signup"),$_smarty_tpl);?>
" class="btn btn-primary btn-success btn-sm"><?php echo smarty_lang(array('code'=>"Create Account"),$_smarty_tpl);?>
</a>
                            <a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-primary btn-sm"><?php echo smarty_lang(array('code'=>"Login"),$_smarty_tpl);?>
</a>
                        </div>

                    </li>
                    <?php }?>
                </ul>
                <!-- Ends -->


                <!-- SHown to middle size displays only  @todo : Add condition for logged in user -->
                <ul class="nav navbar-nav navbar-right nav-login-dd">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-search"></i></a>
                        <div class="dropdown-menu container">
                            <form>
                                <input class="form-control" placeholder="Search your stuff up!"/>
                            </form>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i></a>
                        <div class="dropdown-menu">
                            <form name="login-form" role="form" action="<?php echo cblink(array('name'=>'signup'),$_smarty_tpl);?>
" method="post">
                                <div class="form-group">
                                    <label for="login_username"><?php echo smarty_lang(array('code'=>"Username"),$_smarty_tpl);?>
</label>
                                    <input type="email" class="form-control" id="login_username" placeholder="Enter username">
                                </div>

                                <div class="form-group">
                                    <label for="login_password"><?php echo smarty_lang(array('code'=>"Password"),$_smarty_tpl);?>
</label>
                                    <input type="email" class="form-control" id="login_password" placeholder="Enter password">
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> <?php echo smarty_lang(array('code'=>"Remeber me"),$_smarty_tpl);?>

                                    </label>
                                </div>

                                <button class="btn btn-primary btn-sm btn-block"><?php echo smarty_lang(array('code'=>"Login"),$_smarty_tpl);?>
</button>
                                <a href="<?php echo cblink(array('name'=>'signup'),$_smarty_tpl);?>
" class="btn btn-primary btn-sm btn-block"><?php echo smarty_lang(array('code'=>"Create new accoutn"),$_smarty_tpl);?>
</a>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Ends -->


    </nav>

<script>
    $('.nav-login-dd li .dropdown-menu').click(function(e){
        e.preventDefault();
        return false;
    })
</script>


<div class="modal fade" id="login-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form name="login-form" role="form" action="<?php echo cblink(array('name'=>'signup'),$_smarty_tpl);?>
" method="post">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo smarty_lang(array('code'=>"Login"),$_smarty_tpl);?>
</h4>
            </div>
            <div class="modal-body">

                    <div class="form-group">
                        <label for="login_username"><?php echo smarty_lang(array('code'=>"Username"),$_smarty_tpl);?>
</label>
                        <input type="text" class="form-control" id="login_username" placeholder="Enter username">
                    </div>

                    <div class="form-group">
                        <label for="login_password"><?php echo smarty_lang(array('code'=>"Password"),$_smarty_tpl);?>
</label>
                        <input type="password" class="form-control" id="login_password" placeholder="Enter password">
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> <?php echo smarty_lang(array('code'=>"Remember me"),$_smarty_tpl);?>

                        </label>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo smarty_lang(array('code'=>"Cancel"),$_smarty_tpl);?>
</button>
                <button type="submit" class="btn btn-primary"><?php echo smarty_lang(array('code'=>"Login"),$_smarty_tpl);?>
</button>
            </div>
        </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --><?php }} ?>
