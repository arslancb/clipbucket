<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 14:46:42
         compiled from "/var/www/clipbucket/admin_area/styles/cbv2/layout/global_header.html" */ ?>
<?php /*%%SmartyHeaderCode:42088891152d6590264dce0-44902541%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '101fce9026c11ba8297d7d1163edc917ac4589bd' => 
    array (
      0 => '/var/www/clipbucket/admin_area/styles/cbv2/layout/global_header.html',
      1 => 1389359112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '42088891152d6590264dce0-44902541',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'theme' => 0,
    'baseurl' => 0,
    'imageurl' => 0,
    'js' => 0,
    'googleApi' => 0,
    'Cbucket' => 0,
    'curActive' => 0,
    'type' => 0,
    'file' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d6590269e7c8_70475147',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d6590269e7c8_70475147')) {function content_52d6590269e7c8_70475147($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo cbtitle(array('sub_sep'=>'&#8250;'),$_smarty_tpl);?>
</title>
<link href="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->tpl_vars['theme']->value;?>
/slidemenu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8">
var baseurl = "<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
";
var imageurl = "<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
";
</script>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/functions.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/admin_functions.js"></script>
<?php if ($_smarty_tpl->tpl_vars['googleApi']->value) {?>
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAr5pj809LgbJgBTxDJGy0IxQH8siQo9V3STvJ8WIDHu37hIWsoxRX_d1ABxknSddUPvo4LFb7wq8gwA"></script>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.8/themes/redmond/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript">
 google.load("jquery", "1");
 google.load("jqueryui", "1");
</script>
<?php } else { ?>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/ui/jquery-ui-1.7.2.custom.min.js"></script>
<?php }?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/hover_intent.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/cb.tabs.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/cookie.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/timer.js"></script>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/css/jquery.tooltip.css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/css/screen.css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/css/tipsy.css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" />
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/jquery.bgiframe.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/jquery.dimensions.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/jquery.tooltip.pack.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/chili-1.7.pack.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/compressed/jeditable.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery_plugs/jquery.tipsy.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/swfobject.js" type="text/javascript"></script>
<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->AdminJSArray) {?>
<!-- Including JS Files-->
<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['Cbucket']->value->AdminJSArray; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['file']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
    <?php if ($_smarty_tpl->tpl_vars['curActive']->value==$_smarty_tpl->tpl_vars['type']->value||$_smarty_tpl->tpl_vars['type']->value=='global') {?>
        <?php echo include_js(array('type'=>$_smarty_tpl->tpl_vars['type']->value,'file'=>$_smarty_tpl->tpl_vars['file']->value),$_smarty_tpl);?>

    <?php }?>
<?php } ?>
<!-- Including JS Files-->
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->admin_header_files) {?>
    <!-- Including Headers -->
    <?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['Cbucket']->value->admin_header_files; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['file']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
        <?php echo include_header(array('type'=>$_smarty_tpl->tpl_vars['type']->value,'file'=>$_smarty_tpl->tpl_vars['file']->value),$_smarty_tpl);?>

    <?php } ?>
    <!-- Ending Headers -->
<?php }?>



<!-- Including Nice Edit -->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/nicedit/nicEdit.js"></script>


	<script type="text/javascript" charset="utf-8">
	
		function makeTall(divid){  $('#'+divid).animate({"height":209},200);}
		function makeShort(divid){ $('#'+divid).animate({"height":0},200);}
		var on = false;
		function switch_func(divid)
		{
			if(on)
			{
				makeShort(divid);
				on = false;
			}else{
				makeTall(divid);
				on = true;
			}
				
		}
		
		$(document).ready(function() {
			 $('.edit_lang').editable(baseurl+'/actions/update_phrase.php', { 
			// cancel    : 'Cancel',
			// submit    : 'OK',
			 indicator : '<img src="'+baseurl+'/images/icons/progIndicator.gif">',
			 tooltip   : 'Click to edit...',
     		});
		});
		
		$(document).ready(function() {
			 $('.edit_comment').editable(baseurl+'/actions/edit_comment.php', { 
			// cancel    : 'Cancel',
			// submit    : 'OK',
			 indicator : '<img src="'+baseurl+'/images/icons/progIndicator.gif">',
			 tooltip   : 'Click to edit...',
     		});
			
		
		
		
		$('.widgets-wrap').sortable({
			connectWith: '.widgets-wrap',
			handle: 'h2',
			cursor: 'move',
			placeholder: 'placeholder',
			forcePlaceholderSize: true,
			opacity: 0.4,
			stop: function(event, ui){
				$(ui.item).find('h2').click();
			}
		})
		
		$('#HeadMenu').sortable({
			cursor : 'move',
			opacity : '0.4',
			forcePlaceholderSize: true
		})

		
		$('.dragbox').each(function(){
			$(this).hover(function(){
				$(this).find('h2').addClass('collapse');
			}, function(){
				$(this).find('h2').removeClass('collapse');
			})
			.find('h2').hover(function(){
				$(this).find('.configure').css('visibility', 'visible');
			}, function(){
				$(this).find('.configure').css('visibility', 'hidden');
			})
			.click(function(){
				$(this).siblings('.dragbox-content').toggle();
			})
			.end()
			.find('.configure').css('visibility', 'hidden');
		});
		
		$('.tipsy_tip').tipsy({gravity: 'w'});
		
	});
	
	</script>



<script type="text/javascript">
$(document).ready(function(){
	
	$('#ratios input[name=photo_ratio]').click(function()
	{
		var ratio = this.value;
			ratio_part = ratio.split(':');
			width = ratio_part[0];
			height = ratio_part[1];
			
			ThumbHeight = $('#image_width').val() / width * height;
			ThumbHeight = Math.ceil(ThumbHeight);
			
			MedHeight = $('#med_width').val() / width * height;
			MedHeight = Math.ceil(MedHeight);
			
			$('#image_height').val(ThumbHeight);
			$('#med_height').val(MedHeight);	
	});
		    
	$('#image_width,#med_width').bind(
	{
		click: function()
		{
			$(this).select();
		},
		
		focusout: function()
		{
			var ratio = $('#ratios input:checked').val();
				id = this.id;
				parts = id.split('_');
				word = parts[0];
				
	
			ratio_part = ratio.split(':');
			width = ratio_part[0];
			height = ratio_part[1];
			
			newHeight = this.value / width * height;
			newHeight = Math.ceil(newHeight);
			$('input[id="'+word+'_height"]').val(newHeight);	
		},
		
		keypress: function(event)
		{
			if(event.which == '10')
			{
				var ratio = $('#ratios input:checked').val();
					id = this.id;
					parts = id.split('_');
					word = parts[0];
					
				ratio_part = ratio.split(':');
				width = ratio_part[0];
				height = ratio_part[1];
				
				newHeight = this.value / width * height;
				newHeight = Math.ceil(newHeight);
				$('input[id="'+word+'_height"]').val(newHeight);
				this.blur();
			}
			
		}
	});
});
</script>

</head><?php }} ?>
