// JavaScript Document

var page = baseurl+'/ajax.php';
var loading_img = "<img style='vertical-align:middle' src='"+imageurl+"/ajax-loader.gif'>";
var loading = loading_img+" Loading...";

	function Confirm_Delete(delUrl) {
	  if (confirm("Are you sure you want to delete")) {
		document.location = delUrl;
	  }
	}
	
	function Confirm_Uninstall(delUrl) {
	  if (confirm("Are you sure you want to uninstall this plugin ?")) {
		document.location = delUrl;
	  }
	}
	
	
	function confirm_it(msg)
	{
		var action = confirm(msg);
		if(action)
		{
			return true;
		}else
			return false;
	}
	
	function reloadImage(captcha_src,imgid){img = document.getElementById(imgid);img.src = captcha_src+'?'+Math.random();}
	
	
	//Validate the Add Category Form
	function validate_category_form(thisform)
	{
		with (thisform)
		{
				if (validate_required(title,"Title must be filled out!")==false)
				{
			 title.focus();return false;
				}
				if (validate_required(description,"Description must be filled out!")==false)
				{
			 description.focus();return false;
				}
		
		}
	}
	
	function validate_ad_form(thisform)
	{
		with (thisform)
		{
				if (validate_required(name,"Name must be filled out!")==false)
				{
			 name.focus();return false;
				}
				if (validate_required(type,"Type must be filled out!")==false)
				{
			 type.focus();return false;
				}
				if (validate_required(syntax,"Syntax Must Be Filled Out")==false)
				{
			 syntax.focus();return false;
				}
				if (validate_required(code,"Code Must Be Filled Out")==false)
				{
			 code.focus();return false;
				}
		}
	}
	

	
	function randomString()
	{
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var string_length = 8;
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
		}
		return randomstring;
	}



	var download = 0;
	var total_size = 0;
	var cur_speed = 0;
	
	var status_refesh = 1 //in seconds
	var result_page = baseurl+'/actions/file_results.php';
	var download_page = baseurl+'/actions/file_downloader.php';
	var count = 0;
	
	
	var force_stop = false;
	var remoteObjID = randomString();
	
	function check_remote_url()
	{
		$('#remoteUploadBttn').attr("disabled","disabled").hide();
		$('#ytUploadBttn').attr("disabled","disabled");
		$('#remoteUploadBttnStop').show();
		var file = $("#remote_file_url").val();
		force_stop = false;		
		if(!file || file=='undefined')
		{
			alert("Please enter file url");
			$('#remoteUploadBttn').attr('disabled','').show();
			$('#remoteUploadBttnStop').attr("disabled","disabled").hide();
			$('#ytUploadBttn').attr("disabled","");
			return false;
		}
		var ajaxCall = $.ajax({
			  url: download_page,
			  type: "POST",
			  data: ({file:file,file_name:file_name}),
			  dataType : 'json',
			  beforeSend : function()
			  {
				  
				  status_update();
				  var remoteFileName = getName(file);
				 $("#loading").html('<div style="float: left; display: inline-block;"><img src="'+imageurl+'/ajax-loader.gif"></div><div style="float: left; line-height: 16px; padding-left:5px">'+lang.remoteUploadFile+'</div><div class="clear"></div>');
				 $('#remoteFileName').replaceWith('"'+remoteFileName+'"');
			  },
			  success: function(data)
			  {
				  
				  if(data.error)
				  {		  
					force_stop = true;
					$('#remoteUploadBttn').attr('disabled','');
					$('#ytUploadBttn').attr("disabled","");
					alert(data.error);
				  }				  
				  $("#loading").html('');
				  
				  var vid = data.vid;
				  
				   $.post(baseurl+'/actions/file_uploader.php',
				  {"getForm":"get_form","title":$("#remote_file_url").val(),"objId":remoteObjID},
				  function(data)
				  {
					    $('#remoteUploadBttnStop').hide();
						$('#ytUploadBttn').hide();
						$('#remoteForm').append(data);
							$('#cbSubmitUpload'+remoteObjID)
							.before('<span id="updateVideoDataLoading" style="margin-right:5px"></span>')
							.attr("disabled","")
							.attr("value",lang.saveData)
							.attr("onClick","doUpdateVideo('#uploadForm"+remoteObjID+"','"+remoteObjID+"')")
							.after('<input type="hidden" name="videoid" value="'+vid+'" id="videoid" />')
							.after('<input type="hidden" name="updateVideo" value="yes" id="updateVideo" />');
				  },'text');
			  }
		   }
		);
		
		$('#remoteUploadBttnStop').click(function() { 
		ajaxCall.abort(); force_stop=true; $("#loading").html('');$('#remoteDownloadStatus').hide(); $(this).hide();$('#remoteUploadBttn').attr('disabled','').show(); });
		
		
	}
	
	function youtube_upload()
	{
		$('#remoteUploadBttn').attr("disabled","disabled");
		$('#ytUploadBttn').attr("disabled","disabled");

		var file = $("#remote_file_url").val();
		force_stop = false;		
		if(!file || file=='undefined')
		{
			alert("Please enter file url");
			$('#remoteUploadBttn').attr('disabled','');
			$('#ytUploadBttn').attr("disabled",'');
			return false;
		}
		var ajaxCall = $.ajax({
			  url: download_page,
			  type: "POST",
			  data: ({file:file,file_name:file_name,"youtube":"yes"}),
			  dataType : 'json',
			  beforeSend : function()
			  {
				 $("#loading").html('<div style="float: left; display: inline-block;"><img src="'+imageurl+'/ajax-loader.gif"></div><div style="float: left; line-height: 16px; padding-left:5px">Uploading video from youtube, please wait...</div><div class="clear"></div>');
			  },
			  success: function(data)
			  {
				  
				  if(data.error)
				  {		  
					force_stop = true;
					$('#remoteUploadBttn').attr('disabled','');
					$('#ytUploadBttn').attr("disabled","");
					alert(data.error);
				  }else if(data.vid)
				  {
					  vid = data.vid;
					  $('#remoteUploadBttn').attr("disabled","disabled").hide();
					  $('#ytUploadBttn').attr("disabled","disabled").hide();
					  $.post(baseurl+'/actions/file_uploader.php',
					  {"getForm":"get_form",
					  "title":data.title,
					  "desc":data.desc,
					  "tags":data.tags,"objId":remoteObjID},
					  function(data)
					  {
							$('#remoteForm').append(data);
							$('#cbSubmitUpload'+remoteObjID)
							.before('<span id="updateVideoDataLoading" style="margin-right:5px"></span>')
							.attr("disabled","")
							.attr("value",lang.saveData)
							.attr("onClick","doUpdateVideo('#uploadForm"+remoteObjID+"','"+remoteObjID+"')")
							.after('<input type="hidden" name="videoid" value="'+vid+'" id="videoid" />')
							.after('<input type="hidden" name="updateVideo" value="yes" id="updateVideo" />');
					
					  },'text');
					  
				  }
				  $("#loading").html('');
			  }
		   }
		);
		
	}
	
	var hasLoaded = false;
	var perc_download = 0;
	function status_update()
	{
		
		var ajaxCall = $.ajax({
				  url: result_page,
				  type: "POST",
				  data:({file_name:file_name}),
				  dataType: "json",
				  success: function(data){
				
				  if(data)
				  {
					  var total = data.total_size;
					  var download = data.downloaded;
					  var total_fm = data.total_size_fm;
					  var download_fm = data.downloaded_fm;
					  var speed = data.speed_download;
					  var eta = data.time_eta;
					  var eta_fm = data.time_eta_fm;
					  var time_took = data.time_took;
					  var time_took_fm = data.time_took_fm;
					   
					  if(speed/1024/1024>1)
					  {
						var theSpeed = Math.round(speed / 1024/1024) + " Mbps";
					  }else
						var theSpeed = Math.round(speed/ 1024 ) + " Kbps";
					  
					perc_download = Math.round(download/total*100);
					
					$('#remoteDownloadStatus').show();
					//$('#prog_bar').width(perc_download+'%');
					$('#prog_bar').animate({width:perc_download+'%'},1000);
					$('#prog_bar').html(perc_download+'%');
					$('#dspeed').html(theSpeed);
					$('#eta').html(eta_fm);
					$('#status').html(download_fm+' of '+total_fm);
				  }
					
					var intval = status_refesh*1000;
					if(perc_download<100 && !force_stop)
					setTimeout(function(){status_update()},intval);
					else if(perc_download==100 && total>1)
					{
						
						$('#time_took').html('Time Took : '+time_took_fm);
					}
				  }
			   }
			);
		
	}
	
	
	function upload_file(Val,file_name)
	{
		var page = baseurl+'/actions/file_downloader.php';
		$.post(page, 
		{ 	
			file_url : Val,
			file_name : file_name
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
				submit_upload_form();
		},'text');
	}
	
	
	/**
	 * Function used to delete any item with confirm message
	 */
	function delete_item(obj,id,msg,url)
	{
		$("#"+obj+'-'+id).click(function () {
			if (confirm(msg)) {
				document.location = url;
			}				
		});
	}
	function delete_video(obj,id,msg,url){ return delete_item(obj,id,msg,url); }
	
	
	/**
	 * Function used to load editor's pic video
	 */
	function get_video(type,div)
	{
		$(div).css("display","block");
		$(div).html(loading);
		$(div).html(loading);
		$.post(page, 
		{ 	
			mode : type
		},
		function(data)
		{
			$(div).html(data);
		},'text');
	}
	
	/**
	 * functio used to get photos through ajax
	 */
	function getAjaxPhoto(type,div)
	{
		$(div).css("display","block");
		var preservedHTML = $(div).html();
		$.ajax({
			url : page,
			type : 'POST',
			dataType : 'json',
			data : ({ mode : 'loadAjaxPhotos', 'photosType' : type }),
			beforeSend : function ()
			{
				$(div).html(loading);	
			},
			success : function (data)
			{
				if(data['failed'])
				{
					//alert("No Photos Returned");
					$(div).html(preservedHTML);
				}
					
				if(data['completed'])
				{	
					$(div).html(data['photoBlocks']);
				}
			}
		})
	}


	function rating_over(msg,disable)
	{
		if(disable!='disabled')
			$("#rating_result_container").html(msg);
	}
	function rating_out(msg,disable)
	{
		if(disable!='disabled')
		$("#rating_result_container").html(msg);
	}
	
	
	function submit_share_form(form_id,type)
	{
		$("#share_form_results").css("display","block");
		$("#share_form_results").html(loading);
		$.post(page, 
		{ 	
			mode : 'share_object',
			type : type,
			users : $("#"+form_id+" input:#ShareUsers").val(),
			message : $("#"+form_id+" textarea:#message").val(),
			id : $("#"+form_id+" input:#objectid").val()
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#share_form_results").html(data);
			}
		},'text');
	}
	
	
	
	function flag_object(form_id,id,type)
	{
		$("#flag_form_result").css("display","block");
		$("#flag_form_result").html(loading);
		$.post(page, 
		{ 	
			mode : 'flag_object',
			type : type,
			flag_type : $("#"+form_id+" select:#flag_type").val(),
			id : id
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#flag_form_result").css("display","block");
				$("#flag_form_result").html(data);
			}
		},'text');
	}
	
	function slide_up_watch_video(nodiv)
	{
		if($('.video_action_result_boxes '+nodiv).css("display")!="block")
		$('.video_action_result_boxes > *').slideUp();
	}
	
	function add_to_fav(type,id)
	{
		$("#video_action_result_cont").css("display","block");
		$("#video_action_result_cont").html(loading);
		
		$.post(page, 
		{ 	
			mode : 'add_to_fav',
			type : type,
			id : id
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#video_action_result_cont").css("display","block");
				$("#video_action_result_cont").html(data);
			}
		},'text');
	}
	
	
	function subscriber(user,type,result_cont)
	{
		$("#"+result_cont).css("display","block");
		$("#"+result_cont).html(loading);
		
		$.post(page, 
		{ 	
			mode : type,
			subscribe_to : user
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#"+result_cont).css("display","block");
				$("#"+result_cont).html(data);
			}
		},'text');
	}
	
	function add_friend(uid,result_cont)
	{
		$("#"+result_cont).css("display","block");
		$("#"+result_cont).html(loading);
		
		$.post(page, 
		{ 	
			mode : 'add_friend',
			uid : uid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#"+result_cont).css("display","block");
				$("#"+result_cont).html(data);
			}
		},'text');
	}
	
	
	function block_user(user,result_cont)
	{
		$("#"+result_cont).css("display","block");
		$("#"+result_cont).html(loading);
		
		$.post(page, 
		{ 	
			mode : 'ban_user',
			user : user
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#"+result_cont).css("display","block");
				$("#"+result_cont).html(data);
			}
		},'text');
	}
	
	
	function rate_comment(cid,thumb,type,typeid)
	{

		$.post(page, 
		{ 	
			mode : 'rate_comment',
			thumb : thumb,
			cid : cid,
			type : type,
			typeid : typeid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{

				if(data.msg!='')
					alert(data.msg)
				if(data.rate!='')
					$("#comment_rating_"+cid).html(data.rate);
			}
		},'json');
	}
	
	
	function delete_comment(cid,type)
	{

		$.post(page, 
		{ 	
			mode : 'delete_comment',
			cid : cid,
			type : type
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{

				if(data.msg)
				{
					alert(data.msg);
					$("#comment_"+cid).fadeOut("slow");
					$("#spam_comment_"+cid).fadeOut("slow");;
					
				}
				if(data.err)
					alert(data.err);
			}
		},'json');
	}

	function add_comment_js(form_id,type)
	{
		$("#add_comment_result").css("display","block");
		$("#add_comment_result").html(loading);
		$("#add_comment_button").attr("disabled","disabled");
				
		//var captcha_enabled =  $("#"+form_id+" input:#cb_captcha_enabled").val();
		
		//First we will get all values of form_id and then serialize them
		//so we can forward details to ajax.php
		
		var formObjectData = $('#'+form_id).serialize()+'&mode=add_comment';
		
		$.post(page,formObjectData,
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				
				$("#add_comment_button").attr("disabled","");
				
				$("#add_comment_result").css("display","block");
				if(data.err!='')
				{
					$("#add_comment_result").html(data.err);
				}
				if(data.msg!='')
					$("#add_comment_result").html(data.msg);
				
				if(data.cid)
				{
					get_the_comment(data.cid,"#latest_comment_container");
					$("#"+form_id).slideUp();
				}
			}
		},'json');
	}
	
	function get_the_comment(id,div)
	{

		$(div).html(loading);
		$.post(page, 
		{ 	
			mode : 'get_comment',
			cid : id
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{		
				$(div).css("display","none");
				$(div).html(data).fadeIn("slow");
			}
		},'text');
	}
	
	function add_playlist(mode,vid,form_id,objtype)
	{
		$("#playlist_form_result").css("display","block");
		$("#playlist_form_result").html(loading);
		switch(mode)
		{
			case 'add':
			{
				$.post(page, 
				{ 	
					mode : 'add_playlist',
					id : vid,
					objtype : objtype,
					pid : $("#playlist_id option:selected").val()
		},
				function(data)
				{
					if(!data)
						alert("No data");
					else
					{	
						if(data.err != '')
						{
							$("#playlist_form_result").css("display","block");
							$("#playlist_form_result").html(data.err);
						}
						
						if(data.msg!='')
						{
							$("#playlist_form_result").css("display","block");
							$("#playlist_form_result").html(data.msg);
							$("#"+form_id).css("display","none");
						}	
						
					}
				},'json');
			}
			break;
			
			case 'new':
			{

				$.post(page, 
				{ 	
					mode : 'add_new_playlist',
					id : vid,
					objtype : objtype,
					plname : $("#"+form_id+" input:#playlist_name").val()
		},
				function(data)
				{
					if(!data)
						alert("No data");
					else
					{	
						if(data.err )
						{
							$("#playlist_form_result").css("display","block");
							$("#playlist_form_result").html(data.err);
						}
						
						if(data.msg)
						{
							$("#playlist_form_result").css("display","block");
							$("#playlist_form_result").html(data.msg);
							$("#"+form_id).css("display","none");
						}	
						
					}
				},'json');
			}
			break;
		}
	}
	
	
	/**
	 * Function used to add and remove video from qucklist
	 * THIS FEATURE IS SPECIALLY ADDED ON REQUEST BY JAHANZEB HASSAN
	 */
	function add_quicklist(obj,vid)
	{
		
		$(obj).attr('src',imageurl+"/ajax-loader.gif");
		$(obj).css('background-position',"-200px 200px");
		
		$.post(page, 
		{ 	
			mode : 'quicklist',
			todo : 'add',
			vid : vid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$(obj).attr('src',imageurl+"/dot.gif");
				$(obj).css('background-position',"-0px -0px");
				$(obj).removeClass('add_icon');
				$(obj).addClass('check_icon');
				$(obj).removeAttr('onClick');
				load_quicklist_box();
			}
		},'text');
	}
	
	/**
	 * Function used to remove video from qucklist
	 */
	function remove_qucklist(obj,vid)
	{
		
		$.post(page, 
		{ 	
			mode : 'quicklist',
			todo : 'remove',
			vid : vid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$(obj).slideUp();
				$(obj).hide();
			}
		},'text');
	}
	
	/**
	 * Function used to load quicklist
	 */
	function load_quicklist_box()
	{
		
		$.post(page, 
		{ 	
			mode : 'getquicklistbox'
		},
		function(data)
		{
			data = $.trim(data);
			if(!data)
				$("#quicklist_box").css("display","none");
			else
			{			
					$("#quicklist_box").css("display","block");
					$("#quicklist_box").html(data);
					
				if($.cookie("quick_list_box")!="hide")
				{
					$("#quicklist_cont").css("display","block");
				}
			}
		},'text');
	}
	
	function clear_quicklist()
	{
		$.post(page, 
		{ 	
			mode : 'clear_quicklist'
		},
		function(data)
		{
			load_quicklist_box();
		},'text');
	}
	
	function quick_show_hide_toggle(obj)
	{
		$(obj).slideToggle()
		
		if($.cookie("quick_list_box")=="show")
			$.cookie("quick_list_box","hide")	
		else
			$.cookie("quick_list_box","show")
	}
	
	/**
	 * Function used to set cookies
	 */
	function ini_cookies()
	{
		if(!$.cookie("quick_list_box"))
			$.cookie("quick_list_box","show")
	}
	
	
	function get_group_info(Div,li)
	{
		if( $(Div).css("display")=="none") 
		{
			$("#group_info_cont > div").slideUp();
			$("#group_info_cont "+Div).slideDown();
			$(".group_detail_tabs .selected").removeClass("selected");
			$(li).addClass("selected");
		}
	}

