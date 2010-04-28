<div class="content">
	<h2>Upgrading ClipBucket <?=the_version()?> to <?=VERSION?></h2>
    Please follow all steps , this may take few minutes....
  <div id="the_results" style="border:1px solid #CCC; background-color:#FBFBFB; margin:10px auto; padding:5px">
    	<strong>Step 1/6 - Importing database</strong>
        <?php include("msgs.php") ?>
        <a href="javascript:void(0)" onClick="import_users()">Click Here To Continue Upgrading...</a>
  </div>
</div>
<div class="footer" align="right"></div>
<?=the_installer_footer()?>