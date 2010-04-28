
<form name="form1" method="post" action="" class="install_form"><div class="content"><h2>
 Updating ClipBucket from <?=the_version()?> to <?=VERSION?></h2>
 <div style="font-size:12px; font-weight:normal">You are now about to update your ClipBucket to the latest version, please enter database details in order to perform update</div><br />
 <?php
 if(the_version()<'2.0.4')
 {
 ?>
 	<label for="dbprefix">Database Prefix</label>
    <input type="text" name="dbprefix" id="dbprefix" value="<? if($_POST['dbprefix']) echo form_val(post('dbprefix')); else echo "cb_";?>">
    <?php
 }
 ?>
 
<?php include("msgs.php") ?>
</div>
<div class="footer" align="right">

 
  	    
    <?php
	if(count($errors)>0)
	{
	?>
    <input type="hidden" name="step"  value="update_0" id="step" >
    <input type="submit" name="step0" id="step0" value="Recheck" class="button"  >
    <input type="submit" name="step0" id="step0" value="Continue Update" class="button_disabled"  onClick="return false;">
    <?php
	}else{
	?>
    <input type="hidden" name="step"  value="update_1" id="step" >
   	<input type="submit" name="step0" id="step0" value="Recheck" class="button_disabled"    onClick="return false;">
    <input type="submit" name="step0" id="step0" value="Continue Update" class="button">
    <?php
	}
	?>

    
 
  
</div></form>
<?=the_installer_footer()?> 