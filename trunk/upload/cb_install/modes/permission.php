
<h2>Checking File &amp; Directories Permissions</h2>
    ClipBucket need some files and folders permissions in order to store files properly, please make sure all files given below are chmod properly<br />
<em>CHMOD : the chmod command (abbreviated from <strong>ch</strong>ange <strong>mod</strong>e) is a shell command and C language function in Unix and Unix-like  environments.</em></p>
	
</p>


<dl>
<? $permissions = checkPermissions(); ?>
<?php
    foreach($permissions as $permission)
    {
        ?>
        <dt style="width:300px"><?=$permission['path']?></dt>
        
        <dd><?=msg_arr($permission);?></dd>
        <?
    }
?>
    
</dl>


<form name="installation" method="post" id="installation">
    <div style="padding:10px 0px" align="right">
	<?=button('Recheck',' onclick="$(\'#mode\').val(\'permission\');
	 $(\'#installation\').submit()" ',true);?>
     
	<?=button('Continue To Next Step',' onclick="$(\'#installation\').submit()" ');?></div>
    
    <?php if(!$upgrade): ?>
    <input type="hidden" name="mode" value="database"  id="mode"/>
    <?php else: ?>
    <input type="hidden" name="mode" value="dataimport"  id="mode"/>
    <?php endif; ?>
</form>
