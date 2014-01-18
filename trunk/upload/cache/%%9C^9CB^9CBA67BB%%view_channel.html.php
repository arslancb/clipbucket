<?php /* Smarty version 2.6.18, created on 2014-01-17 12:33:36
         compiled from /var/www/clipbucket/styles/cbv2new/layout/view_channel.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 1, false),array('function', 'get_photos', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 18, false),array('function', 'get_videos', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 21, false),array('function', 'show_video_rating', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 152, false),array('function', 'input_value', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 189, false),array('modifier', 'nl2br', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 120, false),array('modifier', 'niceTime', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 126, false),array('modifier', 'number_format', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 157, false),array('modifier', 'country', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 178, false),array('modifier', 'sprintf', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 233, false),array('modifier', 'date_format', '/var/www/clipbucket/styles/cbv2new/layout/view_channel.html', 284, false),)), $this); ?>
<?php echo smarty_lang(array('code' => 'channel','assign' => 'object_type'), $this);?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/global_header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/view_channel/channel_global.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="container" class="container clearfix">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <div style="height:10px;"></div>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/message.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <div class="channel_inner_box" id="result_cont" style="display:none; background:#eee;"></div>
    
    <div class="moveL clearfix channelBox" style="width:270px;">
    	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/view_channel/channel_left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div> <!-- this is left side -->   
    <div class="moveL clearfix channelBox" style="width:700px;">
    <?php if (isSectionEnabled ( 'photos' ) || isSectionEnabled ( 'videos' )): ?>
    
    <?php if ($this->_tpl_vars['p']['show_my_photos'] != 'no'): ?>
 		<?php echo get_photos(array('assign' => 'latestP','order' => ' date_added DESC','limit' => 10,'user' => $this->_tpl_vars['u']['userid']), $this);?>

    <?php endif; ?>
    
    <?php echo get_videos(array('assign' => 'latestV','order' => ' date_added DESC','limit' => 10,'user' => $this->_tpl_vars['u']['userid'],'status' => 'Successful','broadcast' => 'public'), $this);?>
    
    <?php if ($this->_tpl_vars['latestP'] || $this->_tpl_vars['latestV']): ?>
    	<div class="channelFeatureBox clearfix">
         <?php if ($this->_tpl_vars['p']['show_my_photos'] != 'no' && $this->_tpl_vars['latestP']): ?>
         
        	<div class="itemListing moveL"> 
                <button id="PprevItem" class="carouselButton"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/up.png"></button>
                	<div id="photoListing">
                        <ul style="list-style:none; margin:0px; padding:0px;">
                        <?php if ($this->_tpl_vars['latestP']): ?>
                        test
                        <?php unset($this->_sections['plist']);
$this->_sections['plist']['name'] = 'plist';
$this->_sections['plist']['loop'] = is_array($_loop=$this->_tpl_vars['latestP']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                        	<?php if ($this->_sections['plist']['iteration'] == 1): ?>
								<?php if (! $this->_tpl_vars['profile_item']): ?>
                                	<?php $this->assign('profile_item', $this->_tpl_vars['latestP'][$this->_sections['plist']['index']]); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/photo.html", 'smarty_include_vars' => array('photo' => $this->_tpl_vars['latestP'][$this->_sections['plist']['index']],'display_type' => 'channel_page')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        <?php endfor; endif; ?>
                        <?php else: ?>
                        	<div style="min-height:200px; text-align:center; line-height:200px;"><em><?php echo smarty_lang(array('code' => 'found_no_photos'), $this);?>
</em></div>
                        <?php endif; ?>
                        </ul>
                    </div>
                    <button id="PnextItem" class="carouselButton"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/down.png"></button>
            </div> <!-- itemListing end -->
            <?php endif; ?>
            
            <?php if ($this->_tpl_vars['p']['show_my_photos'] != 'no' || $this->_tpl_vars['p']['show_my_videos'] != 'no'): ?> 
            <div class="viewItemBox">
            	<div id="viewingArea" class="viewingArea">
					 
                </div> <!-- viewingArea end -->
            </div> <!-- viewItemBox end -->
            <?php endif; ?>
            
            
            <?php if ($this->_tpl_vars['p']['show_my_videos'] != 'no' && $this->_tpl_vars['latestV']): ?> 
            <div class="itemListing moveR">              
                
                	<button id="VprevItem" class="carouselButton"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/up.png"></button>
                	<div id="videoListing">
                        <ul style="list-style:none; margin:0px; padding:0px;">
                       
                        <?php unset($this->_sections['vlist']);
$this->_sections['vlist']['name'] = 'vlist';
$this->_sections['vlist']['loop'] = is_array($_loop=$this->_tpl_vars['latestV']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['vlist']['show'] = true;
$this->_sections['vlist']['max'] = $this->_sections['vlist']['loop'];
$this->_sections['vlist']['step'] = 1;
$this->_sections['vlist']['start'] = $this->_sections['vlist']['step'] > 0 ? 0 : $this->_sections['vlist']['loop']-1;
if ($this->_sections['vlist']['show']) {
    $this->_sections['vlist']['total'] = $this->_sections['vlist']['loop'];
    if ($this->_sections['vlist']['total'] == 0)
        $this->_sections['vlist']['show'] = false;
} else
    $this->_sections['vlist']['total'] = 0;
if ($this->_sections['vlist']['show']):

            for ($this->_sections['vlist']['index'] = $this->_sections['vlist']['start'], $this->_sections['vlist']['iteration'] = 1;
                 $this->_sections['vlist']['iteration'] <= $this->_sections['vlist']['total'];
                 $this->_sections['vlist']['index'] += $this->_sections['vlist']['step'], $this->_sections['vlist']['iteration']++):
$this->_sections['vlist']['rownum'] = $this->_sections['vlist']['iteration'];
$this->_sections['vlist']['index_prev'] = $this->_sections['vlist']['index'] - $this->_sections['vlist']['step'];
$this->_sections['vlist']['index_next'] = $this->_sections['vlist']['index'] + $this->_sections['vlist']['step'];
$this->_sections['vlist']['first']      = ($this->_sections['vlist']['iteration'] == 1);
$this->_sections['vlist']['last']       = ($this->_sections['vlist']['iteration'] == $this->_sections['vlist']['total']);
?>
                        	<?php if (! $this->_tpl_vars['profile_item']): ?>
                                <?php $this->assign('profile_item', $this->_tpl_vars['latestV'][$this->_sections['vlist']['index']]); ?>
                            <?php endif; ?>
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/video.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['latestV'][$this->_sections['vlist']['index']],'display_type' => 'channel_page')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        <?php endfor; endif; ?>
                       
                 
                        </ul>
                    </div>
                    <button id="VnextItem" class="carouselButton"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/down.png"></button>                           
            </div> <!-- itemListing end -->
            <?php endif; ?>
        </div> <!-- channelFeatureBox end -->
        <?php endif; ?>
        <?php endif; ?>
        
                
        <?php if ($this->_tpl_vars['profile_item']): ?>
            <div id="profileItemScrap" style="display:none">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/view_channel/channel_item.html", 'smarty_include_vars' => array('object' => $this->_tpl_vars['profile_item'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
            <script>
                $(document).ready
                (
                    function()
                    {
                        $('#viewingArea').html($('#profileItemScrap').html());
                    }
                );
            </script>
        <?php endif; ?>
        
        <div class="clearfix channelBox" style="border:1px solid #ccc; padding:0px;">
        	<ul class="channelBar clearfix">
            	<?php if (isSectionEnabled ( 'feeds' )): ?>
            	<li><a href="#" rel="userFeeds" id="userFeedTab" onClick="channelObjects(this,'#channelDetailsBox','feeds','<?php echo $this->_tpl_vars['u']['userid']; ?>
'); return false;"><?php echo smarty_lang(array('code' => 'activity'), $this);?>
</a></li>
                <?php endif; ?>
            	<li><a href="#" rel="infoDIV"  id="infoTab" onClick="channelObjects(this,'#channelDetailsBox','info','<?php echo $this->_tpl_vars['u']['userid']; ?>
'); return false;"><?php echo smarty_lang(array('code' => 'info'), $this);?>
</a></li>
                <?php if (isSectionEnabled ( 'videos' )): ?>
                <li><a href="#" onClick="channelObjects(this,'#channelDetailsBox','videos','<?php echo $this->_tpl_vars['u']['userid']; ?>
'); return false;"><?php echo smarty_lang(array('code' => 'videos'), $this);?>
</a></li><?php endif; ?>
                <?php if (isSectionEnabled ( 'groups' )): ?>
                <li><a href="#" onClick="channelObjects(this,'#channelDetailsBox','groups','<?php echo $this->_tpl_vars['u']['userid']; ?>
'); return false;"><?php echo smarty_lang(array('code' => 'groups'), $this);?>
</a></li><?php endif; ?>
                <?php if (isSectionEnabled ( 'photos' )): ?>
                <li><a href="#" onClick="channelObjects(this,'#channelDetailsBox','photos','<?php echo $this->_tpl_vars['u']['userid']; ?>
'); return false;"><?php echo smarty_lang(array('code' => 'photos'), $this);?>
</a></li><?php endif; ?>
            </ul>
            <div id="channelDetailsBox">
            	<div id="infoDIV" class="channelInnerPadding clearfix" style="display:none">
            
            <fieldset class="channelFieldset">
            	<div style="width:500px; float:left; margin-right:10px; padding-right:10px;border-right:1px solid #999">
                    <?php if ("$".($this->_tpl_vars['p']).".profile_title"): ?>
                        <div class="channel_title"><?php echo $this->_tpl_vars['p']['profile_title']; ?>
</div>
                    <?php endif; ?>
                    <?php if ("$".($this->_tpl_vars['p']).".profile_desc"): ?>
                        <?php echo ((is_array($_tmp=$this->_tpl_vars['p']['profile_desc'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

                    <?php endif; ?>
                    
                    <div class="channelLegend" style="margin-top:5px"><?php echo smarty_lang(array('code' => 'user_activity'), $this);?>
</div>
        				<div class="channelRow">
                            <div class="channelLabel"><?php echo smarty_lang(array('code' => 'joined'), $this);?>
</div>
                            <div class="channelDetail"><?php echo ((is_array($_tmp=$this->_tpl_vars['u']['doj'])) ? $this->_run_mod_handler('niceTime', true, $_tmp) : niceTime($_tmp)); ?>
</div>
                        </div>
                        
                        <div class="channelRow">
                            <div class="channelLabel"><?php echo smarty_lang(array('code' => 'user_last_login'), $this);?>
</div>
                            <div class="channelDetail"><?php echo ((is_array($_tmp=$this->_tpl_vars['u']['last_logged'])) ? $this->_run_mod_handler('niceTime', true, $_tmp) : niceTime($_tmp)); ?>
</div>
                        </div>
                        
                        
                        <div class="channelRow">
                            <div class="channelLabel"><?php echo smarty_lang(array('code' => 'online_status'), $this);?>
</div>
                            <div class="channelDetail">
                            <?php if ($this->_tpl_vars['userquery']->isOnline($this->_tpl_vars['u']['last_active'],$this->_tpl_vars['p']['online_status'])): ?>
                            	<span style="color:#060"><?php echo smarty_lang(array('code' => 'online'), $this);?>
</span>
                            <?php else: ?>
                            	<span style="color:#ed0000"><?php echo smarty_lang(array('code' => 'offline'), $this);?>
</span>
                            <?php endif; ?>
                            </div>
                        </div>
                       
                        
                </div>
                
                <div style="width:160px;float:left; ">
                    <?php if ($this->_tpl_vars['p']['allow_ratings'] != 'no' && $this->_tpl_vars['p']['allow_ratings'] != 'No'): ?>
                        <div class="rating_container" style="height:auto; padding-right:0px; margin:0px auto 10px auto; border:0px">
                            <?php echo show_video_rating(array('rating' => $this->_tpl_vars['p']['rating'],'ratings' => $this->_tpl_vars['p']['rated_by'],'total' => '10','id' => $this->_tpl_vars['p']['userid'],'type' => 'user'), $this);?>

                        </div>
                    <?php endif; ?>
                    
                	<div class="channelCounts"><?php echo smarty_lang(array('code' => 'views'), $this);?>
<br>
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['u']['profile_hits'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 </div>
                                        <div class="channelCounts" ><?php echo smarty_lang(array('code' => 'subscribers'), $this);?>
<br>
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['u']['subscribers'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</div>
                </div>
                
                <div class="clear"></div>
                
                
            </fieldset>
            <?php $this->assign('channel_profile_fields', $this->_tpl_vars['userquery']->load_user_fields($this->_tpl_vars['p'],'profile')); ?>
            <?php $_from = $this->_tpl_vars['channel_profile_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profile_groups'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profile_groups']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field_group']):
        $this->_foreach['profile_groups']['iteration']++;
?>
                <?php if ($this->_foreach['profile_groups']['iteration'] == 2): ?>
                	<div id="moreDetailsDIV" style="display:none">
                <?php endif; ?>
                <?php if ($this->_tpl_vars['field_group']['channel_view'] != 'no'): ?>              
                    <fieldset class="channelFieldset">
                        <legend class="channelLegend"><?php echo $this->_tpl_vars['field_group']['group_name']; ?>
</legend>
                        
                        <?php if ($this->_tpl_vars['field_group']['group_id'] == 'profile_location'): ?>
                        	<div class="channelRow">
                                <div class="channelLabel"><?php echo smarty_lang(array('code' => 'country'), $this);?>
</div>
                                <div class="channelDetail"><?php echo ((is_array($_tmp=$this->_tpl_vars['u']['country'])) ? $this->_run_mod_handler('country', true, $_tmp) : get_country($_tmp)); ?>
</div>
                            </div>
                        <?php endif; ?>
                        
                       
                        
                        <?php $_from = $this->_tpl_vars['field_group']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
                        <?php if ($this->_tpl_vars['field']['auto_view'] == 'yes' && $this->_tpl_vars['field']['value']): ?>
                            <div class="channelRow">
                            <?php if ($this->_tpl_vars['field']['type'] != 'textarea' && $this->_tpl_vars['field']['type'] != 'text' && $this->_tpl_vars['field']['type'] != 'textfield'): ?>
                                <div class="channelLabel"><?php echo $this->_tpl_vars['field']['title']; ?>
</div>
                                <div class="channelDetail"><?php echo input_value(array('input' => $this->_tpl_vars['field']), $this);?>
</div>
                            <?php elseif ($this->_tpl_vars['field']['type'] == 'textarea'): ?>
                                <div class="channelLabel"><?php echo $this->_tpl_vars['field']['title']; ?>
</div>
                                <div class="channelDetail"><?php echo ((is_array($_tmp=$this->_tpl_vars['field']['value'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
                            <?php else: ?>
                                <div class="channelLabel"><?php echo $this->_tpl_vars['field']['title']; ?>
</div>
                                <div class="channelDetail"><?php echo input_value(array('input' => $this->_tpl_vars['field']), $this);?>
</div>                    
                            <?php endif; ?>                                                
                            </div>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['field_group']['group_id'] == 'profile_basic_info' && $this->_tpl_vars['field']['name'] == 'relation_status' && $this->_tpl_vars['p']['show_dob'] != 'no'): ?>
                        	<div class="channelRow">
                                <div class="channelLabel"><?php echo smarty_lang(array('code' => 'user_date_of_birth'), $this);?>
</div>
                                <div class="channelDetail"><?php echo $this->_tpl_vars['u']['dob']; ?>
</div>
                            </div>
                         <?php endif; ?> 
                        <?php endforeach; endif; unset($_from); ?>                                       
                    </fieldset>
                  
                <?php endif; ?>
               <?php if (($this->_foreach['profile_groups']['iteration'] == $this->_foreach['profile_groups']['total'])): ?>
                </div>
                <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
            
			<fieldset id="moreDetails" class="channelFieldset">
            	<legend class="channelLegend" style=" margin-bottom:0px;" onClick="$('#moreDetails').hide(); $('#moreDetailsDIV').show();"><?php echo smarty_lang(array('code' => 'more'), $this);?>
</legend>
            </fieldset>
            
            </div>
            	
                
                <?php if (isSectionEnabled ( 'feeds' )): ?>
                <div id="userFeeds"  class="channelInnerPadding clearfix" style="display:none">
                	<?php $this->assign('userFeeds', $this->_tpl_vars['cbfeeds']->getUserFeeds($this->_tpl_vars['u'])); ?>
                    
                    <?php if ($this->_tpl_vars['userFeeds']): ?>
                        <?php $_from = $this->_tpl_vars['userFeeds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['userFeed']):
?>
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/feed.html", 'smarty_include_vars' => array('feed' => $this->_tpl_vars['userFeed'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        <?php endforeach; endif; unset($_from); ?>
                    <?php else: ?>
                        <div align="center" class="no_comments">
                            <?php echo smarty_lang(array('code' => 'no_activity','assign' => 'no_activity'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['no_activity'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['u']['username']) : sprintf($_tmp, $this->_tpl_vars['u']['username'])); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="borderLine clearfix" style=" border-width:1px; margin:0px 0px 15px;"></div>
			
            <div class="channelInnerPadding" id="commentsDIV">
            	<fieldset class="channelFieldset">
                	<legend class="channelLegend"><?php echo smarty_lang(array('code' => 'comments'), $this);?>
</legend>


<div id="comments"></div>
<script>
	$(document).ready(function()	
	{
		<?php if (isSectionEnabled ( 'feeds' )): ?>
			channelObjects('#userFeedTab','#channelDetailsBox','feeds','<?php echo $this->_tpl_vars['u']['userid']; ?>
');
		<?php else: ?>
			channelObjects('#infoTab','#channelDetailsBox','info','<?php echo $this->_tpl_vars['u']['userid']; ?>
');
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['p']['show_my_photos'] != 'yes' || ! $this->_tpl_vars['latestV'] || ! $this->_tpl_vars['latestP']): ?>
			var viewingAreaParent = $('#viewingArea').parent().parent().width();	
			$('#viewingArea') .width(viewingAreaParent-180)	
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['myquery']->is_commentable($this->_tpl_vars['p'],'u')): ?>
		comments_voting = 'no';	
		getComments('c','<?php echo $this->_tpl_vars['u']['userid']; ?>
','<?php echo $this->_tpl_vars['u']['last_commented']; ?>
',1,'<?php echo $this->_tpl_vars['u']['comments_count']; ?>
','<?php echo $this->_tpl_vars['object_type']; ?>
')
		<?php endif; ?>
	}
	);
</script>
<hr width="100%" size="1" noshade>
            
            <?php if ($this->_tpl_vars['myquery']->is_commentable($this->_tpl_vars['p'],'u')): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/comments/add_comment.html", 'smarty_include_vars' => array('id' => $this->_tpl_vars['u']['userid'],'type' => 'c')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php else: ?>
    			<div class="disable_msg" align="center"><?php echo smarty_lang(array('code' => 'coments_disabled_profile'), $this);?>
</div>
    		<?php endif; ?>                    
                </fieldset>
            </div>            
        </div>
    </div> <!-- this is right side -->       
</div> <!-- container end -->
    
<div class="clearfix" style="height:10px"></div>
<div id="footer" class="clearfix" style="margin-top:0px;">
    <div class="footer">
        &copy; <?php echo $this->_tpl_vars['title']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>

    </div>
</div> 



<script>var funcToBCalled = '';
$(document).ready(function(){
	var firstChild = $('.itemBox:first-child');
	funcToBCalled = firstChild.attr('onclick');
	;
	}
	).bind(funcToBCalled);
	
	<?php if (@THIS_PAGE == 'view_channel'): ?>	
		
		<?php if ($this->_tpl_vars['p']['show_my_photos'] == 'yes' && $this->_tpl_vars['latestP']): ?>
			
			$('#photoListing').jCarouselLite({
				vertical : true,
				btnNext : "#PnextItem",
				btnPrev : "#PprevItem",
				circular : false,
				speed : 500,
				visible	: 5
			});
		<?php endif; ?>
		<?php if ($this->_tpl_vars['p']['show_my_videos'] == 'yes' && $this->_tpl_vars['latestV']): ?>		
		$('#videoListing').jCarouselLite({
			vertical : true,
			btnNext : "#VnextItem",
			btnPrev : "#VprevItem",
			circular : false,
			speed : 500,
			visible	: 5
		});
		<?php endif; ?>
		
	<?php endif; ?>
</script>
</body>
</html>