<?php /* Smarty version 2.6.18, created on 2014-01-17 12:51:38
         compiled from /var/www/clipbucket/styles/cbv2new/layout/upload.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/upload.html', 1, false),array('function', 'load_form', '/var/www/clipbucket/styles/cbv2new/layout/upload.html', 28, false),array('function', 'link', '/var/www/clipbucket/styles/cbv2new/layout/upload.html', 56, false),array('function', 'AD', '/var/www/clipbucket/styles/cbv2new/layout/upload.html', 70, false),array('modifier', 'sprintf', '/var/www/clipbucket/styles/cbv2new/layout/upload.html', 58, false),)), $this); ?>
<?php echo smarty_lang(array('code' => 'vdo_upload_step2','assign' => 'vdo_upload_step'), $this);?>

<div class="upload_left">

	<!-- STEP 1 - UPLOADING VIDEO DETAILS -->

	<?php if ($this->_tpl_vars['step'] == '1'): ?>

    <?php $this->assign('no_upload', 'yes'); ?>
    <?php $this->assign('opt_list', $this->_tpl_vars['Upload']->load_upload_options()); ?> 
	<ul class="upload_opts clearfix">
    <?php $_from = $this->_tpl_vars['opt_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['opt']):
?>
    
    	    	<?php $this->assign('uploadOptId', $this->_tpl_vars['opt']['load_func']); ?>
        <?php if ($this->_tpl_vars['Cbucket']->configs[$this->_tpl_vars['uploadOptId']] == 'yes'): ?>
        	<?php $this->assign('no_upload', 'no'); ?>
			<li class="upload_opt_head moveL <?php echo $this->_tpl_vars['divid']; ?>
" onclick="show_menu('<?php echo $this->_tpl_vars['divid']; ?>
',false)"><?php echo $this->_tpl_vars['opt']['title']; ?>
</li>
        <?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
    </ul>
    <div class="clear"></div>   
    <div class="uploadFormContent">      
    <?php $_from = $this->_tpl_vars['opt_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['divid'] => $this->_tpl_vars['opt']):
?>     
    	    	<?php $this->assign('uploadOptId', $this->_tpl_vars['opt']['load_func']); ?>
        <?php if ($this->_tpl_vars['Cbucket']->configs[$this->_tpl_vars['uploadOptId']] == 'yes'): ?> 
        <div class="upload_opt" id="<?php echo $this->_tpl_vars['divid']; ?>
" style="display:none">
			<?php echo load_form(array('name' => $this->_tpl_vars['opt']['load_func'],'button_class' => 'cb_button_2','class' => 'upload_form'), $this);?>
 
        </div>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?> 
    
    <?php if ($this->_tpl_vars['no_upload'] == 'yes'): ?>
    	<div style="background-color:#FFEAEB; font-size:13pt; padding:5px">
        	<?php echo smarty_lang(array('code' => 'no_upload_opt'), $this);?>

        </div>
    <?php endif; ?>
    </div>
    <?php echo '
    <script>
		
		$(document).ready(function() { 
		var formToLoad = $(\'.uploadFormContent .upload_opt:first-child\').attr(\'id\');
		show_menu(formToLoad,true) })
	</script>
	'; ?>

    <?php endif; ?>
    
    
    <!-- STEP 1 ENDS HERE -->
    
    <!-- STEP 3 - VIEWING SUCCESSFULL PAGE -->
    <?php if ($this->_tpl_vars['step'] == 3): ?>
    <div class="upload_info">
        <?php echo smarty_lang(array('code' => 'video_complete_msg','assign' => 'video_complete_msg'), $this);?>

        <?php echo cblink(array('name' => 'upload','assign' => 'uploadlink'), $this);?>

        <?php echo cblink(array('name' => 'my_videos','assign' => 'myvidslink'), $this);?>

        <?php echo ((is_array($_tmp=$this->_tpl_vars['video_complete_msg'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['myvidslink'], $this->_tpl_vars['uploadlink'], $this->_tpl_vars['myvidslink']) : sprintf($_tmp, $this->_tpl_vars['myvidslink'], $this->_tpl_vars['uploadlink'], $this->_tpl_vars['myvidslink'])); ?>
    
    </div>
    <?php endif; ?>
    <!-- STEP 3 ENDS HERE -->
    
</div>

<div class="upload_right">
    <div class="instructions">
       <?php echo smarty_lang(array('code' => 'upload_right_guide'), $this);?>

    </div>
    <div style="height:10px"></div>
  <?php echo getAd(array('place' => 'ad_300x250'), $this);?>

</div>
<div class="clearfix"></div>