<div class="content">
  <h2>You are about to upgrade ClipBucket from <?=the_version()?> to <?=VERSION?></h2>
  There possibilies that few of your data may loss during upgrade because of improper details or damaged structer.
<?php include("msgs.php") ?>
</div>
<div class="footer" align="right">

  <form name="form1" method="post" action="">
  	    
    <?php
	if(count($errors)>0)
	{
	?>
    <input type="hidden" name="step"  value="upgrade_0" id="step" >
    <input type="submit" name="step0" id="step0" value="Recheck" class="button"  >
    <input type="submit" name="step0" id="step0" value="Continue Upgrade" class="button_disabled"  onClick="return false;">
    <?php
	}else{
	?>
    <input type="hidden" name="step"  value="upgrade_1" id="step" >
   	<input type="submit" name="step0" id="step0" value="Recheck" class="button_disabled"    onClick="return false;">
    <input type="submit" name="step0" id="step0" value="Continue Upgrade" class="button">
    <?php
	}
	?>

    
  </form>
  
</div>
<?=the_installer_footer()?>