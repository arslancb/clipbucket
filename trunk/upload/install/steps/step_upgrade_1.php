 <form name="form2" method="post" action="" class="install_form"><div class="content">
	<h2>Database Settings</h2>
    <div style="font-size:12px; font-weight:normal; margin-bottom:10px">You are now about to upgrade your ClipBucket to the latest version, please enter database details in order to perform upgrade</div>
    
 <?php include("msgs.php");
 
 if(count($msgs)==0)
	{
 
 ?>
 
 
    <label for="host">Host</label>
    <input name="host" type="text" id="host" value="<? if($_POST['host']) echo form_val(post('host')); else echo "localhost"?>">
    <label for="dbname">Database Name</label>
    <input type="text" name="dbname" id="dbname" value="<? if($_POST['dbname']) echo form_val(post('dbname'));?>">
    <label for="dbuser">Database User</label>
    <input type="text" name="dbuser" id="dbuser" value="<? if($_POST['dbuser']) echo form_val(post('dbuser'));?>">
    <label for="dbpass">Database Password</label>
    <input type="text" name="dbpass" id="dbpass" value="<? if($_POST['dbpass']) echo form_val(post('dbpass'));?>">
    <label for="dbprefix">Database Prefix</label>
    <input type="text" name="dbprefix" id="dbprefix" value="<? if($_POST['dbprefix']) echo form_val(post('dbprefix')); else echo "cb_";?>">
  
  <?php
	}else
	{
	?>
    
    <input name="host" type="hidden" id="host" value="<? if($_POST['host']) echo form_val(post('host')); else echo "localhost"?>">
    <input type="hidden" name="dbname" id="dbname" value="<? if($_POST['dbname']) echo form_val(post('dbname'));?>">
    <input type="hidden" name="dbuser" id="dbuser" value="<? if($_POST['dbuser']) echo form_val(post('dbuser'));?>">
    <input type="hidden" name="dbpass" id="dbpass" value="<? if($_POST['dbpass']) echo form_val(post('dbpass'));?>">
    <input type="hidden" name="dbprefix" id="dbprefix" value="<? if($_POST['dbprefix']) echo form_val(post('dbprefix'));?>">
    <?php
	}
	?>
</div>

<div class="footer" align="right">

  <?php
  	if(count($msgs)>0)
	{
		?>
  	<input type="submit" name="step3" id="step3" value="Continue To Upgrade" class="button">
     <input type="hidden" name="step"  value="upgrade_2" >
     <?php
	}else{
	?>
     <input type="submit" name="check_db_connection" id="step3" value="Check Connection" class="button">
     <input type="hidden" name="step"  value="upgrade_1" >
     <?php
	}
	?>
  
  
</div>

</form>
<?=the_installer_footer()?>