var current_menu = "";
function show_menu(menu,load_from_hash)
{
	if(window.location.hash && load_from_hash)
	{
		var thehash = window.location.hash;
		show_menu(thehash.substr(9),false);
		return false;
	}
	window.location.hash = 'current_'+menu;
	if(current_menu!=menu)
		hide_menu()
	$("#"+menu).show()
	current_menu = menu;	
	$("."+menu).addClass("selected");
}

function hide_menu()
{
	if(current_menu!='')
	{
		$("#"+current_menu).hide(); 
		$("."+current_menu).removeClass("selected");
		return true;
	}
}
	
	
function to_reply(cid)
{
	$("#reply_to").val(cid);
	window.location = "#add_comment";
}

function spam_comment(cid,type,typeid)
	{

		$.post(page, 
		{ 	
			mode : 'spam_comment',
			cid : cid,
			type : type,
			typeid : typeid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				if(data.msg)
				{
					$("#comment_"+cid).hide();
					$("#spam_comment_"+cid).fadeIn("slow");
				}
				if(data.err)
				{
					alert(data.err)
				}
			}
		},'json');
	}

var normal_player_html = '';
var hq_player_html = '';

var has_hq_function = false;
function hq_toggle(nplayer_div,hq_div)
{
	if(has_hq_function)
	{
		var nplayer_div = nplayer_div;
		var hq_div = hq_div;
		hq_function();
		return false;
	}
	if($(nplayer_div).css("display")=='block')
	{
		if(normal_player_html=='')
		normal_player_html = $(nplayer_div).html();
		$(nplayer_div).html("");
	}else
	{
		if(normal_player_html!='')
		$(nplayer_div).html(normal_player_html);
	}
	if($(hq_div).css("display")=='block')
	{
		if(hq_player_html=='')
		hq_player_html = $(hq_div).html();
		$(hq_div).html("");
	}else
	{
		if(hq_player_html!='')
		$(hq_div).html(hq_player_html);
	}
	$(nplayer_div+","+hq_div).toggle()
}



