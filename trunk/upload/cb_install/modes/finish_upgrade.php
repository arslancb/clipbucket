<?php
$cbfile = BASEDIR.'/includes/clipbucket.php';
if(file_exists($cbfile))
	unlink($cbfile);
copy(BASEDIR.'/cb_install/clipbucket.php',$cbfile);


$db->update(tbl("config"),array("value"),array(now())," name='date_updated' ");

?>
<?php
if(file_exists(BASEDIR.'/files/temp/install.me'))
	unlink(BASEDIR.'/files/temp/install.me');
?>



</div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>



<div class="nav_des clearfix">
    <div class="cb_container">
    <h4 style="color:#fff">Your Clipbucket has been successfully upgraded to <?=VERSION?></h4>
    <p style="color:#fff; font-size:13px;">You have succesfully upgraded clipbucket, you may be insterested in following plugins to <strong>enhance your website</strong></p>



</div><!--cb_container-->
</div><!--nav_des-->



<div id="sub_container" class="br5px">


<!--<h2>ClipBucket has been installed successfully</h2>
<p>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=152608291474175&amp;xfbml=1"></script><fb:like href="http://www.facebook.com/ClipBucket" send="true" width="450" show_faces="true" font=""></fb:like>
-->
<div class="errorDiv br5px" id="dbresult" style="">
<?=msg_arr(array('err'=>'<span style="color:#A32727;">Please delete cb_install directory</span>'))?></div>


<div class="fb_finish"><div class="fb-like-box" data-href="https://www.facebook.com/ClipBucket" data-width="348" data-height="500" 
    data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="true" data-show-border="true"></div></div>

<div class="finish">
    <div class="product test">
    <div class="title">
    <a href="http://clip-bucket.com/product/ClipBucket-Mass-Embedder-Pro/83">ClipBucket Mass Embedder Pro</a></div>
    <div class="desc">Let you popluate your website with thousands of Youtube videos entirely based on your 
    queries with autocategorization and auto wordpress poster. it also embed videos from dailymotion, metacafe and revver.com</div>
    <div class="line"></div>
</div>
   
<div class="product br5px">
    <div class="title">
    <a href="http://clip-bucket.com/product/Facebook-Connect/93">Facebook Connect</a></div>
    <div class="desc">Facebook Connect allows your users to login to your website using Facebook account, They will first register and than login. Facebook connect is running on many websites, it can turn your user signup process alot quick and easier. </div>
 <div class="line"></div>
</div>

<div class="product br5px">
    <div class="title">
    <a href="http://clip-bucket.com/product/ClipBucket-Branding-Removals/85">
    ClipBucket Rebranding</a></div>
    <div class="desc">if you want to remove "powered by ClipBucket" from ClipBucket v2 Website, by purchasing this service, you can do that easily </div>
     <div class="line"></div>
</div>

<div class="product br5px">
    <div class="title">
    <a href="http://clip-bucket.com/product/ClipBucket-FLV-Player-Rebranding/76">Pakplayer rebranding</a></div>
    <div class="desc">Removes "Powered by ClipBucket" message and Pakplayer name from your flv player and Let you set your own logo and message on Pakplayer</div>
 <div class="line"></div>
</div>
    
<div class="product br5px">
    <div class="title">
    <a href="http://clip-bucket.com/product/Multiserver/94">Multiserver</a></div>
    <div class="desc">ClipBucket Multi-server gives your website a new edge with more powerful video conversion option and data delivery.With Clipbucket Multi-server, you will have following options
</div>
 <div class="line"></div>
</div>



<div class="product br5px" style="">
    <div class="title">
    <a href="http://clip-bucket.com/product/Paid-Subscription-Module-Billing-System/95">    
Paid Subscription Module </a></div>
    <div class="desc">Paid Subscription module let you transform your website into an online video sharing market with a reliable source of income. Our module comes with very best features that let you control and distribute your content  commercially. 
</div>
 <div class="line"></div>
</div>

<div style="clear:both"></div>

</div>
  
    
   <!-- <div style="font-size:13px; margin:5px 0px" align="center">
        Please read our "<a href="http://docs.clip-bucket.com/clipbucket-docs/clipbucket-installation" style="text-decoration:none"><strong>what to do after installation</strong></a>" guide...
    </div>
</p>-->

<div align="center" style="margin-top:40px"><form name="installation" method="post" id="installation">

    <input type="hidden" name="mode" value="finish" />
    <?=button_danger("Continue to Admin Area",' onclick="window.location=\''.BASEURL.'/admin_area\'" ');?>
     <?=button("Continue to ".config('site_title'),' onclick="window.location=\''.BASEURL.'\'" ');?>

  
</form></div>

