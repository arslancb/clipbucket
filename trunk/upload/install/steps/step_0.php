<div class="content">
	<h2>Please select your installation method</h2>
</div>

<div class="footer" align="right">

  <form name="form1" method="post" action="">
  	<input type="submit" name="step0" id="step0" value="Upgrade From 1.x" class="button_disabled"  onClick="return false;">
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