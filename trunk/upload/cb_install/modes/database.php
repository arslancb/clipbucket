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
    <div style="padding:10px 0px" align="left"><?=button('Check Connection',' onclick="dbconnect()" ');?> <span id="loading"></span></div>
	</form>