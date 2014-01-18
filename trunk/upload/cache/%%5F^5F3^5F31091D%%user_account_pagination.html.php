<?php /* Smarty version 2.6.18, created on 2014-01-17 12:40:01
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/manage/user_account_pagination.html */ ?>
<div class="account_table">
 	<table width="100%" border="0" cellspacing="0" cellpadding="0">
 	  <tr>
 	    <td height="19" align="center" valign="middle" class="last_td">  Pages : <?php if ($this->_tpl_vars['first_link'] != ''): ?><a <?php echo $this->_tpl_vars['first_link']; ?>
>&laquo;</a><?php endif; ?>  <?php if ($this->_tpl_vars['pre_link'] != ''): ?><a <?php echo $this->_tpl_vars['pre_link']; ?>
 >&#8249;</a><?php endif; ?> <?php echo $this->_tpl_vars['pagination']; ?>
  <?php if ($this->_tpl_vars['next_link'] != ''): ?><a <?php echo $this->_tpl_vars['next_link']; ?>
>&#8250;</a><?php endif; ?> <?php if ($this->_tpl_vars['last_link'] != ''): ?><a <?php echo $this->_tpl_vars['last_link']; ?>
>&raquo;</a><?php endif; ?>
</td>
      </tr>
    </table>
</div>