<?php
if(file_exists(BASEDIR.'/files/temp/install.me'))
	unlink(BASEDIR.'/files/temp/install.me');
?>
<h2>ClipBucket has been installed successfully</h2>
<p>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=152608291474175&amp;xfbml=1"></script><fb:like href="http://www.facebook.com/ClipBucket" send="true" width="450" show_faces="true" font=""></fb:like>

<div class="errorDiv br5px" id="dbresult" style="">
<?=msg_arr(array('err'=>'Please delete cb_install directory'))?></div>
<div align="center" style="margin-top:10px"><form name="installation" method="post" id="installation">

    <input type="hidden" name="mode" value="finish" />
    <?=button("Continue to Admin Area",' onclick="window.location=\''.BASEURL.'/admin_area\'" ');?>
     <?=button("Continue to ".config('site_title'),' onclick="window.location=\''.BASEURL.'\'" ');?>
  
</form></div>

<div style="font-size:13px; margin:5px 0px" align="center">now you have succesfully installed clipbucket, you may be insterested in following plugins to <strong>enhance your website</strong></div>


<div><div class="product br5px">
	<div class="title">
    <a href="http://clip-bucket.com/product/ClipBucket-Mass-Embedder-Pro/83">ClipBucket Mass Embedder Pro</a></div>
    <div class="desc">Let you popluate your website with thousands of Youtube videos entirely based on your 
    queries with autocategorization and auto wordpress poster. it also embed videos from dailymotion, metacafe and revver.com</div>
</div>
    
<div class="product br5px">
	<div class="title">
    <a href="http://clip-bucket.com/product/Facebook-Connect/93">Facebook Connect</a></div>
    <div class="desc">Facebook Connect allows your users to login to your website using Facebook account, They will first register and than login. Facebook connect is running on many websites, it can turn your user signup process alot quick and easier. </div>
</div>

<div class="product br5px">
	<div class="title">
    <a href="http://clip-bucket.com/product/ClipBucket-Branding-Removals/85">
    ClipBucket Rebranding</a></div>
    <div class="desc">if you want to remove "powered by ClipBucket" from ClipBucket v2 Website, by purchasing this service, you can do that easily </div>
</div>

<div class="product br5px">
	<div class="title">
    <a href="http://clip-bucket.com/product/ClipBucket-FLV-Player-Rebranding/76">Pakplayer rebranding</a></div>
    <div class="desc">Removes "Powered by ClipBucket" message and Pakplayer name from your flv player and Let you set your own logo and message on Pakplayer</div>
</div>
    
<div class="product br5px">
	<div class="title">
    <a href="http://clip-bucket.com/product/Multiserver/94">Multiserver</a></div>
    <div class="desc">ClipBucket Multi-server gives your website a new edge with more powerful video conversion option and data delivery.With Clipbucket Multi-server, you will have following options
</div>
</div>



<div class="product br5px">
	<div class="title">
    <a href="http://clip-bucket.com/product/Paid-Subscription-Module-Billing-System/95">	
Paid Subscription Module </a></div>
    <div class="desc">Paid Subscription module let you transform your website into an online video sharing market with a reliable source of income. Our module comes with very best features that let you control and distribute your content  commercially. 
</div>
</div>

<div style="clear:both"></div></div>
    
    
    <div style="font-size:13px; margin:5px 0px" align="center">
    	Please read our "<a href="http://docs.clip-bucket.com/clipbucket-docs/clipbucket-installation" style="text-decoration:none"><strong>what to do after installation</strong></a>" guide...
    </div>
</p>

