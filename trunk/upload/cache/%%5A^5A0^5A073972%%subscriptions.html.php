<?php /* Smarty version 2.6.18, created on 2014-01-10 19:00:19
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/subscriptions.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/subscriptions.html', 3, false),array('function', 'AD', '/var/www/clipbucket/styles/cbv2new/layout/blocks/subscriptions.html', 37, false),)), $this); ?>
<?php if (userid ( )): ?>
<div class="main_vids clearfix" style="border-top:1px solid #CCC">
	<span class="subsription"><a href="<?php echo $this->_tpl_vars['baseurl']; ?>
/edit_account.php?mode=subscriptions"><?php echo smarty_lang(array('code' => 'subscriptions'), $this);?>
</a></span>
  
   <div>
   <!-- Listing Subscriptions-->
      <?php $this->assign('subs_uploads', $this->_tpl_vars['userquery']->getSubscriptionsUploadsWeek($this->_tpl_vars['userquery']->userid,10)); ?>
   <?php if ($this->_tpl_vars['subs_uploads']): ?>
   	<?php $_from = $this->_tpl_vars['subs_uploads']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['item']):
?>
    	<?php if ($this->_tpl_vars['type'] == 'videos'): ?>
        	<div class="clearfix">
                <h2 style="font-size:12px; padding:0 5px 5px; color:#333; margin-bottom:5px; border-bottom:1px solid #e8e8e8"><?php echo $this->_tpl_vars['item']['title']; ?>
 (<?php echo $this->_tpl_vars['item']['total']; ?>
 <?php echo $this->_tpl_vars['type']; ?>
)</h2>
                <?php $_from = $this->_tpl_vars['item']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['videos']):
?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/video.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['videos'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php endforeach; endif; unset($_from); ?>
            </div>        
        <?php endif; ?>
    	<?php if ($this->_tpl_vars['type'] == 'photos'): ?>
        	<div class="clearfix">
                <h2 style="font-size:12px; padding:0 5px 5px; color:#333; margin-bottom:5px; border-bottom:1px solid #e8e8e8"><?php echo $this->_tpl_vars['item']['title']; ?>
 (<?php echo $this->_tpl_vars['item']['total']; ?>
 <?php echo $this->_tpl_vars['type']; ?>
)</h2>
                <?php $_from = $this->_tpl_vars['item']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['photos']):
?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/photo.html", 'smarty_include_vars' => array('photo' => $this->_tpl_vars['photos'],'display_type' => 'subscription')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php endforeach; endif; unset($_from); ?>
            </div>
		<?php endif; ?>	
    <?php endforeach; endif; unset($_from); ?>
   <?php else: ?>
   <em><?php echo smarty_lang(array('code' => 'no_new_subs_video'), $this);?>
</em>
   <?php endif; ?>
   <!-- End Listing Subscriptions -->
   </div>
    

</div>
<div class="feature_shadow" ></div>
  <div class="ad"><?php echo getAd(array('place' => 'ad_468x60'), $this);?>
</div>
<?php endif; ?>