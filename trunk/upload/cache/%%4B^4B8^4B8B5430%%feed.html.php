<?php /* Smarty version 2.6.18, created on 2014-01-17 12:33:36
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/feed.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'avatar', '/var/www/clipbucket/styles/cbv2new/layout/blocks/feed.html', 68, false),)), $this); ?>

<?php echo '
<script>
function deleteFeed(uid,file)
{
	$(\'#feedload-\'+file).html(loading_img);
	$.post(page,
		{mode:"delete_feed",uid:uid,file:file},
		function(data)
		{
			$(\'#feedload-\'+file).html(\'\');
			if(data.msg)
				$(\'#feed-\'+file).html(\'<div class="cb_error">\'+data.msg+"</div>");
			else
				$(\'#feed-\'+file).prepend(\'<div class="cb_error" style="color:#ed0000">\'+data.err+"</div>");
		},
	\'json\');
}
</script>
<style>

.userFeed{margin-bottom:10px; border-bottom:1px solid #E9E9E9; margin-bottom:5px; padding-bottom:5px}
.userFeed .feedUser
{ 
	display:inline-block;
	border:1px solid #CCC;
	float:left; margin-right:5px
}

.userFeed .feed{float:left; width:630px}

.userFeed .feedTitle {margin-bottom:5px; font-size:12px}
.userFeed .feedTitle a
{
	font-size:12px; font-weight:bold;
	color:#06c; text-decoration:none;
}

.userFeed .feedContent a
{
	font-size:11px; font-weight:bold;
	color:#06c; text-decoration:none;
}

.userFeed .feedContent .feedThumb{border:1px solid #999; padding:2px; float:left; margin-right:5px}

.userFeed .feedState {margin-top:5px;}
.userFeed .feedState img{display:inline-block; float:left; margin-right:5px}
.userFeed .feedState span{display:inline-block; color:#999; text-transform:lowercase}
.userFeed .feedState span a{text-decoration:none}
.userFeed .feedState .dot{ border:1px solid #999; width:0px;
text-indent:-500000; overflow:hidden; display:inline-block; height:0px}
.userFeed .feedText .objectContent{color:#999}
</style>
'; ?>



<div class="userFeed" id="feed-<?php echo $this->_tpl_vars['feed']['file']; ?>
">
	<div align="right">
	<span id="feedload-<?php echo $this->_tpl_vars['feed']['file']; ?>
"></span>
    <?php if ($this->_tpl_vars['u']['userid'] == userid ( ) || has_access ( 'admin_access' , true )): ?>
    <a href="javascript:void(0)" onclick="deleteFeed('<?php echo $this->_tpl_vars['feed']['user']['userid']; ?>
','<?php echo $this->_tpl_vars['feed']['file']; ?>
')">
    <img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/icons/delete.png" border="0" /></a>
    <?php endif; ?>
    </div>
    <div class="feedUser">
        <a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['feed']['user']); ?>
" title="<?php echo $this->_tpl_vars['feed']['user']['username']; ?>
">
        <img src="<?php echo avatar(array('details' => $this->_tpl_vars['feed']['user'],'size' => 'small'), $this);?>
" alt="<?php echo $this->_tpl_vars['feed']['user']['username']; ?>
" 
        style="padding:1px; vertical-align:middle;" /></a>
    </div>
    <div class="feed">
    	<div class="feedTitle">
        	<?php if (! $this->_tpl_vars['feed']['action_title']): ?>
        		<a href="<?php echo $this->_tpl_vars['userquery']->profile_link($this->_tpl_vars['feed']['user']); ?>
"><?php echo $this->_tpl_vars['feed']['user']['username']; ?>
</a>
            <?php else: ?>
            	<?php echo $this->_tpl_vars['feed']['action_title']; ?>

            <?php endif; ?>
        </div>
        
        <div class="feedContent">
        	
            <?php if ($this->_tpl_vars['feed']['thumb']): ?>
            	<?php if ($this->_tpl_vars['feed']['link']): ?>
                	<a href="<?php echo $this->_tpl_vars['feed']['link']; ?>
">
                <?php endif; ?>
                	<img src="<?php echo $this->_tpl_vars['feed']['thumb']; ?>
" class="feedThumb">                
                <?php if ($this->_tpl_vars['feed']['link']): ?>
                	</a>
                <?php endif; ?>
            <?php endif; ?>
            
            <div class="feedText">
                <?php if ($this->_tpl_vars['feed']['link']): ?>
                    <a href="<?php echo $this->_tpl_vars['feed']['link']; ?>
"><?php echo $this->_tpl_vars['feed']['title']; ?>
</a>
                <?php elseif ($this->_tpl_vars['feed']['title']): ?>
                   <?php echo $this->_tpl_vars['feed']['title']; ?>

                <?php endif; ?>
                
                <?php if ($this->_tpl_vars['feed']['object_content']): ?>
                <div class="objectContent">
                	<?php echo $this->_tpl_vars['feed']['object_content']; ?>

                </div>
                <?php endif; ?>
            </div>
            
            
            <div class="clear"></div>
        </div>
        
        
        <div class="feedState">
        <?php if ($this->_tpl_vars['feed']['icon']): ?>
    		<img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/icons/<?php echo $this->_tpl_vars['feed']['icon']; ?>
"> 
        <?php endif; ?>
        <span><?php echo $this->_tpl_vars['feed']['datetime']; ?>
</span>
        
        <?php if ($this->_tpl_vars['feed']['links']): ?>
        	<?php $_from = $this->_tpl_vars['feed']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
       	    <span class="dot">.</span>
        	<span><?php if ($this->_tpl_vars['link']['link']): ?><a href="<?php echo $this->_tpl_vars['link']['link']; ?>
"><?php endif; ?><?php echo $this->_tpl_vars['link']['text']; ?>
<?php if ($this->_tpl_vars['link']['link']): ?></a><?php endif; ?></span>
            <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>
        
        <div class="clear"></div></div>
                
    </div>   
    <div class="clear"></div>
    
    
</div>