/** 
 * Funcion autplay playlist
 */
function swap_auto_play()
{
	if($.cookie("auto_play_playlist")=="true")
	{
		$.cookie("auto_play_playlist","false",{path:"/"});
		window.location = document.location;
		$('#ap_status').html("off");
		
	}else
	{
		$.cookie("auto_play_playlist","true",{path:"/"});
		window.location = document.location;
		$('#ap_status').html("on");
	}
}

function collection_actions(form,mode,objID,result_con,type,cid)
{
	$(result_con).css("display","block");
	$(result_con).html(loading);
	
	switch(mode)
	{
		case 'add_new_item':
		{
			
			$.post(page,
				   {
					   mode: mode,
					   cid: $("#"+form+' #collection').val(),
				   	   obj_id: objID,
					   type: type
				   },
				   function(data)
				   {
						if(!data)
							alert("No Data returned");
						else
						{
							
							if(data.msg)
								$(result_con).html(data.msg);
								
							if(data.err)
								$(result_con).html(data.err);		
						}
				   },'json')
		}
		break;
		
		case "remove_collection_item":
		{
			$("#"+form).hide();
			$.post(page,
				   {
						mode: mode,
						obj_id: objID,
						type: type,
						cid: cid   
				   },
				   function(data)
				   {
						if(!data)
						{
							alert("No Data Returned");
							$(result_con+"_"+objID).hide();
							$("#"+form).show();
						}
						else
						{
							if(data.err)
							{
								alert(data.err);
								$(result_con+"_"+objID).hide();
								$("#"+form+objID).show();
							}
							
							if(data.msg)
							{
								$(result_con).html(data.msg);
								$("#"+form+"_"+objID).slideUp(350);	
							}
									
						}
				   },'json')	
			
		}
	}
	
	return false;
}

