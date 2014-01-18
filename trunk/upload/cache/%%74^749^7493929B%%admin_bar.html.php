<?php /* Smarty version 2.6.18, created on 2014-01-15 12:18:10
         compiled from /var/www/clipbucket/styles/global/admin_bar.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/global/admin_bar.html', 78, false),)), $this); ?>
<style type="text/css">
<?php echo '
.CBadminBar { min-width:980px; width:980px; width:auto !important; background:#333; border-bottom:1px solid #000; padding:0px; font:bold 11px Tahoma, Geneva, sans-serif; color:#FFF; margin:0px; }
.CBadminBar .CBadminBarWrapper { margin:0px; padding:0px; position:relative; top:0px; z-index:9999999999; }
.CBadminBarWrapper { margin:0px; padding:0px; list-style:none; width:100%; }
.CBadminBarWrapper li { margin:0px; padding:0px; list-style:none; }
.CBadminBarHeading {  margin:0px; padding:0px; position:relative; float:left;}
.CBadminBarHeading a { color:#CCC; text-decoration:none; display:inline-block;  padding:6px; }
.CBadminBarHeading:hover a { background:#EEE; color:black; }

.CBadminBarSubMenuWrapper { display:none; position:absolute; top:25px; width:175px; left:0px; margin:0px; list-style:none; padding:0px; height:auto; z-index:999999999; background:#EEE; }
.CBadminBarSubMenu { display:block; margin:0px; padding:0px; list-style:none;  }

.CBadminBarHeading:hover .CBadminBarSubMenuWrapper { display:block; }
.CBadminBarHeading:hover .CBadminBarSubMenu a { color:#666; width:100%; border:1px solid #EEE;}
.CBadminBarHeading:hover .CBadminBarSubMenu a:hover { color:#FFF; border:1px solid #000; background:#333; }
.CBadminBarToggle { padding:5px; background:#121212; border:1px solid #000; position:fixed; top:10px; left:0px; font:bold 11px Tahoma, Geneva, sans-serif; color:#FFF; }
#showCBadminBar:hover { cursor:pointer }
.clearfix { }
.clearfix:after{ content: "."; display:block; height:0; font-size:0; clear:both; visibility:hidden;  }
'; ?>

</style>
<script type="text/javascript">
<?php echo '
function showCBadminBarButton(height)
{
	$("<div></div>")
	.attr({\'id\':\'showCBadminBar\',\'onClick\' : \'showCBadminBar(this,"\'+height+\'")\'})
	.css({ \'position\':\'fixed\',
		   \'top\':\'-\'+height+\'px\',
		   \'right\':\'0px\',
		   \'width\':\'auto\',
		   \'padding\' : \'6px\',
		   \'background\':\'#333333\',
		   \'border\' : \'1px solid #000\',
		   \'font\' : \'bold 11px Tahoma\',
		   \'color\':\'#FFF\'}).html("Show Menu").insertAfter(\'#CBadminBar\').animate({ \'top\':\'0px\',\'opacity\': 1 },500);
	$.cookie(\'CBadminBar\',\'hidden\',{expires:1,path:\'/\'});	   	
}

function showCBadminBar(obj,height)
{
	$(obj).animate({ \'opacity\' : 0, \'top\' : \'-\'+height+\'px\' },500,function()
	{
		$(\'#CBadminBar\').slideDown(350);
		$(obj).remove();
		var Divoffset = $(\'#CBadminBar\').offset().top;
		$(\'html,body\').animate({ scrollTop : Divoffset },500);	
		$.cookie(\'CBadminBar\',\'shown\',{expires:1,path:\'/\'});	
	});
}

$(document).ready(function(){
	$(\'#CBadminBarToggle\').bind({
		click : function(event)
		{
			var height = $("#CBadminBar").outerHeight();
			$("#CBadminBar").slideUp(350,function() { showCBadminBarButton(height); });	
		}	
	});		
});
'; ?>

</script>
<div id="CBadminBar" class="CBadminBar clearfix"<?php if ($_COOKIE['CBadminBar'] == 'hidden'): ?> style="display:none"<?php endif; ?>>
	<ul class="CBadminBarWrapper">
    <?php $this->assign('adminLinks', $this->_tpl_vars['Cbucket']->AdminMenu); ?>
    <?php $_from = $this->_tpl_vars['adminLinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['menu']):
?>
          <li class="CBadminBarHeading">
          	<a href="javascript:void(0)"><?php echo $this->_tpl_vars['name']; ?>
</a>
            <ul class="CBadminBarSubMenuWrapper" id="<?php echo $this->_tpl_vars['name']; ?>
">
            <?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sub_menu'] => $this->_tpl_vars['sub_link']):
?>        	
                	<li class="CBadminBarSubMenu"><a target="_blank" href="<?php echo $this->_tpl_vars['baseurl']; ?>
/admin_area/<?php echo $this->_tpl_vars['sub_link']; ?>
"><?php echo $this->_tpl_vars['sub_menu']; ?>
</a></li>                           	
            <?php endforeach; endif; unset($_from); ?>
            </ul>
          </li>
    <?php endforeach; endif; unset($_from); ?>
 	<?php if ($this->_tpl_vars['userquery']->is_admin_logged_as_user()): ?>   
    	<li class="CBadminBarHeading" style="float:right;"><a href="<?php echo $this->_tpl_vars['baseurl']; ?>
/admin_area/login_as_user.php?revert=yes"><?php echo smarty_lang(array('code' => 'revert_back_to_admin'), $this);?>
</a></li>
    <?php endif; ?>
    <li id="CBadminBarToggle" class="CBadminBarHeading" style="float:right;"><a href="#">Hide Menu</a></li>
    </ul>      
</div>
<?php if ($_COOKIE['CBadminBar'] == 'hidden'): ?>
	<div id="showCBadminBar" style="position:fixed; top:0px; right:0px; width:auto; padding:6px; background:#333333; border:1px solid #000; color:#fff; font:bold 11px Tahoma;" onclick="showCBadminBar(this,'26');">Show Menu</div>
<?php endif; ?>