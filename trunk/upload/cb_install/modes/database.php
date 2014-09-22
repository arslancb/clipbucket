

</div>
<div class="clearfix"></div>

<div class="nav_des clearfix">
    <div class="cb_container">
    <h4 style="color:#fff">Database Settings</h4>
    <p style="color:#fff; font-size:13px;">To setup ClipBucket, we need some information on the database. You will need to know the following items before proceeding.<br>
    In all likelihood, these items were supplied to you by your Web Host. If you do not have this information, then you will need to contact them before you can continue.<br> If you&rsquo;re all ready&hellip;Below you should enter your database connection details
    </p>



</div><!--cb_container-->
</div><!--nav_des-->
<!--<div style="margin-left:52%;" class="db_png"></div>-->
<div id="sub_container" class="br5px">

<div class="errorDiv br5px" id="dbresult" style="display:none"></div>
<div class="db_image"><img src="<?php echo installer_path(); ?>images/db_img.png" style="margin-top: 28px;margin-left: 485px;"  width="392" height="392"/></div>

<div  class="db_fields" style="margin-top:-430px;">
    <form name="installation" method="post" id="installation">
    
    <div class="field">
    <label class="grey-text" for="host">Host</label>
    <input name="dbhost" type="text" id="host" class="form-control" value="localhost" >
    <p class="grey-text font-size" style="margin-top:0px;">You should be able to get this info from your web host, if localhost <br> does 
     not work.</p>
    </div>


    
    <div class="field">
    <label class="grey-text" for="dbname">Database Name</label>
    <input type="text" name="dbname" id="dbname" value="" class="form-control">
    <p class="grey-text font-size" style="margin-top:0px;">The name of the database you want to run Clipbucket in.</p>
    </div>
    
    <div class="field">
    <label class="grey-text" for="dbuser">Database User</label>
    
    <input type="text" name="dbuser" id="dbuser" value=""class="form-control" >
    <p class="grey-text font-size" style="margin-top:0px;">your MYSQL username.</p>
    </div>
    
    <div class="field">
    <label class="grey-text" for="dbpass">Database Password</label>  
    <input type="text" name="dbpass" id="dbpass" value=""class="form-control" >
    <p class="grey-text font-size" style="margin-top:0px;">your MYSQL password.</p>
    </div>
    
    <div class="field">
    <label class="grey-text" for="dbprefix">Database Prefix</label>
    
    <input type="text" name="dbprefix" id="dbprefix" value="cb_" class="form-control">
    <p class="grey-text font-size" style="margin-top:0px;">If you want to run multiple Clipbucket installations in a single database,<br> 
change this.</p>
    </div>
    
   
    <input type="hidden" name="mode" value="dataimport" />
    
    </form>
<div style="padding:10px 0px" align="left"><?php echo button('Check Connection',' onclick="dbconnect()" ');?> <span id="loading"></span></div>


</div>







<!--
<h2>Database Settings</h2>

To setup ClipBucket, we need some   information on the database. You will need to know the following items   before proceeding.</p>
  <ol>
    <li>Database name</li>
    <li>Database username</li>
    <li>Database password</li>
    <li>Database host</li>
    <li>Table prefix (if you want to run more than one ClipBucket in a   single database) </li>
  </ol>
  <p>In all likelihood, these items were supplied to you by your Web Host.   If you do not have this information, then you will need to contact them   before you can continue. If you&rsquo;re all ready&hellip;Below you should enter your database connection details
  
  








  <div class="errorDiv br5px" id="dbresult" style="display:none">
	
  </div>
	<form name="installation" method="post" id="installation">
    
    <div class="field">
    <label for="host">Host</label>
    <input name="dbhost" type="text" id="host" class="br5px" value="localhost">
    </div>
    
    <div class="field">
    <label for="dbname">Database Name</label>
    <input type="text" name="dbname" id="dbname" value=""
    class="br5px" >
    </div>
    
    <div class="field">
    <label for="dbuser">Database User</label>
    
    <input type="text" name="dbuser" id="dbuser" value=""
    class="br5px" >
    </div>
    
    <div class="field">
    <label for="dbpass">Database Password</label>  
    <input type="text" name="dbpass" id="dbpass" value=""
    class="br5px" >
    </div>
    
    <div class="field">
    <label for="dbprefix">Database Prefix</label>
    
    <input type="text" name="dbprefix" id="dbprefix" value="cb_"
    class="br5px" >
    </div>
    
   
	<input type="hidden" name="mode" value="dataimport" />
    
	</form>

    -->