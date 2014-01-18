<?php /* Smarty version 2.6.18, created on 2014-01-17 12:33:36
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/flag_form.html */ ?>
<!-- Flag This <?php echo $this->_tpl_vars['type']; ?>
 -->
<div id="flag_item" class="action_box" style="display:<?php if ($this->_tpl_vars['display']): ?><?php echo $this->_tpl_vars['display']; ?>
<?php else: ?>none<?php endif; ?>">
	<div class="action_box_title">Flag this <?php echo $this->_tpl_vars['params']['type']; ?>
 &#8212; <span class="cancel"><a href="javascript:void(0)" onclick="$('#flag_item').slideUp();">cancel</a></span></div>
    <div class="form_container" align="center">
    
    <div class="form_result" id="flag_form_result" style="display:none"></div>
    
    <form id="flag_form" name="flag_form" method="post" action="" class="">
    Please select the category that most closely reflects your concern about the video, so that we can review it and determine whether it violates our Community Guidelines or isn't appropriate for all viewers. Abusing this feature is also a violation of the Community Guidelines, so don't do it. 
    	<?php $this->assign('flag_options', get_flag_options(($this->_tpl_vars['type']))); ?>
        <label for="select"></label>
        <select name="select" name="flag_type" id="flag_type">
        	<?php $_from = $this->_tpl_vars['flag_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
        	<option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</option>
            <?php endforeach; endif; unset($_from); ?>
        </select><br>
        <input type="button" name="send_content" value="Flag This <?php echo $this->_tpl_vars['params']['type']; ?>
" class="cb_button" onclick="flag_object('flag_form','<?php echo $this->_tpl_vars['params']['id']; ?>
','<?php echo $this->_tpl_vars['params']['type']; ?>
')"/>
  	</form>
    </div>
</div>
<!-- Flag This <?php echo $this->_tpl_vars['type']; ?>
 -->