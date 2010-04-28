<div class="content">
<div align="center"><p>Welcome to ClipBucket, Here you can choose what installation method you want to go with. </p>
<div style="height:15px"></div>
<h2>Please select your installation method</h2>
</div></div>

<div class="footer" align="right">

  <form name="form1" method="post" action="">
  	<?php
	if(!upgrade_able())
	{
	?>
    <input type="submit" name="step0" id="step0" value="Upgrade From 1.7" class="button_disabled"  onClick="return false;">
    <?php
	}else{
	?>
    <input type="submit" name="step0" id="step0" value="Upgrade From 1.7" class="button"  onClick="$('#step').val('upgrade_0');this.submit()">
    <?php
	}
	?>
    
    <input type="hidden" name="step"  value="1" id="step" >
    <?php
	if(!update_able())
	{
	?>
    <input type="submit" name="step0" id="step0" value="Update From 2.x" class="button_disabled"  onClick="return false;">
    <?php
	}else{
	?>
    <input type="submit" name="step0" id="step0" value="Update From 2.x" class="button"  onClick="$('#step').val('update_0');this.submit()">
    <?php
	}
	?>
  	<input type="submit" name="step0" id="step0" value="Fresh Installation" class="button">
    
  </form>
  
</div>
<?=the_installer_footer()?>