// Simple function to open url with javascript
function openURL(url) {
	document.location = url;
}



function get_item(obj,ci_id,cid,type,direction)
{
	var btn_text = $(obj).html();
	$(obj).html(loading);
		
	$.post(page,
		   {
			   mode : 'get_item',
			   ci_id: ci_id,
			   cid : cid,
			   type: type,
			   direction: direction
		   },
		   function(data)
		   {
				if(!data)
				{
					alert('No '+type+' returned');
					$(obj).text(btn_text);
				} else {
					var jsArray = new Array(type,data['cid'],data['key']);
					construct_url(jsArray);
					$("#collectionItemView").html(data['content']);
				}
		   },'json')
}

function construct_url(jsArr)
{
	var url;
	if(Seo == 'yes')
	{
		url = '#!/item/'+jsArr[0]+'/'+jsArr[1]+'/'+jsArr[2];
		window.location.hash = url
	} else {
		url	= '#!?item='+jsArr[2]+'&type='+jsArr[0]+'&collection='+jsArr[1];
		window.location.hash = url
	}
}

function onReload_item()
{
	var comURL,
		regEX;		
	if(window.location.hash)
	{
		comURL = window.location.href;
		if(Seo == 'yes')
		{	
			regEX = RegExp('\/item.+#!');
			if(regEX.test(comURL))
			{
				comURL = comURL.replace(regEX,'');
				window.location.href = comURL;
			}
		} else {
			regEX = RegExp('\\\?item.+#!');
			if(regEX.test(comURL))
			{	comURL = comURL.replace(regEX,'')		
				window.location.href = comURL;
			}
		}
	}
}

