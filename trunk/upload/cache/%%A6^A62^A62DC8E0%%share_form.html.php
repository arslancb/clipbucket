<?php /* Smarty version 2.6.18, created on 2014-01-17 13:57:00
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/share_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/share_form.html', 11, false),array('function', 'videoLink', '/var/www/clipbucket/styles/cbv2new/layout/blocks/share_form.html', 57, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/blocks/share_form.html', 79, false),array('modifier', 'htmlspecialchars', '/var/www/clipbucket/styles/cbv2new/layout/blocks/share_form.html', 12, false),array('modifier', 'sprintf', '/var/www/clipbucket/styles/cbv2new/layout/blocks/share_form.html', 66, false),)), $this); ?>
<!-- Share This <?php echo $this->_tpl_vars['type']; ?>
 -->
<div id="share_form" class="action_box share_form clearfix" style="display:none">
	<div class="action_box_title">Share <?php if ($this->_tpl_vars['params']['type'] == 'v' || $this->_tpl_vars['params']['type'] == 'video' || $this->_tpl_vars['params']['type'] == 'Video'): ?>or Embed<?php endif; ?> this <?php echo $this->_tpl_vars['params']['type']; ?>
 &#8212; <span class="cancel"><a href="javascript:void(0)" onclick="$('#share_form').slideUp();">cancel</a></span></div>
<?php if ($this->_tpl_vars['params']['type'] == 'v' || $this->_tpl_vars['params']['type'] == 'video' || $this->_tpl_vars['params']['type'] == 'Video'): ?>    
  <div style="margin:0px 0px 5px 0px">
    
    

<?php if ($this->_tpl_vars['Cbucket']->configs['video_embed'] == 1 && $this->_tpl_vars['vdo']['allow_embedding'] == 'yes'): ?>

<div class="form_container"><label for="embed_code" style="font-size:12px; font-weight:bold"><?php echo smarty_lang(array('code' => 'embed_code'), $this);?>
</label>
<textarea name="embed_code" id="embed_code" style="margin-bottom:5px; width:99%; height:auto" onclick="this.select()"  class="left_text_area"><?php if ($this->_tpl_vars['Cbucket']->configs['embed_type'] == 'iframe'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['cbvid']->embed_code($this->_tpl_vars['vdo'],'iframe'))) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['cbvid']->embed_code($this->_tpl_vars['vdo'],'embed_object'))) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
<?php endif; ?></textarea>

<input type="hidden" name="alternate_embed_code" id="alternate_embed_code" value="<?php if ($this->_tpl_vars['Cbucket']->configs['embed_type'] == 'iframe'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['cbvid']->embed_code($this->_tpl_vars['vdo'],'embed_object'))) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['cbvid']->embed_code($this->_tpl_vars['vdo'],'iframe'))) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
<?php endif; ?>" />
</div>

Player size : 

  <label>
    <input type="radio" name="player_size" value="default" id="player_size_0" checked="checked"
    	onclick="<?php echo $this->_tpl_vars['updateEmbedCode']; ?>
('<?php echo $this->_tpl_vars['Cbucket']->configs['embed_player_width']; ?>
'
        		,'<?php echo $this->_tpl_vars['Cbucket']->configs['embed_player_height']; ?>
',autoPlayEmbed)"/>
    Default (<?php echo $this->_tpl_vars['Cbucket']->configs['embed_player_width']; ?>
x<?php echo $this->_tpl_vars['Cbucket']->configs['embed_player_height']; ?>
)</label>
  
  <label>
    <input type="radio" name="player_size" value="small" id="player_size_1" onclick="<?php echo $this->_tpl_vars['updateEmbedCode']; ?>
('440','272',autoPlayEmbed)" />
    Small (440x272)
</label>
  
  <label>
    <input type="radio" name="player_size" value="medium" id="player_size_2"  onclick="<?php echo $this->_tpl_vars['updateEmbedCode']; ?>
('540','334',autoPlayEmbed)"/>
    Medium (540x334)
</label>

  <label>
    <input type="radio" name="player_size" value="large" id="player_size_3"  onclick="<?php echo $this->_tpl_vars['updateEmbedCode']; ?>
('600','370',autoPlayEmbed)"/>
    Large (600x370)</label>
<?php if ($this->_tpl_vars['pakplayer'] == 'yes'): ?>
<div>Autoplay video : <label><input type="radio" name="autoplay" value="yes" onclick="<?php echo $this->_tpl_vars['updateEmbedCode']; ?>
(embedPlayerWidth,embedPlayerHeight,'yes')" <?php if ($this->_tpl_vars['Cbucket']->configs['autoplay_embed'] == 'yes'): ?> checked<?php endif; ?> />Yes</label>
						<label><input type="radio" name="autoplay" value="no"  onclick="<?php echo $this->_tpl_vars['updateEmbedCode']; ?>
(embedPlayerWidth,embedPlayerHeight,'no')" <?php if ($this->_tpl_vars['Cbucket']->configs['autoplay_embed'] != 'yes'): ?> checked<?php endif; ?>/>No</label></div>
<?php endif; ?>
<div>Embed type : <label><input type="radio" name="embed_type" value="iframe" onclick="switchEmbedCode('iframe')" <?php if ($this->_tpl_vars['Cbucket']->configs['embed_type'] == 'iframe'): ?> checked<?php endif; ?> />Iframe</label>
						<label><input type="radio" name="embed_type" value="iframe"  onclick="switchEmbedCode('embed_object')" <?php if ($this->_tpl_vars['Cbucket']->configs['embed_type'] == 'embed_object'): ?> checked<?php endif; ?>/>Embed Object</label></div>
                                                
                        
                        

<?php endif; ?>
      
      
    
  </div>

    <div class="action_box_title"></div>
       <div class="form_container"> <label for="link_video"  style="font-size:12px; font-weight:bold"><?php echo smarty_lang(array('code' => 'link_this_video'), $this);?>
<br />
      </label>
      <input type="text" name="link_video" id="link_video" style="width:99%;margin-bottom:5px; " value="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['vdo']), $this);?>
"  onclick="this.select()"  class="left_text_area" /></div>
      
    <div class="action_box_title"></div>
      <?php endif; ?>
    
    
    <div class="form_container"><div id="share_form_results" class="form_result" style="display:none"></div>
    <form id="share_form" name="share_form" method="post" action="" class="">
        	<div class="form_left">
           	  <label for="users"><?php echo smarty_lang(array('code' => 'share_this_type','assign' => 'share_this_type'), $this);?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['share_this_type'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['type']) : sprintf($_tmp, $this->_tpl_vars['type'])); ?>
</label>
              <?php echo smarty_lang(array('code' => 'seperate_usernames_with_comma'), $this);?>

              <input name="users" type="text" class="left_text_area" id="ShareUsers" value="" size="45" />
              <label for="message">Message <span class="example">optional</span></label>
              <textarea name="message" id="message" cols="45" rows="5" class="left_text_area"></textarea>
              <input name="objectid" id="objectid" type="hidden" value="<?php echo $this->_tpl_vars['params']['id']; ?>
" />
              <div align="right"><input type="button" name="send_content" value="Send" class="cb_button" onclick="submit_share_form('share_form','<?php echo $this->_tpl_vars['params']['type']; ?>
')"/></div>
       		</div>

      </form>
    </div>
    
    
    <?php echo ANCHOR(array('place' => 'video_sharing_options','data' => $this->_tpl_vars['vdo']), $this);?>

</div>
<!-- Share This <?php echo $this->_tpl_vars['type']; ?>
 -->