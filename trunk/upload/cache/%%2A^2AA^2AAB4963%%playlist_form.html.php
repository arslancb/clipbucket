<?php /* Smarty version 2.6.18, created on 2014-01-17 13:57:00
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/playlist_form.html */ ?>
<!-- Add To Playlist This <?php echo $this->_tpl_vars['type']; ?>
 -->
<div id="playlist_form" class="action_box" style="display:none" >
	<div class="action_box_title">Add this <?php echo $this->_tpl_vars['params']['type']; ?>
 to playlist &#8212; <span class="cancel"><a href="javascript:void(0)" onclick="$('#playlist_form').slideUp();">cancel</a></span></div>
    <div class="form_container" align="center">
    
     <div class="form_result" id="playlist_form_result" style="display:none"></div>
     
    <form id="add_playlist_form" name="playlist_form" method="post" action="" class="">
    Please select playlist name from following<br>
	<select name="playlist_id" id="playlist_id">
        <?php unset($this->_sections['plist']);
$this->_sections['plist']['name'] = 'plist';
$this->_sections['plist']['loop'] = is_array($_loop=$this->_tpl_vars['playlists']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['plist']['show'] = true;
$this->_sections['plist']['max'] = $this->_sections['plist']['loop'];
$this->_sections['plist']['step'] = 1;
$this->_sections['plist']['start'] = $this->_sections['plist']['step'] > 0 ? 0 : $this->_sections['plist']['loop']-1;
if ($this->_sections['plist']['show']) {
    $this->_sections['plist']['total'] = $this->_sections['plist']['loop'];
    if ($this->_sections['plist']['total'] == 0)
        $this->_sections['plist']['show'] = false;
} else
    $this->_sections['plist']['total'] = 0;
if ($this->_sections['plist']['show']):

            for ($this->_sections['plist']['index'] = $this->_sections['plist']['start'], $this->_sections['plist']['iteration'] = 1;
                 $this->_sections['plist']['iteration'] <= $this->_sections['plist']['total'];
                 $this->_sections['plist']['index'] += $this->_sections['plist']['step'], $this->_sections['plist']['iteration']++):
$this->_sections['plist']['rownum'] = $this->_sections['plist']['iteration'];
$this->_sections['plist']['index_prev'] = $this->_sections['plist']['index'] - $this->_sections['plist']['step'];
$this->_sections['plist']['index_next'] = $this->_sections['plist']['index'] + $this->_sections['plist']['step'];
$this->_sections['plist']['first']      = ($this->_sections['plist']['iteration'] == 1);
$this->_sections['plist']['last']       = ($this->_sections['plist']['iteration'] == $this->_sections['plist']['total']);
?>
            <option value="<?php echo $this->_tpl_vars['playlists'][$this->_sections['plist']['index']]['playlist_id']; ?>
"><?php echo $this->_tpl_vars['playlists'][$this->_sections['plist']['index']]['playlist_name']; ?>
</option>
        <?php endfor; endif; ?>
    </select> or <a href="javascript:void(0)" onClick="$('#add_playlist_form').css('display','none');$('#new_playlist_form').css('display','block')">create new playlist </a><br>

    <input type="button" name="add_to_playlist" value="Add to playlist" class="cb_button" onclick="add_playlist('add','<?php echo $this->_tpl_vars['params']['id']; ?>
','add_playlist_form','<?php echo $this->_tpl_vars['type']; ?>
')"/>
    </form>
    
    <form id="new_playlist_form" name="new_playlist_form" method="post" action="" class="" style="display:none">
        <label for="playlist_name">Enter Playlist name</label>
        <input name="playlist_name" type="text" class="left_text_area" id="playlist_name" value="" size="45"  style="width:300px"/> or <a href="javascript:void(0)"  onClick="$('#add_playlist_form').css('display','block');$('#new_playlist_form').css('display','none')">Select from list </a><br>
        <input type="button" name="add_new_playlist" value="Add new playlist" class="cb_button" onclick="add_playlist('new','<?php echo $this->_tpl_vars['params']['id']; ?>
','new_playlist_form','<?php echo $this->_tpl_vars['type']; ?>
')"/>
    </form>
    </div>
</div>
<!-- Add To Playlist This <?php echo $this->_tpl_vars['type']; ?>
 -->