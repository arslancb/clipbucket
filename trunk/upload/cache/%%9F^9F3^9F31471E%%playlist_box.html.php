<?php /* Smarty version 2.6.18, created on 2014-01-17 13:57:00
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/playlist_box.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'videoLink', '/var/www/clipbucket/styles/cbv2new/layout/blocks/watch_video/playlist_box.html', 27, false),)), $this); ?>
<?php if ($this->_tpl_vars['cb_playlist'] == ''): ?>
    <?php $this->assign('cb_playlist', $this->_tpl_vars['cbvid']->action->get_playlist($_GET['play_list'])); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['cb_playlist']): ?>
<span class="watch_vids_head" onclick='$(this).toggleClass("watch_vids_head_closed");$("#my_playlist").slideToggle("fast")'>Playlist : <?php echo $this->_tpl_vars['cb_playlist']['playlist_name']; ?>
</span>
<div class="watch_vids_cont" id="my_playlist">
<div align="right"><span class="auto_play"><a href="javascript:void(0)" onclick="swap_auto_play()">Auto Play is <span id="ap_status"><?php if ($_COOKIE['auto_play_playlist'] != 'true'): ?>off<?php else: ?>on<?php endif; ?></span></a></span></div>
<!-- Getting Playlist Items-->
<?php if ($this->_tpl_vars['cb_playlist_items'] == ''): ?>
<?php $this->assign('cb_playlist_items', $this->_tpl_vars['cbvid']->get_playlist_items($this->_tpl_vars['cb_playlist']['playlist_id'])); ?>
<?php endif; ?>
<?php $this->assign('bg', 'fff'); ?>
<?php unset($this->_sections['item']);
$this->_sections['item']['name'] = 'item';
$this->_sections['item']['loop'] = is_array($_loop=$this->_tpl_vars['cb_playlist_items']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['item']['show'] = true;
$this->_sections['item']['max'] = $this->_sections['item']['loop'];
$this->_sections['item']['step'] = 1;
$this->_sections['item']['start'] = $this->_sections['item']['step'] > 0 ? 0 : $this->_sections['item']['loop']-1;
if ($this->_sections['item']['show']) {
    $this->_sections['item']['total'] = $this->_sections['item']['loop'];
    if ($this->_sections['item']['total'] == 0)
        $this->_sections['item']['show'] = false;
} else
    $this->_sections['item']['total'] = 0;
if ($this->_sections['item']['show']):

            for ($this->_sections['item']['index'] = $this->_sections['item']['start'], $this->_sections['item']['iteration'] = 1;
                 $this->_sections['item']['iteration'] <= $this->_sections['item']['total'];
                 $this->_sections['item']['index'] += $this->_sections['item']['step'], $this->_sections['item']['iteration']++):
$this->_sections['item']['rownum'] = $this->_sections['item']['iteration'];
$this->_sections['item']['index_prev'] = $this->_sections['item']['index'] - $this->_sections['item']['step'];
$this->_sections['item']['index_next'] = $this->_sections['item']['index'] + $this->_sections['item']['step'];
$this->_sections['item']['first']      = ($this->_sections['item']['iteration'] == 1);
$this->_sections['item']['last']       = ($this->_sections['item']['iteration'] == $this->_sections['item']['total']);
?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/quicklist/video_block.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['cb_playlist_items'][$this->_sections['item']['index']],'selected' => $this->_tpl_vars['selected'],'bg' => $this->_tpl_vars['bg'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    
    <?php if ($this->_tpl_vars['bg'] == 'fff'): ?>
    	<?php $this->assign('bg', 'EFF5F8'); ?>
    <?php else: ?>
    	<?php $this->assign('bg', 'fff'); ?>
    <?php endif; ?>
    
    <?php if ($this->_tpl_vars['selected'] == $this->_tpl_vars['cb_playlist_items'][$this->_sections['item']['index']]['videoid']): ?>
    	<?php $this->assign('pre_lock', 'yes'); ?>
    <?php else: ?>
		<?php if (! $this->_tpl_vars['pre_lock']): ?>
			<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['cb_playlist_items'][$this->_sections['item']['index']],'assign' => 'pre_vid'), $this);?>

		<?php else: ?>
			<?php if (! $this->_tpl_vars['next_lock']): ?>
				 <?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['cb_playlist_items'][$this->_sections['item']['index']],'assign' => 'next_vid'), $this);?>

				<?php $this->assign('next_lock', 'yes'); ?>
			<?php endif; ?>
		<?php endif; ?>
    <?php endif; ?>
<?php endfor; endif; ?>
<div class="clearfix"></div>
</div>
<?php endif; ?>

<script type="text/javascript">
pre_item='<?php echo $this->_tpl_vars['pre_vid']; ?>
';
next_item='<?php echo $this->_tpl_vars['next_vid']; ?>
';
</script>