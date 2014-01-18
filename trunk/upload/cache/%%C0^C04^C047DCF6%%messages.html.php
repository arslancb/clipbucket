<?php /* Smarty version 2.6.18, created on 2014-01-17 09:49:27
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/pm/messages.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/pm/messages.html', 5, false),array('function', 'private_message', '/var/www/clipbucket/styles/cbv2new/layout/blocks/pm/messages.html', 80, false),array('modifier', 'truncate', '/var/www/clipbucket/styles/cbv2new/layout/blocks/pm/messages.html', 42, false),array('modifier', 'date_format', '/var/www/clipbucket/styles/cbv2new/layout/blocks/pm/messages.html', 75, false),)), $this); ?>


<form name="videos_manager" method="post">
 <div style="float:left; width:300px">
   <img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="arrow_pointing" style="margin-left:20px; margin-right:10px;" /><input type="submit" name="delete_pm" id="delete_fav_videos" value="<?php echo smarty_lang(array('code' => 'delete'), $this);?>
" class="small_button" />
 </div>
 
  
  <div class="clearfix"></div>
<div style="height:10px"></div>
<div class="account_table" style="width:780px">
   
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
 	  <tr>
 	    <td width="25"><input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/>
 	      </td>
 	    <?php if ($this->_tpl_vars['show_from']): ?><td width="100"><?php echo smarty_lang(array('code' => 'from'), $this);?>
</td><?php endif; ?>
        <?php if ($this->_tpl_vars['show_to']): ?><td width="100"><?php echo smarty_lang(array('code' => 'to'), $this);?>
</td><?php endif; ?>
 	    <td><?php echo smarty_lang(array('code' => 'subject'), $this);?>
</td>
 	    <td width="100" class="last_td"><?php echo smarty_lang(array('code' => 'date_sent'), $this);?>
</td>
 	    </tr>
 	  </table>
 </div>

<div class="messages_container" id="messages_container">
	 
  <div style="width:780px">
  
   
 <?php $this->assign('bg', 'fff'); ?>
 <?php unset($this->_sections['msg']);
$this->_sections['msg']['name'] = 'msg';
$this->_sections['msg']['loop'] = is_array($_loop=$this->_tpl_vars['user_msgs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['msg']['show'] = true;
$this->_sections['msg']['max'] = $this->_sections['msg']['loop'];
$this->_sections['msg']['step'] = 1;
$this->_sections['msg']['start'] = $this->_sections['msg']['step'] > 0 ? 0 : $this->_sections['msg']['loop']-1;
if ($this->_sections['msg']['show']) {
    $this->_sections['msg']['total'] = $this->_sections['msg']['loop'];
    if ($this->_sections['msg']['total'] == 0)
        $this->_sections['msg']['show'] = false;
} else
    $this->_sections['msg']['total'] = 0;
if ($this->_sections['msg']['show']):

            for ($this->_sections['msg']['index'] = $this->_sections['msg']['start'], $this->_sections['msg']['iteration'] = 1;
                 $this->_sections['msg']['iteration'] <= $this->_sections['msg']['total'];
                 $this->_sections['msg']['index'] += $this->_sections['msg']['step'], $this->_sections['msg']['iteration']++):
$this->_sections['msg']['rownum'] = $this->_sections['msg']['iteration'];
$this->_sections['msg']['index_prev'] = $this->_sections['msg']['index'] - $this->_sections['msg']['step'];
$this->_sections['msg']['index_next'] = $this->_sections['msg']['index'] + $this->_sections['msg']['step'];
$this->_sections['msg']['first']      = ($this->_sections['msg']['iteration'] == 1);
$this->_sections['msg']['last']       = ($this->_sections['msg']['iteration'] == $this->_sections['msg']['total']);
?>
 <?php if ($this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_id'] == $_GET['mid']): ?>
 <?php $this->assign('bg', 'c6d7e0'); ?>
 <?php endif; ?>
 <div class="account_vid_list<?php if ($this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_status'] == 'unread'): ?> unread_msg<?php else: ?> read_msg<?php endif; ?>" style="background-color:#<?php echo $this->_tpl_vars['bg']; ?>
" id="message-<?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_id']; ?>
">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25"><input type="checkbox" name="msg_id[]" id="msg_id" value="<?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_id']; ?>
" />
     </td>
    <?php if ($this->_tpl_vars['show_from']): ?><td width="100"><a href="?mode=<?php echo $this->_tpl_vars['mode']; ?>
&mid=<?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_id']; ?>
"><?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_from_user']; ?>
</a></td><?php endif; ?>
    <?php if ($this->_tpl_vars['show_to']): ?><td width="100"><a href="?mode=<?php echo $this->_tpl_vars['mode']; ?>
&mid=<?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_id']; ?>
"><?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['to_usernames']; ?>
</a></td><?php endif; ?>
    <td><a href="?mode=<?php echo $this->_tpl_vars['mode']; ?>
&mid=<?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_subject'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 70) : smarty_modifier_truncate($_tmp, 70)); ?>
</a></td>
    <td width="50"><a id="delete_icon-<?php echo $this->_tpl_vars['pr_msg']['message_id']; ?>
" href="?mode=<?php echo $this->_tpl_vars['mode']; ?>
&delete_mid=<?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['message_id']; ?>
"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" border="0" class="delete_icon" /></a>
</td>
    <td width="100" style="text-indent:0px; padding-left:5px"><?php echo $this->_tpl_vars['user_msgs'][$this->_sections['msg']['index']]['date_added']; ?>
</td>
    </tr>
  </table>
  </div>
 <?php if ($this->_tpl_vars['bg'] == 'fff'): ?>
 <?php $this->assign('bg', 'EFF5F8'); ?>
 <?php else: ?>
 <?php $this->assign('bg', 'fff'); ?>
 <?php endif; ?>
 
 <?php endfor; else: ?>
 	<div align="center" style="padding:5px"><strong><em><?php echo smarty_lang(array('code' => 'you_dont_hv_any_pm'), $this);?>
</em></strong></div>
 <?php endif; ?>
 </div>
  
</div>

</form>




<?php if ($this->_tpl_vars['pr_msg'] != ''): ?>

<div class="private_message_container" align="center">
<div style="padding:5px" align="right">
<a id="delete_icon-<?php echo $this->_tpl_vars['pr_msg']['message_id']; ?>
" href="?mode=<?php echo $this->_tpl_vars['mode']; ?>
&delete_mid=<?php echo $this->_tpl_vars['pr_msg']['message_id']; ?>
"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" border="0" class="delete_icon" /></a>
<a id="reply_icon-<?php echo $this->_tpl_vars['pr_msg']['message_id']; ?>
" href="?mode=new_msg&reply=<?php echo $this->_tpl_vars['pr_msg']['message_id']; ?>
"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" border="0" class="reply_icon" title="Reply!" /></a>
</div>
 <div class="pm_message_top" align="left">
  <div class="pm_line"><?php echo smarty_lang(array('code' => 'from'), $this);?>
 : <strong><a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['pr_msg']); ?>
"><?php echo $this->_tpl_vars['pr_msg']['username']; ?>
</a></strong> - <?php echo ((is_array($_tmp=$this->_tpl_vars['pr_msg']['date_added'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %B %e, %Y %I:%M %p") : smarty_modifier_date_format($_tmp, "%A, %B %e, %Y %I:%M %p")); ?>
</div>
  <div class="pm_line"><?php echo smarty_lang(array('code' => 'Subject'), $this);?>
 : <strong><?php echo $this->_tpl_vars['pr_msg']['message_subject']; ?>
</strong></div>
 </div>
 <hr width="100%" size="1" noshade="noshade" />
 <div align="left">
 	<?php echo private_message(array('pm' => $this->_tpl_vars['pr_msg']), $this);?>

 </div>
</div>

<?php endif; ?>

<div class="clearfix" style="height:10px"></div>