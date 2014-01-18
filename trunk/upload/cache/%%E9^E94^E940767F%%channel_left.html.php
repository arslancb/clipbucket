<?php /* Smarty version 2.6.18, created on 2014-01-17 12:33:36
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ANCHOR', '/var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html', 8, false),array('function', 'avatar', '/var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html', 9, false),array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html', 16, false),array('function', 'link', '/var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html', 33, false),array('function', 'get_collections', '/var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html', 49, false),array('function', 'show_flag_form', '/var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html', 81, false),array('modifier', 'sprintf', '/var/www/clipbucket/styles/cbv2new/layout/blocks/view_channel/channel_left.html', 63, false),)), $this); ?>
<div class="clearfix channelBox">
	
	<div class="viewChannelProfileThumb_outline"><div class="channelHeading" style="margin:0 0 5px 0;"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['u']); ?>
">
    <?php $this->assign('category', $this->_tpl_vars['userquery']->get_category($this->_tpl_vars['u']['category'])); ?>
    
   
    
    <?php echo $this->_tpl_vars['u']['username']; ?>
</a> (<?php echo $this->_tpl_vars['category']['category_name']; ?>
)</div> <?php if ($this->_tpl_vars['userquery']->perm_check('admin_access') == 'yes'): ?><div><?php echo ANCHOR(array('place' => 'view_channel_admin_options','data' => $this->_tpl_vars['u']), $this);?>
</div><?php endif; ?><div class="viewChannelProfileThumb" align="center"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['u']); ?>
">
    <img src="<?php echo avatar(array('details' => $this->_tpl_vars['u']), $this);?>
" style="border:1px solid #ccc; padding:1px; margin-right:5px;" /></a></div></div>
    
	<?php $this->assign('isSubscribed', $this->_tpl_vars['userquery']->is_subscribed($this->_tpl_vars['u']['userid'])); ?>
    <div style="height:10px"></div>
    				<ul class="channel_action_links">			
    <?php if (! $this->_tpl_vars['isSubscribed'] && $this->_tpl_vars['p']['allow_subscription'] != 'no'): ?>
                    <li><a href="javascript:void(0)" 
                    onClick="subscriber('<?php echo $this->_tpl_vars['u']['userid']; ?>
','subscribe_user','result_cont')"><?php echo smarty_lang(array('code' => 'subscribe'), $this);?>
</a></li>
                    
                    <?php elseif ($this->_tpl_vars['isSubscribed']): ?>
                   <li><a href="javascript:void(0)" 
                    onClick="subscriber('<?php echo $this->_tpl_vars['u']['userid']; ?>
','unsubscribe_user','result_cont')"><?php echo smarty_lang(array('code' => 'unsubscribe'), $this);?>
</a></li>
                    <?php endif; ?>
                    
                    <?php $this->assign('channel_action_links', $this->_tpl_vars['userquery']->get_channel_action_links($this->_tpl_vars['u'])); ?>
                    
                    <?php $_from = $this->_tpl_vars['channel_action_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link_title'] => $this->_tpl_vars['link']):
?>
                        <li><a href="<?php echo $this->_tpl_vars['link']['link']; ?>
" <?php if ($this->_tpl_vars['link']['onclick']): ?>onClick="<?php echo $this->_tpl_vars['link']['onclick']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['link_title']; ?>
</a></li>
                    <?php endforeach; endif; unset($_from); ?>
                    </ul>    
</div>

<?php if ($this->_tpl_vars['p']['show_my_friends'] != 'no'): ?>
<div class="clearfix channelBox">
	<div class="channelHeading"><?php echo smarty_lang(array('code' => 'friends'), $this);?>
 <small><a href="<?php echo cblink(array('name' => 'user_contacts'), $this);?>
<?php echo $this->_tpl_vars['u']['username']; ?>
"><?php echo smarty_lang(array('code' => 'view_all'), $this);?>
</a></small></div>
    <?php $this->assign('userFriends', $this->_tpl_vars['userquery']->get_contacts($this->_tpl_vars['u']['userid'],'0','yes')); ?>
    <?php if ($this->_tpl_vars['userFriends']): ?>
    	<?php $_from = $this->_tpl_vars['userFriends']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['friend']):
?>
        	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/user.html", 'smarty_include_vars' => array('user' => $this->_tpl_vars['friend'],'block_type' => 'friends')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endforeach; endif; unset($_from); ?>     
    <?php else: ?>
    	<em>User dont any friends yet.</em>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (isSectionEnabled ( 'collections' ) && $this->_tpl_vars['p']['show_my_collections'] != 'no'): ?>
<div class="clearfix channelBox">
	<div class="channelHeading"><?php echo smarty_lang(array('code' => 'Collections'), $this);?>
</div>
    <?php $this->assign('climit', config(collection_channel_page)); ?>
    <?php echo get_collections(array('assign' => 'userCollections','user' => $this->_tpl_vars['u']['userid'],'limit' => $this->_tpl_vars['climit'],'order' => ' date_added DESC'), $this);?>

    <?php if ($this->_tpl_vars['userCollections']): ?>
    	<?php $_from = $this->_tpl_vars['userCollections']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['collection']):
?>
        	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/collection.html", 'smarty_include_vars' => array('collection' => $this->_tpl_vars['collection'],'display_type' => 'user_collections')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endforeach; endif; unset($_from); ?>
        <div align="right" class="clearfix" style="clear:both;display:block;margin-top:2px;"><a href="<?php echo cblink(array('name' => 'user_collections'), $this);?>
<?php echo $this->_tpl_vars['u']['username']; ?>
"><?php echo smarty_lang(array('code' => 'more'), $this);?>
</a> | <a href="<?php echo cblink(array('name' => 'user_fav_collections'), $this);?>
<?php echo $this->_tpl_vars['u']['username']; ?>
"><?php echo smarty_lang(array('code' => 'Favorites'), $this);?>
</a></div>
    <?php else: ?>
    	<em><?php echo smarty_lang(array('code' => 'user_doesnt_any_collection'), $this);?>
</em>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['p']['show_my_subscriptions'] != 'no'): ?>
<div class="clearfix channelBox">
	<div class="channelHeading"><?php echo smarty_lang(array('code' => 'user_subscriptions','assign' => 'users_videos'), $this);?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['users_videos'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['u']['username']) : sprintf($_tmp, $this->_tpl_vars['u']['username'])); ?>
</div>
    <?php $this->assign('users_items_subscriptions', config(users_items_subscriptions)); ?>
    <?php $this->assign('usr_subs', $this->_tpl_vars['userquery']->get_user_subscriptions($this->_tpl_vars['u']['userid'],$this->_tpl_vars['users_items_subscriptions'])); ?>
    <?php if ($this->_tpl_vars['usr_subs']): ?>
    	<?php $_from = $this->_tpl_vars['usr_subs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sub']):
?>
        	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/user.html", 'smarty_include_vars' => array('user' => $this->_tpl_vars['sub'],'block_type' => 'friends')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>   
        <?php endforeach; endif; unset($_from); ?>
    <?php else: ?>
    	<em><?php echo smarty_lang(array('code' => 'user_no_subscriptions','assign' => 'user_subs'), $this);?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['user_subs'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['u']['username']) : sprintf($_tmp, $this->_tpl_vars['u']['username'])); ?>
</em>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['userquery']->userid != $this->_tpl_vars['u']['userid']): ?>
<div class="clearfix channelBox">
    <a href="javascript:void(0)" onClick="$('#flag_item').slideToggle()"><?php echo smarty_lang(array('code' => 'report_this_user'), $this);?>
</a>
</div>
<?php endif; ?>
<?php echo show_flag_form(array('id' => $this->_tpl_vars['u']['userid'],'type' => 'User'), $this);?>