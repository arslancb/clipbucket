<div class="content">
  <p>Welcome to ClipBucket. Before getting started, we need some   information on the database. You will need to know the following items   before proceeding.</p>
  <ol>
    <li>Database name</li>
    <li>Database username</li>
    <li>Database password</li>
    <li>Database host</li>
    <li>Table prefix (if you want to run more than one ClipBucket in a   single database) </li>
  </ol>
  <p>In all likelihood, these items were supplied to you by your Web Host.   If you do not have this information, then you will need to contact them   before you can continue. If you&rsquo;re all ready&hellip;</p>
<h2>License</h2>
  <div style="padding:3px 15px; height:250px; overflow:auto; background-color:#F5F5F5"><?=get_cbla()?></div>
</div>

<div class="footer" align="right">

  <form name="form1" method="post" action="">
  	<input type="submit" name="step2" id="step1" value="I Accept" class="button">
     <input type="hidden" name="step"  value="2" >
  </form>
  
</div>


<?=the_installer_footer()?>