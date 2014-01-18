<?php /* Smarty version 2.6.18, created on 2014-01-10 19:00:19
         compiled from /var/www/clipbucket/styles/global/head.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'include_js', '/var/www/clipbucket/styles/global/head.html', 55, false),array('function', 'include_header', '/var/www/clipbucket/styles/global/head.html', 64, false),array('function', 'lang', '/var/www/clipbucket/styles/global/head.html', 81, false),array('function', 'getThumb', '/var/www/clipbucket/styles/global/head.html', 483, false),array('function', 'fb_embed_video', '/var/www/clipbucket/styles/global/head.html', 487, false),array('function', 'videoLink', '/var/www/clipbucket/styles/global/head.html', 492, false),array('function', 'get_photo', '/var/www/clipbucket/styles/global/head.html', 500, false),array('function', 'ANCHOR', '/var/www/clipbucket/styles/global/head.html', 519, false),array('modifier', 'replace', '/var/www/clipbucket/styles/global/head.html', 85, false),array('modifier', 'description', '/var/www/clipbucket/styles/global/head.html', 482, false),array('modifier', 'strip_tags', '/var/www/clipbucket/styles/global/head.html', 482, false),)), $this); ?>
<script>
var baseurl = '<?php echo $this->_tpl_vars['baseurl']; ?>
';
var imageurl = '<?php echo $this->_tpl_vars['imageurl']; ?>
';
</script>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['baseurl']; ?>
/styles/global/jquery_ui.css" type="text/css" media="all" />
<?php if ($this->_tpl_vars['Cbucket']->configs['use_google_api']): ?>
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAr5pj809LgbJgBTxDJGy0IxQH8siQo9V3STvJ8WIDHu37hIWsoxRX_d1ABxknSddUPvo4LFb7wq8gwA"></script>

<script type="text/javascript">
 google.load("jquery", "1");
 google.load("jqueryui", "1");
</script>
<?php else: ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js']; ?>
/jquery.migrate.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js']; ?>
/ui/jquery-ui.js"></script>
<?php endif; ?> 
<script type="text/javascript">
var embedPlayerWidth = '<?php echo $this->_tpl_vars['Cbucket']->configs['embed_player_width']; ?>
';
var embedPlayerHeight = '<?php echo $this->_tpl_vars['Cbucket']->configs['embed_player_height']; ?>
';
var autoPlayEmbed = '<?php echo $this->_tpl_vars['Cbucket']->configs['autoplay_embed']; ?>
';


<?php echo '
function updateEmbed(width,height,autoplay)
{
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/width=\\"([0-9]+)\\"/g,\'width="\'+width+\'"\') );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/width\\:([0-9]+)px/g,\'width:\'+width+\'px\') );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/height=\\"([0-9]+)\\"/g,\'height="\'+height+\'"\') );
	$(\'#embed_code\').val( $(\'#embed_code\').val().replace(/height\\:([0-9]+)px/g,\'height:\'+height+\'px\') );
}

function switchEmbedCode(type)
{
	if(embed_type==type)
		return false;
	else
	{
		embed_type = type;
		
		var alt_embed =$(\'#alternate_embed_code\').val();
		$(\'#alternate_embed_code\').val($(\'#embed_code\').val());
		$(\'#embed_code\').val(alt_embed);
		
	}
}

'; ?>


</script>


<!-- Including JS Files-->
<?php $_from = $this->_tpl_vars['Cbucket']->JSArray; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
 <?php $_from = $this->_tpl_vars['type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file'] => $this->_tpl_vars['scope']):
?>
  <?php echo include_js(array('type' => $this->_tpl_vars['scope'],'file' => $this->_tpl_vars['file']), $this);?>

 <?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>
<!-- Including JS Files-->
<script type="text/javascript">
	callURLParser();
</script>
<!-- Including Plugin Headers -->
<?php $_from = $this->_tpl_vars['Cbucket']->header_files; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file'] => $this->_tpl_vars['type']):
?>
	<?php echo include_header(array('file' => $this->_tpl_vars['file'],'type' => $this->_tpl_vars['type']), $this);?>

<?php endforeach; endif; unset($_from); ?>
<!-- Ending Plugin Headers -->



<?php if (@THIS_PAGE == 'upload'): ?>

<link href="<?php echo $this->_tpl_vars['js']; ?>
/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js']; ?>
/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js']; ?>
/uploadify/jquery.uploadify.v2.1.4.js"></script>

<script type="text/javascript">
var jsURL = '<?php echo $this->_tpl_vars['js']; ?>
';
var uploadError = false;
var file_name = '<?php echo $this->_tpl_vars['file_name']; ?>
';
var lang = new Array();
lang['saveData'] = '<?php echo smarty_lang(array('code' => 'save_data'), $this);?>
';
lang['savingData'] = '<?php echo smarty_lang(array('code' => 'saving'), $this);?>
';
lang['remoteUploadFile'] = '<?php echo smarty_lang(array('code' => 'remote_upload_file'), $this);?>
';
lang['upload_video_button'] = '<?php echo smarty_lang(array('code' => 'upload_video_button'), $this);?>
';
var fileExt = '<?php echo ((is_array($_tmp=$this->_tpl_vars['Cbucket']->list_extensions())) ? $this->_run_mod_handler('replace', true, $_tmp, ",", ";") : smarty_modifier_replace($_tmp, ",", ";")); ?>
';

var uploadSwfPath = '<?php echo $this->_tpl_vars['uploaderDetails']['uploadSwfPath']; ?>
';
var uploadScriptPath = '<?php echo $this->_tpl_vars['uploaderDetails']['uploadScriptPath']; ?>
';


<?php echo '

  function show_error(msg,ID,fade)
  { $(\'#file_uploads\'+ID+\' .percentage\')
  	.after(\'<div class=\\"uploadErrorDiv\\"><div class=\\"uploadErrorMsg\\">\'+msg+\'<\\/div><\\/div>\'); 
	if(fade){$(\'.uploadSuccessDiv\').delay(3000).fadeOut(\'slow\');}
  }
  
  function show_message(msg,ID,fade)
  { $(\'#uploadForm\'+ID)
  	.prepend(\'<div class=\\"uploadSuccessDiv\\"><div class=\\"uploadSuccessMsg\\">\'+msg+\'<\\/div><\\/div>\');
  if(fade){$(\'.uploadSuccessDiv\').delay(3000).fadeOut(\'slow\');}
  }


function doUpdateVideo(formId,ID)
{
	var formObjectData = $(formId).serialize();
	//alert(serializedData);
	
	$(\'#updateVideoDataLoading\').html(\'<img src="\'+imageurl+\'/ajax-loader.gif" />\');
	$(\'#cbSubmitUpload\'+ID)
	.attr(\'disabled\',\'disabled\')
	.attr("value",lang.savingData)
	.attr("onClick","return false;");
	
	$.post(
	baseurl+\'/actions/file_uploader.php\',formObjectData,function(data)
	{
		if(data.msg)
		show_message(data.msg,ID,true);
		if(data.error)
		show_error(data.error,ID,true);
		$(\'#updateVideoDataLoading\').empty();
		$(\'#cbSubmitUpload\'+ID)
		.removeAttr("disabled")
		.attr("value",lang.saveData)
		.attr("onClick","doUpdateVideo(\'#uploadForm"+ID+"\',\'"+ID+"\')")
	},"json"
	)
}

$(document).ready(function()
{
  

  var instanceNumber = 0;
  function showUploadify(object)
  {
  $(object).uploadify({
    \'uploader\'  : uploadSwfPath,
	\'scriptAccess\' : \'always\',
    \'script\'    : uploadScriptPath,
    \'cancelImg\' : imageurl+\'/cancel_upload.png\',
    \'auto\'      : true,
	\'removeCompleted\' : false,
	\'displayData\' : \'both\',
	\'fileExt\'     : fileExt,
	\'multi\'		  : true,
	\'fileDesc\'    : \'Video Files\',
	\'buttonText\' : lang.upload_video_button,
	\'queueID\' : \'fileUploadQueue\',
	\'queueLimit\' : 2,
	\'onOpen\'	 : function(event,ID,fileObj) {
		
		uploadError = false;
		filename = new Date().getTime() + Math.round((99977 - 10016) * Math.random() + 1) ;
		$.ajax({
			  url: baseurl+\'/actions/file_uploader.php\',
			  type: "POST",
			  data:({"getForm":"get_form","title":fileObj.name,"objId":ID}),
			  dataType: "text",
			  success: function(data)
			  {
			    if(!uploadError)
				{
					$(\'#file_uploads\'+ID).append(data);
				}
			  }
			});
			
			return true;
	},
	\'onComplete\' : function(event, ID, fileObj, response, data)
	{
		var resObj = eval(\'(\' + response + \')\');
		var vid  = "";
		if(resObj.success==\'yes\')
		{
			var file_name = resObj.file_name;
			$.ajax({
				  url: baseurl+\'/actions/file_uploader.php\',
				  type: "POST",
				  data:({"insertVideo":"yes","title":fileObj.name,"file_name":file_name}),
				  dataType: "text",
				  success: function(data)
				  {
					vid = data;
					$("#uploadForm"+ID+" #title").after(\'<input type="hidden" name="videoid" value="\'+vid+\'" id="videoid" />\')
					.after(\'<input type="hidden" name="updateVideo" value="yes" id="updateVideo" />\');
					$(\'#cbSubmitUpload\'+ID)
					.before(\'<span id="updateVideoDataLoading" style="margin-right:5px"></span>\')
					.removeAttr("disabled")
					.attr("value",lang.saveData)
					.attr("onClick","doUpdateVideo(\'#uploadForm"+ID+"\',\'"+ID+"\')");
					
					doUpdateVideo(\'#uploadForm\'+ID,ID);
				  }
				});
		}else
		{
			$("#upload_form"+ID).empty().remove();
			show_error(resObj.error,ID,false);	
			uploadError = true;
			return false;
		}
    }
  });
  }
  
  
  showUploadify(\'#file_uploads\');
});

'; ?>

</script>


<?php else: ?>
<script type="text/javascript">

window.onload = function() {
	<?php if ($this->_tpl_vars['total_quicklist']): ?>
		load_quicklist_box();
	<?php endif; ?>
	ini_cookies();
}

</script>
<?php endif; ?>

<?php if (@THIS_PAGE == 'photo_upload'): ?>
<link href="<?php echo $this->_tpl_vars['js']; ?>
/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js']; ?>
/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js']; ?>
/uploadify/jquery.uploadify.v2.1.4.js"></script>

<script type="text/javascript" language="javascript">
	fileExt = '<?php echo $this->_tpl_vars['cbphoto']->extensions(); ?>
',
	lang = new Array(),
	pData = new Array(),
	ajaxImage = '<img src="'+imageurl+'/ajax-loader.gif" style="vertical-align:middle;" /> ',
	uploadError = false;
<?php if ($_GET['collection']): ?>
	<?php $this->assign('cid', $this->_tpl_vars['cbphoto']->decode_key($_GET['collection'])); ?>
	<?php if ($this->_tpl_vars['cbphoto']->is_addable($this->_tpl_vars['cid'])): ?>
	collectionID = '<?php echo $this->_tpl_vars['cid']; ?>
';
	<?php endif; ?>
<?php endif; ?>	
lang['browsePhrase'] = '<?php echo smarty_lang(array('code' => 'browse_photos'), $this);?>
';
lang['saveData'] = "<?php echo smarty_lang(array('code' => 'save_data'), $this);?>
";
lang['savingData'] = "<?php echo smarty_lang(array('code' => 'saving'), $this);?>
";
lang['upPhoto'] = "<?php echo smarty_lang(array('code' => 'update_photo'), $this);?>
";
var uploadSwfPath = '<?php echo $this->_tpl_vars['photoUploaderDetails']['uploadSwfPath']; ?>
';
var uploadScriptPath = '<?php echo $this->_tpl_vars['photoUploaderDetails']['uploadScriptPath']; ?>
';

<?php echo '

function photoMessage(message,ID,fade)
{
	if(document.getElementById(ID+"Message"))
		$("#"+ID+"Message").children(":first-child").html(message).end().fadeIn(\'slow\');
	else
	{
		$("#Form"+ID).before(\'<div id=\\"\'+ID+\'Message\\" class=\\"uploadSuccessDiv\\"><div class=\\"uploadSuccessMsg\\">\'+message+\'<\\/div><\\/div>\');	
	}
	
	if(fade)
		$("#"+ID+"Message").delay(300).fadeOut(\'slow\');
}

function photoError(message,ID,fade)
{
	if(document.getElementById(ID+"Error"))
		$("#"+ID+"Error").children(":first-child").html(message).end().fadeIn(\'slow\');
	else
	{
		$("#Form"+ID)
		.before(\'<div id=\\"\'+ID+\'Error\\" class=\\"uploadErrorDiv\\"><div class=\\"uploadErrorMsg\\">\'+message+\'<\\/div><\\/div>\');
	}
	
	if(fade)
		$("#"+ID+"Error").delay(3000).fadeOut(\'slow\');
}

function QueueLimitError(message,fade)
{
	$("#photoUploadQueue")
	.before(\'<div id=\\"QueueError\\" style=\\"margin-top:6px;\\" class=\\"uploadErrorDiv\\"><div class=\\"uploadErrorMsg\\">\'+message+\'<\\/div><\\/div>\');
	if(fade)
		$("#QueueError").delay(5000).fadeOut(\'slow\');	
}

function insertAjaxPhoto(ID,filename,extension,server_url,folder)
{

	var photoFields = $("#Form"+ID).serialize();
	$.ajax({
		url : baseurl+\'/actions/photo_uploader.php\',
		type : \'POST\',
		dataType : \'json\',
		data : photoFields+"&filename="+filename+"&ext="+extension+"&insertPhoto=yes&server_url="+server_url+
		"&folder="+folder,
		beforeSend : function()
		{
			$("#cbSubmitUpload"+ID).html(ajaxImage+lang.savingData).attr(\'disabled\',\'disabled\');	
		},
		success : function(data)
		{
			if(data.error)
			{
				photoError(data.error,ID,true);
				$("#cbSubmitUpload"+ID).html("Save Photo")
				.removeAttr(\'disabled\').attr(\'onClick\',\'insertAjaxPhoto("\'+ID+\'","\'+filename+\'","\'+extension+\'");\');	
			}
			
			if(data.success)
			{
				photoMessage(data.success,ID,true);
				showPhotoPreview(ID,data.photoPreview);
				
				$("<input />").attr({
					\'id\' : \'photo_id\',
					\'name\' : \'photo_id\',
					\'value\' : data.photoID,
					\'type\' : \'hidden\'	
				}).prependTo(\'#Form\'+ID);
				
				$("#cbSubmitUpload"+ID).html(lang.upPhoto)
				.removeAttr(\'disabled\').attr(\'onClick\',\'updateAjaxPhoto("\'+ID+\'");\');	
			}
		}
	})
}

function updateAjaxPhoto(ID)
{
	var updateFields = $("#Form"+ID).serialize();
	$.ajax({
		url : baseurl+\'/actions/photo_uploader.php\',
		type : \'POST\',
		dataType : \'json\',
		data : updateFields+"&updatePhoto=yes",
		beforeSend : function()
		{
			$("#cbSubmitUpload"+ID).html(ajaxImage+lang.upPhoto).attr(\'disabled\',\'disabled\');
		},
		success : function(data)
		{
			if(data.error)
			{
				photoError(data.error,ID,true);
				$("#cbSubmitUpload"+ID).removeAttr(\'disabled\').html(lang.upPhoto);	
			}
			
			if(data.success)
			{
				photoMessage(data.success,ID,true);
				$("#Form"+ID).hide();
				$("#cbSubmitUpload"+ID).removeAttr(\'disabled\').html(lang.upPhoto);
				if(!document.getElementById("toggle"+ID))
				{	
					$("<a></a>")
					.addClass(\'reUpdateButton\')
					.attr({
						"id" : "toggle"+ID,
						"href" : "#reUpdate",
						"onClick" : "$(\'#Form"+ID+"\').toggle(); return false;",
						"title" : "Toggle Photo Form"	
					}).html(\'Re-\'+lang.upPhoto).appendTo("#photo_uploads"+ID+" .percentage").fadeIn(350);
				}
			}
		}
	})
}

function showPhotoPreview(ID,preview)
{
	var object = $("#"+ID+"Preview"), parentObj = object.parent();
	var FinalPreview = new Image();
	FinalPreview.src = preview;
	$(FinalPreview).load(function()
	{
		object.hide();
		$("<img />")
		.addClass(object.attr(\'class\'))
		.attr({ "id" : "FinalPreview"+ID, "src" : preview }).appendTo(parentObj).fadeIn(\'normal\');	
	});
}

function update_collection_selection(obj)
{
	if($("#CollectionResult").css(\'display\') == \'block\')
		$("#CollectionResult").slideUp(\'fast\');
		
	var This = $(obj), thisObj = obj;
	This.attr(\'disabled\',\'disabled\');
	This.after(ajaxImage);
	collectionID = thisObj.value;
	
	setTimeout(function(){
		This.next().remove();
		$("#CollectionResult").html("<div class=\'msg\'>Collection Selected</div>").slideDown(350);
		This.removeAttr(\'disabled\');	
	},1000);
}
	
$(document).ready(function() {
	
	function photoUploadify(object)
	{
		$(object).uploadify(
		{
			\'uploader\'  : uploadSwfPath,
			\'script\'    : uploadScriptPath,
			\'scriptAccess\' : \'always\',
			\'fileDataName\' : \'photoUpload\',
			\'cancelImg\' : imageurl+\'/cancel_upload.png\',
			\'auto\'      : true,
			\'removeCompleted\' : false,
			\'displayData\' : \'both\',
			\'fileExt\'     : fileExt,
			\'multi\'		  : true,
			\'queueSizeLimit\': '; ?>
 '<?php echo $this->_tpl_vars['Cbucket']->configs['photo_multi_upload']; ?>
', <?php echo '
			\'fileDesc\'    : \'Photo Files\',
			\'buttonText\' : lang.browsePhrase,
			\'queueID\' : \'photoUploadQueue\',
			\'sizeLimit\' : '; ?>
'<?php echo $this->_tpl_vars['cbphoto']->max_file_size; ?>
',<?php echo '
			\'onSelect\' : function(event,ID,fileObj)
			{
				uploadError = false;
				$.ajax({
					url : baseurl+\'/actions/photo_uploader.php\',
					dataType : \'json\',
					type : \'POST\',
					data : ({ "photoForm":"yes" , "objID":ID , "name":fileObj.name , "collection":collectionID }),
					success : function(data)
					{
						if(!uploadError)
							$("#photo_uploads"+ID).append(data[\'form\']);
					}
				});
			},
			\'onComplete\' : function(event,ID,fileObj,response,data)
			{
			
				var resObj = $.parseJSON(response);
				if(resObj.success == "yes")
				{
					insertAjaxPhoto(ID,resObj.filename,resObj.extension,resObj.server_url,resObj.folder);	
				}
				
			},
			\'onQueueFull\' : function(event,queueSizeLimit)
			{
				QueueError = "I\'m stuffed, "+queueSizeLimit+" is my limit. Thanks.";
				QueueLimitError(QueueError,true);
				return false;
			},
			\'onError\' : function(event,ID,fileObj,errorObj)
			{
				uploadError = true;	
			}
		});
	}
		 
	photoUploadify(\'#photo_uploads\'); 
});	
'; ?>

</script>
<?php endif; ?>

<?php if (@THIS_PAGE == 'view_item'): ?>
<script type="text/javascript">
	var Seo = '<?php echo $this->_tpl_vars['Cbucket']->configs['seo']; ?>
';
	onReload_item();
</script>
<?php endif; ?>

<?php if (@THIS_PAGE == 'watch_video' || $this->_tpl_vars['object']['videoid']): ?>
    <?php if ($this->_tpl_vars['object']['videoid']): ?><?php $this->assign('vdo', $this->_tpl_vars['object']); ?> <?php endif; ?>
    <meta property="og:title" content="<?php echo $this->_tpl_vars['vdo']['title']; ?>
" />
    <meta property="og:description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['vdo']['description'])) ? $this->_run_mod_handler('description', true, $_tmp) : description($_tmp)))) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
" />
    <meta property="og:image" content="<?php echo getSmartyThumb(array('vdetails' => $this->_tpl_vars['vdo']), $this);?>
" />
    
    <?php if ($this->_tpl_vars['Cbucket']->configs['facebook_embed'] == 'yes'): ?>
        <meta property="og:type" content="video">
        <meta property="og:video" content='<?php echo fb_embed_video(array('video' => $this->_tpl_vars['vdo']), $this);?>
'>
        <meta property="og:video:height" content="259" />
        <meta property="og:video:width" content="398" />
        <meta property="og:video:type" content="application/x-shockwave-flash">
    <?php endif; ?>
        <meta property='og:url' content='<?php echo videoSmartyLink(array('vdetails' => $this->_tpl_vars['vdo']), $this);?>
'/>
        <meta property='og:site_name' content='<?php echo $this->_tpl_vars['website_title']; ?>
'/>
    
<?php endif; ?>

<?php if ($this->_tpl_vars['object']['photo_id']): ?>
<meta property="og:title" content="<?php echo $this->_tpl_vars['object']['photo_title']; ?>
" />
<meta property="og:description" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['object']['photo_description'])) ? $this->_run_mod_handler('description', true, $_tmp) : description($_tmp)); ?>
" />
<meta property="og:image" content="<?php echo get_image_file(array('details' => $this->_tpl_vars['object'],'size' => 'm','output' => 'non_html','alt' => $this->_tpl_vars['object']['photo_title']), $this);?>
" />
<meta name="medium" content="image" />
<?php endif; ?>


<?php echo '
<script>
$(function() {

	$( ".date_field" ).datepicker({ 
		dateFormat: \'yy-mm-dd\',
		changeMonth: true,
		changeYear: true,
		yearRange : "1901 : c"
		});
});
</script>
'; ?>


<?php echo ANCHOR(array('place' => 'cb_head'), $this);?>


<!-- ClipBucket version <?php echo $this->_tpl_vars['Cbucket']->cbinfo['version']; ?>
 -->