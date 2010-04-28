 <form name="form2" method="post" action="" class="install_form"><div class="content">
	<h2>Database Settings</h2>
    
  
 <?php include("msgs.php");
 
 if(count($msgs)==0)
	{
 
 ?>
 
 	<p style="font-size:11px; font-weight:normal">  Below you should enter your database connection details. If you're not sure about these, contact your host. </p>
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
    <p style="font-size:12px; font-weight:normal">  Alright....database connection has been created, ClipBucket will now  create some tables and insert data...click continue</p>
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
  	<input type="submit" name="step3" id="step3" value="Continue Installation" class="button">
     <input type="hidden" name="step"  value="4" >
     <?php
	}else{
	?>
     <input type="submit" name="check_db_connection" id="step3" value="Check Connection" class="button">
     <input type="hidden" name="step"  value="3" >
     <?php
	}
	?>
  
  
</div>

</form>
<?=the_installer_footer()?>