<?php /* Smarty version 2.6.18, created on 2014-01-17 13:55:21
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/cb_conversion_queue.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'conv_status', '/var/www/clipbucket/admin_area/styles/cbv2/layout/cb_conversion_queue.html', 44, false),array('modifier', 'date', '/var/www/clipbucket/admin_area/styles/cbv2/layout/cb_conversion_queue.html', 46, false),)), $this); ?>
<h2>ClipBucket Queue Manager</h2> <?php if (conv_lock_exists ( )): ?><span class="button" style="padding:5px; margin-left:10px" onclick="window.location='?delete_lock=yes'">Delete Conversion Lock</span><?php endif; ?>
<div></div>
do not try to change the things here, can cause problems to your Clipbucket conversion system.


<!-- DIsplaying Videos -->
<form name="video_manage" method="post">

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/arrow_return.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
    <input type="submit" name="delete_selected" value="Delete"  class="button" onclick="return confirm_it('Are your sure you want to remove these items')"/>
    <input name="processed" type="submit" class="button" id="processed" value="Set as processed" />
    <input name="pending" type="submit" class="button" id="pending" value="Set as pending" /></td>
    </tr>
</table>


<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle" class="left_head">
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
    <td width="50" class="head">ID</td>
    <td class="head">Details</td>
    <td width="50" class="right_head">&nbsp;</td>
  </tr>
</table>

<?php if ($this->_tpl_vars['queues']): ?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php $_from = $this->_tpl_vars['queues']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['queue']):
?>
<tr bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
	<td width="30" align="center"valign="top" class="video_opt_td" style="height:25px">
    <input name="check_queue[]" type="checkbox"  value="<?php echo $this->_tpl_vars['queue']['cqueue_id']; ?>
" /></td>
    <td width="50"valign="top" class="video_opt_td" style="height:25px">
    	<?php echo $this->_tpl_vars['queue']['cqueue_id']; ?>

    </td>
    <td valign="top" class="video_opt_td" style="height:25px">
      <a href="<?php echo $this->_tpl_vars['baseurl']; ?>
/<?php echo @ADMINDIR; ?>
/view_conversion_log.php?file_name=<?php echo $this->_tpl_vars['queue']['cqueue_name']; ?>
" target="_blank"><?php echo $this->_tpl_vars['queue']['cqueue_name']; ?>
</a> - 
      <span class="vdo_sets">
        File Type : <strong><?php echo $this->_tpl_vars['queue']['cqueue_ext']; ?>
</strong> &#8226; 
        Temp Ext : <strong><?php echo $this->_tpl_vars['queue']['cqueue_tmp_ext']; ?>
</strong> &#8226;
        Conversion Process : <strong><?php echo ((is_array($_tmp=$this->_tpl_vars['queue']['cqueue_conversion'])) ? $this->_run_mod_handler('conv_status', true, $_tmp) : conv_status($_tmp)); ?>
</strong> &#8226;
        <?php if ($this->_tpl_vars['queue']['time_started']): ?>
        Started : <strong><?php echo ((is_array($_tmp="Y-m-d H:i:s")) ? $this->_run_mod_handler('date', true, $_tmp, $this->_tpl_vars['queue']['time_started']) : date($_tmp, $this->_tpl_vars['queue']['time_started'])); ?>
</strong> &#8226;
        <?php endif; ?>
        <?php if ($this->_tpl_vars['queue']['time_completed']): ?>
        Completed : <strong><?php echo ((is_array($_tmp="Y-m-d H:i:s")) ? $this->_run_mod_handler('date', true, $_tmp, $this->_tpl_vars['queue']['time_completed']) : date($_tmp, $this->_tpl_vars['queue']['time_completed'])); ?>
</strong>
        <?php endif; ?>
        </span>    </td>
    </tr>
	
<?php if ($this->_tpl_vars['bgcolor'] == ""): ?>
<?php $this->assign('bgcolor', "#EEEEEE"); ?>
<?php else: ?>
<?php $this->assign('bgcolor', ""); ?>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php else: ?>
	<div align="center"><strong>No Conversion Queue File Found</strong></div>
<?php endif; ?>
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/pagination.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>