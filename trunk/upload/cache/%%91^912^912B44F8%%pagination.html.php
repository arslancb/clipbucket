<?php /* Smarty version 2.6.18, created on 2014-01-17 13:22:55
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/blocks/pagination.html */ ?>
<div class="pagination" align="center"> 
<?php if ($this->_tpl_vars['pagination']): ?>
  Pages : <?php if ($this->_tpl_vars['first_link'] != ''): ?><a <?php echo $this->_tpl_vars['first_link']; ?>
>&laquo;</a><?php endif; ?>  <?php if ($this->_tpl_vars['pre_link'] != ''): ?><a <?php echo $this->_tpl_vars['pre_link']; ?>
 >&#8249;</a><?php endif; ?> <?php echo $this->_tpl_vars['pagination']; ?>
  <?php if ($this->_tpl_vars['next_link'] != ''): ?><a <?php echo $this->_tpl_vars['next_link']; ?>
>&#8250;</a><?php endif; ?> <?php if ($this->_tpl_vars['last_link'] != ''): ?><a <?php echo $this->_tpl_vars['last_link']; ?>
>&raquo;</a><?php endif; ?>
<?php endif; ?>
</div>