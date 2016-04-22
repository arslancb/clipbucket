<?php
if(userid() || $old_version < 2.3 ):
?>

</div>
<div class="nav_des clearfix">
    <div class="cb_container">
    <h4 style="color:#fff;">Upgrading</h4>
    <p style="color:#fff; font-size:13px;">You are now going to upgrade from <?php echo $old_version?> to <?php echo VERSION?>, please read <strong>
    <a href="http://docs.clip-bucket.com/clipbucket-docs/clipbucket-installation" style="color:#fff;text-decoration:underline;">this documentation</a></strong> for further info and help, please click continue upgrade
</p>



</div><!--cb_container-->
</div><!--nav_des-->


<div id="sub_container" >
<form name="installation" method="post" id="installation">
	<input type="hidden" name="mode" value="permission" />
    <div style="padding:10px 0px" align="right"><?php echo button('Continue to upgrade!',' onclick="$(\'#installation\').submit()" ');?></div>
</form>
<?php
else:
?>
<div class="errorDiv br5px" id="dbresult" style="">
<?php echo msg_arr(array('err'=>'Please first login as Website adminstrator by going to admin_area and then try upgrading your website'))?></div><?php
endif;
?>