<?php /* Smarty version 2.6.18, created on 2014-01-10 19:00:19
         compiled from /var/www/clipbucket/styles/cbv2new/layout/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'AD', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 26, false),array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 35, false),array('function', 'link', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 87, false),array('function', 'get_videos', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 98, false),array('function', 'get_photos', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 117, false),array('function', 'get_users', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 137, false),array('modifier', 'sprintf', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 85, false),array('modifier', 'number_format', '/var/www/clipbucket/styles/cbv2new/layout/index.html', 143, false),)), $this); ?>

<div class="content_con clearfix">
	<!--FEATURE_CON--> 
    <?php if (is_installed ( 'editorspick' )): ?>
    <div class="feature_con clearfix">
    	<div class="feature_left" id="ep_video_container">
        	<?php unset($this->_sections['e_list']);
$this->_sections['e_list']['name'] = 'e_list';
$this->_sections['e_list']['loop'] = is_array($_loop=$this->_tpl_vars['editor_picks']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['e_list']['max'] = (int)1;
$this->_sections['e_list']['show'] = true;
if ($this->_sections['e_list']['max'] < 0)
    $this->_sections['e_list']['max'] = $this->_sections['e_list']['loop'];
$this->_sections['e_list']['step'] = 1;
$this->_sections['e_list']['start'] = $this->_sections['e_list']['step'] > 0 ? 0 : $this->_sections['e_list']['loop']-1;
if ($this->_sections['e_list']['show']) {
    $this->_sections['e_list']['total'] = min(ceil(($this->_sections['e_list']['step'] > 0 ? $this->_sections['e_list']['loop'] - $this->_sections['e_list']['start'] : $this->_sections['e_list']['start']+1)/abs($this->_sections['e_list']['step'])), $this->_sections['e_list']['max']);
    if ($this->_sections['e_list']['total'] == 0)
        $this->_sections['e_list']['show'] = false;
} else
    $this->_sections['e_list']['total'] = 0;
if ($this->_sections['e_list']['show']):

            for ($this->_sections['e_list']['index'] = $this->_sections['e_list']['start'], $this->_sections['e_list']['iteration'] = 1;
                 $this->_sections['e_list']['iteration'] <= $this->_sections['e_list']['total'];
                 $this->_sections['e_list']['index'] += $this->_sections['e_list']['step'], $this->_sections['e_list']['iteration']++):
$this->_sections['e_list']['rownum'] = $this->_sections['e_list']['iteration'];
$this->_sections['e_list']['index_prev'] = $this->_sections['e_list']['index'] - $this->_sections['e_list']['step'];
$this->_sections['e_list']['index_next'] = $this->_sections['e_list']['index'] + $this->_sections['e_list']['step'];
$this->_sections['e_list']['first']      = ($this->_sections['e_list']['iteration'] == 1);
$this->_sections['e_list']['last']       = ($this->_sections['e_list']['iteration'] == $this->_sections['e_list']['total']);
?>
            	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/editor_pick/video_block.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['editor_picks'][$this->_sections['e_list']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endfor; else: ?>
            	<div style="font-size:11px;padding:10px;">
                	There is no video in editor's pick, Please Add Videos In Editor's Pick<br />
					Videos Manager > Add to editor's pick
                </div>
            <?php endif; ?>
        </div>
        <div class="feature_right">
        	<?php unset($this->_sections['e_list']);
$this->_sections['e_list']['name'] = 'e_list';
$this->_sections['e_list']['loop'] = is_array($_loop=$this->_tpl_vars['editor_picks']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['e_list']['show'] = true;
$this->_sections['e_list']['max'] = $this->_sections['e_list']['loop'];
$this->_sections['e_list']['step'] = 1;
$this->_sections['e_list']['start'] = $this->_sections['e_list']['step'] > 0 ? 0 : $this->_sections['e_list']['loop']-1;
if ($this->_sections['e_list']['show']) {
    $this->_sections['e_list']['total'] = $this->_sections['e_list']['loop'];
    if ($this->_sections['e_list']['total'] == 0)
        $this->_sections['e_list']['show'] = false;
} else
    $this->_sections['e_list']['total'] = 0;
if ($this->_sections['e_list']['show']):

            for ($this->_sections['e_list']['index'] = $this->_sections['e_list']['start'], $this->_sections['e_list']['iteration'] = 1;
                 $this->_sections['e_list']['iteration'] <= $this->_sections['e_list']['total'];
                 $this->_sections['e_list']['index'] += $this->_sections['e_list']['step'], $this->_sections['e_list']['iteration']++):
$this->_sections['e_list']['rownum'] = $this->_sections['e_list']['iteration'];
$this->_sections['e_list']['index_prev'] = $this->_sections['e_list']['index'] - $this->_sections['e_list']['step'];
$this->_sections['e_list']['index_next'] = $this->_sections['e_list']['index'] + $this->_sections['e_list']['step'];
$this->_sections['e_list']['first']      = ($this->_sections['e_list']['iteration'] == 1);
$this->_sections['e_list']['last']       = ($this->_sections['e_list']['iteration'] == $this->_sections['e_list']['total']);
?>
            	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/editor_pick/index_featured_video.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['editor_picks'][$this->_sections['e_list']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endfor; endif; ?>
        </div>
    </div>
    <div class="feature_shadow" ></div>
    <!--FEATURE_CON END-->
    <?php endif; ?>
    
    <div class="ad"><?php echo getAd(array('place' => 'ad_468x60'), $this);?>
</div>
    
    <?php if (isSectionEnabled ( 'videos' ) || isSectionEnabled ( 'photos' )): ?>
    	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/subscriptions.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  	<?php endif; ?>
    
    <?php if (isSectionEnabled ( 'videos' )): ?>
    <div class="tabs">
        <ul>
            <li class="selected"><a href="javascript:void(0)" id="watched" onclick="get_video('recent_viewed_vids','#index_vid_container')"><?php echo smarty_lang(array('code' => 'being_watched'), $this);?>
</a></li>
            <li><a href="javascript:void(0)" id="most_watched" onclick="get_video('most_viewed','#index_vid_container')"><?php echo smarty_lang(array('code' => 'most_viewed'), $this);?>
</a></li>
            <li><a href="javascript:void(0)" id="recently_watched" onclick="get_video('recently_added','#index_vid_container')"><?php echo smarty_lang(array('code' => 'recently_added'), $this);?>
</a></li>
       </ul>    	
    </div> <!--TABS END-->
    <div class="main_vids clearfix">
    	<div id="style_change">
			<div id="grid" onclick="ToggleView(this);" title="Change To Grid Style"></div> 
			<div id="list" onclick="ToggleView(this);" title="Change to List Style"></div>
			<?php echo smarty_lang(array('code' => 'change_style_of_listing'), $this);?>
 
		</div> <!--STYLE_CHANGE END-->
             
       <div id="index_vid_container">
       
       </div>
        

    </div> <!--MAIN_VIDS END-->
    <div class="main_vid_shadow"></div>
    <div style="height:5px;"></div>
    <?php endif; ?>
    <?php if (isSectionEnabled ( 'photos' )): ?>
     <div class="tabs">
        <ul>
            <li class="selected"><a href="javascript:void(0)" id="watched" onclick="getAjaxPhoto('last_viewed','#index_pho_container')"><?php echo smarty_lang(array('code' => 'being_watched'), $this);?>
</a></li>
            <li><a href="javascript:void(0)" id="most_watched" onclick="getAjaxPhoto('most_viewed','#index_pho_container')"><?php echo smarty_lang(array('code' => 'most_viewed'), $this);?>
</a></li>
            <li><a href="javascript:void(0)" id="recently_watched" onclick="getAjaxPhoto('most_recent','#index_pho_container')"><?php echo smarty_lang(array('code' => 'recently_added'), $this);?>
</a></li>
            <li><a href="javascript:void(0)" id="recently_watched" onclick="getAjaxPhoto('most_downloaded','#index_pho_container')"><?php echo smarty_lang(array('code' => 'most_downloaded'), $this);?>
</a></li>
       </ul>    	
    </div> <!--TABS END-->
    <div class="main_vids clearfix">
    
       <div id="index_pho_container">
       
       </div>
        

    </div> <!--MAIN_VIDS END-->
     <div class="main_vid_shadow"></div>
     <div style="height:5px;"></div>
    <?php endif; ?>
</div> <!--CONTENT_CON END-->

<div class="side_con">

	<!-- Displaying 300x250 Advertisment -->
    <?php echo getAd(array('place' => 'ad_300x250'), $this);?>
   
    
	<?php if (! $this->_tpl_vars['userquery']->login_check('',true)): ?>
    <div class="login">
        	<div class="login_title"><?php echo smarty_lang(array('code' => 'website_members','assign' => 'websitemems'), $this);?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['websitemems'])) ? $this->_run_mod_handler('sprintf', true, $_tmp, $this->_tpl_vars['title']) : sprintf($_tmp, $this->_tpl_vars['title'])); ?>
</div>
            <div class="some_info"><?php echo smarty_lang(array('code' => 'guest_homeright_msg'), $this);?>
</div>
            <div class="regist_link"><a href="<?php echo cblink(array('name' => 'signup'), $this);?>
"><?php echo smarty_lang(array('code' => 'reg_for_free'), $this);?>
</a></div>
            <div class="login_link"><a href="<?php echo cblink(array('name' => 'signup'), $this);?>
"><?php echo smarty_lang(array('code' => 'login'), $this);?>
</a></div>
    </div> <!--BOX END-->
    <?php endif; ?>
 

    
    
    <?php if (isSectionEnabled ( 'videos' )): ?>
    <!-- Getting Random videos -->
    <?php $this->assign('videos_items_columns', config(videos_items_columns)); ?>
    <?php echo get_videos(array('assign' => 'ran_vids','limit' => $this->_tpl_vars['videos_items_columns'],'order' => "RAND()"), $this);?>

    
    <?php if ($this->_tpl_vars['ran_vids']): ?>
    <div style="width:300px; margin:auto">
    <span class="watch_vids_head" onclick='$(this).toggleClass("watch_vids_head_closed");$("#rand_vids").slideToggle("fast")'><?php echo smarty_lang(array('code' => 'rand_vids'), $this);?>
</span>
    <div class="watch_vids_cont" id="rand_vids" style="background-color:#FFF">
    <?php unset($this->_sections['uvlist']);
$this->_sections['uvlist']['name'] = 'uvlist';
$this->_sections['uvlist']['loop'] = is_array($_loop=$this->_tpl_vars['ran_vids']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['uvlist']['show'] = true;
$this->_sections['uvlist']['max'] = $this->_sections['uvlist']['loop'];
$this->_sections['uvlist']['step'] = 1;
$this->_sections['uvlist']['start'] = $this->_sections['uvlist']['step'] > 0 ? 0 : $this->_sections['uvlist']['loop']-1;
if ($this->_sections['uvlist']['show']) {
    $this->_sections['uvlist']['total'] = $this->_sections['uvlist']['loop'];
    if ($this->_sections['uvlist']['total'] == 0)
        $this->_sections['uvlist']['show'] = false;
} else
    $this->_sections['uvlist']['total'] = 0;
if ($this->_sections['uvlist']['show']):

            for ($this->_sections['uvlist']['index'] = $this->_sections['uvlist']['start'], $this->_sections['uvlist']['iteration'] = 1;
                 $this->_sections['uvlist']['iteration'] <= $this->_sections['uvlist']['total'];
                 $this->_sections['uvlist']['index'] += $this->_sections['uvlist']['step'], $this->_sections['uvlist']['iteration']++):
$this->_sections['uvlist']['rownum'] = $this->_sections['uvlist']['iteration'];
$this->_sections['uvlist']['index_prev'] = $this->_sections['uvlist']['index'] - $this->_sections['uvlist']['step'];
$this->_sections['uvlist']['index_next'] = $this->_sections['uvlist']['index'] + $this->_sections['uvlist']['step'];
$this->_sections['uvlist']['first']      = ($this->_sections['uvlist']['iteration'] == 1);
$this->_sections['uvlist']['last']       = ($this->_sections['uvlist']['iteration'] == $this->_sections['uvlist']['total']);
?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/watch_video/video_box.html", 'smarty_include_vars' => array('video' => $this->_tpl_vars['ran_vids'][$this->_sections['uvlist']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endfor; endif; ?>
    <div class="clearfix"></div>
    </div>
    <div style="height:10px"></div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    
    <?php if (isSectionEnabled ( 'photos' )): ?>
    <!-- Getting Random videos -->
    <?php $this->assign('limit', config(photo_other_limit)); ?>
    <?php echo get_photos(array('assign' => 'ran_ph','limit' => $this->_tpl_vars['limit'],'order' => "RAND()"), $this);?>

    
    <?php if ($this->_tpl_vars['ran_ph']): ?>
    <div style="width:300px; margin:auto">
    <span class="watch_vids_head" onclick='$(this).toggleClass("watch_vids_head_closed");$("#rand_fotos").slideToggle("fast")'><?php echo smarty_lang(array('code' => 'rand_photos'), $this);?>
</span>
    <div class="watch_vids_cont" id="rand_fotos" style="background-color:#FFF">
    <?php unset($this->_sections['plist']);
$this->_sections['plist']['name'] = 'plist';
$this->_sections['plist']['loop'] = is_array($_loop=$this->_tpl_vars['ran_ph']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['style_dir'])."/blocks/photo.html", 'smarty_include_vars' => array('photo' => $this->_tpl_vars['ran_ph'][$this->_sections['plist']['index']],'display_type' => 'side_photos')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endfor; endif; ?>
    <div class="clearfix"></div>
    </div>
    <div style="height:10px"></div>
    </div>
    <?php endif; ?>
    <?php endif; ?> 
    
    <?php if (isSectionEnabled ( 'channels' )): ?>
	<div class="box">
   	  <div class="top_bg">
        	<h2><?php echo smarty_lang(array('code' => 't_10_users'), $this);?>
</h2>
            <?php echo get_users(array('assign' => 'topusers','limit' => 10,'order' => ' total_videos DESC '), $this);?>


            <?php unset($this->_sections['tusers']);
$this->_sections['tusers']['name'] = 'tusers';
$this->_sections['tusers']['loop'] = is_array($_loop=$this->_tpl_vars['topusers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['tusers']['show'] = true;
$this->_sections['tusers']['max'] = $this->_sections['tusers']['loop'];
$this->_sections['tusers']['step'] = 1;
$this->_sections['tusers']['start'] = $this->_sections['tusers']['step'] > 0 ? 0 : $this->_sections['tusers']['loop']-1;
if ($this->_sections['tusers']['show']) {
    $this->_sections['tusers']['total'] = $this->_sections['tusers']['loop'];
    if ($this->_sections['tusers']['total'] == 0)
        $this->_sections['tusers']['show'] = false;
} else
    $this->_sections['tusers']['total'] = 0;
if ($this->_sections['tusers']['show']):

            for ($this->_sections['tusers']['index'] = $this->_sections['tusers']['start'], $this->_sections['tusers']['iteration'] = 1;
                 $this->_sections['tusers']['iteration'] <= $this->_sections['tusers']['total'];
                 $this->_sections['tusers']['index'] += $this->_sections['tusers']['step'], $this->_sections['tusers']['iteration']++):
$this->_sections['tusers']['rownum'] = $this->_sections['tusers']['iteration'];
$this->_sections['tusers']['index_prev'] = $this->_sections['tusers']['index'] - $this->_sections['tusers']['step'];
$this->_sections['tusers']['index_next'] = $this->_sections['tusers']['index'] + $this->_sections['tusers']['step'];
$this->_sections['tusers']['first']      = ($this->_sections['tusers']['iteration'] == 1);
$this->_sections['tusers']['last']       = ($this->_sections['tusers']['iteration'] == $this->_sections['tusers']['total']);
?>
        <div class="top_user">
        <div class="user_no"><?php echo $this->_sections['tusers']['iteration']; ?>
</div>
                <div class="avatar"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['topusers'][$this->_sections['tusers']['index']]); ?>
"><img src="<?php echo $this->_tpl_vars['userquery']->getuserthumb($this->_tpl_vars['topusers'][$this->_sections['tusers']['index']],'small'); ?>
" width="30" height="30" border="0" class="vsmall_thumb" /></a></div>
                <div class="utitle"><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['topusers'][$this->_sections['tusers']['index']]); ?>
"><?php echo $this->_tpl_vars['topusers'][$this->_sections['tusers']['index']]['username']; ?>
</a><?php echo smarty_lang(array('code' => 'views'), $this);?>
 : <?php echo ((is_array($_tmp=$this->_tpl_vars['topusers'][$this->_sections['tusers']['index']]['profile_hits'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
 - <?php echo smarty_lang(array('code' => 'videos'), $this);?>
 : <?php echo ((is_array($_tmp=$this->_tpl_vars['topusers'][$this->_sections['tusers']['index']]['total_videos'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>

				</div>
            <div class="clearfix"></div>  
            </div>
            <?php endfor; endif; ?>            
        </div>
    </div>
    <?php endif; ?>
     <!--BOX END-->
    
</div> <!--SIDE_CON END-->