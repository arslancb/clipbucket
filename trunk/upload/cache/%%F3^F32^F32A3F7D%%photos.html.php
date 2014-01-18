<?php /* Smarty version 2.6.18, created on 2014-01-17 12:41:04
         compiled from /var/www/clipbucket/styles/cbv2new/layout/photos.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/photos.html', 5, false),array('function', 'link', '/var/www/clipbucket/styles/cbv2new/layout/photos.html', 8, false),array('function', 'AD', '/var/www/clipbucket/styles/cbv2new/layout/photos.html', 33, false),)), $this); ?>
<div style="width:810px; float:left" class="vid_page_conainer" >
<div class="sort_cont">

<ul>
	<li><?php echo smarty_lang(array('code' => 'sort_by'), $this);?>
 :</li>
    <?php $this->assign('sorting_links', sorting_links()); ?>
    <?php $_from = $this->_tpl_vars['sorting_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sort'] => $this->_tpl_vars['name']):
?>
    	<li <?php if ($_GET['sort'] == $this->_tpl_vars['sort']): ?> class="selected"<?php endif; ?>><a href="<?php echo cblink(array('name' => 'sort','sort' => $this->_tpl_vars['sort'],'type' => 'photos'), $this);?>
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
    <a href="<?php echo cblink(array('name' => 'time','sort' => $this->_tpl_vars['sort'],'type' => 'photos'), $this);?>
" <?php if ($_GET['time'] == $this->_tpl_vars['sort']): ?> class="selected"<?php endif; ?>><?php echo $this->_tpl_vars['name']; ?>
</a> <?php if (! ($this->_foreach['times']['iteration'] == $this->_foreach['times']['total'])): ?>|<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</div>        
     <div id="photos_page">   
        <?php unset($this->_sections['p_list']);
$this->_sections['p_list']['name'] = 'p_list';
$this->_sections['p_list']['loop'] = is_array($_loop=$this->_tpl_vars['photos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['p_list']['show'] = true;
$this->_sections['p_list']['max'] = $this->_sections['p_list']['loop'];
$this->_sections['p_list']['step'] = 1;
$this->_sections['p_list']['start'] = $this->_sections['p_list']['step'] > 0 ? 0 : $this->_sections['p_list']['loop']-1;
if ($this->_sections['p_list']['show']) {
    $this->_sections['p_list']['total'] = $this->_sections['p_list']['loop'];
    if ($this->_sections['p_list']['total'] == 0)
        $this->_sections['p_list']['show'] = false;
} else
    $this->_sections['p_list']['total'] = 0;
if ($this->_sections['p_list']['show']):

            for ($this->_sections['p_list']['index'] = $this->_sections['p_list']['start'], $this->_sections['p_list']['iteration'] = 1;
                 $this->_sections['p_list']['iteration'] <= $this->_sections['p_list']['total'];
                 $this->_sections['p_list']['index'] += $this->_sections['p_list']['step'], $this->_sections['p_list']['iteration']++):
$this->_sections['p_list']['rownum'] = $this->_sections['p_list']['iteration'];
$this->_sections['p_list']['index_prev'] = $this->_sections['p_list']['index'] - $this->_sections['p_list']['step'];
$this->_sections['p_list']['index_next'] = $this->_sections['p_list']['index'] + $this->_sections['p_list']['step'];
$this->_sections['p_list']['first']      = ($this->_sections['p_list']['iteration'] == 1);
$this->_sections['p_list']['last']       = ($this->_sections['p_list']['iteration'] == $this->_sections['p_list']['total']);
?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/photo.html", 'smarty_include_vars' => array('photo' => $this->_tpl_vars['photos'][$this->_sections['p_list']['index']])));
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
<div style="width:160px; margin:auto; margin-top:5px;">
   <?php echo getAd(array('place' => 'ad_160x600'), $this);?>

</div>

</div>

<div class="clearfix" style="margin-bottom:10px"></div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/pagination.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>