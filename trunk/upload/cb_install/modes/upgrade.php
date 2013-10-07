<?php
if(userid() || $old_version < 2.3 ):
?>
<p>You are now going to upgrade from <?=$old_version?> to <?=VERSION?>, please read <strong><a href="http://docs.clip-bucket.com/clipbucket-docs/clipbucket-installation">this documentation</a></strong> for further info and help, please click continue upgrade </p>

<form name="installation" method="post" id="installation">
	<input type="hidden" name="mode" value="permission" />
    <div style="padding:10px 0px" align="right"><?=button('Continue to upgrade!',' onclick="$(\'#installation\').submit()" ');?></div>
</form>
<?php
else:
?>
<div class="errorDiv br5px" id="dbresult" style="">
<?=msg_arr(array('err'=>'Please first login as Website adminstrator by going to admin_area and then try upgrading your website'))?></div><?php
endif;
?>