<?php /* Smarty version 2.6.18, created on 2014-01-17 13:57:00
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'videoLink', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html', 6, false),array('function', 'getThumb', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html', 7, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html', 7, false),array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html', 13, false),array('modifier', 'SetTime', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html', 8, false),array('modifier', 'truncate', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html', 12, false),array('modifier', 'number_format', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/video_box.html', 13, false),)), $this); ?>

<!-- Video Box -->
<?php if ($this->_tpl_vars['display_type'] == 'normal' || $this->_tpl_vars['display_type'] == ''): ?>
<div class="watch_video_box">
    <div class="watch_video_box_thumb" align="center"> 
        <a href="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['video']), $this);?>
" alt="<?php echo $this->_tpl_vars['video']['title']; ?>
" title="<?php echo $this->_tpl_vars['video']['title']; ?>
">
        <img src="<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['video']), $this);?>
" width="82" height="48" border="0" <?php echo ANCHOR(array('place' => 'video_thumb','data' => $this->_tpl_vars['video']), $this);?>
 /></a>
        <div class="duration"><?php echo ((is_array($_tmp=$this->_tpl_vars['video']['duration'])) ? $this->_run_mod_handler('SetTime', true, $_tmp) : SetTime($_tmp)); ?>
</div>
        <img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="add_icon" onclick="add_quicklist(this,'<?php echo $this->_tpl_vars['video']['videoid']; ?>
')" title="add <?php echo $this->_tpl_vars['video']['title']; ?>
 to qucklist" alt="quicklist">
    </div> 
   	<div class="watch_video_box_details">
    <a href="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['video']), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['video']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30) : smarty_modifier_truncate($_tmp, 30)); ?>
</a><br>
<?php echo smarty_lang(array('code' => 'views'), $this);?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['video']['views'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
<br>

<a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['video']); ?>
" title="<?php echo $this->_tpl_vars['video']['username']; ?>
" alt="<?php echo $this->_tpl_vars['video']['username']; ?>
"><?php echo $this->_tpl_vars['video']['username']; ?>
</a>

</div>
    <!--VIDEO_THUMB END-->  
</div>
<?php endif; ?>
<!-- Video Box -->