<?php /* Smarty version 2.6.18, created on 2014-01-17 13:17:33
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/upload/form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'sprintf', '/var/www/clipbucket/styles/cbv2new/layout/blocks/upload/form.html', 5, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/blocks/upload/form.html', 30, false),array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/upload/form.html', 74, false),)), $this); ?>
<?php $this->assign('video_fields', $this->_tpl_vars['Upload']->load_video_fields($this->_tpl_vars['input'])); ?>


<div class="upload_info" id="upload_form<?php echo $this->_tpl_vars['objId']; ?>
" style="margin-top:10px;">
    <h2><?php echo ((is_array($_tmp=$this->_tpl_vars['vdo_upload_step'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, 1) : sprintf($_tmp, 1)); ?>
</h2>
    <form name="upload_form" class="upload_form" id="uploadForm<?php echo $this->_tpl_vars['objId']; ?>
" method="post" action="" onSubmit="return false">



    <?php $_from = $this->_tpl_vars['video_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field_group']):
?>
     <fieldset>
      	          <?php if ($this->_tpl_vars['field_group']['group_id'] != 'required_fields'): ?> 
            <span class="form_head" 
                onclick="$('#more_fields_<?php echo $this->_tpl_vars['field_group']['group_id']; ?>
<?php echo $this->_tpl_vars['objId']; ?>
').slideToggle()"
                    style="cursor:pointer"><?php echo $this->_tpl_vars['field_group']['group_name']; ?>
</span>
            <div id="more_fields_<?php echo $this->_tpl_vars['field_group']['group_id']; ?>
<?php echo $this->_tpl_vars['objId']; ?>
" style="display:none">
          <?php else: ?>
            <span class="form_head"><?php echo $this->_tpl_vars['field_group']['group_name']; ?>
</span>
          <?php endif; ?>
    
        
           <div <?php if ($this->_tpl_vars['field_group']['group_id'] == 'required_fields'): ?> style="width:400px; float:left"<?php endif; ?>>
            <?php $_from = $this->_tpl_vars['field_group']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
            
            <?php if ($this->_tpl_vars['field']['name'] != 'category[]'): ?>
                <div class="field" id="field-<?php echo $this->_tpl_vars['field']['id']; ?>
">
                <label for="<?php echo $this->_tpl_vars['field']['id']; ?>
" class="label"><?php echo $this->_tpl_vars['field']['title']; ?>
</label>
                <?php if ($this->_tpl_vars['field']['hint_1']): ?><div class="hint"><?php echo $this->_tpl_vars['field']['hint_1']; ?>
</div><br><?php endif; ?>
                <?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_before']), $this);?>

                <?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['field']); ?>

				<?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_after']), $this);?>

                <?php if ($this->_tpl_vars['field']['hint_2']): ?><br>
                <div class="hint"><?php echo $this->_tpl_vars['field']['hint_2']; ?>
</div><?php endif; ?>
                </div>
            <?php else: ?>
                <?php $this->assign('category_array', $this->_tpl_vars['field']); ?>
            <?php endif; ?>
            
            <?php endforeach; endif; unset($_from); ?>
            </div>
            
                        <?php if ($this->_tpl_vars['category_array']): ?>
            <div style="float:left; width:200px; margin-left:10px;">
                <div class="field">
                <label for="<?php echo $this->_tpl_vars['field']['id']; ?>
" class="label"><?php echo $this->_tpl_vars['category_array']['title']; ?>
</label>
                <?php if ($this->_tpl_vars['category_array']['hint_1']): ?><div class="hint"><?php echo $this->_tpl_vars['category_array']['hint_1']; ?>
</div><br><?php endif; ?>
                <?php echo ANCHOR(array('place' => $this->_tpl_vars['category_array']['anchor_before']), $this);?>

                <div style="overflow:auto; height:100%">
                    <?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['category_array']); ?>

                </div>
				<?php echo ANCHOR(array('place' => $this->_tpl_vars['category_array']['anchor_after']), $this);?>

                <?php if ($this->_tpl_vars['category_array']['hint_2']): ?><br>
                <div class="hint"><?php echo $this->_tpl_vars['category_array']['hint_2']; ?>
</div><?php endif; ?>
                </div>
            </div>
            
            
            <?php $this->assign('category_array', ''); ?>
            <?php endif; ?>

			<?php if ($this->_tpl_vars['field_group']['group_id'] == 'required_fields'): ?><div class="clearfix"></div><?php endif; ?>
         
           
        <?php if ($this->_tpl_vars['field_group']['group_id'] != 'required_fields'): ?> 
        </div>
        <?php endif; ?>
      </fieldset>
    <?php endforeach; endif; unset($_from); ?>	


    <div align="right" style="margin-top:10px">
    <input type="submit" name="submit_data" value="<?php echo smarty_lang(array('code' => 'uploading'), $this);?>
" 
    id="cbSubmitUpload<?php echo $this->_tpl_vars['objId']; ?>
" disabled="disabled" class="cbSubmitUpload" ></div>
    </form>
</div>