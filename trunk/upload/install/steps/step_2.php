<div class="content">
	<h2>Checking File &amp; Directories Permissions</h2>
    <p>ClipBucket need some files and folders permissions in order to store files properly, please make sure all files given below are chmod properly<br />
<em>CHMOD : the chmod command (abbreviated from <strong>ch</strong>ange <strong>mod</strong>e) is a shell command and C language function in Unix and Unix-like  environments.</em></p>
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
<?=the_installer_footer()?>
