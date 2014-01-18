<?php /* Smarty version 2.6.18, created on 2014-01-17 10:38:28
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/add_members.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ANCHOR', '/var/www/clipbucket/admin_area/styles/cbv2/layout/add_members.html', 20, false),)), $this); ?>
<?php $this->assign('required_fields', $this->_tpl_vars['userquery']->load_signup_fields()); ?>
<?php $this->assign('custom_field', $this->_tpl_vars['userquery']->custom_signup_fields); ?>

<h2>Add New Member</h2>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="10" align="center" valign="middle" class="left_head">&nbsp;</td>
    <td class="head">Required Member Details</td>
    <td width="10" class="right_head">&nbsp;</td>
  </tr>
</table>

<form action="" method="post">
  <fieldset class="fieldset">
    <legend>User Information</legend>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
      <?php $_from = $this->_tpl_vars['required_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
      <tr>
        <td width="200"><label for="<?php echo $this->_tpl_vars['field']['id']; ?>
" class="label"><?php echo $this->_tpl_vars['field']['title']; ?>
</label></td>
        <td><?php if ($this->_tpl_vars['field']['hint_1']): ?><?php echo $this->_tpl_vars['field']['hint_1']; ?>
<br><?php endif; ?><?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_before']), $this);?>
<?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['field']); ?>
<?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_after']), $this);?>
<?php if ($this->_tpl_vars['field']['hint_2']): ?><br><?php echo $this->_tpl_vars['field']['hint_2']; ?>
<?php endif; ?></td>
      </tr>
      <?php endforeach; endif; unset($_from); ?> 
      <tr>
          <td width="200"><label for="level" class="label">User level</label></td>
          <td><select name="level" id="level">
        <?php $this->assign('levels', $this->_tpl_vars['userquery']->get_levels()); ?>
        <?php $_from = $this->_tpl_vars['levels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
        	<option value="<?php echo $this->_tpl_vars['level']['user_level_id']; ?>
"  <?php if ($_POST['level'] == $this->_tpl_vars['level']['user_level_id']): ?> selected<?php elseif ($this->_tpl_vars['level']['user_level_id'] == 2): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['level']['user_level_name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
        </select></td>
      </tr>
      <tr>
        <td>Status</td>
        <td><label for="active"></label>
          <select name="active" id="active">
            <option value="Ok">Active</option>
            <option value="ToActivate">Inactive</option>
        </select></td>
      </tr>
    </table>
  </fieldset>
    
    <?php if ($this->_tpl_vars['custom_field']): ?>
    <fieldset class="fieldset">
      <legend>Other</legend>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
      <?php $_from = $this->_tpl_vars['custom_field']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
        <tr>
          <td width="200"><label for="<?php echo $this->_tpl_vars['field']['id']; ?>
" class="label"><?php echo $this->_tpl_vars['field']['title']; ?>
</label></td>
          <td><?php if ($this->_tpl_vars['field']['hint_1']): ?><?php echo $this->_tpl_vars['field']['hint_1']; ?>
<br><?php endif; ?><?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_before']), $this);?>
<?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['field']); ?>
<?php echo ANCHOR(array('place' => $this->_tpl_vars['field']['anchor_after']), $this);?>
<?php if ($this->_tpl_vars['field']['hint_2']): ?><br><?php echo $this->_tpl_vars['field']['hint_2']; ?>
<?php endif; ?></td>
        </tr>
      <?php endforeach; endif; unset($_from); ?> 
      </table>
    </fieldset>
    <?php endif; ?>
    
    <div align="left" style="padding:10px"><input type="submit" name="add_member" value="Add Member" class="button" id="add_member" ></div>
</form>