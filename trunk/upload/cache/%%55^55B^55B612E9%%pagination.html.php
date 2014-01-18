<?php /* Smarty version 2.6.18, created on 2014-01-17 12:40:27
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/pagination.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/pagination.html', 5, false),)), $this); ?>
<div class="pagination" align="center"> 
<?php if ($this->_tpl_vars['pagination']): ?>

	<?php if (! $this->_tpl_vars['commentPagination']): ?>
       <?php echo smarty_lang(array('code' => 'Pages'), $this);?>
 : <?php if ($this->_tpl_vars['first_link'] != ''): ?><a <?php echo $this->_tpl_vars['first_link']; ?>
>&laquo;</a><?php endif; ?>  <?php if ($this->_tpl_vars['pre_link'] != ''): ?><a <?php echo $this->_tpl_vars['pre_link']; ?>
 >&#8249;</a><?php endif; ?> <?php echo $this->_tpl_vars['pagination']; ?>
  <?php if ($this->_tpl_vars['next_link'] != ''): ?><a <?php echo $this->_tpl_vars['next_link']; ?>
>&#8250;</a><?php endif; ?> <?php if ($this->_tpl_vars['last_link'] != ''): ?><a <?php echo $this->_tpl_vars['last_link']; ?>
>&raquo;</a><?php endif; ?>
      
    <?php else: ?>
    
      <?php echo smarty_lang(array('code' => 'Pages'), $this);?>
 : <?php if ($this->_tpl_vars['first_link'] != ''): ?>
        <a href="javascript:void(0)" onclick="getComments('<?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['type_id']; ?>
','<?php echo $this->_tpl_vars['last_update']; ?>
','1','<?php echo $this->_tpl_vars['total']; ?>
','<?php echo $this->_tpl_vars['object_type']; ?>
')">&laquo;</a>
      <?php endif; ?>  
      
      <?php if ($this->_tpl_vars['pre_link'] != ''): ?><a href="javascript:void(0)" 
       onclick="getComments('<?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['type_id']; ?>
','<?php echo $this->_tpl_vars['last_update']; ?>
','<?php echo $this->_tpl_vars['pre_page']; ?>
','<?php echo $this->_tpl_vars['total']; ?>
','<?php echo $this->_tpl_vars['object_type']; ?>
')" >&#8249;</a><?php endif; ?> 
      
      <?php echo $this->_tpl_vars['pagination']; ?>
  
      
      <?php if ($this->_tpl_vars['next_link'] != ''): ?><a href="javascript:void(0)"  
      onclick="getComments('<?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['type_id']; ?>
','<?php echo $this->_tpl_vars['last_update']; ?>
','<?php echo $this->_tpl_vars['next_page']; ?>
','<?php echo $this->_tpl_vars['total']; ?>
','<?php echo $this->_tpl_vars['object_type']; ?>
')">&#8250;</a><?php endif; ?> 
      
      <?php if ($this->_tpl_vars['last_link'] != ''): ?>
        <a href="javascript:void(0)"
        onclick=" getComments('<?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['type_id']; ?>
','<?php echo $this->_tpl_vars['last_update']; ?>
','<?php echo $this->_tpl_vars['total_pages']; ?>
','<?php echo $this->_tpl_vars['total']; ?>
','<?php echo $this->_tpl_vars['object_type']; ?>
')" >&raquo;</a>
      <?php endif; ?>
    <?php endif; ?>
  
<?php endif; ?>
</div>