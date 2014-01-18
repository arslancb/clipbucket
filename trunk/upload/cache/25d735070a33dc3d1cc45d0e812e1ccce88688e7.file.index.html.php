<?php /* Smarty version Smarty-3.1.15, created on 2014-01-18 15:15:07
         compiled from "/var/www/clipbucket/admin_area/styles/cb_2014/layout/index.html" */ ?>
<?php /*%%SmartyHeaderCode:142351095452da540977b435-24995882%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25d735070a33dc3d1cc45d0e812e1ccce88688e7' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cb_2014/layout/index.html',
      1 => 1390040105,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '142351095452da540977b435-24995882',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52da54097835a7_91442732',
  'variables' => 
  array (
    'title' => 0,
    'cbvid' => 0,
    'cbgroup' => 0,
    'userquery' => 0,
    'cbphoto' => 0,
    'cbcollection' => 0,
    'users' => 0,
    'user' => 0,
    'Cbucket' => 0,
    'ClipBucket' => 0,
    'myquery' => 0,
    'notes' => 0,
    'note' => 0,
    'imageurl' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52da54097835a7_91442732')) {function content_52da54097835a7_91442732($_smarty_tpl) {?>
	
<table width="100%" border="0" class="index_table">
  <tr>
    <td valign="top" style="padding-right:13px">
    
<div class="widgets-wrap" id="column1">
	 
     
     <div class="dragbox" id="cbstats" >
        <h2><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 Quick Stats</h2>
        <div class="dragbox-content" >
            <div class="item clearfix">
            	<div class="stats_subitem">Videos</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_videos(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> | Active : <strong><?php echo get_videos(array('count_only'=>'yes','active'=>"yes"),$_smarty_tpl);?>
</strong> | Flagged : <strong><?php echo $_smarty_tpl->tpl_vars['cbvid']->value->action->count_flagged_objects();?>
</strong> | Processing : <strong><?php echo get_videos(array('count_only'=>'yes','status'=>'Processing'),$_smarty_tpl);?>
</strong></div>
            </div>
            
             <div class="item clearfix">
            	<div class="stats_subitem">Groups</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_groups(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> | Active : <strong><?php echo get_groups(array('count_only'=>'yes','active'=>'yes'),$_smarty_tpl);?>
</strong> | Flagged : <strong><?php echo $_smarty_tpl->tpl_vars['cbgroup']->value->action->count_flagged_objects();?>
</strong></div>
            </div>
            
             <div class="item clearfix">
            	<div class="stats_subitem">Members</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_users(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> | Active : <strong><?php echo get_users(array('count_only'=>'yes','status'=>'Ok'),$_smarty_tpl);?>
</strong> | Flagged : <strong><?php echo $_smarty_tpl->tpl_vars['userquery']->value->action->count_flagged_objects();?>
</strong> | Banned : <strong><?php echo get_users(array('count_only'=>'yes','ban'=>'yes'),$_smarty_tpl);?>
</strong></div>
            </div>
            
            
            
            <div class="item clearfix">
            	<div class="stats_subitem">Photos</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_photos(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> | Active : <strong><?php echo get_photos(array('count_only'=>'yes','active'=>'yes'),$_smarty_tpl);?>
</strong> | Flagged : <strong><?php echo $_smarty_tpl->tpl_vars['cbphoto']->value->action->count_flagged_objects();?>
</strong> </div>
            </div>
            
            
            
             <div class="item clearfix">
            	<div class="stats_subitem">Collections</div>
                <div class="stats_subitem_d">Total : <strong><?php echo get_collections(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> | Active : <strong><?php echo get_collections(array('count_only'=>'yes','active'=>'yes'),$_smarty_tpl);?>
</strong> 
                | Flagged : <strong><?php echo $_smarty_tpl->tpl_vars['cbcollection']->value->action->count_flagged_objects();?>
 </strong>
                | Videos : <strong><?php echo get_collections(array('count_only'=>'yes','type'=>'videos'),$_smarty_tpl);?>
</strong> | Photos : <strong><?php echo get_collections(array('count_only'=>'yes','type'=>'photos'),$_smarty_tpl);?>
</strong>
                </strong> </div>
            </div>
            
            
            <div class="item">
            <?php if (isset($_smarty_tpl->tpl_vars['users'])) {$_smarty_tpl->tpl_vars['users'] = clone $_smarty_tpl->tpl_vars['users'];
$_smarty_tpl->tpl_vars['users']->value = $_smarty_tpl->tpl_vars['userquery']->value->get_online_users(); $_smarty_tpl->tpl_vars['users']->nocache = null; $_smarty_tpl->tpl_vars['users']->scope = 0;
} else $_smarty_tpl->tpl_vars['users'] = new Smarty_variable($_smarty_tpl->tpl_vars['userquery']->value->get_online_users(), null, 0);?>
            <strong><a href="online_users.php">Online Users(<?php echo $_smarty_tpl->tpl_vars['userquery']->value->get_online_users(false,true);?>
)</a></strong><br />
            
            <?php if ($_smarty_tpl->tpl_vars['users']->value) {?>
            	<?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['user']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['user']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value) {
$_smarty_tpl->tpl_vars['user']->_loop = true;
 $_smarty_tpl->tpl_vars['user']->iteration++;
 $_smarty_tpl->tpl_vars['user']->last = $_smarty_tpl->tpl_vars['user']->iteration === $_smarty_tpl->tpl_vars['user']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['onlines']['last'] = $_smarty_tpl->tpl_vars['user']->last;
?>
                <?php if ($_smarty_tpl->tpl_vars['user']->value['username']!='') {?>
               	 <strong><a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['user']->value);?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
<?php if ($_smarty_tpl->tpl_vars['user']->value['logins']>1) {?>(<?php echo $_smarty_tpl->tpl_vars['user']->value['logins'];?>
)<?php }?></a><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['onlines']['last']) {?>, <?php }?></strong>			<?php }?>
                <?php } ?>
               
               
                <?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['user']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['user']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value) {
$_smarty_tpl->tpl_vars['user']->_loop = true;
 $_smarty_tpl->tpl_vars['user']->iteration++;
 $_smarty_tpl->tpl_vars['user']->last = $_smarty_tpl->tpl_vars['user']->iteration === $_smarty_tpl->tpl_vars['user']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['onlines']['last'] = $_smarty_tpl->tpl_vars['user']->last;
?>
                    <?php if ($_smarty_tpl->tpl_vars['user']->value['session_string']=='guest') {?>
                        <strong>and <?php echo $_smarty_tpl->tpl_vars['user']->value['logins'];?>
 guest<?php if ($_smarty_tpl->tpl_vars['user']->value['logins']>1) {?>s<?php }?></strong>
                     <?php }?>
                <?php } ?>
                
            <?php } else { ?>
            	No User is Online
            <?php }?>    
            
            
            </div>
            
            <div class="item subitem">
            	<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['new_available']) {?>
                	<div class="stats_subitem" style="width:60%; color:#060">Currently you are running <strong><?php echo $_smarty_tpl->tpl_vars['ClipBucket']->value->cbinfo['version'];?>
 <?php echo $_smarty_tpl->tpl_vars['ClipBucket']->value->cbinfo['state'];?>
</strong><br />
Latest Version <strong><?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['latest']['version'];?>
 <?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['latest']['state'];?>
 </strong></div>
               		<div class="stats_subitem" style="width:39%"><span class="simple_button"><a href="<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['latest']['link'];?>
">Update Now</a></span></div>
                	<div class="clearfix"></div>
                <?php } else { ?>
            		<div>
                    Currently you are running <strong><?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['version'];?>
 <?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['state'];?>
</strong> - No New Version Available</div>
                <?php }?>
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
        	<div class="item"><a href="video_manager.php?search=search&active=no">Approve Videos (<?php echo get_videos(array('active'=>'no','count_only'=>true),$_smarty_tpl);?>
)</a></div>
            <div class="item"><a href="members.php?search=yes&amp;status=ToActivate">Approve Members (<?php echo get_users(array('status'=>'ToActivate','count_only'=>true),$_smarty_tpl);?>
)</a></div>
            <div class="item"><a href="groups_manager.php?active=no&amp;search=yes">Approve Groups (<?php echo get_groups(array('active'=>'no','count_only'=>true),$_smarty_tpl);?>
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
        <?php if (isset($_smarty_tpl->tpl_vars['notes'])) {$_smarty_tpl->tpl_vars['notes'] = clone $_smarty_tpl->tpl_vars['notes'];
$_smarty_tpl->tpl_vars['notes']->value = $_smarty_tpl->tpl_vars['myquery']->value->get_notes(); $_smarty_tpl->tpl_vars['notes']->nocache = null; $_smarty_tpl->tpl_vars['notes']->scope = 0;
} else $_smarty_tpl->tpl_vars['notes'] = new Smarty_variable($_smarty_tpl->tpl_vars['myquery']->value->get_notes(), null, 0);?>
        
        <div id="the_notes">
        <?php if ($_smarty_tpl->tpl_vars['notes']->value) {?>
       		<div id="no_note"></div>
            <?php  $_smarty_tpl->tpl_vars['note'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['note']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['notes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['note']->key => $_smarty_tpl->tpl_vars['note']->value) {
$_smarty_tpl->tpl_vars['note']->_loop = true;
?>
            	<div class="item" id="note-<?php echo $_smarty_tpl->tpl_vars['note']->value['note_id'];?>
">
                	<img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/cross.png" class="delete_note" onclick="delete_note('<?php echo $_smarty_tpl->tpl_vars['note']->value['note_id'];?>
');" />
                    <?php echo nl2br($_smarty_tpl->tpl_vars['note']->value['note']);?>

                </div>
            <?php } ?>
        <?php } else { ?>
        	<div id="no_note" align="center"><strong><em>No notes</em></strong></div>
        <?php }?>
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

$(document).ready(function(){
	$.ajax({
	  url :  baseurl+'/ajax.php',
	  type: "POST",
	  dataType : 'text',
	  data : {'mode':'get_news'},
	  success : function(news){ $('#clipbucket_news').html(news) },
	  timeout : 5000,
	  error  :  function(err) { $('#clipbucket_news').html(' <div align="center"><em><strong>Unable to fetch news</strong></em></div>')},
	  
	});
});

</script><?php }} ?>
