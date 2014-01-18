<?php /* Smarty version 2.6.18, created on 2014-01-17 12:51:38
         compiled from /var/www/clipbucket/styles/global/upload_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/global/upload_form.html', 2, false),array('modifier', 'sprintf', '/var/www/clipbucket/styles/global/upload_form.html', 12, false),)), $this); ?>
<div style="font-size:13px; padding:5px">
	<?php echo smarty_lang(array('code' => 'upload_desktop_msg'), $this);?>

</div>

<div style="border:1px solid #CCC; padding:5px; background-color:#FFF; display:inline-block; float:left; width:350px; padding:30px 0px;" align="center">
	<input id="file_uploads" name="file_uploads" type="file" />
</div>
<div style="display:inline-block; padding-left:10px">
    <?php echo smarty_lang(array('code' => 'upload_videos_can_be','assign' => 'uploadVidCanBe'), $this);?>

    <?php $this->assign('allowedSize', $this->_tpl_vars['Cbucket']->configs['max_upload_size']); ?>
    <?php $this->assign('allowedDuration', $this->_tpl_vars['Cbucket']->configs['max_video_duration']); ?>
    <?php echo ((is_array($_tmp=$this->_tpl_vars['uploadVidCanBe'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, ($this->_tpl_vars['allowedSize'])." MB", ($this->_tpl_vars['allowedDuration'])." minutes") : sprintf($_tmp, ($this->_tpl_vars['allowedSize'])." MB", ($this->_tpl_vars['allowedDuration'])." minutes")); ?>

</div>
<div class="clear"></div>

<div id="fileUploadQueue"></div>