function pagination(object,cid,type,pageNumber)
{
	var obj = $(object), objID = obj.id, 
		paginationParent = obj.parent(), paginationParentID, paginationInnerHTML = obj.html();
	
	if(paginationParent.attr('id'))
		paginationParentID = parent.attr('id')
	else
	{	paginationParent.attr('id','loadMoreParent'); paginationParentID = paginationParent.attr('id'); }
			
	newCall = 
	$.ajax({
		url: page,
		type: "post",
		dataType: "json",
		data: { mode: "moreItems", page:pageNumber, cid: cid, type: type },
		beforeSend: function() { obj.removeAttr('onClick'); obj.html(loading) },
		success : function(data) { 
						if(data['error'])
						{
							if(object.tagName == "BUTTON")
								obj.attr('disabled','disabled');
							obj.removeAttr('onClick'); obj.html('No more '+type);	
						} else {
							$('#collectionItemsList').append(data['content']); 
							$('#NewPagination').html(data['pagination']);
							obj.html(paginationInnerHTML);
						}
					}		
	});
}
var collectionID;
function ajax_add_collection(obj)
{
	var formID = obj.form.id, Form = $('#'+formID),
		This = $(obj), AjaxCall, ButtonHTML = This.html(),
		Result = $('#CollectionResult');	
	AjaxCall = 
	$.ajax
	({
		url: page,
		type: "post",
		dataType: "json",
		data: "mode=add_collection&"+Form.serialize(),
		beforeSend: function() { if(Result.css('display') == 'block') Result.slideUp('fast'); This.attr('disabled','disabled'); This.html(loading) },
		success: function(data) {
					if(data.msg)
					{
						$('#CollectionDIV').slideUp('fast');
						Result.html(data['msg']).slideDown('fast');
						collectionID = data['id'];
					}
					else
					{
						Result.html(data['err']).slideDown('fast');
						This.removeAttr('disabled'); This.html(ButtonHTML);
					}
				 }
	});	
}



