<?php /* Smarty version 2.6.18, created on 2014-01-15 15:35:01
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/plugin_manager.html */ ?>
<h2>Installed Plugins</h2>
<?php if ($this->_tpl_vars['installed_plugin_list']): ?>

<form name="installed_plugins" id="installed_plugins" method="post" action="plugin_manager.php">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/arrow_return.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
     		<input type="submit" name="activate_selected" value="Activate"  class="button"/>
            <input type="submit" name="deactivate_selected" value="Deactivate" class="button" />
            <input type="submit" name="uninstall_selected" value="Uninstall"  class="button" onclick="return confirm_it('Are you sure you want to uninstall selected plugin(s)')" id="uninstall_selected"/>
    
    </td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle" class="left_head">
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
    <td class="head">Plugin Details</td>
    <td width="50" class="right_head">&nbsp;</td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php $this->assign('bgcolor', ""); ?>
<?php $_from = $this->_tpl_vars['installed_plugin_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['item']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['plug']):
        $this->_foreach['item']['iteration']++;
?>

<tr class="video_opt_td"  bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
    <td width="30" align="center" valign="top" class="video_opt_td"><input name="check_plugin[]" type="checkbox"  value="<?php echo $this->_foreach['item']['iteration']; ?>
" />
    <input type="hidden" name="plugin_file_<?php echo $this->_foreach['item']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['plug']['plugin_file']; ?>
" />
    <input type="hidden" name="plugin_folder_<?php echo $this->_foreach['item']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['plug']['folder']; ?>
" />
    </td>
    <td valign="top"  class="video_opt_td">
      <strong><?php echo $this->_tpl_vars['plug']['name']; ?>
</strong> &#8212; 
      <span class="vdo_sets">
      Active:<strong><?php echo $this->_tpl_vars['plug']['plugin_active']; ?>
</strong> &#8226;
      Author:<strong><a href="<?php echo $this->_tpl_vars['plug']['website']; ?>
"><?php echo $this->_tpl_vars['plug']['author']; ?>
</a></strong> &#8226;
      Version:<strong><?php echo $this->_tpl_vars['plug']['version']; ?>
</strong></span>
      <div style="margin-bottom:10px"> <?php echo $this->_tpl_vars['plug']['description']; ?>
</div>
      <span class="vdo_sets"><?php if ($this->_tpl_vars['plug']['plugin_active'] == 'yes'): ?><a href="?deactivate=<?php echo $this->_tpl_vars['plug']['plugin_file']; ?>
<?php if ($this->_tpl_vars['plug']['folder'] != ''): ?>&f=<?php echo $this->_tpl_vars['plug']['folder']; ?>
<?php endif; ?>">Deactivate</a><?php else: ?><a href="?activate=<?php echo $this->_tpl_vars['plug']['plugin_file']; ?>
<?php if ($this->_tpl_vars['plug']['folder'] != ''): ?>&f=<?php echo $this->_tpl_vars['plug']['folder']; ?>
<?php endif; ?>">Activate</a><?php endif; ?> | 
    <a href="javascript:Confirm_Uninstall('?uninstall=<?php echo $this->_tpl_vars['plug']['plugin_file']; ?>
<?php if ($this->_tpl_vars['plug']['folder'] != ''): ?>&f=<?php echo $this->_tpl_vars['plug']['folder']; ?>
<?php endif; ?>')">Uninstall</a></span>
    <div style="height:5px"></div>
     </td>
    </tr>
    <?php if ($this->_tpl_vars['bgcolor'] == ""): ?>
    <?php $this->assign('bgcolor', "#EEEEEE"); ?>
    <?php else: ?>
    <?php $this->assign('bgcolor', ""); ?>
    <?php endif; ?>
        
<?php endforeach; endif; unset($_from); ?>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/arrow_return_invert.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
     <input type="submit" name="activate_selected" value="Activate"  class="button"/>
            <input type="submit" name="deactivate_selected" value="Deactivate" class="button" />
            <input type="submit" name="uninstall_selected" value="Uninstall"  class="button" onclick="return confirm_it('Are you sure you want to delete selected video(s)')" id="uninstall_selected"/>
    
    </td>
  </tr>
</table>
</form>

<?php else: ?>
<div align="center"><strong><em>No Installed Plugin Found</em></strong></div>
<?php endif; ?>






<div style="height:25px"></div>
<h2>Available Plugins </h2>

<?php if ($this->_tpl_vars['new_plugin_list']): ?><form name="available_plugins" id="available_plugins" action="plugin_manager.php" method="post">

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/arrow_return.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px"><input type="submit" name="install_selected" value="Install"  class="button" onclick="return confirm_it('Are you sure you want to install selected plugin(s)')" id="install_selected"/>
    
    </td>
  </tr>
</table>



<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle" class="left_head">
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
    <td class="head">Plugin Details</td>
    <td width="50" class="right_head">&nbsp;</td>
  </tr>
</table>





<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php $this->assign('bgcolor', ""); ?>
<?php $_from = $this->_tpl_vars['new_plugin_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['item']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['plug']):
        $this->_foreach['item']['iteration']++;
?>

<tr class="video_opt_td"  bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
    <td width="30" align="center" valign="top" class="video_opt_td">
   <input name="check_plugin[]" type="checkbox"  value="<?php echo $this->_foreach['item']['iteration']; ?>
" />
    <input type="hidden" name="plugin_file_<?php echo $this->_foreach['item']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['plug']['file']; ?>
" />
    <input type="hidden" name="plugin_folder_<?php echo $this->_foreach['item']['iteration']; ?>
" value="<?php echo $this->_tpl_vars['plug']['folder']; ?>
" /></td>
    <td valign="top"  class="video_opt_td" >
      <strong><?php echo $this->_tpl_vars['plug']['name']; ?>
</strong> &#8212; 
      <span class="vdo_sets">
      Active:<strong><?php echo $this->_tpl_vars['plug']['plugin_active']; ?>
</strong> &#8226;
      Author:<strong><a href="<?php echo $this->_tpl_vars['plug']['website']; ?>
"><?php echo $this->_tpl_vars['plug']['author']; ?>
</a></strong> &#8226;
      Version:<strong><?php echo $this->_tpl_vars['plug']['version']; ?>
</strong></span>
      <div style="margin-bottom:10px"> <?php echo $this->_tpl_vars['plug']['description']; ?>
</div>
      <span class="vdo_sets"><a href="?install_plugin=<?php echo $this->_tpl_vars['plug']['file']; ?>
<?php if ($this->_tpl_vars['plug']['folder'] != ''): ?>&f=<?php echo $this->_tpl_vars['plug']['folder']; ?>
<?php endif; ?>">Install Plugin</a></span>
    <div style="height:5px"></div>
    </td>
  </tr>
    <?php if ($this->_tpl_vars['bgcolor'] == ""): ?>
    <?php $this->assign('bgcolor', "#EEEEEE"); ?>
    <?php else: ?>
    <?php $this->assign('bgcolor', ""); ?>
    <?php endif; ?>
        
<?php endforeach; endif; unset($_from); ?>
</table>




<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/arrow_return_invert.png" width="25" height="25" /></td>
    <td height="50" style="padding-left:15px"><input type="submit" name="install_selected" value="Install"  class="button" onclick="return confirm_it('Are you sure you want to install selected plugin(s)')" id="install_selected"/>
    
    </td>
  </tr>
</table>
</form>


 <?php else: ?>
<div align="center"><strong><em>No Plugin is available</em></strong></div>
<?php endif; ?>