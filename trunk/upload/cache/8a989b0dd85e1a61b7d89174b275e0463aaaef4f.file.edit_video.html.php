<?php /* Smarty version Smarty-3.1.15, created on 2014-01-17 09:24:03
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/edit_video.html" */ ?>
<?php /*%%SmartyHeaderCode:159422843552d8f6b3527cc1-40046316%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8a989b0dd85e1a61b7d89174b275e0463aaaef4f' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/edit_video.html',
      1 => 1389950537,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '159422843552d8f6b3527cc1-40046316',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'Upload' => 0,
    'vidthumbs' => 0,
    'vid_thumb' => 0,
    'requiredFields' => 0,
    'custom_upload_fields' => 0,
    'field' => 0,
    'formObj' => 0,
    'video_fields' => 0,
    'field_group' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d8f6b3675a57_67891122',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d8f6b3675a57_67891122')) {function content_52d8f6b3675a57_67891122($_smarty_tpl) {?>
<?php if (isset($_smarty_tpl->tpl_vars['custom_upload_fields'])) {$_smarty_tpl->tpl_vars['custom_upload_fields'] = clone $_smarty_tpl->tpl_vars['custom_upload_fields'];
$_smarty_tpl->tpl_vars['custom_upload_fields']->value = $_smarty_tpl->tpl_vars['Upload']->value->load_custom_upload_fields($_smarty_tpl->tpl_vars['data']->value,true); $_smarty_tpl->tpl_vars['custom_upload_fields']->nocache = null; $_smarty_tpl->tpl_vars['custom_upload_fields']->scope = 0;
} else $_smarty_tpl->tpl_vars['custom_upload_fields'] = new Smarty_variable($_smarty_tpl->tpl_vars['Upload']->value->load_custom_upload_fields($_smarty_tpl->tpl_vars['data']->value,true), null, 0);?>
<?php if (isset($_smarty_tpl->tpl_vars['video_fields'])) {$_smarty_tpl->tpl_vars['video_fields'] = clone $_smarty_tpl->tpl_vars['video_fields'];
$_smarty_tpl->tpl_vars['video_fields']->value = $_smarty_tpl->tpl_vars['Upload']->value->load_video_fields($_smarty_tpl->tpl_vars['data']->value); $_smarty_tpl->tpl_vars['video_fields']->nocache = null; $_smarty_tpl->tpl_vars['video_fields']->scope = 0;
} else $_smarty_tpl->tpl_vars['video_fields'] = new Smarty_variable($_smarty_tpl->tpl_vars['Upload']->value->load_video_fields($_smarty_tpl->tpl_vars['data']->value), null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['data']->value['title']!='') {?>


<form action="" method="post" name="Edit Member" id="Edit Member">

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="50%" valign="top">
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" class="left_head" style="padding-left:10px"><div class="head">Editing <?php echo $_smarty_tpl->tpl_vars['data']->value['title'];?>
</div></td>
    <td width="20" align="right" class="right_head">&nbsp;</td>
  </tr>
</table>

<div style="width:98%; margin:auto; padding:10px">
<!--<button onClick="location.href='view_video.php?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
'" class="button">View This video</button>-->

<ul class="admin_links">
<li><a href="<?php echo videoSmartyLink(array('vdetails'=>$_smarty_tpl->tpl_vars['data']->value),$_smarty_tpl);?>
">Watch this video</a></li>
<?php if ($_smarty_tpl->tpl_vars['data']->value['active']!='yes') {?>
<li><a href="edit_video.php?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
&amp;mode=activate">Activate</a></li>
<?php } else { ?>
<li><a href="edit_video.php?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
&amp;mode=deactivate">Deactivate</a></li>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['data']->value['featured']!='yes') {?>
<li><a href="edit_video.php?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
&amp;mode=featured">Make Featured</a></li>
<?php } else { ?>
<li><a href="edit_video.php?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
&amp;mode=unfeature">Make Unfeatured</a></li>
<?php }?>
<li><a href="javascript:void(0)" onClick="javascript:Confirm_Delete('video_manager.php?delete_video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
')">Delete This Video</a></li>
</ul>
</div>

  

<input name="admin" type="hidden" id="admin" value="true" />
<input name="videoid" type="hidden"  value="<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
" />

<fieldset class="fieldset">
  <legend>User Information</legend>
  <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
    <tr>
      <td width="200"><strong>Useid</strong></td>
      <td><a href="view_user.php?uid=<?php echo $_smarty_tpl->tpl_vars['data']->value['userid'];?>
"><?php echo get_username($_smarty_tpl->tpl_vars['data']->value['userid']);?>
</a></td>
    </tr>
  </table>
</fieldset>

<fieldset class="fieldset">
  <legend>Important Details</legend>
  <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
    <tr>
      <td width="200"><strong>Video Id</strong></td>
      <td>
        <input disabled="disabled" name="videokey" type="text" id="videokey" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
" size="45" />
      </td>
    </tr>
    <tr>
      <td width="200"><strong>Video Key</strong></td>
      <td>
        <input disabled="disabled" name="flvname" type="text" id="flvname" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['videokey'];?>
" size="45" />
      </td>
    </tr>
    <tr>
      <td width="200"><strong>File Name</strong></td>
      <td>
        <input disabled="disabled" name="flvname" type="text" id="flvname" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['file_name'];?>
" size="45" />
      </td>
    </tr>
    <tr>
      <td width="200"><strong>Total Video Files</strong></td>
      <td><?php echo get_all_video_files_smarty(array('vdetails'=>$_smarty_tpl->tpl_vars['data']->value,'count_only'=>true),$_smarty_tpl);?>
 - <a href="view_conversion_log.php?file_name=<?php echo $_smarty_tpl->tpl_vars['data']->value['file_name'];?>
"><strong>View File Details and Conversion Log</strong></a></td>
    </tr>
    <tr>
      <td width="200"><strong>Total Thumbnails</strong></td>
      <td><?php echo getSmartyThumb(array('vdetails'=>$_smarty_tpl->tpl_vars['data']->value,'count_only'=>true),$_smarty_tpl);?>
</td>
    </tr>

    <tr>
      <td width="200"><strong>Thumbnails<BR>
<?php if ($_smarty_tpl->tpl_vars['data']->value['embeded']!='yes') {?><a href="upload_thumbs.php?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
&amp;gen_more=true">Regenerate  Thumbs</a><?php }?><br />
    <a href="upload_thumbs.php?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
">Manage Thumbs</a></strong></td>
      <td><?php if (isset($_smarty_tpl->tpl_vars['vidthumbs'])) {$_smarty_tpl->tpl_vars['vidthumbs'] = clone $_smarty_tpl->tpl_vars['vidthumbs'];
$_smarty_tpl->tpl_vars['vidthumbs']->value = get_thumb($_smarty_tpl->tpl_vars['data']->value,1,true,false,true,false); $_smarty_tpl->tpl_vars['vidthumbs']->nocache = null; $_smarty_tpl->tpl_vars['vidthumbs']->scope = 0;
} else $_smarty_tpl->tpl_vars['vidthumbs'] = new Smarty_variable(get_thumb($_smarty_tpl->tpl_vars['data']->value,1,true,false,true,false), null, 0);?>

    <?php  $_smarty_tpl->tpl_vars['vid_thumb'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['vid_thumb']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['vidthumbs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['vid_thumb']->key => $_smarty_tpl->tpl_vars['vid_thumb']->value) {
$_smarty_tpl->tpl_vars['vid_thumb']->_loop = true;
?>
    	<div style="width:140px; float:left" align="center">
    	<label for="<?php echo getname($_smarty_tpl->tpl_vars['vid_thumb']->value);?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['vid_thumb']->value;?>
" border="1" style="margin:4px;max-width:120px" /><br />
        <input type="radio" value="<?php echo getname($_smarty_tpl->tpl_vars['vid_thumb']->value);?>
.<?php echo getext($_smarty_tpl->tpl_vars['vid_thumb']->value);?>
" id="<?php echo getname($_smarty_tpl->tpl_vars['vid_thumb']->value);?>
" name="default_thumb" <?php if ($_smarty_tpl->tpl_vars['data']->value['default_thumb']==get_thumb_num($_smarty_tpl->tpl_vars['vid_thumb']->value)) {?> checked="checked"<?php }?> />Default</label><br />
  <?php if (getname($_smarty_tpl->tpl_vars['vid_thumb']->value)!='processing') {?>
  <a href="?video=<?php echo $_smarty_tpl->tpl_vars['data']->value['videoid'];?>
&delete=<?php echo getname($_smarty_tpl->tpl_vars['vid_thumb']->value);?>
.<?php echo getext($_smarty_tpl->tpl_vars['vid_thumb']->value);?>
">Delete</a>
  <?php }?>
  </div>
    <?php } ?>
    
    </td>
    </tr>
    
    <?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['requiredFields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
  <?php } ?>
  
    <tr>
      <td width="200"><strong>Status</strong></td>
      <td>
        <select name="status">
          <option value="Successful" <?php if ($_smarty_tpl->tpl_vars['data']->value['status']=='Successful') {?> selected <?php }?>>Successfull</option>
          <option value="processing" <?php if ($_smarty_tpl->tpl_vars['data']->value['status']=='Processing') {?> selected <?php }?>>Processing</option>
          <option value="Failed" 	<?php if ($_smarty_tpl->tpl_vars['data']->value['status']=='Failed') {?> selected <?php }?>>Failed</option>
        </select>
      </td>
    </tr>
    <tr>
      <td width="200"><strong>Duration (seconds) </strong></td>
      <td>
        <label>
          <input name="duration" type="text" id="duration" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['duration'];?>
" size="5" />
        </label>
seconds</td>
    </tr>
  </table>
</fieldset>


<fieldset class="fieldset">
  <legend>Video Stats</legend>
  <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
    <tr>
    <td width="200" ><strong>Views</strong></td>
    <td><input name="views" type="text" id="views" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['views'];?>
" size="45" /></td>
  </tr>
  <tr>
    <td width="200" ><strong>Rating</strong></td>
    <td><input name="rating" type="text" id="rating" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['rating'];?>
" size="5" /> 
    of 10 </td>
  </tr>
  <tr>
    <td width="200" ><strong>RatedBy</strong></td>
    <td><input name="rated_by" type="text" id="rated_by" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['rated_by'];?>
" size="45" /></td>
  </tr>
  </table>
</fieldset>
<fieldset class="fieldset">
  <legend>Custom Upload Fields</legend>
  <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
      <?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['custom_upload_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
  <tr>
    <td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['field']->value['title'];?>
</strong></td>
    <td><?php echo $_smarty_tpl->tpl_vars['field']->value['hint_1'];?>
<?php echo ANCHOR(array('place'=>$_smarty_tpl->tpl_vars['field']->value['anchor_before']),$_smarty_tpl);?>
<?php echo $_smarty_tpl->tpl_vars['formObj']->value->createField($_smarty_tpl->tpl_vars['field']->value);?>

                    <br>
      <?php echo $_smarty_tpl->tpl_vars['field']->value['hint_2'];?>
</td>
  </tr>
  <?php } ?>
  </table>
</fieldset>












<div style="width:98%; margin:auto">
<input type="submit" class="button" value="Update video details" style="margin:5px 0px 15px 0px" name="update">
</div>


</td>
    <td width="50%" valign="top"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left" class="left_head" style="padding-left:10px"><div class="head">Viewing <?php echo $_smarty_tpl->tpl_vars['data']->value['title'];?>
</div></td>
        <td width="20" align="right" class="right_head">&nbsp;</td>
      </tr>
    </table>
    <div style="padding:5px">

        <?php echo flashPlayer(array('vdetails'=>$_smarty_tpl->tpl_vars['data']->value,'width'=>'100%','autoplay'=>'false'),$_smarty_tpl);?>


    </div>
    
    <?php  $_smarty_tpl->tpl_vars['field_group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field_group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['video_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field_group']->key => $_smarty_tpl->tpl_vars['field_group']->value) {
$_smarty_tpl->tpl_vars['field_group']->_loop = true;
?>
    <fieldset class="fieldset">
        <legend><?php echo $_smarty_tpl->tpl_vars['field_group']->value['group_name'];?>
</legend>
        <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="block">
            <?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['field_group']->value['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value) {
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
                <tr>
                	<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['field']->value['title'];?>
 </strong></td>
                	<td ><?php if ($_smarty_tpl->tpl_vars['field']->value['hint_1']) {?><?php echo $_smarty_tpl->tpl_vars['field']->value['hint_1'];?>
<br /><?php }?>
                	  <?php echo $_smarty_tpl->tpl_vars['formObj']->value->createField($_smarty_tpl->tpl_vars['field']->value);?>

               	    <?php if ($_smarty_tpl->tpl_vars['field']->value['hint_2']) {?><br /><?php echo $_smarty_tpl->tpl_vars['field']->value['hint_2'];?>
<?php }?></td>
                </tr>
            <?php } ?>
        </table>
    </fieldset>
	<?php } ?>
    
    
</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><?php if (isset($_smarty_tpl->tpl_vars['id'])) {$_smarty_tpl->tpl_vars['id'] = clone $_smarty_tpl->tpl_vars['id'];
$_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['data']->value['videoid']; $_smarty_tpl->tpl_vars['id']->nocache = null; $_smarty_tpl->tpl_vars['id']->scope = 0;
} else $_smarty_tpl->tpl_vars['id'] = new Smarty_variable($_smarty_tpl->tpl_vars['data']->value['videoid'], null, 0);?><?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['style_dir']->value)."/blocks/comments.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('type'=>'v','id'=>$_smarty_tpl->tpl_vars['data']->value['videoid'],'link'=>"video=".((string)$_smarty_tpl->tpl_vars['id']->value)), 0);?>
</td>
  </tr>
  
  
  
</table>
</form>
<?php }?><?php }} ?>
