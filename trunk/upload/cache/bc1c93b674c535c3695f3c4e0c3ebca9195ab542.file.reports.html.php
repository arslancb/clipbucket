<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:46:55
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/reports.html" */ ?>
<?php /*%%SmartyHeaderCode:2085336252d6590f064d12-02431136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bc1c93b674c535c3695f3c4e0c3ebca9195ab542' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/reports.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2085336252d6590f064d12-02431136',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'baseurl' => 0,
    'myquery' => 0,
    'cbvid' => 0,
    'userquery' => 0,
    'cbgroup' => 0,
    'vid_dir' => 0,
    'thumb_dir' => 0,
    'orig_dir' => 0,
    'user_thumbs' => 0,
    'user_bg' => 0,
    'grp_thumbs' => 0,
    'cat_thumbs' => 0,
    'db_size' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d6590f14a5c7_27147050',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d6590f14a5c7_27147050')) {function content_52d6590f14a5c7_27147050($_smarty_tpl) {?><div class="clearfix"></div>
<div id="my_chart"></div>
<script type="text/javascript">
var daily_activity = "<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/admin_area/charts/daily_activity.php";
swfobject.embedSWF("<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/player/open-flash-chart.swf", "my_chart", "99%", "300", "9.0.0", "expressInstall.swf", {"data-file":daily_activity} );
</script>
<div style="height:20px"></div>



<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="block">
            <tr>
              <td colspan="3"><h2>Videos Reports</h2></td>
            </tr>
            <tr>
              <td width="120">All Time</td>
              <td>Total <strong><?php echo get_videos(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_videos(array('count_only'=>'yes','active'=>"yes"),$_smarty_tpl);?>
</strong> &#8211; Processing <strong><?php echo get_videos(array('count_only'=>'yes','status'=>'Processing'),$_smarty_tpl);?>
</strong></td>
              
        </tr>
            <tr>
              <td width="120">Todays Videos</td>
              <td>Total <strong><?php echo get_videos(array('count_only'=>'yes','date_span'=>'today'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_videos(array('count_only'=>'yes','active'=>"yes",'date_span'=>'today'),$_smarty_tpl);?>
</strong> &#8211; Processing <strong><?php echo get_videos(array('count_only'=>'yes','status'=>'Processing','date_span'=>'today'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td width="120">This Week</td>
              <td>Total <strong><?php echo get_videos(array('count_only'=>'yes','date_span'=>'this_week'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_videos(array('count_only'=>'yes','active'=>"yes",'date_span'=>'this_week'),$_smarty_tpl);?>
</strong> &#8211; Processing <strong><?php echo get_videos(array('count_only'=>'yes','status'=>'Processing','date_span'=>'this_week'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td>Last Week</td>
              <td>Total <strong><?php echo get_videos(array('count_only'=>'yes','date_span'=>'last_week'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_videos(array('count_only'=>'yes','active'=>"yes",'date_span'=>'last_week'),$_smarty_tpl);?>
</strong> &#8211; Processing <strong><?php echo get_videos(array('count_only'=>'yes','status'=>'Processing','date_span'=>'last_week'),$_smarty_tpl);?>
</strong></td>
            </tr>
            <tr>
              <td>This Month</td>
              <td>Total <strong><?php echo get_videos(array('count_only'=>'yes','date_span'=>'this_month'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_videos(array('count_only'=>'yes','active'=>"yes",'date_span'=>'this_month'),$_smarty_tpl);?>
</strong> &#8211; Processing <strong><?php echo get_videos(array('count_only'=>'yes','status'=>'Processing','date_span'=>'this_month'),$_smarty_tpl);?>
</strong></td>
            </tr>
            <tr>
              <td>Last Month</td>
              <td>Total <strong><?php echo get_videos(array('count_only'=>'yes','date_span'=>'last_month'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_videos(array('count_only'=>'yes','active'=>"yes",'date_span'=>'last_month'),$_smarty_tpl);?>
</strong> &#8211; Processing <strong><?php echo get_videos(array('count_only'=>'yes','status'=>'Processing','date_span'=>'last_month'),$_smarty_tpl);?>
</strong></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
    </table></td>
    <td width="50%" valign="top"><div id="videos_stats"></div>
<script type="text/javascript">
var daily_activity = "<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/admin_area/charts/videos_activity.php";
swfobject.embedSWF("<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/player/open-flash-chart.swf", "videos_stats", "98%", "200", "9.0.0", "expressInstall.swf", {"data-file":daily_activity} );
</script></td>
  </tr>
</table>



<div style="height:25px"></div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="block">
            <tr>
              <td colspan="3"><h2>User Reports</h2></td>
            </tr>
            <tr>
              <td width="120">All Time</td>
              <td>Total <strong><?php echo get_users(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_users(array('count_only'=>'yes','status'=>'Ok'),$_smarty_tpl);?>
</strong> &#8211; Inactive <strong><?php echo get_users(array('count_only'=>'yes','status'=>'ToActivate'),$_smarty_tpl);?>
</strong></td>
              
        </tr>
            <tr>
              <td width="120">Todays Users</td>
              <td>Total <strong><?php echo get_users(array('count_only'=>'yes','date_span'=>'today'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_users(array('count_only'=>'yes','status'=>'Ok','date_span'=>'today'),$_smarty_tpl);?>
</strong> &#8211; Inactive <strong><?php echo get_users(array('count_only'=>'yes','status'=>'ToActivate','date_span'=>'today'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td width="120">This Week</td>
              <td>Total <strong><?php echo get_users(array('count_only'=>'yes','date_span'=>'this_week'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_users(array('count_only'=>'yes','status'=>'Ok','date_span'=>'this_week'),$_smarty_tpl);?>
</strong> &#8211; Inactive <strong><?php echo get_users(array('count_only'=>'yes','status'=>'ToActivate','date_span'=>'this_week'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td width="120">Last Week</td>
              <td>Total <strong><?php echo get_users(array('count_only'=>'yes','date_span'=>'last_week'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_users(array('count_only'=>'yes','status'=>'Ok','date_span'=>'last_week'),$_smarty_tpl);?>
</strong> &#8211; Processing <strong><?php echo get_users(array('count_only'=>'yes','status'=>'ToActivate','date_span'=>'last_week'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td>This Month</td>
              <td>Total <strong><?php echo get_users(array('count_only'=>'yes','date_span'=>'this_month'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_users(array('count_only'=>'yes','status'=>'Ok','date_span'=>'this_month'),$_smarty_tpl);?>
</strong> &#8211; Inactive <strong><?php echo get_users(array('count_only'=>'yes','status'=>'ToActivate','date_span'=>'this_month'),$_smarty_tpl);?>
</strong></td>
            </tr>
            <tr>
              <td>Last Month</td>
              <td>Total <strong><?php echo get_users(array('count_only'=>'yes','date_span'=>'last_month'),$_smarty_tpl);?>
</strong> &#8211; Active <strong><?php echo get_users(array('count_only'=>'yes','status'=>'Ok','date_span'=>'last_month'),$_smarty_tpl);?>
</strong> &#8211; Inactive <strong><?php echo get_users(array('count_only'=>'yes','status'=>'ToActivate','date_span'=>'last_month'),$_smarty_tpl);?>
</strong></td>
            </tr>
    </table></td>
    <td width="50%" valign="top"><div id="user_stats"></div>
<script type="text/javascript">
var daily_activity = "<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/admin_area/charts/users_activity.php";
swfobject.embedSWF("<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/player/open-flash-chart.swf", "user_stats", "98%", "200", "9.0.0", "expressInstall.swf", {"data-file":daily_activity} );
</script></td>
  </tr>
</table>






<div style="height:25px"></div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="block">
            <tr>
              <td colspan="3"><h2>Group Reports</h2></td>
            </tr>
            <tr>
              <td width="120">All Time</td>
              <td>Total <strong><?php echo get_groups(array('count_only'=>'yes'),$_smarty_tpl);?>
</strong> &#8211; 
              Active <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'yes'),$_smarty_tpl);?>
</strong> &#8211; 
              Inactive <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'no'),$_smarty_tpl);?>
</strong>
              </td>
              
        </tr>
            <tr>
              <td width="120">Today</td>
              <td>Total <strong><?php echo get_groups(array('count_only'=>'yes','date_span'=>'today'),$_smarty_tpl);?>
</strong> &#8211; 
              Active <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'yes','date_span'=>'today'),$_smarty_tpl);?>
</strong> &#8211; 
              Inactive <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'no','date_span'=>'today'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td width="120">This Week</td>
              <td>Total <strong><?php echo get_groups(array('count_only'=>'yes','date_span'=>'this_week'),$_smarty_tpl);?>
</strong> &#8211; 
              Active <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'yes','date_span'=>'this_week'),$_smarty_tpl);?>
</strong> &#8211; 
              Inactive <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'no','date_span'=>'this_week'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td width="120">Last Week</td>
              <td>Total <strong><?php echo get_groups(array('count_only'=>'yes','date_span'=>'last_week'),$_smarty_tpl);?>
</strong> &#8211; 
              Active <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'yes','date_span'=>'last_week'),$_smarty_tpl);?>
</strong> &#8211; 
              Inactive <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'no','date_span'=>'last_week'),$_smarty_tpl);?>
</strong></td>
        </tr>
            <tr>
              <td>This Month</td>
              <td>Total <strong><?php echo get_groups(array('count_only'=>'yes','date_span'=>'this_month'),$_smarty_tpl);?>
</strong> &#8211; 
              Active <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'yes','date_span'=>'this_month'),$_smarty_tpl);?>
</strong> &#8211; 
              Inactive <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'no','date_span'=>'this_month'),$_smarty_tpl);?>
</strong></td>
            </tr>
            <tr>
              <td>Last Month</td>
              <td>Total <strong><?php echo get_groups(array('count_only'=>'yes','date_span'=>'last_month'),$_smarty_tpl);?>
</strong> &#8211; 
              Active <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'yes','date_span'=>'last_month'),$_smarty_tpl);?>
</strong> &#8211; 
              Inactive <strong> <?php echo get_groups(array('count_only'=>'yes','active'=>'no','date_span'=>'last_month'),$_smarty_tpl);?>
</strong></td>
            </tr>
    </table></td>
    <td width="50%" valign="top"><div id="group_stats"></div>
<script type="text/javascript">
var daily_activity = "<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/admin_area/charts/groups_activity.php";
swfobject.embedSWF("<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/player/open-flash-chart.swf", "group_stats", "98%", "200", "9.0.0", "expressInstall.swf", {"data-file":daily_activity} );
</script></td>
  </tr>
</table>


<div style="height:25px"></div>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="block">
  <tr>
    <td width="25%"><h2>Comments</h2></td>
    <td width="25%"><h2>Flags</h2></td>
    <td width="25%"><h2>Playlists</h2></td>
    <td width="25%"><h2>Favorites</h2></td>
  </tr>
  <tr>
    <td valign="top"><ul>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['myquery']->value->get_comments('wildcard','v',true);?>
 </strong>video comments</li>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['myquery']->value->get_comments('wildcard','t',true);?>
 </strong>Group Discussions</li>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['myquery']->value->get_comments('wildcard','c',true);?>
 </strong>Profile Comments</li>
    </ul></td>
    <td valign="top"><ul>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['cbvid']->value->action->count_flagged_objects();?>
</strong> videos are reported</li>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['userquery']->value->action->count_flagged_objects();?>
</strong> groups are reported</li>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['cbgroup']->value->action->count_flagged_objects();?>
</strong> profiles are reported</li>
    </ul>
    <p>&nbsp;</p></td>
    <td valign="top"><ul>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['cbvid']->value->action->count_total_playlist();?>
</strong> Video Playlists are created</li>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['cbvid']->value->action->count_total_playlist(true);?>
</strong> Video Items are in playlist</li>
    </ul></td>
    <td valign="top"><ul>
      <li><strong><?php echo $_smarty_tpl->tpl_vars['cbvid']->value->action->total_favorites();?>
</strong> favorite videos</li>
    </ul></td>
  </tr>
</table>




<div style="height:25px"></div>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="block">
  <tr>
    <td width="25%"><h2>Files and Sizes</h2></td>
  </tr>
  <tr>
    <td valign="top"><ul>
      <li>Video Files : <strong><?php echo number_format($_smarty_tpl->tpl_vars['vid_dir']->value['count']);?>
</strong> &#8211; Folder Size : <strong><?php echo formatfilesize($_smarty_tpl->tpl_vars['vid_dir']->value['size']);?>
</strong></li>
      <li>Thumb Files : <strong><?php echo number_format($_smarty_tpl->tpl_vars['thumb_dir']->value['count']);?>
</strong> &#8211; Folder Size : <strong><?php echo formatfilesize($_smarty_tpl->tpl_vars['thumb_dir']->value['size']);?>
</strong></li>
      <li>Original Video Files : <strong><?php echo number_format($_smarty_tpl->tpl_vars['orig_dir']->value['count']);?>
</strong> &#8211; Folder Size : <strong><?php echo formatfilesize($_smarty_tpl->tpl_vars['orig_dir']->value['size']);?>
</strong></li>
      <li>User Thumb Files : <strong><?php echo number_format($_smarty_tpl->tpl_vars['user_thumbs']->value['count']);?>
</strong> &#8211; Folder Size : <strong><?php echo formatfilesize($_smarty_tpl->tpl_vars['user_thumbs']->value['size']);?>
</strong></li>
      <li>User Background Files <strong><?php echo number_format($_smarty_tpl->tpl_vars['user_bg']->value['count']);?>
</strong> &#8211; Folder Size : <strong><?php echo formatfilesize($_smarty_tpl->tpl_vars['user_bg']->value['size']);?>
</strong></li>
      <li>Groups Thumb Files : <strong><?php echo number_format($_smarty_tpl->tpl_vars['grp_thumbs']->value['count']);?>
</strong> &#8211; Folder Size : <strong><?php echo formatfilesize($_smarty_tpl->tpl_vars['grp_thumbs']->value['size']);?>
</strong></li>
      <li>Category Thumb Files <strong><?php echo number_format($_smarty_tpl->tpl_vars['cat_thumbs']->value['count']);?>
</strong> &#8211; Folder Size : <strong><?php echo formatfilesize($_smarty_tpl->tpl_vars['cat_thumbs']->value['size']);?>
</strong></li>
      <li>Database Size : <strong><?php echo $_smarty_tpl->tpl_vars['db_size']->value;?>
</strong></li>
    </ul></td>
  </tr>
</table><?php }} ?>