function getDetails(obj)
{
	var forms = getInputs(obj), ParamArray = new Array(forms.length);
		
	$.each(forms,function(index,form) {
			query = $("#"+form.id+" *").serialize();
			query += "&mode=ajaxPhotos";
			ParamArray[index] = query;
	})
		
	return ParamArray;
}

function getName(File)
{
	var url = File;
	var filename = url.substring(url.lastIndexOf('/')+1);
	return filename;
}

function viewRatings(object,pid)
{
	var obj = $(object), innerHTML = obj.html();
	if(document.getElementById('RatingStatContainer'))
			$("#RatingStatContainer").toggle();
	else
	{       
			loadAjax = 
			$.ajax
			({
					url:page,
					type: "post",
					dataType: "text",
					data: { mode:"viewPhotoRating", photoid:pid },
					beforeSend: function() { obj.html(loading); },
					success:function(data) {
							obj.html(innerHTML); 
							if(data)
							{
									$("<div />").attr('id','RatingStatContainer')
									.addClass('clearfix')
									.css({
											"padding" : "8px",
											"font" : "normal 11px Tahoma",
											"border" : "1px solid #ccc",
											"backgroundColor" : "#FFF"     
									}).html(data).fadeIn(350).insertAfter(obj);
							} else {
									obj.removeAttr('onclick');
									alert("Photo has not recieved any rating yet.");        
							}
					}       
			});
	}
}

