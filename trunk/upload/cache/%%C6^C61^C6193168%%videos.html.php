<?php /* Smarty version 2.6.18, created on 2014-01-17 12:40:27
         compiled from /var/www/clipbucket/styles/cbv2new/layout/videos.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/videos.html', 10, false),array('function', 'link', '/var/www/clipbucket/styles/cbv2new/layout/videos.html', 13, false),array('function', 'AD', '/var/www/clipbucket/styles/cbv2new/layout/videos.html', 45, false),)), $this); ?>

<!-- Listing Categories -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/category_list.html", 'smarty_include_vars' => array('type' => 'video')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!-- Listing Categories End -->

<div style="width:620px; float:left" class="vid_page_conainer" >
<div class="sort_cont">

<ul>
	<li><?php echo smarty_lang(array('code' => 'sort_by'), $this);?>
 :</li>
    <?php $this->assign('sorting_links', sorting_links()); ?>
    <?php $_from = $this->_tpl_vars['sorting_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sort'] => $this->_tpl_vars['name']):
?>
    	<li <?php if ($_GET['sort'] == $this->_tpl_vars['sort']): ?> class="selected"<?php endif; ?>><a href="<?php echo cblink(array('name' => 'sort','sort' => $this->_tpl_vars['sort'],'type' => 'videos'), $this);?>
"><?php echo $this->_tpl_vars['name']; ?>
</a></li>
    <?php endforeach; endif; unset($_from); ?>
</ul>


</div>

<div class="time_cont">
<?php $this->assign('time_links', time_links()); ?>
<?php $_from = $this->_tpl_vars['time_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['times'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['times']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sort'] => $this->_tpl_vars['name']):
        $this->_foreach['times']['iteration']++;
?>
    <a href="<?php echo cblink(array('name' => 'time','sort' => $this->_tpl_vars['sort'],'type' => 'videos'), $this);?>
" <?php if ($_GET['time'] == $this->_tpl_vars['sort']): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['name']; ?>
</a> <?php if (! ($this->_foreach['times']['iteration'] == $this->_foreach['times']['total'])): ?>|<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</div>

    <div id="style_change" style="margin-top:10px">
			<div id="grid" onclick="ToggleView(this);" title="Change To Grid Style"></div> 
			<div id="list" onclick="ToggleView(this);" title="Change to List Style"></div>	
			<?php echo smarty_lang(array('code' => 'change_style_of_listing'), $this);?>

	</div> 
        
     <div id="videos_page">   
        <?php unset($this->_sections['v_list']);
$this->_sections['v_list']['name'] = 'v_list';
$this->_sections['v_list']['loop'] = is_array($_loop=$this->_tpl_vars['videos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['v_list']['show'] = true;
$this->_sections['v_list']['max'] = $this->_sections['v_list']['loop'];
$this->_sections['v_list']['step'] = 1;
$this->_sections['v_list']['start'] = $this->_sections['v_list']['step'] > 0 ? 0 : $this->_sections['v_list']['loop']-1;
if ($this->_sections['v_list']['show']) {
    $this->_sections['v_list']['total'] = $this->_sections['v_list']['loop'];
    if ($this->_sections['v_list']['total'] == 0)
        $this->_sections['v_list']['show'] = false;
} else
    $this->_sections['v_list']['total'] = 0;
if ($this->_sections['v_list']['show']):

            for ($this->_sections['v_list']['index'] = $this->_sections['v_list']['start'], $this->_sections['v_list']['iteration'] = 1;
                 $this->_sections['v_list']['iteration'] <= $this->_sections['v_list']['total'];
                 $this->_sections['v_list']['index'] += $this->_sections['v_list']['step'], $this->_sections['v_list']['iteration']++):
$this->_sections['v_list']['rownum'] = $this->_sections['v_list']['iteration'];
$this->_sections['v_list']['index_prev'] = $this->_sections['v_list']['index'] - $this->_sections['v_list']['step'];
$this->_sections['v_list']['index_next'] = $this->_sections['v_list']['index'] + $this->_sections['v_list']['step'];
$this->_sections['v_list']['first']      = ($this->_sections['v_list']['iteration'] == 1);
$this->_sections['v_list']['last']       = ($this->_sections['v_list']['iteration'] == $this->_sections['v_list']['total']);
?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/video.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['videos'][$this->_sections['v_list']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endfor; else: ?>
            <?php echo smarty_lang(array('code' => 'no_results_found'), $this);?>

        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
</div>

<div class="vert_add_box">
<div style="width:160px; margin:auto; margin-top:5px">
   <?php echo getAd(array('place' => 'ad_160x600'), $this);?>

</div>

</div>

<div class="clearfix" style="margin-bottom:10px"></div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/pagination.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>