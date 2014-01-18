<?php /* Smarty version Smarty-3.1.15, created on 2014-01-15 12:18:25
         compiled from "/var/www/clipbucket/styles/global/head.html" */ ?>
<?php /*%%SmartyHeaderCode:3227643352d6364128d9a6-38983927%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d93422e156720bc12f974f83ad076c82ae7e5ee' => 
    array (
      0 => '/var/www/clipbucket/styles/global/head.html',
      1 => 1389359113,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3227643352d6364128d9a6-38983927',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'baseurl' => 0,
    'imageurl' => 0,
    'Cbucket' => 0,
    'js' => 0,
    'type' => 0,
    'scope' => 0,
    'file' => 0,
    'file_name' => 0,
    'uploaderDetails' => 0,
    'total_quicklist' => 0,
    'cbphoto' => 0,
    'cid' => 0,
    'photoUploaderDetails' => 0,
    'object' => 0,
    'vdo' => 0,
    'website_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_52d6364138d4b9_22338936',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52d6364138d4b9_22338936')) {function content_52d6364138d4b9_22338936($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/clipbucket/includes/smartyv3/plugins/modifier.replace.php';
?><script>
var baseurl = '<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
';
var imageurl = '<?php echo $_smarty_tpl->tpl_vars['imageurl']->value;?>
';
</script>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/styles/global/jquery_ui.css" type="text/css" media="all" />
<?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->configs['use_google_api']) {?>
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAr5pj809LgbJgBTxDJGy0IxQH8siQo9V3STvJ8WIDHu37hIWsoxRX_d1ABxknSddUPvo4LFb7wq8gwA"></script>

<script type="text/javascript">
 google.load("jquery", "1");
 google.load("jqueryui", "1");
</script>
<?php } else { ?>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/jquery.migrate.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/ui/jquery-ui.js"></script>
<?php }?> 
<script type="text/javascript">
var embedPlayerWidth = '<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['embed_player_width'];?>
';
var embedPlayerHeight = '<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['embed_player_height'];?>
';
var autoPlayEmbed = '<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['autoplay_embed'];?>
';



function updateEmbed(width,height,autoplay)
{
	$('#embed_code').val( $('#embed_code').val().replace(/width=\"([0-9]+)\"/g,'width="'+width+'"') );
	$('#embed_code').val( $('#embed_code').val().replace(/width\:([0-9]+)px/g,'width:'+width+'px') );
	$('#embed_code').val( $('#embed_code').val().replace(/height=\"([0-9]+)\"/g,'height="'+height+'"') );
	$('#embed_code').val( $('#embed_code').val().replace(/height\:([0-9]+)px/g,'height:'+height+'px') );
}

function switchEmbedCode(type)
{
	if(embed_type==type)
		return false;
	else
	{
		embed_type = type;
		
		var alt_embed =$('#alternate_embed_code').val();
		$('#alternate_embed_code').val($('#embed_code').val());
		$('#embed_code').val(alt_embed);
		
	}
}



</script>


<!-- Including JS Files-->
<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['Cbucket']->value->JSArray; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
 <?php  $_smarty_tpl->tpl_vars['scope'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['scope']->_loop = false;
 $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['type']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['scope']->key => $_smarty_tpl->tpl_vars['scope']->value) {
$_smarty_tpl->tpl_vars['scope']->_loop = true;
 $_smarty_tpl->tpl_vars['file']->value = $_smarty_tpl->tpl_vars['scope']->key;
?>
  <?php echo include_js(array('type'=>$_smarty_tpl->tpl_vars['scope']->value,'file'=>$_smarty_tpl->tpl_vars['file']->value),$_smarty_tpl);?>

 <?php } ?>
<?php } ?>
<!-- Including JS Files-->
<script type="text/javascript">
	callURLParser();
</script>
<!-- Including Plugin Headers -->
<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['Cbucket']->value->header_files; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['file']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
	<?php echo include_header(array('file'=>$_smarty_tpl->tpl_vars['file']->value,'type'=>$_smarty_tpl->tpl_vars['type']->value),$_smarty_tpl);?>

<?php } ?>
<!-- Ending Plugin Headers -->



<?php if (@constant('THIS_PAGE')=='upload') {?>

<link href="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/uploadify/jquery.uploadify.v2.1.4.js"></script>

<script type="text/javascript">
var jsURL = '<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
';
var uploadError = false;
var file_name = '<?php echo $_smarty_tpl->tpl_vars['file_name']->value;?>
';
var lang = new Array();
lang['saveData'] = '<?php echo smarty_lang(array('code'=>'save_data'),$_smarty_tpl);?>
';
lang['savingData'] = '<?php echo smarty_lang(array('code'=>'saving'),$_smarty_tpl);?>
';
lang['remoteUploadFile'] = '<?php echo smarty_lang(array('code'=>"remote_upload_file"),$_smarty_tpl);?>
';
lang['upload_video_button'] = '<?php echo smarty_lang(array('code'=>"upload_video_button"),$_smarty_tpl);?>
';
var fileExt = '<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['Cbucket']->value->list_extensions(),",",";");?>
';

var uploadSwfPath = '<?php echo $_smarty_tpl->tpl_vars['uploaderDetails']->value['uploadSwfPath'];?>
';
var uploadScriptPath = '<?php echo $_smarty_tpl->tpl_vars['uploaderDetails']->value['uploadScriptPath'];?>
';




  function show_error(msg,ID,fade)
  { $('#file_uploads'+ID+' .percentage')
  	.after('<div class=\"uploadErrorDiv\"><div class=\"uploadErrorMsg\">'+msg+'<\/div><\/div>'); 
	if(fade){$('.uploadSuccessDiv').delay(3000).fadeOut('slow');}
  }
  
  function show_message(msg,ID,fade)
  { $('#uploadForm'+ID)
  	.prepend('<div class=\"uploadSuccessDiv\"><div class=\"uploadSuccessMsg\">'+msg+'<\/div><\/div>');
  if(fade){$('.uploadSuccessDiv').delay(3000).fadeOut('slow');}
  }


function doUpdateVideo(formId,ID)
{
	var formObjectData = $(formId).serialize();
	//alert(serializedData);
	
	$('#updateVideoDataLoading').html('<img src="'+imageurl+'/ajax-loader.gif" />');
	$('#cbSubmitUpload'+ID)
	.attr('disabled','disabled')
	.attr("value",lang.savingData)
	.attr("onClick","return false;");
	
	$.post(
	baseurl+'/actions/file_uploader.php',formObjectData,function(data)
	{
		if(data.msg)
		show_message(data.msg,ID,true);
		if(data.error)
		show_error(data.error,ID,true);
		$('#updateVideoDataLoading').empty();
		$('#cbSubmitUpload'+ID)
		.removeAttr("disabled")
		.attr("value",lang.saveData)
		.attr("onClick","doUpdateVideo('#uploadForm"+ID+"','"+ID+"')")
	},"json"
	)
}

$(document).ready(function()
{
  

  var instanceNumber = 0;
  function showUploadify(object)
  {
  $(object).uploadify({
    'uploader'  : uploadSwfPath,
	'scriptAccess' : 'always',
    'script'    : uploadScriptPath,
    'cancelImg' : imageurl+'/cancel_upload.png',
    'auto'      : true,
	'removeCompleted' : false,
	'displayData' : 'both',
	'fileExt'     : fileExt,
	'multi'		  : true,
	'fileDesc'    : 'Video Files',
	'buttonText' : lang.upload_video_button,
	'queueID' : 'fileUploadQueue',
	'queueLimit' : 2,
	'onOpen'	 : function(event,ID,fileObj) {
		
		uploadError = false;
		filename = new Date().getTime() + Math.round((99977 - 10016) * Math.random() + 1) ;
		$.ajax({
			  url: baseurl+'/actions/file_uploader.php',
			  type: "POST",
			  data:({"getForm":"get_form","title":fileObj.name,"objId":ID}),
			  dataType: "text",
			  success: function(data)
			  {
			    if(!uploadError)
				{
					$('#file_uploads'+ID).append(data);
				}
			  }
			});
			
			return true;
	},
	'onComplete' : function(event, ID, fileObj, response, data)
	{
		var resObj = eval('(' + response + ')');
		var vid  = "";
		if(resObj.success=='yes')
		{
			var file_name = resObj.file_name;
			$.ajax({
				  url: baseurl+'/actions/file_uploader.php',
				  type: "POST",
				  data:({"insertVideo":"yes","title":fileObj.name,"file_name":file_name}),
				  dataType: "text",
				  success: function(data)
				  {
					vid = data;
					$("#uploadForm"+ID+" #title").after('<input type="hidden" name="videoid" value="'+vid+'" id="videoid" />')
					.after('<input type="hidden" name="updateVideo" value="yes" id="updateVideo" />');
					$('#cbSubmitUpload'+ID)
					.before('<span id="updateVideoDataLoading" style="margin-right:5px"></span>')
					.removeAttr("disabled")
					.attr("value",lang.saveData)
					.attr("onClick","doUpdateVideo('#uploadForm"+ID+"','"+ID+"')");
					
					doUpdateVideo('#uploadForm'+ID,ID);
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
  
  
  showUploadify('#file_uploads');
});


</script>


<?php } else { ?>
<script type="text/javascript">

window.onload = function() {
	<?php if ($_smarty_tpl->tpl_vars['total_quicklist']->value) {?>
		load_quicklist_box();
	<?php }?>
	ini_cookies();
}

</script>
<?php }?>

<?php if (@constant('THIS_PAGE')=='photo_upload') {?>
<link href="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
/uploadify/jquery.uploadify.v2.1.4.js"></script>

<script type="text/javascript" language="javascript">
	fileExt = '<?php echo $_smarty_tpl->tpl_vars['cbphoto']->value->extensions();?>
',
	lang = new Array(),
	pData = new Array(),
	ajaxImage = '<img src="'+imageurl+'/ajax-loader.gif" style="vertical-align:middle;" /> ',
	uploadError = false;
<?php if ($_GET['collection']) {?>
	<?php if (isset($_smarty_tpl->tpl_vars['cid'])) {$_smarty_tpl->tpl_vars['cid'] = clone $_smarty_tpl->tpl_vars['cid'];
$_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['cbphoto']->value->decode_key($_GET['collection']); $_smarty_tpl->tpl_vars['cid']->nocache = null; $_smarty_tpl->tpl_vars['cid']->scope = 0;
} else $_smarty_tpl->tpl_vars['cid'] = new Smarty_variable($_smarty_tpl->tpl_vars['cbphoto']->value->decode_key($_GET['collection']), null, 0);?>
	<?php if ($_smarty_tpl->tpl_vars['cbphoto']->value->is_addable($_smarty_tpl->tpl_vars['cid']->value)) {?>
	collectionID = '<?php echo $_smarty_tpl->tpl_vars['cid']->value;?>
';
	<?php }?>
<?php }?>	
lang['browsePhrase'] = '<?php echo smarty_lang(array('code'=>"browse_photos"),$_smarty_tpl);?>
';
lang['saveData'] = "<?php echo smarty_lang(array('code'=>'save_data'),$_smarty_tpl);?>
";
lang['savingData'] = "<?php echo smarty_lang(array('code'=>'saving'),$_smarty_tpl);?>
";
lang['upPhoto'] = "<?php echo smarty_lang(array('code'=>'update_photo'),$_smarty_tpl);?>
";
var uploadSwfPath = '<?php echo $_smarty_tpl->tpl_vars['photoUploaderDetails']->value['uploadSwfPath'];?>
';
var uploadScriptPath = '<?php echo $_smarty_tpl->tpl_vars['photoUploaderDetails']->value['uploadScriptPath'];?>
';



function photoMessage(message,ID,fade)
{
	if(document.getElementById(ID+"Message"))
		$("#"+ID+"Message").children(":first-child").html(message).end().fadeIn('slow');
	else
	{
		$("#Form"+ID).before('<div id=\"'+ID+'Message\" class=\"uploadSuccessDiv\"><div class=\"uploadSuccessMsg\">'+message+'<\/div><\/div>');	
	}
	
	if(fade)
		$("#"+ID+"Message").delay(300).fadeOut('slow');
}

function photoError(message,ID,fade)
{
	if(document.getElementById(ID+"Error"))
		$("#"+ID+"Error").children(":first-child").html(message).end().fadeIn('slow');
	else
	{
		$("#Form"+ID)
		.before('<div id=\"'+ID+'Error\" class=\"uploadErrorDiv\"><div class=\"uploadErrorMsg\">'+message+'<\/div><\/div>');
	}
	
	if(fade)
		$("#"+ID+"Error").delay(3000).fadeOut('slow');
}

function QueueLimitError(message,fade)
{
	$("#photoUploadQueue")
	.before('<div id=\"QueueError\" style=\"margin-top:6px;\" class=\"uploadErrorDiv\"><div class=\"uploadErrorMsg\">'+message+'<\/div><\/div>');
	if(fade)
		$("#QueueError").delay(5000).fadeOut('slow');	
}

function insertAjaxPhoto(ID,filename,extension,server_url,folder)
{

	var photoFields = $("#Form"+ID).serialize();
	$.ajax({
		url : baseurl+'/actions/photo_uploader.php',
		type : 'POST',
		dataType : 'json',
		data : photoFields+"&filename="+filename+"&ext="+extension+"&insertPhoto=yes&server_url="+server_url+
		"&folder="+folder,
		beforeSend : function()
		{
			$("#cbSubmitUpload"+ID).html(ajaxImage+lang.savingData).attr('disabled','disabled');	
		},
		success : function(data)
		{
			if(data.error)
			{
				photoError(data.error,ID,true);
				$("#cbSubmitUpload"+ID).html("Save Photo")
				.removeAttr('disabled').attr('onClick','insertAjaxPhoto("'+ID+'","'+filename+'","'+extension+'");');	
			}
			
			if(data.success)
			{
				photoMessage(data.success,ID,true);
				showPhotoPreview(ID,data.photoPreview);
				
				$("<input />").attr({
					'id' : 'photo_id',
					'name' : 'photo_id',
					'value' : data.photoID,
					'type' : 'hidden'	
				}).prependTo('#Form'+ID);
				
				$("#cbSubmitUpload"+ID).html(lang.upPhoto)
				.removeAttr('disabled').attr('onClick','updateAjaxPhoto("'+ID+'");');	
			}
		}
	})
}

function updateAjaxPhoto(ID)
{
	var updateFields = $("#Form"+ID).serialize();
	$.ajax({
		url : baseurl+'/actions/photo_uploader.php',
		type : 'POST',
		dataType : 'json',
		data : updateFields+"&updatePhoto=yes",
		beforeSend : function()
		{
			$("#cbSubmitUpload"+ID).html(ajaxImage+lang.upPhoto).attr('disabled','disabled');
		},
		success : function(data)
		{
			if(data.error)
			{
				photoError(data.error,ID,true);
				$("#cbSubmitUpload"+ID).removeAttr('disabled').html(lang.upPhoto);	
			}
			
			if(data.success)
			{
				photoMessage(data.success,ID,true);
				$("#Form"+ID).hide();
				$("#cbSubmitUpload"+ID).removeAttr('disabled').html(lang.upPhoto);
				if(!document.getElementById("toggle"+ID))
				{	
					$("<a></a>")
					.addClass('reUpdateButton')
					.attr({
						"id" : "toggle"+ID,
						"href" : "#reUpdate",
						"onClick" : "$('#Form"+ID+"').toggle(); return false;",
						"title" : "Toggle Photo Form"	
					}).html('Re-'+lang.upPhoto).appendTo("#photo_uploads"+ID+" .percentage").fadeIn(350);
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
		.addClass(object.attr('class'))
		.attr({ "id" : "FinalPreview"+ID, "src" : preview }).appendTo(parentObj).fadeIn('normal');	
	});
}

function update_collection_selection(obj)
{
	if($("#CollectionResult").css('display') == 'block')
		$("#CollectionResult").slideUp('fast');
		
	var This = $(obj), thisObj = obj;
	This.attr('disabled','disabled');
	This.after(ajaxImage);
	collectionID = thisObj.value;
	
	setTimeout(function(){
		This.next().remove();
		$("#CollectionResult").html("<div class='msg'>Collection Selected</div>").slideDown(350);
		This.removeAttr('disabled');	
	},1000);
}
	
$(document).ready(function() {
	
	function photoUploadify(object)
	{
		$(object).uploadify(
		{
			'uploader'  : uploadSwfPath,
			'script'    : uploadScriptPath,
			'scriptAccess' : 'always',
			'fileDataName' : 'photoUpload',
			'cancelImg' : imageurl+'/cancel_upload.png',
			'auto'      : true,
			'removeCompleted' : false,
			'displayData' : 'both',
			'fileExt'     : fileExt,
			'multi'		  : true,
			'queueSizeLimit':  '<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['photo_multi_upload'];?>
', 
			'fileDesc'    : 'Photo Files',
			'buttonText' : lang.browsePhrase,
			'queueID' : 'photoUploadQueue',
			'sizeLimit' : '<?php echo $_smarty_tpl->tpl_vars['cbphoto']->value->max_file_size;?>
',
			'onSelect' : function(event,ID,fileObj)
			{
				uploadError = false;
				$.ajax({
					url : baseurl+'/actions/photo_uploader.php',
					dataType : 'json',
					type : 'POST',
					data : ({ "photoForm":"yes" , "objID":ID , "name":fileObj.name , "collection":collectionID }),
					success : function(data)
					{
						if(!uploadError)
							$("#photo_uploads"+ID).append(data['form']);
					}
				});
			},
			'onComplete' : function(event,ID,fileObj,response,data)
			{
			
				var resObj = $.parseJSON(response);
				if(resObj.success == "yes")
				{
					insertAjaxPhoto(ID,resObj.filename,resObj.extension,resObj.server_url,resObj.folder);	
				}
				
			},
			'onQueueFull' : function(event,queueSizeLimit)
			{
				QueueError = "I'm stuffed, "+queueSizeLimit+" is my limit. Thanks.";
				QueueLimitError(QueueError,true);
				return false;
			},
			'onError' : function(event,ID,fileObj,errorObj)
			{
				uploadError = true;	
			}
		});
	}
		 
	photoUploadify('#photo_uploads'); 
});	

</script>
<?php }?>

<?php if (@constant('THIS_PAGE')=='view_item') {?>
<script type="text/javascript">
	var Seo = '<?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->configs['seo'];?>
';
	onReload_item();
</script>
<?php }?>

<?php if (@constant('THIS_PAGE')=='watch_video'||$_smarty_tpl->tpl_vars['object']->value['videoid']) {?>
    <?php if ($_smarty_tpl->tpl_vars['object']->value['videoid']) {?><?php if (isset($_smarty_tpl->tpl_vars['vdo'])) {$_smarty_tpl->tpl_vars['vdo'] = clone $_smarty_tpl->tpl_vars['vdo'];
$_smarty_tpl->tpl_vars['vdo']->value = $_smarty_tpl->tpl_vars['object']->value; $_smarty_tpl->tpl_vars['vdo']->nocache = null; $_smarty_tpl->tpl_vars['vdo']->scope = 0;
} else $_smarty_tpl->tpl_vars['vdo'] = new Smarty_variable($_smarty_tpl->tpl_vars['object']->value, null, 0);?> <?php }?>
    <meta property="og:title" content="<?php echo $_smarty_tpl->tpl_vars['vdo']->value['title'];?>
" />
    <meta property="og:description" content="<?php echo preg_replace('!<[^>]*?>!', ' ', description($_smarty_tpl->tpl_vars['vdo']->value['description']));?>
" />
    <meta property="og:image" content="<?php echo getSmartyThumb(array('vdetails'=>$_smarty_tpl->tpl_vars['vdo']->value),$_smarty_tpl);?>
" />
    
    <?php if ($_smarty_tpl->tpl_vars['Cbucket']->value->configs['facebook_embed']=='yes') {?>
        <meta property="og:type" content="video">
        <meta property="og:video" content='<?php echo fb_embed_video(array('video'=>$_smarty_tpl->tpl_vars['vdo']->value),$_smarty_tpl);?>
'>
        <meta property="og:video:height" content="259" />
        <meta property="og:video:width" content="398" />
        <meta property="og:video:type" content="application/x-shockwave-flash">
    <?php }?>
        <meta property='og:url' content='<?php echo videoSmartyLink(array('vdetails'=>$_smarty_tpl->tpl_vars['vdo']->value),$_smarty_tpl);?>
'/>
        <meta property='og:site_name' content='<?php echo $_smarty_tpl->tpl_vars['website_title']->value;?>
'/>
    
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['object']->value['photo_id']) {?>
<meta property="og:title" content="<?php echo $_smarty_tpl->tpl_vars['object']->value['photo_title'];?>
" />
<meta property="og:description" content="<?php echo description($_smarty_tpl->tpl_vars['object']->value['photo_description']);?>
" />
<meta property="og:image" content="<?php echo get_image_file(array('details'=>$_smarty_tpl->tpl_vars['object']->value,'size'=>'m','output'=>'non_html','alt'=>$_smarty_tpl->tpl_vars['object']->value['photo_title']),$_smarty_tpl);?>
" />
<meta name="medium" content="image" />
<?php }?>



<script>
$(function() {

	$( ".date_field" ).datepicker({ 
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		yearRange : "1901 : c"
		});
});
</script>


<?php echo ANCHOR(array('place'=>'cb_head'),$_smarty_tpl);?>


<!-- ClipBucket version <?php echo $_smarty_tpl->tpl_vars['Cbucket']->value->cbinfo['version'];?>
 --><?php }} ?>
