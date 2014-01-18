<?php /* Smarty version Smarty-3.1.15, created on 2014-01-17 09:24:03
         compiled from "/var/www/clipbucket/player/pak_player/player.html" */ ?>
<?php /*%%SmartyHeaderCode:4342628252d8f6b36877f9-45092570%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd622b20ee9da505a1c4ec8560ac59ddfcbf2b016' => 
    array (
      0 => '/var/www/clipbucket/player/pak_player/player.html',
      1 => 1389359136,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4342628252d8f6b36877f9-45092570',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ytcode' => 0,
    'normal_vid_file' => 0,
    'player_data' => 0,
    'pak_player_url' => 0,
    'player_logo' => 0,
    'hq_vid_file' => 0,
    'vdata' => 0,
    'Cbucket' => 0,
    'pakconfigs' => 0,
    'config' => 0,
    'value' => 0,
    'youtube' => 0,
    'logo_position' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d8f6b36d93b1_93126355',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d8f6b36d93b1_93126355')) {function content_52d8f6b36d93b1_93126355($_smarty_tpl) {?><a href="<?php if (!$_smarty_tpl->tpl_vars['ytcode']->value) {?><?php echo $_smarty_tpl->tpl_vars['normal_vid_file']->value;?>
<?php } else { ?>api:<?php echo $_smarty_tpl->tpl_vars['ytcode']->value;?>
<?php }?>" style="display:block;width:<?php echo $_smarty_tpl->tpl_vars['player_data']->value['width'];?>
; height:<?php echo $_smarty_tpl->tpl_vars['player_data']->value['height'];?>
" id="the_Video_Player"></a>
<script language="JavaScript">
var pakplayer_path = '<?php echo $_smarty_tpl->tpl_vars['pak_player_url']->value;?>
';
var player_logo = '<?php echo $_smarty_tpl->tpl_vars['player_logo']->value;?>
';
var hq_video_file = '<?php echo $_smarty_tpl->tpl_vars['hq_vid_file']->value;?>
';
var normal_video_file = '<?php echo $_smarty_tpl->tpl_vars['normal_vid_file']->value;?>
';
var ytcode = '<?php echo $_smarty_tpl->tpl_vars['ytcode']->value;?>
';
var pre_item = "";
var next_item = "";
var splash_img = '<?php echo getSmartyThumb(array('vdetails'=>$_smarty_tpl->tpl_vars['vdata']->value,'size'=>"big"),$_smarty_tpl);?>
';
var embed_type = '<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['embed_type'];?>
';

//Now lets write an HQ video function that swaps the original video with hq
var video_quality = 'normal';
function toggleQuality(obj)
{
	
		if(video_quality=='normal')
		{
			video_quality = 'hq';
			flowplayer(obj).stop();
			flowplayer(obj).setClip({url:hq_video_file});
			flowplayer(obj).play();
		}
		else
		{
			video_quality = 'normal';
			flowplayer(obj).stop();
			flowplayer(obj).setClip({url:normal_video_file});
			flowplayer(obj).play();
		}
}

function pakplayer_hq()
{
	toggleQuality('the_Video_Player');
}
has_hq_function = true;
hq_function = pakplayer_hq;




flowplayer("the_Video_Player", {"src":"<?php echo $_smarty_tpl->tpl_vars['pak_player_url']->value;?>
/pakplayer<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->configs['pak_license']) {?>.unlimited<?php }?>.swf"}, 
	{
	<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->configs['pak_license']) {?>
	key : '<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['pak_license'];?>
',	
	<?php }?>
	
	plugins: {
		controls: {
			
			
			<?php if ($_smarty_tpl->tpl_vars['pakconfigs']->value) {?>
				<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['config'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pakconfigs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['config']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
					<?php echo $_smarty_tpl->tpl_vars['config']->value;?>
 : "<?php echo $_smarty_tpl->tpl_vars['value']->value['default'];?>
",
				<?php } ?>
			<?php } else { ?>
			background: "url("+pakplayer_path+"/bg.png) repeat",
			url: "pakplayer.controls.swf"
			<?php }?>
					
		<?php if ($_smarty_tpl->tpl_vars['youtube']->value) {?>
		},
		youtube :
		
		{
			url:pakplayer_path+'/pakplayer.youtube.swf',
			enableGdata: true
		}
		
		<?php } else { ?>
		
		} // Take this you shitty IE7
		<?php }?>
		
		<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->configs['pseudostreaming']=='yes'&&!$_smarty_tpl->tpl_vars['youtube']->value) {?>,lighttpd : { url: pakplayer_path+"/pakplayer.pseudo.swf"}<?php }?>

	},
	canvas: {
	backgroundColor: '#000000',  // For some reason we have to define this :|
	backgroundGradient : 'none',
	background : '#000000 url('+splash_img+') no-repeat 50pct 50pct'
  },

	clip:
	{
		linkUrl : "#",
		
		<?php if ($_smarty_tpl->tpl_vars['youtube']->value) {?>
		provider : 'youtube',
		urlResolvers: 'youtube',
		
		<?php } else { ?>
		<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->configs['pseudostreaming']=='yes') {?>provider: 'lighttpd',<?php }?>
		scaling : 'fit',
		<?php }?>
		
		<?php if ($_smarty_tpl->tpl_vars['player_data']->value['autoplay']) {?>
		autoPlay :true,
		<?php } else { ?>
		autoPlay : false,
		<?php }?>
		
		
		onStart : function()
		{
			// Removing background image
			 this.getPlugin("canvas").css({ "background" : "#000000"})
		},
		onLastSecond: function()
		{
			if(next_item)
			{
				if($.cookie('auto_play_playlist'))
				window.location = next_item;
			}
		}
	},
	
	
	playlists :
	[
		
		{
		<?php if ($_smarty_tpl->tpl_vars['youtube']->value) {?>
		url : 'api:<?php echo $_smarty_tpl->tpl_vars['ytcode']->value;?>
'		
		<?php } else { ?>
		url : normal_video_file
		<?php }?>
		
		}
	],
	logo:
	{
		url: player_logo,
		fullscreenOnly: false,
		
		<?php echo $_smarty_tpl->tpl_vars['logo_position']->value;?>

		
		
	}
});


function htmlDecode(value){ 
  return $('<div/>').html(value).text(); 
}
function updateEmbedCode(width,height,autoplay)
{ 
	if(autoplay=='yes')
		autoPlayVid = 'yes';
	else
		autoPlayVid = 'no';
	
	embedPlayerWidth = width;
	embedPlayerHeight = height;
	autoPlayEmbed = autoplay;
	var embedCode =  $('#embed_code').val();
	
	$('#embed_code').val( $('#embed_code').val().replace(/height=\"([0-9]+)\"/g,'height="'+height+'"') );
	$('#embed_code').val( $('#embed_code').val().replace(/width=\"([0-9]+)\"/g,'width="'+width+'"') );
	$('#embed_code').val( $('#embed_code').val().replace(/height=([0-9]+)/g,'height='+height+'') );
	$('#embed_code').val( $('#embed_code').val().replace(/width=([0-9]+)/g,'width='+width+'') );
	$('#embed_code').val( $('#embed_code').val().replace(/autoplay\%3D(yes|no)/g,'autoplay%3D'+autoPlayVid) );
	$('#embed_code').val( $('#embed_code').val().replace(/autoplay\=(yes|no)/g,'autoplay='+autoPlayVid) );
}

</script>
<?php }} ?>