function showAdvanceSearch(simple,advance,expandClass,collapseClass)
{
	var simpleObj = $("#"+simple); var advanceObj = $("#"+advance);
	var	value = $('#SearchType').val();
	simpleObj.toggle();
	advanceObj.toggle();
	if(advanceObj.css('display') == 'block')	
		advanceObj.children().hide().filter('#'+value).show();
	$('.'+expandClass).toggleClass(collapseClass);
}

function toggleCategory(object,perPage)
{
	var obj = $(object), childTarget = obj.attr('alt'), child = $("#"+childTarget),
		childparts = childTarget.split("_"), childID = childparts[0];
	var browser = $.browser.msie; var browserVersion = $.browser.version;
	
	if(child.css('display') == "none")
	{
		child.slideDown(350);
		if(browser && browserVersion == "7.0")
			child.addClass('internetExplorer7CategoryToggleFix');
		$.cookie(childID,'expanded',{ expires: 1, path: '/' });
		obj.removeClass('none').addClass('block');	
	} else {
		child.slideUp(350);
		if(browser && browserVersion == "7.0")
			child.removeClass('internetExplorer7CategoryToggleFix');		
		$.cookie(childID,'collapsed',{ expires: 1, path: '/' });
		obj.removeClass('block').addClass('none');		
	}
}

function loadObject(currentDOM,type,objID,container)
{
	var object = new Array(4);
		object['this'] = currentDOM, object['type'] = type,
		object['objID'] = objID, object['container'] = container;
		
	var obj = $(object['this']);
	
	{
		obj.parent().css('position','relative');
	
		$.ajax({
			url : page,
			type : 'POST',
			dataType : 'json',
			data  : ({ mode : 'channelFeatured',
					   contentType : object['type'],
					   objID : object['objID']
					}),
			beforeSend : function()
			{
				obj.find('img').animate({ opacity : .5 });
				$("#"+object['container']).animate({ opacity : .5 });
			},
			success : function(data)
			{
				if(data['error'])
				{
					obj.find('img').animate({ opacity : 1 });
					$("#"+object['container']).animate({ opacity : 1 });
					alert(data['error']);
				}
				else
				{
					obj.parent().children('.selected').removeClass('selected');
					obj.addClass('selected');						
					obj.find('img').animate({ opacity : 1 });				
					$("#"+object['container']).html(data['data']);					
					$("#"+object['container']).animate({ opacity : 1 });
				}
			}
		})	
	}
}

function channelObjects(object,div,type,user,assign)
{
	var obj = $(object), curRel = obj.attr('rel'),
		DIV = $(div), oldRel = obj.parents('ul').find('a.selected').attr('rel');
	
	if(curRel)
	{
		if($("#"+curRel).css('display') == 'block')
			return false;
		else
		{
			obj.parents('ul').find('a.selected').removeClass('selected');
			obj.addClass('selected');
			
			$("#"+oldRel).hide();
			$("#"+curRel).show();		
		}
	} else {
		var newRel = type+"DIV";
		obj.attr('rel',newRel);
		$.ajax({
		 url : page,
		 type : "POST",
		 dataType : "json",
		 data : ({ mode : "channelObjects", content : type, user : user, assign : assign}),
		 beforeSend : function() { obj.append(loading_img) },
		 success : function(data)
		 {
			obj.find('img').remove();
			obj.parents('ul').find('a.selected').removeClass('selected');
			obj.addClass('selected');		
		
			$("#"+oldRel).hide();
			$("<div></div>").attr('id',newRel).addClass($("#"+oldRel).attr('class')).html(data.html).appendTo(DIV); 
		 }
		})	
	}
}

var comments_voting = 'no';
function getComments(type,type_id,last_update,pageNum,total,object_type,admin)
{
	$('#comments').html("<div style='padding:5px 0px;'>"+loading+"</div>");
	$.ajax({
	  type: 'POST',
	  url: page,
	  data:  {mode:'getComments',
	  page:pageNum,type:type,
	  type_id:type_id,
	  object_type : object_type,
	  last_update : last_update,
	  total_comments : total,
	  comments_voting : comments_voting,admin : admin},
	  success: function(data)
	  {
		$('#comments').hide();
		$('#comments').html(data);
		$('#comments').fadeIn('slow');
	  },
	  dataType: 'text'
	});
}

function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
	
/**
 * Function used to rate object
 */
