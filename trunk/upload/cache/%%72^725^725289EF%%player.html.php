<?php /* Smarty version 2.6.18, created on 2014-01-17 13:56:59
         compiled from /var/www/clipbucket/player/pak_player/player.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'getThumb', '/var/www/clipbucket/player/pak_player/player.html', 10, false),)), $this); ?>
<a href="<?php if (! $this->_tpl_vars['ytcode']): ?><?php echo $this->_tpl_vars['normal_vid_file']; ?>
<?php else: ?>api:<?php echo $this->_tpl_vars['ytcode']; ?>
<?php endif; ?>" style="display:block;width:<?php echo $this->_tpl_vars['player_data']['width']; ?>
; height:<?php echo $this->_tpl_vars['player_data']['height']; ?>
" id="the_Video_Player"></a>
<script language="JavaScript">
var pakplayer_path = '<?php echo $this->_tpl_vars['pak_player_url']; ?>
';
var player_logo = '<?php echo $this->_tpl_vars['player_logo']; ?>
';
var hq_video_file = '<?php echo $this->_tpl_vars['hq_vid_file']; ?>
';
var normal_video_file = '<?php echo $this->_tpl_vars['normal_vid_file']; ?>
';
var ytcode = '<?php echo $this->_tpl_vars['ytcode']; ?>
';
var pre_item = "";
var next_item = "";
var splash_img = '<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['vdata'],'size' => 'big'), $this);?>
';
var embed_type = '<?php echo $this->_tpl_vars['Cbucket']->configs['embed_type']; ?>
';
<?php echo '
//Now lets write an HQ video function that swaps the original video with hq
var video_quality = \'normal\';
function toggleQuality(obj)
{
	
		if(video_quality==\'normal\')
		{
			video_quality = \'hq\';
			flowplayer(obj).stop();
			flowplayer(obj).setClip({url:hq_video_file});
			flowplayer(obj).play();
		}
		else
		{
			video_quality = \'normal\';
			flowplayer(obj).stop();
			flowplayer(obj).setClip({url:normal_video_file});
			flowplayer(obj).play();
		}
}

function pakplayer_hq()
{
	toggleQuality(\'the_Video_Player\');
}
has_hq_function = true;
hq_function = pakplayer_hq;

'; ?>



flowplayer("the_Video_Player", {"src":"<?php echo $this->_tpl_vars['pak_player_url']; ?>
/pakplayer<?php if ($this->_tpl_vars['Cbucket']->configs['pak_license']): ?>.unlimited<?php endif; ?>.swf"}, 
	<?php echo '{'; ?>

	<?php if ($this->_tpl_vars['Cbucket']->configs['pak_license']): ?>
	key : '<?php echo $this->_tpl_vars['Cbucket']->configs['pak_license']; ?>
',	
	<?php endif; ?>
	<?php echo '
	plugins: {
		controls: {
			
			'; ?>

			<?php if ($this->_tpl_vars['pakconfigs']): ?>
				<?php $_from = $this->_tpl_vars['pakconfigs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['config'] => $this->_tpl_vars['value']):
?>
					<?php echo $this->_tpl_vars['config']; ?>
 : "<?php echo $this->_tpl_vars['value']['default']; ?>
",
				<?php endforeach; endif; unset($_from); ?>
			<?php else: ?>
			background: "url("+pakplayer_path+"/bg.png) repeat",
			url: "pakplayer.controls.swf"
			<?php endif; ?>
					
		<?php if ($this->_tpl_vars['youtube']): ?>
		},
		youtube :
		<?php echo '
		{
			url:pakplayer_path+\'/pakplayer.youtube.swf\',
			enableGdata: true
		}
		'; ?>

		<?php else: ?>
		
		} // Take this you shitty IE7
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['Cbucket']->configs['pseudostreaming'] == 'yes' && ! $this->_tpl_vars['youtube']): ?>,lighttpd : { url: pakplayer_path+"/pakplayer.pseudo.swf"}<?php endif; ?>
<?php echo '
	},
	canvas: {
	backgroundColor: \'#000000\',  // For some reason we have to define this :|
	backgroundGradient : \'none\',
	background : \'#000000 url(\'+splash_img+\') no-repeat 50pct 50pct\'
  },

	clip:
	{
		linkUrl : "#",
		'; ?>

		<?php if ($this->_tpl_vars['youtube']): ?>
		provider : 'youtube',
		urlResolvers: 'youtube',
		
		<?php else: ?>
		<?php if ($this->_tpl_vars['Cbucket']->configs['pseudostreaming'] == 'yes'): ?>provider: 'lighttpd',<?php endif; ?>
		scaling : 'fit',
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['player_data']['autoplay']): ?>
		autoPlay :true,
		<?php else: ?>
		autoPlay : false,
		<?php endif; ?>
		
		<?php echo '
		onStart : function()
		{
			// Removing background image
			 this.getPlugin("canvas").css({ "background" : "#000000"})
		},
		onLastSecond: function()
		{
			if(next_item)
			{
				if($.cookie(\'auto_play_playlist\'))
				window.location = next_item;
			}
		}
	},
	
	
	playlists :
	[
		'; ?>

		{
		<?php if ($this->_tpl_vars['youtube']): ?>
		url : 'api:<?php echo $this->_tpl_vars['ytcode']; ?>
'		
		<?php else: ?>
		url : normal_video_file
		<?php endif; ?>
		<?php echo '
		}
	],
	logo:
	{
		url: player_logo,
		fullscreenOnly: false,
		'; ?>

		<?php echo $this->_tpl_vars['logo_position']; ?>

		<?php echo '
		
	}
});


function htmlDecode(value){ 
  return $(\'<div/>\').html(value).text(); 
}
function updateEmbedCode(width,height,autoplay)
{ 
	if(autoplay==\'yes\')
		autoPlayVid = \'yes\';
	else
		autoPlayVid = \'no\';
	
	embedPlayerWidth = width;
	embedPlayerHeight = height;
	autoPlayEmbed = autoplay;
	var embedCode =  $(\'#embed_code\').val();
	
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/height=\\"([0-9]+)\\"/g,\'height="\'+height+\'"\') );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/width=\\"([0-9]+)\\"/g,\'width="\'+width+\'"\') );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/height=([0-9]+)/g,\'height=\'+height+\'\') );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/width=([0-9]+)/g,\'width=\'+width+\'\') );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/autoplay\\%3D(yes|no)/g,\'autoplay%3D\'+autoPlayVid) );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/autoplay\\=(yes|no)/g,\'autoplay=\'+autoPlayVid) );
}

</script>
'; ?>