
</div>

<div class="nav_des clearfix">
    <div class="cb_container">
    <h4 style="color:#fff;">Checking File &amp; Directories Permissions</h4>
    <p style="color:#fff; font-size:13px;">  ClipBucket need some files and folders permissions in order to store files properly, please make sure all files given below are chmod properly.<br />
<em>CHMOD : the chmod command (abbreviated from <strong>ch</strong>ange <strong>mod</strong>e) is a shell command and C language function in Unix and Unix-like  environments.</em></p>



</div><!--cb_container-->
</div><!--nav_des-->



<div id="sub_container" class="br5px">
<dl>
<?php $permissions = checkPermissions(); ?>
<?php
    foreach($permissions as $permission)
    {
        ?>
        <dt style="width:300px;" class="grey-text"><?=$permission['path']?></dt>
        
        <dd  class="grey-text"><?=msg_arr($permission);?></dd>
        <?php
    }
?>
    
</dl>




<form name="installation" method="post" id="installation">
    <div style="padding:10px 0px" align="right">
    <?=button_green('Recheck',' onclick="$(\'#mode\').val(\'permission\');
     $(\'#installation\').submit()" ',true);?>
     
    <?=button('Continue To Next Step',' onclick="$(\'#installation\').submit()" ');?></div>
    
    <?php if(!$upgrade): ?>
    <input type="hidden" name="mode" value="database"  id="mode"/>
    <?php else: ?>
    <input type="hidden" name="mode" value="dataimport"  id="mode"/>
    <?php endif; ?>
</form>



















