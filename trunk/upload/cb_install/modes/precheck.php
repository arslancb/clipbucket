<?php 
$php_ver = check_module('php');
?>
<h2>Prechecking</h2>
Clipbucket requires following modules in order to work properly, we are performing some initial search to find modules.
<a href="http://docs.clip-bucket.com/clipbucket-docs/clipbucket-installation#requirments">Click here</a> for why we need these modules
<p>
	
</p>

<dl>
    <dt>PHP</dt>
    	<?php $php_ver = check_module('php');  ?>
    	<dd><?php echo msg_arr($php_ver);?></dd>
    <dt>FFMPEG</dt>
    	<?php $ffmpeg_ver = check_module('ffmpeg'); ?>
    <dd><?php echo msg_arr($ffmpeg_ver);?></dd>
    <dt>FLVtool2</dt>
         <?php $flvtool2_ver = check_module('flvtool2'); ?>
    	<dd><?php echo msg_arr($flvtool2_ver);?></dd>
        
    <dt>MP4Box</dt>
    	<?php $mp4boxver = check_module('mp4box'); ?>
    	<dd><?php echo msg_arr($mp4boxver);?></dd>
    <dt>cURL</dt>
    	<?php $curlver = check_module('curl'); ?>
    	<dd><?php echo msg_arr($curlver);?></dd>
    <dt>PHPShield</dt>
    	<?php $phpshield = check_module('phpshield'); ?>
    	<dd><?php echo msg_arr($phpshield);?></dd>
</dl>
<p></p>


<form name="installation" method="post" id="installation">
	<input type="hidden" name="mode" value="permission" />
    <div style="padding:10px 0px" align="right"><?=button('Continue To Next Step',' onclick="$(\'#installation\').submit()" ');?></div>
</form>
