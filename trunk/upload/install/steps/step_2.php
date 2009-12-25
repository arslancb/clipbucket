<div class="content">
	<h2>Checking File &amp; Directories Permissions</h2>
    <?php include("msgs.php") ?>
</div>

<div class="footer" align="right">

 
  <?php
    if(count($errors)>0)
	{
	?>
    <form name="form1" method="post" action="">
   		<input type="submit" name="step1" id="step1" value="Recheck" class="button">
        <input type="hidden" name="step"  value="2" >
        <input type="button" name="step1" id="step1" value="Continue" class="button_disabled" onClick="return false;">
    </form>
  	
    <?php
	}else{
	?>
    <form name="form1" method="post" action="">
    <input type="hidden" name="step"  value="3" >
    <input type="submit" name="step1" id="step1" value="Continue" class="button">
    </form>
    <?php
	}
	?>
  </form>
  
</div>

