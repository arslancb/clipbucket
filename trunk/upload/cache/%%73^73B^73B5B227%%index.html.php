<?php /* Smarty version 2.6.18, created on 2014-01-15 16:03:38
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_videos', '/var/www/clipbucket/admin_area/styles/cbv2/layout/index.html', 15, false),array('function', 'get_groups', '/var/www/clipbucket/admin_area/styles/cbv2/layout/index.html', 20, false),array('function', 'get_users', '/var/www/clipbucket/admin_area/styles/cbv2/layout/index.html', 25, false),array('function', 'get_photos', '/var/www/clipbucket/admin_area/styles/cbv2/layout/index.html', 32, false),array('function', 'get_collections', '/var/www/clipbucket/admin_area/styles/cbv2/layout/index.html', 39, false),array('modifier', 'nl2br', '/var/www/clipbucket/admin_area/styles/cbv2/layout/index.html', 160, false),)), $this); ?>

	
<table width="100%" border="0" class="index_table">
  <tr>
    <td valign="top" style="padding-right:13px">
    
<div class="widgets-wrap" id="column1">
	 
     
     <div class="dragbox" id="cbstats" >
        <h2><?php echo $this->_tpl_vars['title']; ?>
 Quick Stats</h2>
        <div class="dragbox-content" >
            <div class="item clearfix">
            	<div class="stats_subitem">Videos</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_videos(array('count_only' => true), $this);?>
</strong> | Active : <strong><?php echo get_videos(array('count_only' => true,'active' => 'yes'), $this);?>
</strong> | Flagged : <strong><?php echo $this->_tpl_vars['cbvid']->action->count_flagged_objects(); ?>
</strong> | Processing : <strong><?php echo get_videos(array('count_only' => true,'status' => 'Processing'), $this);?>
</strong></div>
            </div>
            
             <div class="item clearfix">
            	<div class="stats_subitem">Groups</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_groups(array('count_only' => true), $this);?>
</strong> | Active : <strong><?php echo get_groups(array('count_only' => true,'active' => 'yes'), $this);?>
</strong> | Flagged : <strong><?php echo $this->_tpl_vars['cbgroup']->action->count_flagged_objects(); ?>
</strong></div>
            </div>
            
             <div class="item clearfix">
            	<div class="stats_subitem">Members</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_users(array('count_only' => true), $this);?>
</strong> | Active : <strong><?php echo get_users(array('count_only' => true,'status' => 'Ok'), $this);?>
</strong> | Flagged : <strong><?php echo $this->_tpl_vars['userquery']->action->count_flagged_objects(); ?>
</strong> | Banned : <strong><?php echo get_users(array('count_only' => true,'ban' => 'yes'), $this);?>
</strong></div>
            </div>
            
            
            
            <div class="item clearfix">
            	<div class="stats_subitem">Photos</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_photos(array('count_only' => true), $this);?>
</strong> | Active : <strong><?php echo get_photos(array('count_only' => true,'active' => 'yes'), $this);?>
</strong> | Flagged : <strong><?php echo $this->_tpl_vars['cbphoto']->action->count_flagged_objects(); ?>
</strong> </div>
            </div>
            
            
            
             <div class="item clearfix">
            	<div class="stats_subitem">Collections</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_collections(array('count_only' => true), $this);?>
</strong> | Active : <strong><?php echo get_collections(array('count_only' => true,'active' => 'yes'), $this);?>
</strong> 
                | Flagged : <strong><?php echo $this->_tpl_vars['cbcollection']->action->count_flagged_objects(); ?>
 </strong>
                | Videos : <strong><?php echo get_collections(array('count_only' => true,'type' => 'videos'), $this);?>
</strong> | Photos : <strong><?php echo get_collections(array('count_only' => true,'type' => 'photos'), $this);?>
</strong>
                </strong> </div>
            </div>
            
            
            <div class="item">
            <?php $this->assign('users', $this->_tpl_vars['userquery']->get_online_users()); ?>
            <strong><a href="online_users.php">Online Users(<?php echo $this->_tpl_vars['userquery']->get_online_users(false,true); ?>
)</a></strong><br />
            
            <?php if ($this->_tpl_vars['users']): ?>
            	<?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['onlines'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['onlines']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['user']):
        $this->_foreach['onlines']['iteration']++;
?>
                <?php if ($this->_tpl_vars['user']['username'] != ""): ?>
               	 <strong><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['user']); ?>
" target="_blank"><?php echo $this->_tpl_vars['user']['username']; ?>
<?php if ($this->_tpl_vars['user']['logins'] > 1): ?>(<?php echo $this->_tpl_vars['user']['logins']; ?>
)<?php endif; ?></a><?php if (! ($this->_foreach['onlines']['iteration'] == $this->_foreach['onlines']['total'])): ?>, <?php endif; ?></strong>			<?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
               
               
                <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['onlines'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['onlines']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['user']):
        $this->_foreach['onlines']['iteration']++;
?>
                    <?php if ($this->_tpl_vars['user']['session_string'] == 'guest'): ?>
                        <strong>and <?php echo $this->_tpl_vars['user']['logins']; ?>
 guest<?php if ($this->_tpl_vars['user']['logins'] > 1): ?>s<?php endif; ?></strong>
                     <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
                
            <?php else: ?>
            	No User is Online
            <?php endif; ?>    
            
            
            </div>
            
            <div class="item subitem">
            	<?php if ($this->_tpl_vars['Cbucket']->cbinfo['new_available']): ?>
                	<div class="stats_subitem" style="width:60%; color:#060">Currently you are running <strong><?php echo $this->_tpl_vars['ClipBucket']->cbinfo['version']; ?>
 <?php echo $this->_tpl_vars['ClipBucket']->cbinfo['state']; ?>
</strong><br />
Latest Version <strong><?php echo $this->_tpl_vars['Cbucket']->cbinfo['latest']['version']; ?>
 <?php echo $this->_tpl_vars['Cbucket']->cbinfo['latest']['state']; ?>
 </strong></div>
               		<div class="stats_subitem" style="width:39%"><span class="simple_button"><a href="<?php echo $this->_tpl_vars['Cbucket']->cbinfo['latest']['link']; ?>
">Update Now</a></span></div>
                	<div class="clearfix"></div>
                <?php else: ?>
            		<div>
                    Currently you are running <strong><?php echo $this->_tpl_vars['Cbucket']->cbinfo['version']; ?>
 <?php echo $this->_tpl_vars['Cbucket']->cbinfo['state']; ?>
</strong> - No New Version Available</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
       
    <div class="dragbox" id="cbnews" >
        <h2>ClipBucket News</h2>
        <div class="dragbox-content" >
        	<div  id="clipbucket_news"></div>
        </div>
    </div>
    
    <div style="height:20px;"></div>
    <div style="font-size:12px">
    <h2>Words from ClipBucket!</h2>
    <p>ClipBucket is  developed by <strong><a href="http://arslanhassan.com">Arslan Hassan</a></strong> and <strong><a href="http://clip-bucket.com/fawaz-tahir">Fawaz Tahir</a></strong>, we started with a small website application with only functionality of serving videos to the community later on we decided to add every possible social networking feature to make social communities more interactive. We didn't have any milestones, no road map, no charts, no nothing. Only thing we had was a vision as of our captain <strong><a href="http://clip-bucket.com/jahanzeb-hassan">Jahanzeb Hassan</a></strong> and plenty of hard work. </p>
    <p>We also have two more members named <a href="http://clip-bucket.com/ruman-malik">Ruman Malik</a> and <a href="http://clip-bucket.com/zomail-tahir">Zomail Tahir</a> who manage sales and support department.</p>
    <p><strong>ClipBucket team gives special thanks to</strong><br />
      <br />
      * Frank White for helping us knowing the basics of professional application development<br />
      * Christian Russemen for keep our community active<br />
      * BigStack for his great forum support
      <br />
      * You for using ClipBucket.</p>
    <h3>What lies head?</h3>
    <p>* Easy ClipBucket integeration with other applications<br />
      * Make more secured, fast and optimized<br />
      * HTML5 ready with mobile support<br />
      * Full documentation of our source code
    </p>
    <h3>Where in the world we belong to?</h3>
    <p>We belong to <a href="http://clip-bucket.com/pakistan">Islamic Republic of Pakistan</a> :) you may have seen many negative news about Pakistan on television but there are still <a href="http://clip-bucket.com/pakistan#the_truth">some facts</a> you should know about. Its a great country for us and we will always love it.<br />
      <strong>Pakistan Zindabad </strong><br />
  <strong><br />
</strong></p></div>
</div>    
    
    </td>
    <td width="210" valign="top">


   
<div class="widgets-wrap" style="width:210px" id="column2">

    <!-- Admin Todo List -->  
    <div class="dragbox" id="todo_list" >
        <h2>Todo List</h2>
        <div class="dragbox-content" >
        	<div class="item"><a href="video_manager.php?search=search&active=no">Approve Videos (<?php echo get_videos(array('active' => 'no','count_only' => true), $this);?>
)</a></div>
            <div class="item"><a href="members.php?search=yes&amp;status=ToActivate">Approve Members (<?php echo get_users(array('status' => 'ToActivate','count_only' => true), $this);?>
)</a></div>
            <div class="item"><a href="groups_manager.php?active=no&amp;search=yes">Approve Groups (<?php echo get_groups(array('active' => 'no','count_only' => true), $this);?>
)</a></div>
	    </div>
    </div>
    <!-- Admin Todo List -->
    
    <!-- Admin Todo List -->  
    <div class="dragbox" id="quick_actions" >
        <h2>Quick Action</h2>
        <div class="dragbox-content" >
        	<div class="item"><a href="main.php">Website Settings</a></div>
            <div class="item"><a href="add_member.php">Add Members</a></div>
            <div class="item"><a href="add_group.php">Add Group</a></div>
            <div class="item"><a href="cb_mod_check.php">Check Video Modules</a></div>          
	    </div>
    </div>
    <!-- Admin Todo List -->  


    <!-- Admin personal Note Widget -->
	<div class="dragbox" id="private_notes" >
        <h2>Personal Notes</h2>
        <div class="dragbox-content" >
        <?php $this->assign('notes', $this->_tpl_vars['myquery']->get_notes()); ?>
        
        <div id="the_notes">
        <?php if ($this->_tpl_vars['notes']): ?>
       		<div id="no_note"></div>
            <?php $_from = $this->_tpl_vars['notes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['note']):
?>
            	<div class="item" id="note-<?php echo $this->_tpl_vars['note']['note_id']; ?>
">
                	<img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/cross.png" class="delete_note" onclick="delete_note('<?php echo $this->_tpl_vars['note']['note_id']; ?>
');" />
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['note']['note'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

                </div>
            <?php endforeach; endif; unset($_from); ?>
        <?php else: ?>
        	<div id="no_note" align="center"><strong><em>No notes</em></strong></div>
        <?php endif; ?>
        </div>
        <form method="post">
        	<textarea name="personal_note" id="personal_note" style="width:90%; height:50px; margin:5px; border:1px solid #999"></textarea>
            <div align="right" style="padding-right:10px"><a href="javascript:void(0)" 
            onclick="add_note('#personal_note')">Add Note</a></div>
        </form>
	    </div>
    </div>
    <!-- Admin personal Note Widget -->
    
    
</div>
    
    
    </td>
  </tr>
</table>


<script>
<?php echo '
$(document).ready(function(){
	$.ajax({
	  url :  baseurl+\'/ajax.php\',
	  type: "POST",
	  dataType : \'text\',
	  data : {\'mode\':\'get_news\'},
	  success : function(news){ $(\'#clipbucket_news\').html(news) },
	  timeout : 5000,
	  error  :  function(err) { $(\'#clipbucket_news\').html(\' <div align="center"><em><strong>Unable to fetch news</strong></em></div>\')},
	  
	});
});
'; ?>

</script>