function rate(id,rating,type)
{
	var page = baseurl+'/ajax.php';
	$.post(page, 
	{ 	
		mode : 'rating',
		id:id,
		rating:rating,
		type:type
	},
	function(data)
	{
		if(!data)
			alert("No data");
		else
			$("#rating_container").html(data);
	},'text');
}

function setPageHash(Page)
{
	// Removing baseurl
	var hashPart = Page.replace(baseurl,"");
	var prevHash = window.location.hash.replace("#!",'');
	//alert(hashPart+"       "+prevHash);
    {
		window.location.hash = "#!"+hashPart;
	}
	
}

function callURLParser()
{
	var expression = /(\#![/a-zA-Z0-9=\.\&\-\_\?]*)/g,
		   location = window.location.href,
		   returned = location.match(expression),
		   lastVisited;
	if(returned)
	{
		lastVisited = returned[returned.length - 1];	   
		if(lastVisited)
			window.location.href = baseurl+lastVisited.replace("#!",'');	
	}
}

function groupsAjax(event,selector,divSelector)
{
	event.preventDefault(); // prevent from redirecting to URL
	var ajaxPage, onLink = false, PreserveHTML, ParentTag, DIV;
	if(divSelector == undefined)
		divSelector = "ajaxGroupResultContainer";
	if(selector.href) // Means function is on link
	{
		ajaxPage = selector.href;
		onLink = true;
		jqueryObj = $(selector);
		javaObj = selector;	
	} else {
		ajaxPage = selector.childNodes[0].href;
		jqueryObj = $(selector.childNodes[0]);
		javaObj = selector.childNodes[0];	
	}
	if(ajaxPage == "undefined") {
		alert("URL not found"); 
		return false;
	} else {
		PreserveHTML = jqueryObj.html();
		setPageHash(ajaxPage);
		//return false;
		if(onLink == true) {
			ParentTag = jqueryObj.parent().parent();
			ParentTag.children().filter('.selected').removeClass('selected');
			jqueryObj.parent().addClass('selected');
		} else {
			ParentTag = jqueryObj.parent();
			ParentTag.children().filter('.selected').removeClass('selected');
			jqueryObj.addClass('selected');
		}
		jqueryObj.html(loading_img);
		$("#"+divSelector).load(ajaxPage+" #"+divSelector+"",function(response, status, xhr){
				jqueryObj.html(PreserveHTML);
				if(document.getElementById('flag_item'))
					$('#flag_item').show();	
		});
	}
}


var ua = navigator.userAgent.toLowerCase();
if (ua.indexOf(" chrome/") >= 0 || ua.indexOf(" firefox/") >= 0 || ua.indexOf(' gecko/') >= 0) {
	var StringMaker = function () {
		this.str = "";
		this.length = 0;
		this.append = function (s) {
			this.str += s;
			this.length += s.length;
		}
		this.prepend = function (s) {
			this.str = s + this.str;
			this.length += s.length;
		}
		this.toString = function () {
			return this.str;
		}
	}
} else {
	var StringMaker = function () {
		this.parts = [];
		this.length = 0;
		this.append = function (s) {
			this.parts.push(s);
			this.length += s.length;
		}
		this.prepend = function (s) {
			this.parts.unshift(s);
			this.length += s.length;
		}
		this.toString = function () {
			return this.parts.join('');
		}
	}
}


var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function encode64(input) {
	var output = new StringMaker();
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	while (i < input.length) {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output.append(keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4));
   }
   
   return output.toString();
}

function decode64(input) {
	var output = new StringMaker();
	var chr1, chr2, chr3;
	var enc1, enc2, enc3, enc4;
	var i = 0;

	// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
	input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

	while (i < input.length) {
		enc1 = keyStr.indexOf(input.charAt(i++));
		enc2 = keyStr.indexOf(input.charAt(i++));
		enc3 = keyStr.indexOf(input.charAt(i++));
		enc4 = keyStr.indexOf(input.charAt(i++));

		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;

		output.append(String.fromCharCode(chr1));

		if (enc3 != 64) {
			output.append(String.fromCharCode(chr2));
		}
		if (enc4 != 64) {
			output.append(String.fromCharCode(chr3));
		}
	}

	return output.toString();
}


	/**
	 * Function used to load editor's pic video
	 */
	function get_ep_video(vid)
	{
		
		$("#ep_video_container").html(loading);
		var page = baseurl+'/plugins/editors_pick/get_ep_video.php';
		$.ajax({
			url : page,
			type : 'POST',
			dataType : 'json',
			timeout : 8000,
			data  : ({ vid : vid}),
			success : function(msg)
								{
									if(!msg.data)
										alert("No data");
									else
										$("#ep_video_container").html(msg.data);
								}
			});

	}