<?php /* Smarty version 2.6.18, created on 2014-01-15 15:32:41
         compiled from /var/www/clipbucket/styles/cbv2new/layout/signup.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/signup.html', 7, false),array('function', 'link', '/var/www/clipbucket/styles/cbv2new/layout/signup.html', 10, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/signup.html', 18, false),array('function', 'load_captcha', '/var/www/clipbucket/styles/cbv2new/layout/signup.html', 90, false),array('modifier', 'sprintf', '/var/www/clipbucket/styles/cbv2new/layout/signup.html', 11, false),)), $this); ?>
<?php $this->assign('required_fields', $this->_tpl_vars['userquery']->load_signup_fields()); ?>
<?php $this->assign('custom_field', $this->_tpl_vars['userquery']->custom_signup_fields); ?>

<?php if ($this->_tpl_vars['mode'] == 'signup_success'): ?>
<div class="simple_container">
    	<?php if ($this->_tpl_vars['udetails']['usr_status'] != 'Ok'): ?>
        	<?php echo smarty_lang(array('code' => 'signup_success_usr_ok'), $this);?>

    	<?php else: ?>
    		<?php echo smarty_lang(array('code' => 'signup_success_usr_emailverify','assign' => 'signupsuccessusremailverify'), $this);?>

            <?php echo cblink(array('name' => 'login','assign' => 'login_link'), $this);?>

            <?php echo ((is_array($_tmp=$this->_tpl_vars['signupsuccessusremailverify'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['login_link']) : sprintf($_tmp, $this->_tpl_vars['login_link'])); ?>

<?php endif; ?> </div>
<?php else: ?>    
<div class="signup_left">
	<h2><?php echo smarty_lang(array('code' => 'user_mem_login'), $this);?>
</h2>
	<?php echo smarty_lang(array('code' => 'if_you_already_hv_account'), $this);?>

	<div class="signup_container">
    	<form name="login_form" id="login_form" method="post" action="" <?php echo ANCHOR(array('place' => 'cb_login_form_tag'), $this);?>
>
        
       	  <label for="login_username" class="label"><?php echo smarty_lang(array('code' => 'username'), $this);?>
 : </label>
            <div class="input_container">
        		<input name="username" type="text" id="cb_login_username" size="30" >
            </div>
            <div class="clearfix"></div>
          <label for="login_password" class="label"><?php echo smarty_lang(array('code' => 'password'), $this);?>
 : </label>
            <div class="input_container">
            <input name="password" type="password" id="cb_login_password" size="30" >
            </div>
            <div class="clearfix"></div>
            <label for="" class="label">&nbsp;</label>
            <div class="input_container">
            <?php echo ANCHOR(array('place' => 'before_login_button'), $this);?>

            <input type="submit" name="login" class="cb_button_2" value="<?php echo smarty_lang(array('code' => 'login'), $this);?>
" id="login_signup_bttn" <?php echo ANCHOR(array('place' => 'login_signup_bttn'), $this);?>
 >
            <?php echo ANCHOR(array('place' => 'after_login_button'), $this);?>

            </div>
            <div class="clear"></div>
            <div align="center"><a href="<?php echo $this->_tpl_vars['baseurl']; ?>
/forgot.php"><?php echo smarty_lang(array('code' => 'user_forgot_password'), $this);?>
</a> | <a href="<?php echo $this->_tpl_vars['baseurl']; ?>
/forgot.php?mode=recover_username"><?php echo smarty_lang(array('code' => 'user_forgot_username'), $this);?>
</a></div>
            
            <?php echo ANCHOR(array('place' => 'login_form'), $this);?>

           
        </form>        
        
  </div>
    
   <?php echo smarty_lang(array('code' => 'signup_message_under_login'), $this);?>

</div>
<div class="signup_right">
	<h2><?php echo smarty_lang(array('code' => 'new_mems_signup_here'), $this);?>
</h2>
    <?php echo smarty_lang(array('code' => 'register_as_our_website_member'), $this);?>

    <div class="signup_container">
    	<form name="login_form" id="login_form" method="post" action="" >
        	<?php $_from = $this->_tpl_vars['required_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
            	<label for="<?php echo $this->_tpl_vars['field']['id']; ?>
" class="label"><?php echo $this->_tpl_vars['field']['title']; ?>
</label>
                <div class="input_container">
                <?php if ($this->_tpl_vars['field']['hint_1']): ?>
                <div class="hint"><?php echo $this->_tpl_vars['field']['hint_1']; ?>
</div>
                <?php endif; ?>
                <?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_before']), $this);?>
<?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['field']); ?>
<?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_after']), $this);?>

                <?php if ($this->_tpl_vars['field']['hint_2']): ?>
                <div class="hint"><?php echo $this->_tpl_vars['field']['hint_2']; ?>
</div>
                <?php endif; ?>
                </div>
                <div class="clearfix"></div>
               
                
            <?php endforeach; endif; unset($_from); ?> 
            
            <!-- Loading Custom Fields -->
            <?php $_from = $this->_tpl_vars['custom_field']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
            	<label for="<?php echo $this->_tpl_vars['field']['id']; ?>
" class="label"><?php echo $this->_tpl_vars['field']['title']; ?>
</label>
                <div class="input_container">
                <?php if ($this->_tpl_vars['field']['hint_1']): ?>
                <div class="hint"><?php echo $this->_tpl_vars['field']['hint_1']; ?>
</div>
                <?php endif; ?>
                <?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_before']), $this);?>
<?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['field']); ?>
<?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_after']), $this);?>

                <?php if ($this->_tpl_vars['field']['hint_2']): ?>
                <div class="hint"><?php echo $this->_tpl_vars['field']['hint_2']; ?>
</div>
                <?php endif; ?>
                </div>
                <div class="clearfix"></div>
               
                
            <?php endforeach; endif; unset($_from); ?> 
            
            <!-- Loading Captcha if anny -->
            <?php $this->assign('captcha', get_captcha()); ?>
            <?php if ($this->_tpl_vars['captcha']): ?> 
                <?php if ($this->_tpl_vars['captcha']['show_field']): ?>
                    <label class="label" for="verification_code">Verification Code</label>
                        <?php echo load_captcha(array('captcha' => $this->_tpl_vars['captcha'],'load' => 'field','field_params' => ' id="verification_code" '), $this);?>

                   <div class="clearfix"></div>
                <?php endif; ?>

                	<div align="center"><?php echo load_captcha(array('captcha' => $this->_tpl_vars['captcha'],'load' => 'function'), $this);?>
</div>

            <?php endif; ?>
            <div class="clear"></div>
            <div align="center">
                <input name="agree" type="checkbox" id="agree" value="yes" checked="checked" />
                <?php echo smarty_lang(array('code' => 'user_i_agree_to_the','assign' => 'user_i_agree_to_the'), $this);?>

                - <?php echo ((is_array($_tmp=$this->_tpl_vars['user_i_agree_to_the'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['cbpage']->get_page_link(3), $this->_tpl_vars['cbpage']->get_page_link(2)) : sprintf($_tmp, $this->_tpl_vars['cbpage']->get_page_link(3), $this->_tpl_vars['cbpage']->get_page_link(2))); ?>
</a>
          </div>
            <label for="" class="label">&nbsp;</label>
            <div class="input_container">
            <input type="submit" name="signup" class="cb_button_2" value="<?php echo smarty_lang(array('code' => 'signup'), $this);?>
" style="margin-top:10px" />
            </div>
            <div class="clearfix"></div>
            <?php echo ANCHOR(array('place' => 'signup_form'), $this);?>

        </form>
    </div>
</div>
<?php endif; ?>    
<div class="clearfix"></div>