<?php /* Smarty version 2.6.18, created on 2014-01-17 13:57:00
         compiled from /var/www/clipbucket/styles/cbv2new/layout//blocks/collection_form.html */ ?>
<!-- Add To Collection This <?php echo $this->_tpl_vars['type']; ?>
 -->
<div id="collection_form" class="action_box"  >
	<div class="action_box_title">Add this <?php echo $this->_tpl_vars['params']['type']; ?>
 to collection <?php if ($this->_tpl_vars['params']['type'] == 'video'): ?> or playlist<?php endif; ?></span></div>
    <div class="form_container" align="center">
    
     <div class="form_result" id="collection_form_result" style="display:none"></div>
     
    <form id="add_collection_form" name="collection_form" method="post" action="" class="">
    Please select collection name from following<br>
	<select name="collection" id="collection" style="font:normal 11px Tahoma, Geneva, sans-serif;">
    <?php unset($this->_sections['clist']);
$this->_sections['clist']['name'] = 'clist';
$this->_sections['clist']['loop'] = is_array($_loop=$this->_tpl_vars['collections']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['clist']['show'] = true;
$this->_sections['clist']['max'] = $this->_sections['clist']['loop'];
$this->_sections['clist']['step'] = 1;
$this->_sections['clist']['start'] = $this->_sections['clist']['step'] > 0 ? 0 : $this->_sections['clist']['loop']-1;
if ($this->_sections['clist']['show']) {
    $this->_sections['clist']['total'] = $this->_sections['clist']['loop'];
    if ($this->_sections['clist']['total'] == 0)
        $this->_sections['clist']['show'] = false;
} else
    $this->_sections['clist']['total'] = 0;
if ($this->_sections['clist']['show']):

            for ($this->_sections['clist']['index'] = $this->_sections['clist']['start'], $this->_sections['clist']['iteration'] = 1;
                 $this->_sections['clist']['iteration'] <= $this->_sections['clist']['total'];
                 $this->_sections['clist']['index'] += $this->_sections['clist']['step'], $this->_sections['clist']['iteration']++):
$this->_sections['clist']['rownum'] = $this->_sections['clist']['iteration'];
$this->_sections['clist']['index_prev'] = $this->_sections['clist']['index'] - $this->_sections['clist']['step'];
$this->_sections['clist']['index_next'] = $this->_sections['clist']['index'] + $this->_sections['clist']['step'];
$this->_sections['clist']['first']      = ($this->_sections['clist']['iteration'] == 1);
$this->_sections['clist']['last']       = ($this->_sections['clist']['iteration'] == $this->_sections['clist']['total']);
?>
    	<option value="<?php echo $this->_tpl_vars['collections'][$this->_sections['clist']['index']]['collection_id']; ?>
"><?php echo $this->_tpl_vars['collections'][$this->_sections['clist']['index']]['collection_name']; ?>
 (<?php echo $this->_tpl_vars['collections'][$this->_sections['clist']['index']]['total_objects']; ?>
)</option>
    <?php endfor; else: ?>
    	<option>No Collection Found</option>    
    <?php endif; ?>
    </select>

    <input type="button" name="add_to_playlist" value="Add to collection" class="cb_button" onclick="collection_actions('add_collection_form','add_new_item','<?php echo $this->_tpl_vars['id']; ?>
','#collection_form_result','video');"/>
    </form>
    </div>
</div>
<!-- Add To Collection This <?php echo $this->_tpl_vars['type']; ?>
 -->