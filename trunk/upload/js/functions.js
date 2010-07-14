// JavaScript Document

var page = baseurl+'/ajax.php';
var loading_img = "<img src='"+imageurl+"/ajax-loader.gif'>";
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

	function Confirm_DelVid(delUrl) {
	  if (confirm("Are you sure you want to delete this video?")) {
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
	
	function reloadImage(captcha_src,imgid)
	{
	img = document.getElementById(imgid);
	img.src = captcha_src+'?'+Math.random();
	}
	
	
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
	
	//Validate the Add Advertisment Form
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
	
	
	//CHECKK ALL FUNCTIOn

		<!--
		function checkAll(wotForm,wotState) {
			for (a=0; a<wotForm.elements.length; a++) {
				if (wotForm.elements[a].id.indexOf("delete_") == 0) {
					wotForm.elements[a].checked = wotState ;
				}
			}
		}
		// -->


	function check_remote_url()
	{

		var page = baseurl+'/actions/file_downloader.php';
		var Val = $("#remote_file_url").val();
		$("#remote_upload_result_cont").html(loading);
		$("#check_url").attr("disabled","disabled");
		$.post(page, 
		{ 	
			check_url	:	'yes' ,
			file_url : Val,
			file_name : file_name
		},				
		
		function (data) {
			if(data.err)
			{
				$("#remote_upload_result_cont").html(data.err);
				$("#check_url").attr("disabled","");
			}else{
				
				$("#remote_upload_div").html(loading_img+" uploading file, please wait...");
				upload_file(Val,file_name);
			}
		}, "json");
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
	function get_ep_video(vid)
	{
		var page = baseurl+'/plugins/editors_pick/get_ep_video.php';
		$("#ep_video_container").html(loading);
		$.post(page, 
		{ 	
			vid : vid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
				$("#ep_video_container").html(data);
		},'text');
	}
	
	
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
			users : $("#"+form_id+" input:#users").val(),
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
	
	
	function rate_comment(cid,thumb)
	{

		$.post(page, 
		{ 	
			mode : 'rate_comment',
			thumb : thumb,
			cid : cid
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
				
		var captcha_enabled =  $("#"+form_id+" input:#cb_captcha_enabled").val();
		$.post(page, 
		{ 	
			mode : 'add_comment',
			name : $("#"+form_id+" input:#name").val(),
			email : $("#"+form_id+" input:#email").val(),
			comment : $("#"+form_id+" textarea:#comment_box").val(),
			obj_id : $("#"+form_id+" input:#obj_id").val(),
			reply_to : $("#"+form_id+" input:#reply_to").val(),
			type : type,
			cb_captcha_enabled: $("#"+form_id+" input:#cb_captcha_enabled").val(),
			cb_captcha: $("#"+form_id+" input:#captcha").val()
		},
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
					captcha_enabled
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
	
	function add_playlist(mode,vid,form_id)
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
					vid : vid,
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
					vid : vid,
					plname : $("#"+form_id+" input:#playlist_name").val()
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
function show_menu(menu)
{
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

function spam_comment(cid)
	{

		$.post(page, 
		{ 	
			mode : 'spam_comment',
			cid : cid
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
		$.cookie("auto_play_playlist","false");
		window.location = document.location;
		$('#ap_status').html("off");
		
	}else
	{
		$.cookie("auto_play_playlist","true");
		window.location = document.location;
		$('#ap_status').html("on");
	}
}