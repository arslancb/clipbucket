<?php /* Smarty version Smarty-3.1.15, created on 2014-01-18 15:09:31
         compiled from "/var/www/clipbucket/admin_area/styles/cb_2014/layout/comments.html" */ ?>
<?php /*%%SmartyHeaderCode:184019374352da52db40b213-70495799%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '52077c49e0c08fe38610c8fc9405d44c7e35b8df' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cb_2014/layout/comments.html',
      1 => 1390039769,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '184019374352da52db40b213-70495799',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'imageurl' => 0,
    'comments' => 0,
    'comment' => 0,
    'userquery' => 0,
    'bgcolor' => 0,
    'type' => 0,
    'comment_owner' => 0,
    'cbgroup' => 0,
    'cbcollection' => 0,
    'cbphoto' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52da52db5c17d1_34143636',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52da52db5c17d1_34143636')) {function content_52da52db5c17d1_34143636($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/clipbucket/includes/smartyv3/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_truncate')) include '/var/www/clipbucket/includes/smartyv3/plugins/modifier.truncate.php';
?><span class="page_title">Comments Manager</span>
<br />
<div style="padding:10px;">
<div style="clear:both; height:5px;"></div>
<a href="?type=v" title="View Video Comments">View Video Comments</a> | <a href="?type=t">View Topic Posts</a> | <a href="?type=c">View Channel Comments</a> | <a href="?type=cl">View Collection Comments</a> | <a href="?type=p">View Photo Comments</a>
<div style="clear:both; height:5px;"></div>
</div>

<form name="comments_manager" method="post">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="30" align="center" valign="middle"><img src="<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
/arrow_return.png" width="25" height="25"></td>
    <td height="50" style="padding-left:15px">
     		<input type="submit" name="mark_spam" value="Mark Spam"  class="button" id="mark_spam"/>
     		<input type="submit" name="not_spam" value="Not Spam"  class="button" id="not_spam"/>
     		<input type="submit" name="delete_selected" value="Delete"  class="button" onclick="return confirm_it('Are you sure you want to delete selected comment(s)')"/>
    
    </td>
    </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="40" align="center" valign="middle" class="left_head">
    <input type="checkbox" name="checkall" onclick="checkUncheckAll(this);"/></td>
    <td width="50" class="head">CID</td>
    <td class="head">Comment</td>
    <td width="50" class="right_head">&nbsp;</td>
  </tr>
</table>

<?php if ($_smarty_tpl->tpl_vars['comments']->value) {?>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['comments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value) {
$_smarty_tpl->tpl_vars['comment']->_loop = true;
?>

<?php if ($_smarty_tpl->tpl_vars['comment']->value['anonym_name']!='') {?>
	<?php if (isset($_smarty_tpl->tpl_vars['comment_owner'])) {$_smarty_tpl->tpl_vars['comment_owner'] = clone $_smarty_tpl->tpl_vars['comment_owner'];
$_smarty_tpl->tpl_vars['comment_owner']->value = $_smarty_tpl->tpl_vars['comment']->value['anonym_name']; $_smarty_tpl->tpl_vars['comment_owner']->nocache = null; $_smarty_tpl->tpl_vars['comment_owner']->scope = 0;
} else $_smarty_tpl->tpl_vars['comment_owner'] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value['anonym_name'], null, 0);?>
<?php } else { ?>
	<?php if (isset($_smarty_tpl->tpl_vars['comment_owner'])) {$_smarty_tpl->tpl_vars['comment_owner'] = clone $_smarty_tpl->tpl_vars['comment_owner'];
$_smarty_tpl->tpl_vars['comment_owner']->value = $_smarty_tpl->tpl_vars['userquery']->value->get_user_details($_smarty_tpl->tpl_vars['comment']->value[3]); $_smarty_tpl->tpl_vars['comment_owner']->nocache = null; $_smarty_tpl->tpl_vars['comment_owner']->scope = 0;
} else $_smarty_tpl->tpl_vars['comment_owner'] = new Smarty_variable($_smarty_tpl->tpl_vars['userquery']->value->get_user_details($_smarty_tpl->tpl_vars['comment']->value[3]), null, 0);?>
<?php }?> 
 
<tr class="video_opt_td" id="comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" style="background-color:<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']>0) {?> #ffd7d7<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['bgcolor']->value;?>
<?php }?>;">
<td width="40" align="center" valign="top" class="video_opt_td"><input name="check_comments[]" type="checkbox" id="check_comments" value="<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" /></td>
<td width="50" align="left" valign="top" class="video_opt_td"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
</td>
<td valign="top"  class="video_opt_td" onmouseover="$('#comm_opt-<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
').show()" onmouseout="$('#comm_opt-<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
').hide()" >
 <?php if ($_smarty_tpl->tpl_vars['type']->value==''||$_smarty_tpl->tpl_vars['type']->value=='v') {?>
<span class="vdo_sets">
 <?php if (is_array($_smarty_tpl->tpl_vars['comment_owner']->value)) {?> <a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['comment_owner']->value);?>
"><strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value['username'];?>
</strong></a> <?php } else { ?> <strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value;?>
</strong> <?php }?> commented on <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date_added']);?>
</strong>  on a video named <span style="text-transform:none"><a href="<?php echo videoSmartyLink(array('vdetails'=>$_smarty_tpl->tpl_vars['comment']->value),$_smarty_tpl);?>
" title="<?php echo $_smarty_tpl->tpl_vars['comment']->value['title'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['comment']->value['title'],40);?>
</a></span>
</span> 

<div style="height:5px; clear:both;"></div>
<span style="font-size:11px;" id="<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" class="edit_comment"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</span>
<div style="height:20px;">
<div id="comm_opt-<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" class="vid_opts" style="display:none">
<a href="<?php echo videoSmartyLink(array('vdetails'=>$_smarty_tpl->tpl_vars['comment']->value),$_smarty_tpl);?>
#<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']>0) {?>spam_<?php }?>comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" target="_blank"><?php echo smarty_lang(array('code'=>'view'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'comment'),$_smarty_tpl);?>
</a> |

<a href="#" id="spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" onclick="admin_spam_comment(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']==0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>>Mark <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> <a href="#" id="remove_spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
"  onclick="admin_remove_spam(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"
<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']!=0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>><?php echo smarty_lang(array('code'=>'remove'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> | 

<a href="#" onclick="delete_comment('<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
','v'); return false;"><?php echo smarty_lang(array('code'=>'delete'),$_smarty_tpl);?>
</a>
</div></div>
</td>
<td width="50" valign="top" class="video_opt_td">&nbsp;</td>	
</tr>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['type']->value=='t') {?>
<span class="vdo_sets">
 <?php if (is_array($_smarty_tpl->tpl_vars['comment_owner']->value)) {?> <a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['comment_owner']->value);?>
"><strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value['username'];?>
</strong></a> <?php } else { ?> <strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value;?>
</strong> <?php }?> commented on <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date_added']);?>
</strong>  on a topic named <span style="text-transform:none"><a href="<?php echo $_smarty_tpl->tpl_vars['cbgroup']->value->topic_link($_smarty_tpl->tpl_vars['comment']->value);?>
" title="<?php echo $_smarty_tpl->tpl_vars['comment']->value['topic_title'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['comment']->value['topic_title'],40);?>
</a></span>
</span> 
<div style="height:5px; clear:both;"></div>
<span style="font-size:11px;" class="edit_comment"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</span>
<div style="height:20px;">
<div id="comm_opt-<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" class="vid_opts" style="display:none">
<a href="<?php echo $_smarty_tpl->tpl_vars['cbgroup']->value->topic_link($_smarty_tpl->tpl_vars['comment']->value);?>
#<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']>0) {?>spam_<?php }?>comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" target="_blank"><?php echo smarty_lang(array('code'=>'view'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'comment'),$_smarty_tpl);?>
</a> |

<a href="#" id="spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" onclick="admin_spam_comment(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']==0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>>Mark <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> <a href="#" id="remove_spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
"  onclick="admin_remove_spam(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"
<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']!=0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>><?php echo smarty_lang(array('code'=>'remove'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> | 

<a href="#" onclick="delete_comment('<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
','v'); return false;"><?php echo smarty_lang(array('code'=>'delete'),$_smarty_tpl);?>
</a>
</div></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['type']->value=='c') {?>
<span class="vdo_sets">
 <?php if (is_array($_smarty_tpl->tpl_vars['comment_owner']->value)) {?> <a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['comment_owner']->value);?>
"><strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value['username'];?>
</strong></a> <?php } else { ?> <strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value;?>
</strong> <?php }?> commented on <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date_added']);?>
</strong>  on a channel named <span style="text-transform:none"><a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['comment']->value);?>
" title="<?php echo $_smarty_tpl->tpl_vars['comment']->value['username'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['comment']->value['username'],40);?>
</a></span>
</span> 
<div style="height:5px; clear:both;"></div>
<span style="font-size:11px;" class="edit_comment"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</span>

<div style="height:20px;">
<div id="comm_opt-<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" class="vid_opts" style="display:none">
<a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['comment']->value);?>
#<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']>0) {?>spam_<?php }?>comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" target="_blank"><?php echo smarty_lang(array('code'=>'view'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'comment'),$_smarty_tpl);?>
</a> |

<a href="#" id="spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" onclick="admin_spam_comment(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']==0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>>Mark <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> <a href="#" id="remove_spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
"  onclick="admin_remove_spam(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"
<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']!=0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>><?php echo smarty_lang(array('code'=>'remove'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> | 

<a href="#" onclick="delete_comment('<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
','v'); return false;"><?php echo smarty_lang(array('code'=>'delete'),$_smarty_tpl);?>
</a>
</div></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['type']->value=='cl') {?>
<span class="vdo_sets">
 <?php if (is_array($_smarty_tpl->tpl_vars['comment_owner']->value)) {?> <a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['comment_owner']->value);?>
"><strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value['username'];?>
</strong></a> <?php } else { ?> <strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value;?>
</strong> <?php }?> commented on <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date_added']);?>
</strong>  on a collection named <span style="text-transform:none"><a href="<?php echo $_smarty_tpl->tpl_vars['cbcollection']->value->collection_links($_smarty_tpl->tpl_vars['comment']->value,'vc');?>
" title="<?php echo $_smarty_tpl->tpl_vars['comment']->value['collection_name'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['comment']->value['collection_name'],40);?>
</a></span>
</span> 
<div style="height:5px; clear:both;"></div>
<span style="font-size:11px;" class="edit_comment"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</span>

<div style="height:20px;">
<div id="comm_opt-<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" class="vid_opts" style="display:none">
<a href="<?php echo $_smarty_tpl->tpl_vars['cbcollection']->value->collection_links($_smarty_tpl->tpl_vars['comment']->value,'vc');?>
#<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']>0) {?>spam_<?php }?>comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" target="_blank"><?php echo smarty_lang(array('code'=>'view'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'comment'),$_smarty_tpl);?>
</a> |

<a href="#" id="spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" onclick="admin_spam_comment(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']==0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>>Mark <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> <a href="#" id="remove_spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
"  onclick="admin_remove_spam(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"
<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']!=0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>><?php echo smarty_lang(array('code'=>'remove'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> | 

<a href="#" onclick="delete_comment('<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
','v'); return false;"><?php echo smarty_lang(array('code'=>'delete'),$_smarty_tpl);?>
</a>
</div></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['type']->value=='p') {?>
<span class="vdo_sets">
 <?php if (is_array($_smarty_tpl->tpl_vars['comment_owner']->value)) {?> <a href="<?php echo $_smarty_tpl->tpl_vars['userquery']->value->profile_link($_smarty_tpl->tpl_vars['comment_owner']->value);?>
"><strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value['username'];?>
</strong></a> <?php } else { ?> <strong><?php echo $_smarty_tpl->tpl_vars['comment_owner']->value;?>
</strong> <?php }?> commented on <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date_added']);?>
</strong>  on a photo named <span style="text-transform:none"><a href="<?php echo $_smarty_tpl->tpl_vars['cbphoto']->value->photo_links($_smarty_tpl->tpl_vars['comment']->value,'view_photo');?>
" title="<?php echo $_smarty_tpl->tpl_vars['comment']->value['photo_title'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['comment']->value['photo_title'],40);?>
</a></span>
</span> 
<div style="height:5px; clear:both;"></div>
<span style="font-size:11px;" class="edit_comment"><?php echo $_smarty_tpl->tpl_vars['comment']->value['comment'];?>
</span>

<div style="height:20px;">
<div id="comm_opt-<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" class="vid_opts" style="display:none">
<a href="<?php echo $_smarty_tpl->tpl_vars['cbphoto']->value->photo_links($_smarty_tpl->tpl_vars['comment']->value,'view_photo');?>
#<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']>0) {?>spam_<?php }?>comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" target="_blank"><?php echo smarty_lang(array('code'=>'view'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'comment'),$_smarty_tpl);?>
</a> |

<a href="#" id="spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
" onclick="admin_spam_comment(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']==0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>>Mark <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> <a href="#" id="remove_spam_comment_<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
"  onclick="admin_remove_spam(<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
); return false;"
<?php if ($_smarty_tpl->tpl_vars['comment']->value['spam_votes']!=0) {?>  style="display:inline"<?php } else { ?> style="display:none;"<?php }?>><?php echo smarty_lang(array('code'=>'remove'),$_smarty_tpl);?>
 <?php echo smarty_lang(array('code'=>'spam'),$_smarty_tpl);?>
</a> | 

<a href="#" onclick="delete_comment('<?php echo $_smarty_tpl->tpl_vars['comment']->value['comment_id'];?>
','v'); return false;"><?php echo smarty_lang(array('code'=>'delete'),$_smarty_tpl);?>
</a>
</div></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['bgcolor']->value=='') {?>
<?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = "#EEE"; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable("#EEE", null, 0);?>
<?php } else { ?>
<?php if (isset($_smarty_tpl->tpl_vars['bgcolor'])) {$_smarty_tpl->tpl_vars['bgcolor'] = clone $_smarty_tpl->tpl_vars['bgcolor'];
$_smarty_tpl->tpl_vars['bgcolor']->value = ''; $_smarty_tpl->tpl_vars['bgcolor']->nocache = null; $_smarty_tpl->tpl_vars['bgcolor']->scope = 0;
} else $_smarty_tpl->tpl_vars['bgcolor'] = new Smarty_variable('', null, 0);?>
<?php }?>    
<?php } ?>
</table>
<?php } else { ?>
	<div align="center"><em><?php echo smarty_lang(array('code'=>'no_comments'),$_smarty_tpl);?>
</em></div>
<?php }?>
</form>
<div style="height:10px; clear:both"></div>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/blocks/pagination.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
