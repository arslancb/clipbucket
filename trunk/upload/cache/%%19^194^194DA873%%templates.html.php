<?php /* Smarty version 2.6.18, created on 2014-01-15 12:18:18
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/templates.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '/var/www/clipbucket/admin_area/styles/cbv2/layout/templates.html', 10, false),)), $this); ?>
<h2>Selected Template</h2>
<div style="margin-top:10px">
<?php $this->assign('selected', $this->_tpl_vars['Cbucket']->configs['template_dir']); ?>
<?php $this->assign('curtpl', $this->_tpl_vars['cbtpl']->get_template_details($this->_tpl_vars['selected'])); ?>
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="190"><img src="<?php echo $this->_tpl_vars['cbtpl']->get_preview_thumb($this->_tpl_vars['curtpl']['dir']); ?>
" class="preview_thumb_template" ></td>
    <td valign="top"><h2 style="display:inline"><?php echo $this->_tpl_vars['curtpl']['name']; ?>
</h2> &#8212; <em><a href="<?php echo $this->_tpl_vars['curtpl']['website']['link']; ?>
"><strong><?php echo $this->_tpl_vars['curtpl']['author']; ?>
</strong></a></em><br />
<?php echo $this->_tpl_vars['curtpl']['description']; ?>
<br />
Version : <?php echo $this->_tpl_vars['curtpl']['version']; ?>
, Released on <?php echo ((is_array($_tmp=$this->_tpl_vars['curtpl']['released'])) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>
<br />
Website : <a href="<?php echo $this->_tpl_vars['curtpl']['website']['link']; ?>
"><?php echo $this->_tpl_vars['curtpl']['website']['title']; ?>
</a></td>
  </tr>
</table>


</div>

<hr size="1" noshade />

    
<?php $this->assign('templates', $this->_tpl_vars['cbtpl']->get_templates()); ?>
<?php if ($this->_tpl_vars['templates']): ?> 
    <h2>Available Templates</h2>
	   
    <div class="templates_container">
    <?php $_from = $this->_tpl_vars['templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['template']):
?>
    <?php if ($this->_tpl_vars['selected'] != $this->_tpl_vars['template']['dir']): ?>
        <div class="template_box" align="center">
            <img src="<?php echo $this->_tpl_vars['cbtpl']->get_preview_thumb($this->_tpl_vars['template']['dir']); ?>
" class="preview_thumb_template" >
    <div align="center" style="" class="tpl_title">
                <?php echo $this->_tpl_vars['template']['name']; ?>
 <br /><em>by <strong><?php echo $this->_tpl_vars['template']['author']; ?>
</strong></em><br />
            <a href="templates.php?change=<?php echo $this->_tpl_vars['template']['dir']; ?>
">Activate This Template</a><br />
            <a href="<?php echo $this->_tpl_vars['baseurl']; ?>
?template=<?php echo $this->_tpl_vars['template']['dir']; ?>
" target="_blank">Preview</a><br />
          <a href="<?php echo $this->_tpl_vars['baseurl']; ?>
?template=<?php echo $this->_tpl_vars['template']['dir']; ?>
&amp;set_template=yes" target="_blank">Preview &amp; Activate</a></div>
        </div>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    </div>
<?php else: ?>
	<div align="center">No New Template Found</div>
<?php endif; ?>