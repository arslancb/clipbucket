<?php /* Smarty version 2.6.18, created on 2014-01-17 13:22:55
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/video_manager.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'get_form_val', '/var/www/clipbucket/admin_area/styles/cbv2/layout/video_manager.html', 9, false),array('modifier', 'truncate', '/var/www/clipbucket/admin_area/styles/cbv2/layout/video_manager.html', 119, false),array('modifier', 'SetTime', '/var/www/clipbucket/admin_area/styles/cbv2/layout/video_manager.html', 120, false),array('modifier', 'niceTime', '/var/www/clipbucket/admin_area/styles/cbv2/layout/video_manager.html', 125, false),array('function', 'getThumb', '/var/www/clipbucket/admin_area/styles/cbv2/layout/video_manager.html', 113, false),)), $this); ?>

<h2>Video Manager</h2>

<img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="sarch_button" onclick="toggle_search('searchdiv')" />
<div class="search_box" id="searchdiv" <?php if ($_COOKIE['show_searchdiv_search'] != 'show'): ?> style="display:none"<?php endif; ?>>  <form id="video_search" name="video_search" method="get" action="video_manager.php" class="video_search">
  <table width="400" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td width="106" align="right"><label for="title">Video title</label></td>
      <td width="280"><input name="title" type="text" class="input" id="title" value="<?php echo ((is_array($_tmp='title')) ? $this->_run_mod_handler('get_form_val', true, $_tmp, true) : get_form_val($_tmp, true)); ?>
" //></td>
    </tr>
    <tr>
      <td align="right"><label for="videokey">Video key</label></td>
      <td><input name="videokey" type="text" class="input" id="videokey" value="<?php echo ((is_array($_tmp='videokey')) ? $this->_run_mod_handler('get_form_val', true, $_tmp, true) : get_form_val($_tmp, true)); ?>
" //></td>
    </tr>
    <tr>
      <td align="right"><label for="videoid">Video id</label></td>
      <td><input name="videoid" type="text" class="input" id="videoid" value="<?php echo ((is_array($_tmp='videoid')) ? $this->_run_mod_handler('get_form_val', true, $_tmp, true) : get_form_val($_tmp, true)); ?>
" //></td>
    </tr>
    <tr>
      <td align="right"><label for="tags">Video tags</label></td>
      <td><input name="tags" type="text" class="input" id="tags" value="<?php echo ((is_array($_tmp='tags')) ? $this->_run_mod_handler('get_form_val', true, $_tmp, true) : get_form_val($_tmp, true)); ?>
" /></td>
    </tr>
    <tr>
      <td align="right">Featured</td>
      <td><label for="featured"></label>
        <select name="featured" id="featured" class="input">
          <option value="" ></option>
          <option value="yes" <?php if ($_GET['featured'] == 'yes'): ?> selected="selected"<?php endif; ?>>Yes</option>
          <option value="no" <?php if ($_GET['featured'] == 'no'): ?> selected="selected"<?php endif; ?>>No</option>
        </select></td>
    </tr>
    <tr>
      <td align="right">Active</td>
      <td><select name="active" id="active" class="input">
      <option value="" ></option>
        <option value="yes" <?php if ($_GET['active'] == 'yes'): ?> selected="selected"<?php endif; ?>>Yes</option>
        <option value="no" <?php if ($_GET['active'] == 'no'): ?> selected="selected"<?php endif; ?>>No</option>
      </select></td>
    </tr>
    <tr>
      <td align="right">Conversion Status</td>
      <td><select name="status" id="status" class="input">
      <option value="" ></option>
        <option value="Successful" <?php if ($_GET['status'] == 'Successful'): ?> selected="selected"<?php endif; ?>>Successful</option>
        <option value="Processing" <?php if ($_GET['status'] == 'Processing'): ?> selected="selected"<?php endif; ?>>Processing</option>
        <option value="Failed" <?php if ($_GET['status'] == 'Failed'): ?> selected="selected"<?php endif; ?>>Failed</option>
</select></td>
    </tr>
    <tr>
      <td align="right">Userid</td>
      <td><input name="userid" type="text" class="input" id="userid" value="<?php echo ((is_array($_tmp='userid')) ? $this->_run_mod_handler('get_form_val', true, $_tmp, true) : get_form_val($_tmp, true)); ?>
" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Category</td>
      <td>
     <?php echo $this->_tpl_vars['formObj']->createField($this->_tpl_vars['cat_array']); ?>
 
</td>
    </tr>
  </table>
  <br />
  <input type="submit" name="search" id="search" value="Search Form" class="button"/>
</form>
</div>
  
  
<!-- DIsplaying Videos -->
<form name="video_manage" method="post">

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/arrow_return.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
     		<input type="submit" name="activate_selected" value="Activate"  class="button"/>
            <input type="submit" name="deactivate_selected" value="Deactivate" class="button" />
            <input type="submit" name="make_featured_selected" value="Make Featured"  class="button"/>
            <input type="submit" name="make_unfeatured_selected" value="Make Unfeatured"  class="button"/>
            <input type="submit" name="delete_selected" value="Delete"  class="button" onclick="return confirm_it('Are you sure you want to delete selected video(s)')"/>
    
    </td>
    </tr>
</table>


<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle" class="left_head">
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
    <td width="50" class="head">VID</td>
    <td width="50" class="head">&nbsp;</td>
    <td class="head">Details</td>
    <td width="50" class="right_head">&nbsp;</td>
  </tr>
</table>




<?php if ($this->_tpl_vars['videos']): ?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php $this->assign('bgcolor', ""); ?>
<?php unset($this->_sections['list']);
$this->_sections['list']['name'] = 'list';
$this->_sections['list']['loop'] = is_array($_loop=$this->_tpl_vars['videos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['list']['show'] = true;
$this->_sections['list']['max'] = $this->_sections['list']['loop'];
$this->_sections['list']['step'] = 1;
$this->_sections['list']['start'] = $this->_sections['list']['step'] > 0 ? 0 : $this->_sections['list']['loop']-1;
if ($this->_sections['list']['show']) {
    $this->_sections['list']['total'] = $this->_sections['list']['loop'];
    if ($this->_sections['list']['total'] == 0)
        $this->_sections['list']['show'] = false;
} else
    $this->_sections['list']['total'] = 0;
if ($this->_sections['list']['show']):

            for ($this->_sections['list']['index'] = $this->_sections['list']['start'], $this->_sections['list']['iteration'] = 1;
                 $this->_sections['list']['iteration'] <= $this->_sections['list']['total'];
                 $this->_sections['list']['index'] += $this->_sections['list']['step'], $this->_sections['list']['iteration']++):
$this->_sections['list']['rownum'] = $this->_sections['list']['iteration'];
$this->_sections['list']['index_prev'] = $this->_sections['list']['index'] - $this->_sections['list']['step'];
$this->_sections['list']['index_next'] = $this->_sections['list']['index'] + $this->_sections['list']['step'];
$this->_sections['list']['first']      = ($this->_sections['list']['iteration'] == 1);
$this->_sections['list']['last']       = ($this->_sections['list']['iteration'] == $this->_sections['list']['total']);
?>
<script type="text/javascript">
<?php echo '
   $(function() { '; ?>

		$("#thumbs_<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
").tooltip(<?php echo '{showURL: false,delay: 0,opacity: 1.0});
	});
'; ?>

</script>

<tr class="video_opt_td"  bgcolor="<?php echo $this->_tpl_vars['bgcolor']; ?>
">
    <td width="30" align="center" valign="top" class="video_opt_td">    <input name="check_video[]" type="checkbox" id="check_video" value="<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
" /></td>
    <td width="50" align="left" valign="top" class="video_opt_td"><?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
</td>
    <td width="50" valign="top" class="video_opt_td"><img src="<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['videos'][$this->_sections['list']['index']]), $this);?>
" width="46" height="36" id="thumbs_<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
"
    title="<img src='<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['videos'][$this->_sections['list']['index']],'num' => 1), $this);?>
'  /> <img src='<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['videos'][$this->_sections['list']['index']],'num' => 2), $this);?>
'  /> <img src='<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['videos'][$this->_sections['list']['index']],'num' => 3), $this);?>
'  />" /></td>
    <td valign="top"  class="video_opt_td" 
    onmouseover="$('#vid_opt-<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
').show()" 
    onmouseout="$('#vid_opt-<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
').hide()" >
    <div><a href="edit_video.php?video=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
" target="_blank" style="text-indent:10px">
    <?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['list']['index']]['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 80) : smarty_modifier_truncate($_tmp, 80)); ?>

    </a> (<?php if ($this->_tpl_vars['videos'][$this->_sections['list']['index']]['duration'] > 1): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['list']['index']]['duration'])) ? $this->_run_mod_handler('SetTime', true, $_tmp) : SetTime($_tmp)); ?>
<?php else: ?>00:00<?php endif; ?>)</div>
    <span class="vdo_sets">
    Featured:<strong><?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['featured']; ?>
 </strong> &#8226;
    Active:<strong><?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['active']; ?>
</strong> &#8226;
    Status:<strong><?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['status']; ?>
</strong>  &#8226;
    Uploaded:<strong><?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['list']['index']]['date_added'])) ? $this->_run_mod_handler('niceTime', true, $_tmp) : niceTime($_tmp)); ?>
</strong>  &#8226;
    User:<strong><a href="view_user.php?uid=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['userid']; ?>
"><?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['username']; ?>
</a></strong>
    </span>
    
    <br />
    <div id="vid_opt-<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
" style="display:none" class="vid_opts">
   	 
      <a href="edit_video.php?video=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
">Edit</a> | 
      <a href="<?php echo $this->_tpl_vars['baseurl']; ?>
/watch_video.php?v=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videokey']; ?>
" target="_blank">Watch</a> <?php if ($this->_tpl_vars['videos'][$this->_sections['list']['index']]['featured'] == yes): ?> | 
      <a href="javascript:Confirm_Delete('?delete_video=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
')">Delete</a> | 
      <a href="?make_unfeature=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
">Make Unfeatured</a> <?php endif; ?> 
      <?php if ($this->_tpl_vars['videos'][$this->_sections['list']['index']]['featured'] == no): ?> | 
      <a href="?make_feature=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
">Make Featured</a> <?php endif; ?>
      <?php if ($this->_tpl_vars['videos'][$this->_sections['list']['index']]['active'] == yes): ?>  | 
      <a href="?deactivate=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
">Deactivate</a> <?php else: ?> | 
      <a href="?activate=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
">Activate</a> <?php endif; ?>  | 
      <a href="view_conversion_log.php?file_name=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['file_name']; ?>
">File conversion details</a> | 
      <a href="?delete_video=<?php echo $this->_tpl_vars['videos'][$this->_sections['list']['index']]['videoid']; ?>
">Delete</a>
      <?php $_from = $this->_tpl_vars['cbvid']->video_manager_links; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['links']):
?>
      	<?php echo $this->_tpl_vars['cbvid']->video_manager_link($this->_tpl_vars['links'],$this->_tpl_vars['videos'][$this->_sections['list']['index']]); ?>

      <?php endforeach; endif; unset($_from); ?>
    </div>
    </td>
    <td width="50" valign="top" class="video_opt_td">&nbsp;</td>
    </tr>
    <?php if ($this->_tpl_vars['bgcolor'] == ""): ?>
    <?php $this->assign('bgcolor', "#EEEEEE"); ?>
    <?php else: ?>
    <?php $this->assign('bgcolor', ""); ?>
    <?php endif; ?>
        
<?php endfor; endif; ?>
</table>
<?php else: ?>
  <div align="center"><strong><em>No Video Found</em></strong></div>
<?php endif; ?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/arrow_return_invert.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
     <input type="submit" name="activate_selected" value="Activate"  class="button"/>
            <input type="submit" name="deactivate_selected" value="Deactivate" class="button" />
            <input type="submit" name="make_featured_selected" value="Make Featured"  class="button"/>
            <input type="submit" name="make_unfeatured_selected" value="Make Unfeatured"  class="button"/>
            <input type="submit" name="delete_selected" value="Delete"  class="button" onclick="return confirm_it('Are you sure you want to delete selected video(s)')"/>
    
    </td>
    </tr>
</table>

</form>

<!-- DIsplaying Videos Ends-->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/pagination.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>