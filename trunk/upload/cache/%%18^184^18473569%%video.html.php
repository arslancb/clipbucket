<?php /* Smarty version 2.6.18, created on 2014-01-17 13:56:55
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/video.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'videoLink', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 10, false),array('function', 'getThumb', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 10, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 10, false),array('function', 'show_rating', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 22, false),array('modifier', 'SetTime', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 11, false),array('modifier', 'truncate', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 19, false),array('modifier', 'description', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 24, false),array('modifier', 'niceTime', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 26, false),array('modifier', 'strip_tags', '/var/www/clipbucket/styles/cbv2new/layout/blocks/video.html', 65, false),)), $this); ?>
<?php if ($this->_tpl_vars['cur_class'] == ''): ?>
<?php $this->assign('cur_class', $_COOKIE['current_style']); ?>
<?php endif; ?>


<?php if ($this->_tpl_vars['display_type'] == 'normal' || $this->_tpl_vars['display_type'] == ''): ?>
<!-- Video Box -->
<div id="vid_wrap_<?php echo $this->_tpl_vars['video']['videoid']; ?>
" class="<?php if ($this->_tpl_vars['video_view']): ?><?php echo $this->_tpl_vars['video_view']; ?>
<?php else: ?>grid_view <?php if ($this->_tpl_vars['cur_class'] == 'grid_view'): ?><?php else: ?><?php echo $this->_tpl_vars['cur_class']; ?>
<?php endif; ?><?php endif; ?>">
	<div class="vid_thumb">
    	<a href="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['video']), $this);?>
"><img src="<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['video']), $this);?>
" title="<?php echo $this->_tpl_vars['video']['title']; ?>
" alt="<?php echo $this->_tpl_vars['video']['title']; ?>
" <?php echo ANCHOR(array('place' => 'video_thumb','data' => $this->_tpl_vars['video']), $this);?>
 /></a>
        <span class="vid_time"><?php echo ((is_array($_tmp=$this->_tpl_vars['video']['duration'])) ? $this->_run_mod_handler('SetTime', true, $_tmp) : SetTime($_tmp)); ?>
</span>
		<img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="add_icon" onclick="add_quicklist(this,'<?php echo $this->_tpl_vars['video']['videoid']; ?>
')" title="Add <?php echo $this->_tpl_vars['video']['title']; ?>
 to Quicklist" alt="Quicklist" />   
        <?php if ($this->_tpl_vars['video']['broadcast'] == 'private'): ?>
        	<a href="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['video']), $this);?>
"><span class="private_video">&nbsp;</span></a>
        <?php endif; ?>
        <?php echo ANCHOR(array('place' => 'in_video_thumb','data' => $this->_tpl_vars['video']), $this);?>
    
    </div> <!--VID_THUMB END-->
    <div class="vid_info_wrap">
        <h2 class="title truncatedtitle"><a href="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['video']), $this);?>
" title="<?php echo $this->_tpl_vars['video']['title']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['video']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40) : smarty_modifier_truncate($_tmp, 40)); ?>
</a></h2>
        <h2 class="title fulltitle" style="display:none"><a href="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['video']), $this);?>
"><?php echo $this->_tpl_vars['video']['title']; ?>
</a></h2>
        <div class="list_rating">
        	<?php echo show_rating(array('class' => 'rating','rating' => $this->_tpl_vars['video']['rating'],'ratings' => $this->_tpl_vars['video']['rated_by'],'total' => '10'), $this);?>

		</div>            
        <p id="desc" class="vid_info"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['video']['description'])) ? $this->_run_mod_handler('description', true, $_tmp) : description($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 100) : smarty_modifier_truncate($_tmp, 100)); ?>
</p>
        <p class="vid_info"><?php echo $this->_tpl_vars['video']['views']; ?>
 View(<strong>s</strong>) <span class="list_commnets"> | <?php echo $this->_tpl_vars['video']['comments_count']; ?>
 Comment(<strong>s</strong>)</span></p>
        <p class="vid_info"><span class="info_list">Uploaded By </span><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['video']); ?>
"><?php echo $this->_tpl_vars['video']['username']; ?>
</a> <span class="list_up_time">(<?php echo ((is_array($_tmp=$this->_tpl_vars['video']['date_added'])) ? $this->_run_mod_handler('niceTime', true, $_tmp) : niceTime($_tmp)); ?>
)</span></p>
    </div> <!--VID_INFO_WRAP END-->
</div> <!--VID_WRAP END-->

<?php if ($this->_tpl_vars['only_once']): ?>
    <div class="clearfix"></div>
<?php endif; ?>
<!-- Video Box -->
<?php endif; ?>


<?php if ($this->_tpl_vars['display_type'] == 'channel_page'): ?>
	<li class="itemBox" onclick="loadObject(this,'videos','<?php echo $this->_tpl_vars['video']['videoid']; ?>
','viewingArea')">
    	<div align="center"><img src="<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['video']), $this);?>
" alt="<?php echo $this->_tpl_vars['video']['title']; ?>
" width="110" height="64" /></div>
    </li> <!-- itemBox <?php echo $this->_tpl_vars['video']['videokey']; ?>
 end -->
<?php endif; ?>

<?php if ($this->_tpl_vars['display_type'] == 'add_type'): ?>
<div class="vertical">
    <div class="video_thumb">
    	<?php echo ANCHOR(array('place' => 'in_video_thumb','data' => $this->_tpl_vars['video']), $this);?>

        <label for="check_video_<?php echo $this->_tpl_vars['video']['videoid']; ?>
"><img src="<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['video']), $this);?>
" width="110" height="66" border="0"  /></label>
        <?php if ($this->_tpl_vars['check_type'] == 'array'): ?>
        <input type="checkbox" name="check_video[]" id="check_video_<?php echo $this->_tpl_vars['video']['videoid']; ?>
" style="position:absolute; bottom:0px; left:0px" <?php if ($this->_tpl_vars['check_this']): ?> checked="checked"<?php endif; ?> value="<?php echo $this->_tpl_vars['video']['videoid']; ?>
" />
        <?php else: ?>
        <input type="checkbox" name="check_video_<?php echo $this->_tpl_vars['video']['videoid']; ?>
" id="check_video_<?php echo $this->_tpl_vars['video']['videoid']; ?>
" style="position:absolute; bottom:0px; left:0px" <?php if ($this->_tpl_vars['check_this']): ?> checked="checked"<?php endif; ?> value="yes" />
        <?php endif; ?>
        <div class="duration"><?php echo ((is_array($_tmp=$this->_tpl_vars['video']['duration'])) ? $this->_run_mod_handler('SetTime', true, $_tmp) : SetTime($_tmp)); ?>
</div>
        
</div> <!--VIDEO_THUMB END-->
    <div class="details_block">
        <div class="lvl1 clearfix">
            <div class="title"><a href="<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['video']), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['video']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30) : smarty_modifier_truncate($_tmp, 30)); ?>
</a></div>
            
            <?php echo show_rating(array('class' => 'rating','rating' => $this->_tpl_vars['video']['rating'],'ratings' => $this->_tpl_vars['video']['rated_by'],'total' => '10'), $this);?>

           
        </div> <!--LVL1 END-->
        
        <div class="lvl2 clearfix">
            <div class="desc"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['video']['description'])) ? $this->_run_mod_handler('description', true, $_tmp) : description($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 100) : smarty_modifier_truncate($_tmp, 100)); ?>
</div>
            <div class="length">Time: <span><?php echo ((is_array($_tmp=$this->_tpl_vars['video']['duration'])) ? $this->_run_mod_handler('SetTime', true, $_tmp, false) : SetTime($_tmp, false)); ?>
</span></div>
        </div> <!--LVL2 END-->
        
        <div class="lvl3 clearfix">
            <div class="views"><span><?php echo $this->_tpl_vars['video']['views']; ?>
</span> view(<strong>s</strong>)</div>
        </div> <!--LVL3 END-->
        
        <div class="lvl4 clearfix">
            <div class="uploaded">
             <a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['video']); ?>
"><?php echo $this->_tpl_vars['video']['username']; ?>
</a>
            </div>
        </div> <!--LVL4 END-->
        
            
    </div> <!--DETAILS_BLOCK END-->
    <div class="clearfix"></div>
 </div>  
<?php if ($this->_tpl_vars['only_once']): ?>
    <div class="clearfix"></div>
<?php endif; ?>

<?php endif; ?>