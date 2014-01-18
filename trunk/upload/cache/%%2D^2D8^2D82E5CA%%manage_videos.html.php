<?php /* Smarty version 2.6.18, created on 2014-01-17 12:40:01
         compiled from /var/www/clipbucket/styles/cbv2new/layout/manage_videos.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/manage_videos.html', 7, false),array('modifier', 'form_val', '/var/www/clipbucket/styles/cbv2new/layout/manage_videos.html', 13, false),)), $this); ?>
<div>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <div class="account_box">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_head.html", 'smarty_include_vars' => array('user' => $this->_tpl_vars['user'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 
 <?php if ($this->_tpl_vars['mode'] == 'uploaded'): ?>
 <h2><?php echo smarty_lang(array('code' => 'com_manage_vids'), $this);?>
</h2>
 
 
 <!-- Manage Uploaded Videos Start -->
 <div align="right" style="float:right; display:inline">
   <form id="form1" name="form1" method="get" action="">
    <input name="query" type="text" class="search_field" id="query" value="<?php echo ((is_array($_tmp=$_GET['query'])) ? $this->_run_mod_handler('form_val', true, $_tmp) : form_val($_tmp)); ?>
" />
    <input name="input" type="submit" class="search_field_button" value="<?php echo smarty_lang(array('code' => 'search'), $this);?>
"/> 
    <input name="mode" value="<?php echo $this->_tpl_vars['mode']; ?>
" type="hidden" />
   </form>
  <div class="clearfix"></div></div>
 <form name="videos_manager" method="post">
 <div style="float:left; width:300px">
   <img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="arrow_pointing" style="margin-left:20px; margin-right:10px;" /><input type="submit" name="delete_videos" id="delete_videos" value="<?php echo smarty_lang(array('code' => 'delete'), $this);?>
" class="small_button" />
 </div>
 
  
  <div class="clear"></div>
  
  
   <div class="account_table">
   
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
 	  <tr>
 	    <td width="25"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/>
 	      </td>
 	    <td><?php echo smarty_lang(array('code' => 'title'), $this);?>
</td>
 	    <td width="100"><?php echo smarty_lang(array('code' => 'date_added'), $this);?>
</td>
 	    <td width="100"><?php echo smarty_lang(array('code' => 'views'), $this);?>
</td>
 	    <td width="100"><?php echo smarty_lang(array('code' => 'comments'), $this);?>
</td>
 	    <td width="100" class="last_td"><?php echo smarty_lang(array('code' => 'status'), $this);?>
</td>
 	    </tr>
 	  </table>
 </div>
 <?php $this->assign('bg', 'fff'); ?>
 <?php unset($this->_sections['uvid']);
$this->_sections['uvid']['name'] = 'uvid';
$this->_sections['uvid']['loop'] = is_array($_loop=$this->_tpl_vars['uservids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['uvid']['show'] = true;
$this->_sections['uvid']['max'] = $this->_sections['uvid']['loop'];
$this->_sections['uvid']['step'] = 1;
$this->_sections['uvid']['start'] = $this->_sections['uvid']['step'] > 0 ? 0 : $this->_sections['uvid']['loop']-1;
if ($this->_sections['uvid']['show']) {
    $this->_sections['uvid']['total'] = $this->_sections['uvid']['loop'];
    if ($this->_sections['uvid']['total'] == 0)
        $this->_sections['uvid']['show'] = false;
} else
    $this->_sections['uvid']['total'] = 0;
if ($this->_sections['uvid']['show']):

            for ($this->_sections['uvid']['index'] = $this->_sections['uvid']['start'], $this->_sections['uvid']['iteration'] = 1;
                 $this->_sections['uvid']['iteration'] <= $this->_sections['uvid']['total'];
                 $this->_sections['uvid']['index'] += $this->_sections['uvid']['step'], $this->_sections['uvid']['iteration']++):
$this->_sections['uvid']['rownum'] = $this->_sections['uvid']['iteration'];
$this->_sections['uvid']['index_prev'] = $this->_sections['uvid']['index'] - $this->_sections['uvid']['step'];
$this->_sections['uvid']['index_next'] = $this->_sections['uvid']['index'] + $this->_sections['uvid']['step'];
$this->_sections['uvid']['first']      = ($this->_sections['uvid']['iteration'] == 1);
$this->_sections['uvid']['last']       = ($this->_sections['uvid']['iteration'] == $this->_sections['uvid']['total']);
?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_video.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['uservids'][$this->_sections['uvid']['index']],'control' => 'full','bg' => $this->_tpl_vars['bg'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php if ($this->_tpl_vars['bg'] == 'fff'): ?>
 <?php $this->assign('bg', 'EFF5F8'); ?>
 <?php else: ?>
 <?php $this->assign('bg', 'fff'); ?>
 <?php endif; ?>
 
 <?php endfor; else: ?>
 	<div align="center" style="padding:5px"><strong><em><?php echo smarty_lang(array('code' => 'you_dont_hv_fav_vids'), $this);?>
</em></strong></div>
 <?php endif; ?>
 
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/user_account_pagination.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 
 
 
  </form>
 <?php endif; ?>
 <!-- Manage Uploaded Videos End -->
 
 
 
 <!-- Manage Favorite Videos -->
 <?php if ($this->_tpl_vars['mode'] == 'favorites'): ?>
 	<h2><?php echo smarty_lang(array('code' => 'com_manage_fav'), $this);?>
</h2>
 
 
 <!-- Manage Uploaded Videos Start -->
 <div align="right" style="float:right; display:inline">
   <form id="form1" name="form1" method="get" action="">
    <input name="query" type="text" class="search_field" id="query" value="<?php echo ((is_array($_tmp=$_GET['query'])) ? $this->_run_mod_handler('form_val', true, $_tmp) : form_val($_tmp)); ?>
" />
    <input name="input" type="submit" class="search_field_button" value="<?php echo smarty_lang(array('code' => 'search'), $this);?>
"/> 
    <input name="mode" value="<?php echo $this->_tpl_vars['mode']; ?>
" type="hidden" />
   </form>
  <div class="clearfix"></div></div>
 <form name="videos_manager" method="post">
 <div style="float:left; width:300px">
   <img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="arrow_pointing" style="margin-left:20px; margin-right:10px;" /><input type="submit" name="delete_fav_videos" id="delete_fav_videos" value="<?php echo smarty_lang(array('code' => 'delete'), $this);?>
" class="small_button" />
 </div>
 
  
  <div class="clearfix"></div>
  
  
   <div class="account_table">
   
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
 	  <tr>
 	    <td width="25"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/>
 	      </td>
 	    <td><?php echo smarty_lang(array('code' => 'title'), $this);?>
</td>
 	    <td width="100"><?php echo smarty_lang(array('code' => 'date_added'), $this);?>
</td>
 	    <td width="100"><?php echo smarty_lang(array('code' => 'views'), $this);?>
</td>
 	    <td width="100" class="last_td"><?php echo smarty_lang(array('code' => 'comments'), $this);?>
</td>
 	    </tr>
 	  </table>
 </div>
 <?php $this->assign('bg', 'fff'); ?>
 <?php unset($this->_sections['uvid']);
$this->_sections['uvid']['name'] = 'uvid';
$this->_sections['uvid']['loop'] = is_array($_loop=$this->_tpl_vars['uservids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['uvid']['show'] = true;
$this->_sections['uvid']['max'] = $this->_sections['uvid']['loop'];
$this->_sections['uvid']['step'] = 1;
$this->_sections['uvid']['start'] = $this->_sections['uvid']['step'] > 0 ? 0 : $this->_sections['uvid']['loop']-1;
if ($this->_sections['uvid']['show']) {
    $this->_sections['uvid']['total'] = $this->_sections['uvid']['loop'];
    if ($this->_sections['uvid']['total'] == 0)
        $this->_sections['uvid']['show'] = false;
} else
    $this->_sections['uvid']['total'] = 0;
if ($this->_sections['uvid']['show']):

            for ($this->_sections['uvid']['index'] = $this->_sections['uvid']['start'], $this->_sections['uvid']['iteration'] = 1;
                 $this->_sections['uvid']['iteration'] <= $this->_sections['uvid']['total'];
                 $this->_sections['uvid']['index'] += $this->_sections['uvid']['step'], $this->_sections['uvid']['iteration']++):
$this->_sections['uvid']['rownum'] = $this->_sections['uvid']['iteration'];
$this->_sections['uvid']['index_prev'] = $this->_sections['uvid']['index'] - $this->_sections['uvid']['step'];
$this->_sections['uvid']['index_next'] = $this->_sections['uvid']['index'] + $this->_sections['uvid']['step'];
$this->_sections['uvid']['first']      = ($this->_sections['uvid']['iteration'] == 1);
$this->_sections['uvid']['last']       = ($this->_sections['uvid']['iteration'] == $this->_sections['uvid']['total']);
?>
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/account_video.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['uservids'][$this->_sections['uvid']['index']],'bg' => $this->_tpl_vars['bg'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 <?php if ($this->_tpl_vars['bg'] == 'fff'): ?>
 <?php $this->assign('bg', 'EFF5F8'); ?>
 <?php else: ?>
 <?php $this->assign('bg', 'fff'); ?>
 <?php endif; ?>
 
 <?php endfor; else: ?>
 	<div align="center" style="padding:5px"><strong><em><?php echo smarty_lang(array('code' => 'you_dont_hv_fav_vids'), $this);?>
</em></strong></div>
 <?php endif; ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/manage/user_account_pagination.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 
 
 
  </form>
 <?php endif; ?>
 <!-- Manage Favorite Videos END-->
 </div>
 

</div>