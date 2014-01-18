<?php /* Smarty version 2.6.18, created on 2014-01-17 12:40:29
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/user.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', '/var/www/clipbucket/styles/cbv2new/layout/blocks/user.html', 6, false),array('modifier', 'nicetime', '/var/www/clipbucket/styles/cbv2new/layout/blocks/user.html', 10, false),array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/user.html', 6, false),array('function', 'avatar', '/var/www/clipbucket/styles/cbv2new/layout/blocks/user.html', 30, false),)), $this); ?>
<?php if ($this->_tpl_vars['block_type'] == '' || $this->_tpl_vars['block_type'] == 'normal'): ?>
<div class="user_block" id="user-<?php echo $this->_tpl_vars['user']['userid']; ?>
">
<div class="thumb_container" ><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
"><img src="<?php echo $this->_tpl_vars['userquery']->getuserthumb($this->_tpl_vars['user']); ?>
" border="0" class="user_thumb_normal"></a>
</div>
<div class="prof_title"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
"><?php echo $this->_tpl_vars['user']['username']; ?>
</a></div>
<?php if (isSectionEnabled ( 'videos' )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['user']['total_videos'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 <?php echo smarty_lang(array('code' => 'videos'), $this);?>
<?php endif; ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['user']['profile_hits'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 <?php echo smarty_lang(array('code' => 'views'), $this);?>
<br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['user']['subscribers'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 <?php echo smarty_lang(array('code' => 'subscribers'), $this);?>
 
<?php if (isSectionEnabled ( 'photos' )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['user']['total_photos'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 <?php echo smarty_lang(array('code' => 'photos'), $this);?>
<?php endif; ?>
<br />
<?php echo smarty_lang(array('code' => 'last_active'), $this);?>
 : <?php echo ((is_array($_tmp=$this->_tpl_vars['user']['last_active'])) ? $this->_run_mod_handler('nicetime', true, $_tmp) : nicetime($_tmp)); ?>
 </div>
<?php endif; ?>

<?php if ($this->_tpl_vars['block_type'] == 'small'): ?>
<div class="user_block_small" id="user-<?php echo $this->_tpl_vars['user']['userid']; ?>
" align="center">
<div><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
"><?php echo $this->_tpl_vars['user']['username']; ?>
</a></div>
<a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
"><img src="<?php echo $this->_tpl_vars['userquery']->getuserthumb($this->_tpl_vars['user']); ?>
" border="0" class="user_mid_thumb"></a>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['block_type'] == 'medium'): ?>
<div class="user_block_med" id="user-<?php echo $this->_tpl_vars['user']['userid']; ?>
">
<div class="thumb_container_medium" ><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
"><img src="<?php echo $this->_tpl_vars['userquery']->getuserthumb($this->_tpl_vars['user']); ?>
" border="0" class="user_thumb_medium"></a>
</div>
<div class="prof_title"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
"><?php echo $this->_tpl_vars['user']['username']; ?>
</a></div>
<?php echo ((is_array($_tmp=$this->_tpl_vars['user']['total_videos'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 <?php echo smarty_lang(array('code' => 'videos'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['user']['profile_hits'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 <?php echo smarty_lang(array('code' => 'views'), $this);?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['block_type'] == 'friends'): ?>
	<div style="float:left; margin:0 0px 2px 0;" id="user-<?php echo $this->_tpl_vars['user']['userid']; ?>
">
    	<a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
" title="<?php echo $this->_tpl_vars['user']['username']; ?>
"><img src="<?php echo avatar(array('details' => $this->_tpl_vars['user'],'size' => 'small'), $this);?>
" alt="<?php echo $this->_tpl_vars['user']['username']; ?>
" style="padding:1px; vertical-align:middle;" /></a>
    </div> <!-- friend_<?php echo $this->_tpl_vars['user']['userid']; ?>
 end -->
<?php endif; ?>

<?php if ($this->_tpl_vars['block_type'] == 'topic_view'): ?>
	<div class="topicStarterAvatar moveL">
		<i class="topicStarterAvatarBG" style="background:url(<?php echo avatar(array('details' => $this->_tpl_vars['user']), $this);?>
) center no-repeat; width:56px; height:56px;"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
"><?php echo $this->_tpl_vars['user']['username']; ?>
</a></i>
    </div>
<?php endif; ?>